<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'order' => 'nullable|integer|min:0',
            'max_marks' => 'nullable|integer|min:0',
            'content_type' => 'nullable|string|max:50',
            'grading_criteria' => 'nullable|string',
            'grade_scale' => 'nullable|string',
            'ai_options' => 'nullable|string',
        ]);

        $validated['module_id'] = $module->id;
        $validated['order'] = $validated['order'] ?? (($module->units()->max('order') ?? 0) + 1);

        // Handle file upload and content extraction
        if ($request->hasFile('unit_file')) {
            $file = $request->file('unit_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('unit_files', $fileName, 'public');
            $validated['file_path'] = $filePath;

            // Extract content based on file type
            $extractedContent = $this->extractFileContent($file);
            $validated['extracted_content'] = $extractedContent;

            // If description is empty, use extracted content
            if (empty($validated['description']) && !empty($extractedContent)) {
                $validated['description'] = substr($extractedContent, 0, 500);
            }
        }

        // Normalize empty JSON-like payloads
        if (!empty($validated['grading_criteria']) && is_array($validated['grading_criteria'])) {
            $validated['grading_criteria'] = json_encode($validated['grading_criteria']);
        }
        if (!empty($validated['grade_scale']) && is_array($validated['grade_scale'])) {
            $validated['grade_scale'] = json_encode($validated['grade_scale']);
        }

        Unit::create($validated);

        return redirect()->back()->with('success', 'Unit added successfully.');
    }

    public function update(Request $request, Unit $unit)
    {
        $this->authorizeModuleAccess($unit->module);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'order' => 'nullable|integer|min:0',
            'max_marks' => 'nullable|integer|min:0',
            'content_type' => 'nullable|string|max:50',
            'grading_criteria' => 'nullable|string',
            'grade_scale' => 'nullable|string',
            'ai_options' => 'nullable|string',
        ]);

        if ($request->hasFile('unit_file')) {
            $file = $request->file('unit_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('unit_files', $fileName, 'public');

            if ($unit->file_path && Storage::disk('public')->exists($unit->file_path)) {
                Storage::disk('public')->delete($unit->file_path);
            }

            $validated['file_path'] = $filePath;
            $validated['extracted_content'] = $this->extractFileContent($file);

            if (empty($validated['description']) && !empty($validated['extracted_content'])) {
                $validated['description'] = substr($validated['extracted_content'], 0, 500);
            }
        }

        // Normalize JSONish fields when provided from the form
        if (!empty($validated['grading_criteria']) && is_array($validated['grading_criteria'])) {
            $validated['grading_criteria'] = json_encode($validated['grading_criteria']);
        }
        if (!empty($validated['grade_scale']) && is_array($validated['grade_scale'])) {
            $validated['grade_scale'] = json_encode($validated['grade_scale']);
        }

        $unit->update($validated);

        return redirect()->back()->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $this->authorizeModuleAccess($unit->module);

        $unit->delete();

        return redirect()->back()->with('success', 'Unit deleted successfully.');
    }

    private function authorizeModuleAccess(CourseModule $module)
    {
        $teacher = auth()->user()->teacher;
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'You do not have permission to access this module.');
        }
    }

    private function extractFileContent($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $content = '';

        switch (strtolower($extension)) {
            case 'txt':
                $content = file_get_contents($file->getPathname());
                break;

            case 'pdf':
                // For PDF, we'll use a simple text extraction
                // Note: For production, consider using a library like smalot/pdfparser
                $content = '[PDF content extraction requires additional library. File uploaded: ' . $file->getClientOriginalName() . ']';
                break;

            case 'doc':
            case 'docx':
                // For Word documents, we'll use a placeholder
                // Note: For production, consider using a library like phpoffice/phpword
                $content = '[Word document content extraction requires additional library. File uploaded: ' . $file->getClientOriginalName() . ']';
                break;

            default:
                $content = '[Unsupported file type]';
        }

        return $content;
    }
}
