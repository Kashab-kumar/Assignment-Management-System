<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'type', 'due_date', 'max_score'];

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
}
