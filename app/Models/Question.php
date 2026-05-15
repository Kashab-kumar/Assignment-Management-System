<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'unit_id',
        'topic',
        'question_type',
        'question_text',
        'options',
        'answer',
        'marks',
        'difficulty',
        'tags',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'tags' => 'array',
        'attachments' => 'array',
        'marks' => 'float',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }
}
