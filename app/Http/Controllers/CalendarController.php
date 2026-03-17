<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CalendarController extends Controller
{
    public function studentIndex(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $selectedMonth = $this->resolveMonth($request->input('month'));
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

        $assignments = Assignment::with(['course', 'submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->whereBetween('due_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when(
                Schema::hasColumn('assignments', 'course_id') && !empty($student->course_id),
                fn ($q) => $q->where(function ($inner) use ($student) {
                    $inner->where('course_id', $student->course_id)
                        ->orWhereNull('course_id');
                })
            )
            ->orderBy('due_date')
            ->get();

        $exams = Exam::with(['course', 'results' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->whereBetween('exam_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when(
                Schema::hasColumn('exams', 'course_id') && !empty($student->course_id),
                fn ($q) => $q->where(function ($inner) use ($student) {
                    $inner->where('course_id', $student->course_id)
                        ->orWhereNull('course_id');
                })
            )
            ->orderBy('exam_date')
            ->get();

        $today = now()->startOfDay();

        $assignmentEvents = $assignments->map(function ($assignment) use ($today) {
            $isDone = $assignment->submissions->isNotEmpty();
            $eventDate = Carbon::parse($assignment->due_date)->startOfDay();

            return [
                'date' => $eventDate,
                'title' => $assignment->title,
                'type' => 'assignment',
                'source' => 'assignment',
                'course' => $assignment->course?->name,
                'status' => $isDone ? 'done' : ($eventDate->lt($today) ? 'overdue' : 'upcoming'),
            ];
        });

        $examEvents = $exams->map(function ($exam) use ($today) {
            $isDone = $exam->results->isNotEmpty();
            $eventDate = Carbon::parse($exam->exam_date)->startOfDay();
            $examType = in_array($exam->type ?? 'exam', ['quiz', 'test'], true) ? $exam->type : 'exam';

            return [
                'date' => $eventDate,
                'title' => $exam->title,
                'type' => $examType,
                'source' => 'exam',
                'course' => $exam->course?->name,
                'status' => $isDone ? 'done' : ($eventDate->lt($today) ? 'overdue' : 'upcoming'),
            ];
        });

        $events = $assignmentEvents
            ->merge($examEvents)
            ->sortBy(fn ($e) => $e['date']->timestamp)
            ->values();

        $counts = [
            'done' => $events->where('status', 'done')->count(),
            'upcoming' => $events->where('status', 'upcoming')->count(),
            'overdue' => $events->where('status', 'overdue')->count(),
        ];

        return view('student.calendar.index', compact('events', 'counts', 'selectedMonth', 'monthStart', 'student'));
    }

    public function teacherIndex(Request $request)
    {
        $selectedMonth = $this->resolveMonth($request->input('month'));
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        $selectedCourseId = $request->integer('course_id') ?: null;

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $assignmentEvents = Assignment::with('course')
            ->whereBetween('due_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->orderBy('due_date')
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => null,
                    'date' => Carbon::parse($assignment->due_date),
                    'title' => $assignment->title,
                    'type' => 'assignment',
                    'source' => 'assignment',
                    'course' => $assignment->course?->name,
                    'description' => $assignment->description,
                ];
            });

        $examEvents = Exam::with('course')
            ->whereBetween('exam_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->orderBy('exam_date')
            ->get()
            ->map(function ($exam) {
                return [
                    'id' => null,
                    'date' => Carbon::parse($exam->exam_date),
                    'title' => $exam->title,
                    'type' => in_array($exam->type ?? 'exam', ['quiz', 'test'], true) ? $exam->type : 'exam',
                    'source' => 'exam',
                    'course' => $exam->course?->name,
                    'description' => $exam->description,
                ];
            });

        $customEvents = CalendarEvent::with('course')
            ->where('user_id', auth()->id())
            ->whereBetween('event_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->orderBy('event_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'date' => Carbon::parse($event->event_date),
                    'title' => $event->title,
                    'type' => $event->event_type,
                    'source' => 'custom',
                    'course' => $event->course?->name,
                    'description' => $event->description,
                ];
            });

        $events = $assignmentEvents
            ->merge($examEvents)
            ->merge($customEvents)
            ->sortBy(fn ($e) => $e['date']->timestamp)
            ->values();

        return view('teacher.calendar.index', compact('events', 'courses', 'selectedCourseId', 'selectedMonth', 'monthStart'));
    }

    public function storeTeacherEvent(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:assignment,quiz,exam,other',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        CalendarEvent::create([
            'user_id' => auth()->id(),
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Calendar event added successfully.');
    }

    public function destroyTeacherEvent(CalendarEvent $event)
    {
        abort_unless($event->user_id === auth()->id(), 403);

        $event->delete();

        return back()->with('success', 'Calendar event deleted successfully.');
    }

    private function resolveMonth(?string $month): string
    {
        if ($month && preg_match('/^\\d{4}-\\d{2}$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }
}
