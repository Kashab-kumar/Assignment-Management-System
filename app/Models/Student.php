<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'student_id', 'name', 'email', 'course_id', 'class'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function getAverageScore()
    {
        $submissions = (float) ($this->submissions()->where('status', 'graded')->avg('score') ?? 0);
        $exams = (float) ($this->examResults()->avg('score') ?? 0);

        return ($submissions + $exams) / 2;
    }
}
