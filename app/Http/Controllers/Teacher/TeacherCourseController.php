<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TeacherCourseController extends Controller
{
    public function index()
    {
        $selectedCategory = request('category_name');
        $selectedClass = request('class_name');
        $assignedCourseIds = $this->assignedCourseIds();

        $courses = Course::withCount('students')
            ->whereIn('id', $assignedCourseIds)
            ->when($selectedCategory, fn ($q) => $q->where('category_name', $selectedCategory))
            ->when($selectedClass, fn ($q) => $q->where('class_name', $selectedClass))
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $courseTree = $courses
            ->groupBy(fn ($c) => $c->category_name ?: 'Uncategorized')
            ->map(fn ($cat) => $cat->groupBy(fn ($c) => $c->class_name ?: 'Unassigned Class'));

        $categoryOptions = Course::whereNotNull('category_name')->where('category_name', '!=', '')
            ->distinct()->orderBy('category_name')->pluck('category_name');

        $classOptions = Course::whereNotNull('class_name')->where('class_name', '!=', '')
            ->distinct()->orderBy('class_name')->pluck('class_name');

        return view('teacher.courses.index', compact(
            'courses', 'courseTree', 'categoryOptions', 'classOptions', 'selectedCategory', 'selectedClass'
        ));
    }

    public function show(Course $course)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        $modulesEnabled = Schema::hasTable('course_modules');
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $course->load(['students.user'])
            ->loadCount(['assignments', 'exams']);

        if ($modulesEnabled) {
            $teacher = auth()->user()->teacher;
            $course->load([
                'modules' => function ($query) use ($moduleItemsEnabled, $teacher) {
                    $query->where('teacher_id', $teacher->id)->with('teacher');
                    if ($moduleItemsEnabled) {
                        $query->with('items.creator');
                    }
                },
            ]);
        }

        $relatedCourses = collect();
        if (!empty($course->class_name)) {
            $relatedCourses = Course::where('class_name', $course->class_name)
                ->where('id', '!=', $course->id)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'category_name']);
        }

        return view('teacher.courses.show', compact('course', 'relatedCourses', 'modulesEnabled', 'moduleItemsEnabled'));
    }

    public function storeModuleItem(Request $request, Course $course, CourseModule $module)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        $teacher = $request->user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        if (!Schema::hasTable('course_module_items')) {
            return back()->withErrors(['error' => 'Course module items table not found. Please run migrations first.']);
        }

        if ($module->course_id !== $course->id) {
            abort(404);
        }

        if (!empty($module->teacher_id) && (int) $module->teacher_id !== (int) $teacher->id) {
            return back()->withErrors([
                'error' => 'You can only add content to modules assigned to you.',
            ]);
        }

        $validated = $request->validate([
            'type' => 'required|in:unit_outline,quiz,test,note,video',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $filePath = $file->store('module-files', 'public');
        }

        $nextPosition = ($module->items()->max('position') ?? 0) + 1;

        $module->items()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'position' => $nextPosition,
            'created_by' => $request->user()->id,
            'is_active' => true,
        ]);

        return back()->with('success', 'Module content added successfully.');
    }

    public function showModule(Course $course, CourseModule $module)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        if ($module->course_id !== $course->id) {
            abort(404);
        }

        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $module->load([
            'course',
            'teacher',
            'items' => fn ($query) => $query->with('creator')->latest('created_at')->take(12),
        ]);

        $assignments = Assignment::query()
            ->withCount('submissions')
            ->where('course_id', $course->id)
            ->where('module_id', $module->id)
            ->latest('due_date')
            ->take(10)
            ->get();

        $exams = Exam::query()
            ->withCount('results')
            ->withAvg('results', 'score')
            ->where('course_id', $course->id)
            ->where('module_id', $module->id)
            ->orderByDesc('exam_date')
            ->take(10)
            ->get();

        $recentSubmissions = Submission::query()
            ->with(['student', 'assignment'])
            ->whereHas('assignment', fn ($query) => $query->where('course_id', $course->id))
            ->latest('submitted_at')
            ->take(10)
            ->get();

        $recentResults = ExamResult::query()
            ->with(['student', 'exam'])
            ->whereHas('exam', fn ($query) => $query->where('course_id', $course->id))
            ->latest()
            ->take(10)
            ->get();

        $recents = collect();

        if ($moduleItemsEnabled) {
            $recents = $module->items
                ->toBase()
                ->map(function ($item) {
                    return [
                        'kind' => 'module_item',
                        'title' => $item->title,
                        'subtitle' => ucfirst(str_replace('_', ' ', $item->type)) . ' by ' . ($item->creator?->name ?? 'Teacher'),
                        'date' => $item->created_at,
                    ];
                });
        }

        $recents = $recents
            ->merge($recentSubmissions->map(function ($submission) {
                return [
                    'kind' => 'submission',
                    'title' => $submission->assignment?->title ?? 'Assignment submission',
                    'subtitle' => ($submission->student?->name ?? 'Student') . ' submitted',
                    'date' => $submission->submitted_at,
                ];
            }))
            ->merge($recentResults->map(function ($result) {
                return [
                    'kind' => 'grade',
                    'title' => $result->exam?->title ?? 'Assessment result',
                    'subtitle' => ($result->student?->name ?? 'Student') . ' scored ' . $result->score,
                    'date' => $result->created_at,
                ];
            }))
            ->filter(fn ($item) => !empty($item['date']))
            ->sortByDesc('date')
            ->take(12)
            ->values();

        return view('teacher.courses.module', compact(
            'course',
            'module',
            'assignments',
            'exams',
            'recentSubmissions',
            'recentResults',
            'recents',
            'moduleItemsEnabled'
        ));
    }

    public function createModuleItem(Course $course, CourseModule $module)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        if ($module->course_id !== $course->id) {
            abort(404);
        }

        $teacher = auth()->user()->teacher;
        if (!empty($module->teacher_id) && (int) $module->teacher_id !== (int) $teacher->id) {
            abort(403, 'You can only add content to modules assigned to you.');
        }

        return view('teacher.courses.module-item-create', compact('course', 'module'));
    }

    public function generateAIContent(Request $request)
    {
        try {
            $title = $request->input('title', '');
            $type = $request->input('type', '');
            $itemsCount = $request->input('items_count', 3);
            $difficulty = $request->input('difficulty', 'intermediate');
            $includeExamples = $request->input('include_examples', false);
            $includeSummary = $request->input('include_summary', false);
            $includeKeyPoints = $request->input('include_key_points', false);
            $includePractice = $request->input('include_practice', false);
            
            $fileText = '';
            $analyzedContent = '';
            
            // Extract text from uploaded file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileExtension = strtolower($file->getClientOriginalExtension());
                
                switch ($fileExtension) {
                    case 'txt':
                        $fileText = file_get_contents($file->getPathname());
                        $analyzedContent = "Successfully analyzed TXT file: " . $file->getClientOriginalName();
                        break;
                    case 'pdf':
                        $fileText = $this->extractTextFromPDF($file);
                        $analyzedContent = "Successfully analyzed PDF file: " . $file->getClientOriginalName();
                        break;
                    case 'doc':
                    case 'docx':
                        $fileText = $this->extractTextFromDoc($file);
                        $analyzedContent = "Successfully analyzed Word document: " . $file->getClientOriginalName();
                        break;
                    default:
                        return response()->json(['success' => false, 'error' => 'Unsupported file type']);
                }
                
                if (empty($fileText)) {
                    return response()->json(['success' => false, 'error' => 'Could not extract text from file']);
                }
            }

            // Build the prompt for Gemini
            $prompt = $this->buildPrompt($title, $type, $fileText, $difficulty, $includeExamples, $includeSummary, $includeKeyPoints, $includePractice, $itemsCount);
            
            // Call Gemini API
            $content = $this->callGeminiAPI($prompt);
            
            return response()->json([
                'success' => true,
                'content' => $content,
                'analyzed_content' => $analyzedContent
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AI Generation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function extractTextFromPDF($file)
    {
        // For now, return a placeholder - would need a PDF parsing library
        // You can install 'spatie/pdf-to-text' or similar package
        return '[PDF text extraction would require a PDF parsing library. For now, please use TXT files or copy-paste the content.]';
    }

    private function extractTextFromDoc($file)
    {
        // For now, return a placeholder - would need a DOCX parsing library
        // You can install 'phpoffice/phpword' package
        return '[DOC/DOCX text extraction would require a Word parsing library. For now, please use TXT files or copy-paste the content.]';
    }

    private function buildPrompt($title, $type, $fileText, $difficulty, $includeExamples, $includeSummary, $includeKeyPoints, $includePractice, $itemsCount)
    {
        $prompt = "You are an educational content creator. ";
        
        if (!empty($fileText)) {
            $prompt .= "I have provided a document with the following content:\n\n" . substr($fileText, 0, 10000) . "\n\n";
            $prompt .= "Based on this document, create educational content for: " . $title . "\n";
        } else {
            $prompt .= "Create educational content for the topic: " . $title . "\n";
        }
        
        $prompt .= "Content Type: " . ucfirst($type) . "\n";
        $prompt .= "Difficulty Level: " . ucfirst($difficulty) . "\n";
        $prompt .= "Number of items to generate: " . $itemsCount . "\n\n";
        
        $prompt .= "Requirements:\n";
        if ($includeExamples) $prompt .= "- Include practical examples\n";
        if ($includeSummary) $prompt .= "- Include a summary section\n";
        if ($includeKeyPoints) $prompt .= "- Highlight key points\n";
        if ($includePractice) $prompt .= "- Include practice questions\n";
        
        $prompt .= "\nPlease generate comprehensive, well-structured educational content that is appropriate for the specified difficulty level.";
        
        return $prompt;
    }

    private function callGeminiAPI($prompt)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('Gemini API key not configured');
        }

        $client = new \GuzzleHttp\Client();
        
        $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 8192,
                ]
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }
        
        throw new \Exception('Invalid response from Gemini API');
    }

    private function assignedCourseIds(): array
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return [];
        }

        return $teacher->courses()->pluck('courses.id')->all();
    }
}
