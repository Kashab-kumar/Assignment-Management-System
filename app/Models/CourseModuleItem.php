<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModuleItem extends Model
{
    protected $fillable = [
        'course_module_id',
        'type',
        'title',
        'content',
        'position',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}