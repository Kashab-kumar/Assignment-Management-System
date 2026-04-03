<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['course_id', 'type', 'title', 'description', 'exam_date', 'exam_time', 'duration_minutes', 'max_score', 'secure_mode', 'secure_instructions', 'max_violations', 'max_warnings'];

    protected $casts = [
        'exam_date' => 'date',
        'secure_mode' => 'boolean',
    ];

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('position')->orderBy('id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function getStartDateTimeAttribute(): \Carbon\Carbon
    {
        $date = $this->exam_date->copy();

        if (!empty($this->exam_time)) {
            [$hour, $minute] = array_pad(explode(':', $this->exam_time), 2, 0);
            return $date->setTime((int) $hour, (int) $minute, 0);
        }

        return $date->startOfDay();
    }

    public function getEndDateTimeAttribute(): ?\Carbon\Carbon
    {
        if (!$this->duration_minutes) {
            return null;
        }

        return $this->start_datetime->copy()->addMinutes($this->duration_minutes);
    }

    public function isLive(): bool
    {
        $now = now();
        return $now->greaterThanOrEqualTo($this->start_datetime) && 
               (!$this->end_datetime || $now->lessThanOrEqualTo($this->end_datetime));
    }

    public function isSecure(): bool
    {
        return (bool) $this->secure_mode;
    }
}
