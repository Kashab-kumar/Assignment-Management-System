<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentUnitGrade extends Model
{
    protected $table = 'student_unit_grades';

    protected $fillable = [
        'student_id',
        'unit_id',
        'course_id',
        'achieved_score',
        'total_possible_score',
        'percentage',
        'status',
        'attempt_count',
        'first_attempted_at',
        'last_attempted_at',
    ];

    protected $casts = [
        'achieved_score' => 'decimal:2',
        'total_possible_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'first_attempted_at' => 'date',
        'last_attempted_at' => 'date',
        'attempt_count' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the status color for UI display
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Mastered' => 'green',
            'In Progress' => 'yellow',
            'Needs Attention' => 'red',
            default => 'gray'
        };
    }

    /**
     * Determine if student has mastered this unit
     * @return bool
     */
    public function isMastered(): bool
    {
        return $this->status === 'Mastered';
    }

    /**
     * Determine if student is failing this unit
     * @return bool
     */
    public function isFailing(): bool
    {
        return $this->status === 'Needs Attention' || ($this->percentage && $this->percentage < 50);
    }
}
