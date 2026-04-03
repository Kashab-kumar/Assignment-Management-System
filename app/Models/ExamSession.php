<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'ended_at',
        'violations',
        'warnings',
        'fullscreen_exits',
        'tab_switches',
        'violation_log',
        'termination_reason',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'violation_log' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function addViolation(string $type, array $details = [])
    {
        $log = $this->violation_log ?? [];
        $log[] = [
            'type' => $type,
            'timestamp' => now()->toISOString(),
            'details' => $details,
        ];

        $this->violation_log = $log;
        
        switch ($type) {
            case 'tab_switch':
                $this->tab_switches++;
                break;
            case 'fullscreen_exit':
                $this->fullscreen_exits++;
                break;
            case 'warning':
                $this->warnings++;
                break;
            case 'violation':
                $this->violations++;
                break;
        }

        $this->save();
    }

    public function isTerminated(): bool
    {
        return !is_null($this->ended_at);
    }

    public function canContinue(): bool
    {
        if ($this->isTerminated()) {
            return false;
        }

        $exam = $this->exam;
        
        if (!$exam) {
            return false;
        }

        return $this->violations < $exam->max_violations && 
               $this->warnings < $exam->max_warnings;
    }

    public function getRemainingTime(): ?int
    {
        if ($this->isTerminated() || !$this->exam->duration_minutes) {
            return null;
        }

        $endTime = $this->started_at->copy()->addMinutes($this->exam->duration_minutes);
        $remaining = $endTime->diffInSeconds(now());

        return max(0, $remaining);
    }
}
