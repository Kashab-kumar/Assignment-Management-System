<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['title', 'description', 'exam_date', 'max_score'];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
