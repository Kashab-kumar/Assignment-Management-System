<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';
    protected $primaryKey = 'test_id';

    protected $fillable = [
        'module_id',
        'unit_id',
        'title',
        'instructions',
        'total_marks',
        'passing_marks',
        'duration',
        'weightage',
        'is_ai_generated',
        'status',
    ];

    protected $casts = [
        'is_ai_generated' => 'boolean',
        'weightage' => 'decimal:2',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
