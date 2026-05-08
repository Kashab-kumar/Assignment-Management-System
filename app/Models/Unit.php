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
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }
}
