<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitAssessmentConfiguration extends Model
{
    protected $table = 'unit_assessment_configurations';

    protected $fillable = [
        'unit_id',
        'assessment_type',
        'weight_percent',
        'description',
        'is_active',
    ];

    protected $casts = [
        'weight_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the unit this configuration belongs to
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get all assignments for this unit with this assessment type
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'unit_id', 'unit_id')
            ->where('assessment_type', $this->assessment_type);
    }

    /**
     * Get all exams for this unit with this assessment type
     */
    public function exams()
    {
        return $this->hasMany(Exam::class, 'unit_id', 'unit_id')
            ->where('assessment_type', $this->assessment_type);
    }

    /**
     * Get total weight for this unit
     * (all assessment types combined)
     */
    public static function getTotalWeightForUnit($unitId)
    {
        return self::where('unit_id', $unitId)
            ->where('is_active', true)
            ->sum('weight_percent');
    }

    /**
     * Check if unit assessment weights are properly configured (sum to 100%)
     */
    public static function isUnitProperlyConfigured($unitId)
    {
        $totalWeight = self::getTotalWeightForUnit($unitId);
        // Allow 0.01 tolerance for floating point calculations
        return abs($totalWeight - 100) <= 0.01;
    }

    /**
     * Get weight for specific assessment type in unit
     */
    public static function getWeightForAssessmentType($unitId, $assessmentType)
    {
        return self::where('unit_id', $unitId)
            ->where('assessment_type', $assessmentType)
            ->where('is_active', true)
            ->value('weight_percent') ?? 0;
    }
}
