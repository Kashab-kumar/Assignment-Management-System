@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold text-gray-900">Add Assessment Type</h1>
            <p class="mt-2 text-gray-600">Configure how this assessment type contributes to the unit grade</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('teacher.units.assessment-config.store', $unit) }}">
                @csrf

                <!-- Unit Info -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Unit</h3>
                    <p class="text-gray-600">{{ $unit->title }}</p>
                </div>

                <!-- Assessment Type Selection -->
                <div class="mb-6">
                    <label for="assessment_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Assessment Type <span class="text-red-600">*</span>
                    </label>
                    <select id="assessment_type" name="assessment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assessment_type') border-red-500 @enderror" required>
                        <option value="">Select an assessment type</option>
                        @foreach ($availableTypes as $type)
                            <option value="{{ $type }}" @selected(old('assessment_type') == $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('assessment_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight Percentage -->
                <div class="mb-6">
                    <label for="weight_percent" class="block text-sm font-medium text-gray-700 mb-2">
                        Weight Percentage <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="number" id="weight_percent" name="weight_percent" step="0.01" min="0" max="100"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('weight_percent') border-red-500 @enderror"
                            value="{{ old('weight_percent', '') }}" required>
                        <span class="text-gray-600 font-medium">%</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Example: If you have Assignment (40%), Quiz (30%), and Exam (30%), the weights sum to 100%
                    </p>
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
                        placeholder="E.g., 'Weekly homework and coding assignments'">{{ old('description', '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Information Box -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">How Assessment Weights Work:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• The weight determines how much this assessment type contributes to the unit grade</li>
                        <li>• All weights for a unit should sum to 100%</li>
                        <li>• Student's unit grade = (avg score × weight) summed across all assessment types</li>
                        <li>• Example: Unit grade = (Assignment avg × 40%) + (Quiz avg × 30%) + (Exam avg × 30%)</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                        Add Assessment Type
                    </button>
                    <a href="{{ route('teacher.units.assessment-config.index', $unit) }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Available Types Info -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Assessment Types</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Assignment</h4>
                    <p class="text-sm text-gray-600">Homework, projects, practical work</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Quiz</h4>
                    <p class="text-sm text-gray-600">Quick assessments, short tests</p>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Exam</h4>
                    <p class="text-sm text-gray-600">Final assessment, comprehensive test</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Project</h4>
                    <p class="text-sm text-gray-600">Semester/unit projects, capstone work</p>
                </div>
                <div class="border-l-4 border-pink-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Practical</h4>
                    <p class="text-sm text-gray-600">Lab work, hands-on activities</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="font-semibold text-gray-900">Test</h4>
                    <p class="text-sm text-gray-600">Mid-term or periodic assessments</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
