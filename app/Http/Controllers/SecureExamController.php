<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SecureExamController extends Controller
{
    public function startSession(Request $request, Exam $exam): JsonResponse
    {
        $student = auth()->user()->student;

        if (!$student) {
            return response()->json(['error' => 'Student profile not found'], 403);
        }

        if (!$exam->secure_mode) {
            return response()->json(['error' => 'This exam is not in secure mode'], 403);
        }

        $now = now();
        
        if ($now->lt($exam->start_datetime)) {
            return response()->json([
                'error' => 'Exam has not started yet',
                'starts_at' => $exam->start_datetime->toISOString()
            ], 403);
        }

        if ($exam->end_datetime && $now->gt($exam->end_datetime)) {
            return response()->json(['error' => 'Exam has ended'], 403);
        }

        // Check if session already exists
        $existingSession = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSession) {
            if ($existingSession->isTerminated()) {
                return response()->json([
                    'error' => 'Your exam session was terminated',
                    'reason' => $existingSession->termination_reason
                ], 403);
            }

            if (!$existingSession->canContinue()) {
                return response()->json([
                    'error' => 'You have exceeded the maximum allowed violations/warnings'
                ], 403);
            }

            return response()->json([
                'session_id' => $existingSession->id,
                'started_at' => $existingSession->started_at->toISOString(),
                'remaining_time' => $existingSession->getRemainingTime(),
                'violations' => $existingSession->violations,
                'warnings' => $existingSession->warnings,
            ]);
        }

        // Create new session
        $session = ExamSession::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'started_at' => $now,
        ]);

        return response()->json([
            'session_id' => $session->id,
            'started_at' => $session->started_at->toISOString(),
            'remaining_time' => $session->getRemainingTime(),
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'duration_minutes' => $exam->duration_minutes,
                'max_violations' => $exam->max_violations,
                'max_warnings' => $exam->max_warnings,
                'secure_instructions' => $exam->secure_instructions,
            ]
        ]);
    }

    public function recordViolation(Request $request, Exam $exam): JsonResponse
    {
        $student = auth()->user()->student;

        if (!$student) {
            return response()->json(['error' => 'Student profile not found'], 403);
        }

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$session || $session->isTerminated()) {
            return response()->json(['error' => 'No active exam session'], 403);
        }

        $violationType = $request->input('type');
        $details = $request->input('details', []);

        if (!in_array($violationType, ['tab_switch', 'fullscreen_exit', 'copy_attempt', 'right_click', 'keyboard_shortcut'])) {
            return response()->json(['error' => 'Invalid violation type'], 400);
        }

        $session->addViolation($violationType, $details);

        $shouldTerminate = false;
        $terminationReason = null;

        if ($session->violations >= $exam->max_violations) {
            $shouldTerminate = true;
            $terminationReason = 'Maximum violations exceeded';
        } elseif ($session->warnings >= $exam->max_warnings) {
            $shouldTerminate = true;
            $terminationReason = 'Maximum warnings exceeded';
        }

        if ($shouldTerminate) {
            $session->ended_at = now();
            $session->termination_reason = $terminationReason;
            $session->save();

            return response()->json([
                'terminated' => true,
                'reason' => $terminationReason,
                'violations' => $session->violations,
                'warnings' => $session->warnings,
            ]);
        }

        return response()->json([
            'recorded' => true,
            'violations' => $session->violations,
            'warnings' => $session->warnings,
            'remaining_violations' => $exam->max_violations - $session->violations,
            'remaining_warnings' => $exam->max_warnings - $session->warnings,
        ]);
    }

    public function heartbeat(Request $request, Exam $exam): JsonResponse
    {
        $student = auth()->user()->student;

        if (!$student) {
            return response()->json(['error' => 'Student profile not found'], 403);
        }

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$session || $session->isTerminated()) {
            return response()->json(['terminated' => true], 403);
        }

        // Check if exam time has expired
        if ($exam->end_datetime && now()->gt($exam->end_datetime)) {
            $session->ended_at = now();
            $session->termination_reason = 'Exam time expired';
            $session->save();

            return response()->json([
                'terminated' => true,
                'reason' => 'Exam time expired'
            ]);
        }

        return response()->json([
            'active' => true,
            'remaining_time' => $session->getRemainingTime(),
            'violations' => $session->violations,
            'warnings' => $session->warnings,
        ]);
    }

    public function endSession(Request $request, Exam $exam): JsonResponse
    {
        $student = auth()->user()->student;

        if (!$student) {
            return response()->json(['error' => 'Student profile not found'], 403);
        }

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$session || $session->isTerminated()) {
            return response()->json(['error' => 'No active exam session'], 403);
        }

        $session->ended_at = now();
        $session->termination_reason = 'Student submitted exam';
        $session->save();

        return response()->json([
            'ended' => true,
            'session_duration' => $session->started_at->diffInSeconds($session->ended_at),
            'violations' => $session->violations,
            'warnings' => $session->warnings,
        ]);
    }
}
