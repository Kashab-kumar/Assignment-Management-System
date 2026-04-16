<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['course_id', 'module_id', 'teacher_id', 'title', 'description', 'type', 'due_date', 'max_score', 'weightage', 'instructions'];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
