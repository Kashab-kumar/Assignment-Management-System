<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['module_id'] = $module->id;
        $validated['order'] = $validated['order'] ?? ($module->units()->count() + 1);

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

        Unit::create($validated);

        return redirect()->back()->with('success', 'Unit added successfully.');
    }

    public function update(Request $request, Unit $unit)
    {
        $this->authorizeModuleAccess($unit->module);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

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
