<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240'
        ]);

        $student = auth()->user()->student;
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        Submission::create([
            'student_id' => $student->id,
            'assignment_id' => $assignment->id,
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
            'status' => 'pending'
        ]);

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Assignment submitted successfully!');
    }
}
