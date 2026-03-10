<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'student_id', 'name', 'email', 'course_id'];

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
        $submissions = $this->submissions()->where('status', 'graded')->avg('score');
        $exams = $this->examResults()->avg('score');
        return ($submissions + $exams) / 2;
    }
}
