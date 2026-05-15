<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::query();

        // Filters
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }
        if ($request->filled('module_id')) {
            $query->where('module_id', $request->input('module_id'));
        }
        if ($request->filled('topic')) {
            $topic = strtolower(trim($request->input('topic')));
            $query->whereRaw('LOWER(topic) LIKE ?', ["%{$topic}%"]);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'LIKE', "%{$search}%")
                  ->orWhere('topic', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $questions = $query->orderByDesc('id')->paginate($perPage)->appends($request->except('page'));

        return response()->json([
            'questions' => $questions->items(),
            'meta' => [
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'per_page' => $questions->perPage(),
                'total' => $questions->total(),
            ]
        ]);
    }

    private function parseJsonIfPossible($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // If looks like JSON, attempt decode
        if ((Str::startsWith($value, '{') || Str::startsWith($value, '['))) {
            $decoded = json_decode($value, true);
            return $decoded === null ? $value : $decoded;
        }

        // Comma separated options -> array
        if (Str::contains($value, ',')) {
            return array_map('trim', explode(',', $value));
        }

        return $value;
    }

    private function splitTags($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        return array_filter(array_map('trim', preg_split('/[,;]+/', $value)));
    }

    // Teacher management UI listing
    public function manageIndex(Request $request)
    {
        $query = Question::query();
        $teacherId = Auth::id();
        $query->where('created_by', $teacherId);

        $questions = $query->orderByDesc('id')->paginate(25);

        return view('teacher.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('teacher.questions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module_id' => 'nullable|exists:course_modules,id',
            'unit_id' => 'nullable|exists:units,id',
            'topic' => 'nullable|string|max:255',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'options' => 'nullable|string',
            'answer' => 'nullable|string',
            'marks' => 'nullable|numeric|min:0',
            'difficulty' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $data['options'] = $this->parseJsonIfPossible($data['options'] ?? null);
        $data['tags'] = $this->splitTags($data['tags'] ?? null);
        $data['created_by'] = Auth::id();

        Question::create($data);

        return redirect()->route('teacher.questions.index')->with('success', 'Question added');
    }

    public function edit(Question $question)
    {
        abort_unless($question->created_by === Auth::id(), 403);
        return view('teacher.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        abort_unless($question->created_by === Auth::id(), 403);

        $data = $request->validate([
            'module_id' => 'nullable|exists:course_modules,id',
            'unit_id' => 'nullable|exists:units,id',
            'topic' => 'nullable|string|max:255',
            'question_type' => 'required|string|max:50',
            'question_text' => 'required|string',
            'options' => 'nullable|string',
            'answer' => 'nullable|string',
            'marks' => 'nullable|numeric|min:0',
            'difficulty' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $data['options'] = $this->parseJsonIfPossible($data['options'] ?? null);
        $data['tags'] = $this->splitTags($data['tags'] ?? null);

        $question->update($data);

        return redirect()->route('teacher.questions.index')->with('success', 'Question updated');
    }

    public function destroy(Question $question)
    {
        abort_unless($question->created_by === Auth::id(), 403);
        $question->delete();
        return redirect()->route('teacher.questions.index')->with('success', 'Question deleted');
    }
}
