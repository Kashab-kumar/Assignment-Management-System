<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Course extends Model
{
    protected $fillable = ['name', 'code', 'category_name', 'class_name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students()
    {
        // Support both schemas:
        // - New schema: students.course_id -> courses.id
        // - Legacy schema: students.class -> courses.name
        if (Schema::hasColumn('students', 'course_id')) {
            return $this->hasMany(Student::class, 'course_id', 'id');
        }

        return $this->hasMany(Student::class, 'class', 'name');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
