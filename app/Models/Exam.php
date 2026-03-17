<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['course_id', 'type', 'title', 'description', 'exam_date', 'exam_time', 'duration_minutes', 'max_score'];

    protected $casts = [
        'exam_date' => 'date',
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
}
