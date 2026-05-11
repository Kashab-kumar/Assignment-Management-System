<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitAssessmentConfiguration;
use Illuminate\Http\Request;

class UnitAssessmentConfigurationController extends Controller
{
    /**
     * Display assessment configurations for a unit
     */
    public function index(Unit $unit)
    {
        $configurations = $unit->assessmentConfigurations()->orderBy('assessment_type')->get();
        $totalWeight = UnitAssessmentConfiguration::getTotalWeightForUnit($unit->id);
        $isProperlyConfigured = UnitAssessmentConfiguration::isUnitProperlyConfigured($unit->id);

        return view('unit-assessment-config.index', [
            'unit' => $unit,
            'configurations' => $configurations,
            'totalWeight' => $totalWeight,
            'isProperlyConfigured' => $isProperlyConfigured,
        ]);
    }

    /**
     * Create new assessment configuration for a unit
     */
    public function create(Unit $unit)
    {
        $existingTypes = $unit->assessmentConfigurations()->pluck('assessment_type')->toArray();
        $availableTypes = ['assignment', 'quiz', 'exam', 'project', 'practical', 'homework', 'test', 'midterm', 'final'];
        $availableTypes = array_diff($availableTypes, $existingTypes);

        return view('unit-assessment-config.create', [
            'unit' => $unit,
            'availableTypes' => $availableTypes,
        ]);
    }

    /**
     * Store new assessment configuration
     */
    public function store(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'assessment_type' => 'required|string|unique:unit_assessment_configurations,assessment_type,NULL,id,unit_id,' . $unit->id,
            'weight_percent' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $validated['unit_id'] = $unit->id;

        UnitAssessmentConfiguration::create($validated);

        // Check if weights now sum to 100%
        $totalWeight = UnitAssessmentConfiguration::getTotalWeightForUnit($unit->id);
        $message = "Assessment configuration added. Current total weight: {$totalWeight}%";

        if ($totalWeight > 100) {
            $message .= " (Exceeds 100% - please adjust)";
            return redirect()->route('teacher.units.assessment-config.index', $unit)
                ->with('warning', $message);
        } elseif ($totalWeight == 100) {
            $message = "Assessment configuration added. Total weight is now 100% - properly configured!";
            return redirect()->route('teacher.units.assessment-config.index', $unit)
                ->with('success', $message);
        }

        return redirect()->route('teacher.units.assessment-config.index', $unit)
            ->with('info', $message);
    }

    /**
     * Edit assessment configuration
     */
    public function edit(Unit $unit, UnitAssessmentConfiguration $configuration)
    {
        if ($configuration->unit_id !== $unit->id) {
            abort(404);
        }

        return view('unit-assessment-config.edit', [
            'unit' => $unit,
            'configuration' => $configuration,
        ]);
    }

    /**
     * Update assessment configuration
     */
    public function update(Request $request, Unit $unit, UnitAssessmentConfiguration $configuration)
    {
        if ($configuration->unit_id !== $unit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'weight_percent' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $configuration->update($validated);

        $totalWeight = UnitAssessmentConfiguration::getTotalWeightForUnit($unit->id);

        if ($totalWeight > 100) {
            return redirect()->route('teacher.units.assessment-config.index', $unit)
                ->with('warning', "Total weight is now {$totalWeight}% (Exceeds 100% - please adjust)");
        } elseif ($totalWeight == 100) {
            return redirect()->route('teacher.units.assessment-config.index', $unit)
                ->with('success', "Configuration updated. Total weight is 100% - properly configured!");
        }

        return redirect()->route('teacher.units.assessment-config.index', $unit)
            ->with('info', "Configuration updated. Current total weight: {$totalWeight}%");
    }

    /**
     * Delete assessment configuration
     */
    public function destroy(Unit $unit, UnitAssessmentConfiguration $configuration)
    {
        if ($configuration->unit_id !== $unit->id) {
            abort(404);
        }

        $assessmentType = $configuration->assessment_type;
        $configuration->delete();

        return redirect()->route('teacher.units.assessment-config.index', $unit)
            ->with('success', "Assessment type '{$assessmentType}' removed from unit.");
    }

    /**
     * API endpoint: Get assessment configuration summary
     */
    public function getSummary(Unit $unit)
    {
        $configurations = $unit->assessmentConfigurations()
            ->where('is_active', true)
            ->get(['assessment_type', 'weight_percent', 'description']);

        $totalWeight = UnitAssessmentConfiguration::getTotalWeightForUnit($unit->id);
        $isConfigured = UnitAssessmentConfiguration::isUnitProperlyConfigured($unit->id);

        return response()->json([
            'configurations' => $configurations,
            'total_weight' => $totalWeight,
            'is_configured' => $isConfigured,
            'warning' => !$isConfigured ? "Unit assessment weights do not sum to 100%. Current: {$totalWeight}%" : null,
        ]);
    }

    /**
     * Bulk update all configurations at once
     */
    public function bulkUpdate(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'configurations' => 'required|array',
            'configurations.*.id' => 'required|exists:unit_assessment_configurations,id',
            'configurations.*.weight_percent' => 'required|numeric|min:0|max:100',
        ]);

        $totalWeight = 0;

        foreach ($validated['configurations'] as $config) {
            $configuration = UnitAssessmentConfiguration::find($config['id']);
            if ($configuration->unit_id === $unit->id) {
                $configuration->update(['weight_percent' => $config['weight_percent']]);
                $totalWeight += $config['weight_percent'];
            }
        }

        if ($totalWeight > 100) {
            return response()->json([
                'success' => false,
                'message' => "Total weight is {$totalWeight}% (exceeds 100%)",
                'total_weight' => $totalWeight,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "Configurations updated. Total weight: {$totalWeight}%",
            'total_weight' => $totalWeight,
            'is_configured' => abs($totalWeight - 100) <= 0.01,
        ]);
    }
}
