<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SecureExamMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $exam = $request->route('exam');
        $student = auth()->user()->student;
        
        if (!$exam || !$student) {
            return $next($request);
        }

        // Check if exam is in secure mode
        if (!$exam->secure_mode) {
            return $next($request);
        }

        // Get exam start time
        $examStartsAt = $this->getExamStartDateTime($exam);
        $now = now();

        // Check if exam has started
        if ($now->lt($examStartsAt)) {
            return redirect()->route('student.exams.show', $exam)
                ->withErrors(['error' => 'This secure exam has not started yet. It will begin at ' . $examStartsAt->format('M d, Y h:i A')]);
        }

        // Check if exam duration has expired
        if ($exam->duration_minutes && $now->gt($examStartsAt->addMinutes($exam->duration_minutes))) {
            return redirect()->route('student.exams.show', $exam)
                ->withErrors(['error' => 'This secure exam has ended. The time limit was ' . $exam->duration_minutes . ' minutes.']);
        }

        // Set secure exam session
        $sessionKey = "secure_exam_{$exam->id}_{$student->id}";
        
        if (!$request->session()->has($sessionKey)) {
            // Initialize secure session
            $request->session()->put($sessionKey, [
                'started_at' => $now->timestamp,
                'last_activity' => $now->timestamp,
                'violations' => 0,
                'warnings' => 0,
                'fullscreen_exits' => 0,
                'tab_switches' => 0,
            ]);
        }

        // Update last activity
        $sessionData = $request->session()->get($sessionKey);
        $sessionData['last_activity'] = $now->timestamp;
        $request->session()->put($sessionKey, $sessionData);

        // Add security headers
        $response = $next($request);
        
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline'; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self';"
        );

        return $response;
    }

    private function getExamStartDateTime($exam): \Carbon\Carbon
    {
        $date = $exam->exam_date->copy();

        if (!empty($exam->exam_time)) {
            [$hour, $minute] = array_pad(explode(':', $exam->exam_time), 2, 0);
            return $date->setTime((int) $hour, (int) $minute, 0);
        }

        return $date->startOfDay();
    }
}
