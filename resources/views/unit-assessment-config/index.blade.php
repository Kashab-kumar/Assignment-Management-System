@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Assessment Configuration</h1>
                    <p class="mt-2 text-gray-600">Define assessment types and their weights for this unit</p>
                </div>
                <a href="{{ route('teacher.units.assessment-config.create', $unit) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    + Add Assessment Type
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Unit Information -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Unit: {{ $unit->title }}</h2>
            <p class="text-gray-600">{{ $unit->description }}</p>

            <!-- Status Badge -->
            <div class="mt-4">
                @if ($isProperlyConfigured)
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">Properly Configured (100%)</span>
                    </div>
                @else
                    <div class="inline-flex items-center px-4 py-2 @if($totalWeight > 100) bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">Configuration Incomplete ({{ $totalWeight }}%)</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assessment Configurations Table -->
        @if ($configurations->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Assessment Types</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($configurations as $config)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 capitalize">
                                            {{ $config->assessment_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                                @php
                                                    $percentage = min($config->weight_percent, 100);
                                                    $color = $percentage > 50 ? 'bg-blue-500' : 'bg-gray-400';
                                                @endphp
                                                <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="ml-2 text-sm font-semibold text-gray-900">{{ $config->weight_percent }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $config->description ?? 'No description' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($config->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('teacher.units.assessment-config.edit', [$unit, $config]) }}" class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                                        <form method="POST" action="{{ route('teacher.units.assessment-config.destroy', [$unit, $config]) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Weight Summary -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Weight:</span>
                        <div class="flex items-center">
                            <div class="w-48 bg-gray-300 rounded-full h-3 mr-3">
                                @php
                                    $displayWidth = min($totalWeight, 100);
                                    $barColor = $totalWeight == 100 ? 'bg-green-500' : ($totalWeight > 100 ? 'bg-red-500' : 'bg-yellow-500');
                                @endphp
                                <div class="{{ $barColor }} h-3 rounded-full transition-all duration-300" style="width: {{ $displayWidth }}%"></div>
                            </div>
                            <span class="text-lg font-bold @if($totalWeight == 100) text-green-600 @elseif($totalWeight > 100) text-red-600 @else text-yellow-600 @endif">
                                {{ $totalWeight }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guidance -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">How This Works:</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>✓ Each assessment type (e.g., Assignment, Quiz, Exam) should have a weight assigned</li>
                    <li>✓ The weights should sum to exactly 100% for proper grading</li>
                    <li>✓ When students submit work, their grade is calculated based on these weights</li>
                    <li>✓ Example: If Assignment=40%, Quiz=30%, Exam=30%, a student's unit grade = (assignment_avg × 0.4) + (quiz_avg × 0.3) + (exam_avg × 0.3)</li>
                </ul>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-yellow-900">No Assessment Types Configured</h3>
                <p class="mt-2 text-yellow-700">Add assessment types to define how grades are calculated for this unit.</p>
                <a href="{{ route('teacher.units.assessment-config.create', $unit) }}" class="mt-4 inline-block px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                    Add First Assessment Type
                </a>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.units.update', $unit) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                ← Back to Unit
            </a>
        </div>
    </div>
</div>
@endsection
