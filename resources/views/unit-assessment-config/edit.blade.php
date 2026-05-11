@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Assessment Type</h1>
            <p class="mt-2 text-gray-600">Modify the weight and settings for this assessment type</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('teacher.units.assessment-config.update', [$unit, $configuration]) }}">
                @csrf
                @method('PUT')

                <!-- Unit Info -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Unit</h3>
                    <p class="text-gray-600">{{ $unit->title }}</p>
                </div>

                <!-- Assessment Type (Read-only) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Assessment Type
                    </label>
                    <div class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 capitalize">
                            {{ $configuration->assessment_type }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">(Cannot be changed)</p>
                </div>

                <!-- Weight Percentage -->
                <div class="mb-6">
                    <label for="weight_percent" class="block text-sm font-medium text-gray-700 mb-2">
                        Weight Percentage <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="number" id="weight_percent" name="weight_percent" step="0.01" min="0" max="100"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('weight_percent') border-red-500 @enderror"
                            value="{{ old('weight_percent', $configuration->weight_percent) }}" required>
                        <span class="text-gray-600 font-medium">%</span>
                    </div>
                    @error('weight_percent')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description (Optional)
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="E.g., 'Weekly homework and coding assignments'">{{ old('description', $configuration->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active Toggle -->
                <div class="mb-6">
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            @checked(old('is_active', $configuration->is_active))>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                    <p class="mt-2 text-sm text-gray-500">Uncheck to temporarily disable this assessment type without deleting it</p>
                </div>

                <!-- Warning Box -->
                @if ($configuration->weight_percent != 100 / $unit->assessmentConfigurations->count())
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-yellow-900 mb-1">⚠ Weight Configuration</h4>
                        <p class="text-sm text-yellow-800">
                            Current total weight for this unit:
                            <strong id="totalWeight">{{ \App\Models\UnitAssessmentConfiguration::getTotalWeightForUnit($unit->id) }}%</strong>
                        </p>
                        <p class="text-sm text-yellow-700 mt-1">Weights should sum to 100% for proper grading calculations.</p>
                    </div>
                @endif

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                        Update Configuration
                    </button>
                    <a href="{{ route('teacher.units.assessment-config.index', $unit) }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Danger Zone -->
            <div class="mt-8 pt-8 border-t-2 border-red-200">
                <h3 class="text-lg font-semibold text-red-900 mb-4">Danger Zone</h3>
                <form method="POST" action="{{ route('teacher.units.assessment-config.destroy', [$unit, $configuration]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this assessment type? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        Delete Assessment Type
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('weight_percent').addEventListener('input', function() {
        // You could add real-time calculation here if needed
        // This would show updated total weight as user types
    });
</script>
@endsection
