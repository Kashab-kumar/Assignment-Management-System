<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['course_id', 'module_id', 'unit_id', 'teacher_id', 'title', 'description', 'type', 'assessment_type', 'due_date', 'max_score', 'weightage', 'instructions', 'instruction_file_path', 'instruction_file_name', 'covered_topics', 'selected_questions'];

    protected $casts = [
        'due_date' => 'date',
        'covered_topics' => 'array',
        'selected_questions' => 'array',
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

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
