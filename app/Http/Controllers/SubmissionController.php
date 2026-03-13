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
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240'
        ]);

        // Ensure at least one submission method is provided
        if (empty($validated['content']) && !$request->hasFile('file')) {
            return back()->withErrors(['error' => 'Please provide either a text answer or upload a file.']);
        }

        $student = auth()->user()->student;
        
        // Check if student record exists
        if (!$student) {
            return back()->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }
        
        // Check if student already submitted
        $existingSubmission = Submission::where('student_id', $student->id)
            ->where('assignment_id', $assignment->id)
            ->first();
            
        if ($existingSubmission) {
            return back()->withErrors(['error' => 'You have already submitted this assignment.']);
        }
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        Submission::create([
            'student_id' => $student->id,
            'assignment_id' => $assignment->id,
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
            'status' => 'pending',
            'submitted_at' => now()
        ]);

        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', 'Assignment submitted successfully!');
    }
}
