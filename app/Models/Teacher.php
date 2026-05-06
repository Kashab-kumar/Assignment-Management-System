<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'teacher_id', 'name', 'email', 'subject'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function subjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'teacher_id');
    }
}
