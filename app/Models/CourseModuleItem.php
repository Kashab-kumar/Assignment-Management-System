<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModuleItem extends Model
{
    protected $fillable = [
        'course_module_id',
        'unit_id',
        'type',
        'title',
        'content',
        'file_path',
        'file_name',
        'file_type',
        'position',
        'created_by',
        'is_active',
        'grade_scale',
        'grading_criteria',
        'ai_options',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'grade_scale' => 'array',
        'grading_criteria' => 'array',
        'ai_options' => 'array',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
