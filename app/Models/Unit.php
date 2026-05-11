<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'file_path',
        'extracted_content',
        'order',
        'max_marks',
        'content_type',
        'grading_criteria',
        'grade_scale',
        'ai_options',
        'weightage_percent',
        'is_active',
    ];

    protected $casts = [
        'weightage_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function studentUnitGrades()
    {
        return $this->hasMany(StudentUnitGrade::class);
    }

    public function assessmentConfigurations()
    {
        return $this->hasMany(UnitAssessmentConfiguration::class);
    }
}
