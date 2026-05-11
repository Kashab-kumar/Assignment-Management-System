@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Syllabus Mastery Progress</h1>
                    <p class="mt-2 text-gray-600">Track your learning progress across course units</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Course</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $course->title }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Progress Summary -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Overall Score -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm text-gray-600">Overall Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $courseProgress['overall_percentage'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Units Completed -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm text-gray-600">Mastered</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $courseProgress['mastered'] }}</p>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm text-gray-600">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $courseProgress['in_progress'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Needs Attention -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v.01M12 3a9 9 0 110 18 9 9 0 010-18z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm text-gray-600">Needs Attention</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $courseProgress['needs_attention'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Syllabus Mastery Bar Chart -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Unit-wise Performance</h2>
            <p class="text-sm text-gray-600 mb-6">
                <span class="inline-block mr-4">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-sm mr-1"></span>
                    Below 50% (Failing)
                </span>
                <span class="inline-block mr-4">
                    <span class="inline-block w-3 h-3 bg-yellow-500 rounded-sm mr-1"></span>
                    50-80% (In Progress)
                </span>
                <span class="inline-block">
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-sm mr-1"></span>
                    80%+ (Mastered)
                </span>
            </p>
            <canvas id="syllabusMasteryChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Detailed Unit Breakdown -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Detailed Unit Breakdown</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($unitGrades as $grade)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $grade->unit->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $grade->achieved_score }} / {{ $grade->total_possible_score }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            @php
                                                $percentage = $grade->percentage;
                                                $color = $percentage >= 80 ? 'bg-green-500' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="{{ $color }} h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $grade->percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Mastered' => 'green',
                                            'In Progress' => 'yellow',
                                            'Needs Attention' => 'red',
                                        ];
                                        $statusColor = $statusColors[$grade->status] ?? 'gray';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ $grade->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $grade->attempt_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No unit grades yet. Start completing assignments and exams!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Fetch and display syllabus mastery chart
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const response = await fetch('{{ route("analytics.syllabus-mastery-data", $course->id) }}');
            const data = await response.json();

            const ctx = document.getElementById('syllabusMasteryChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Percentage Score (%)',
                        data: data.data,
                        backgroundColor: data.colors,
                        borderColor: data.colors.map(c => c.replace('0.8', '1')),
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            plugins: {
                                annotation: {
                                    annotations: {
                                        threshold: {
                                            type: 'line',
                                            yMin: data.passingThreshold,
                                            yMax: data.passingThreshold,
                                            borderColor: 'rgba(239, 68, 68, 0.5)',
                                            borderWidth: 2,
                                            borderDash: [5, 5],
                                            label: {
                                                content: ['Passing Threshold (50%)'],
                                                enabled: true,
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                },
                plugins: [{
                    afterDatasetsDraw: function(chart) {
                        // Draw passing threshold line
                        const yScale = chart.scales.y;
                        const yPixel = yScale.getPixelForValue(50);
                        const xStart = chart.chartArea.left;
                        const xEnd = chart.chartArea.right;
                        const ctx = chart.ctx;

                        ctx.save();
                        ctx.strokeStyle = 'rgba(239, 68, 68, 0.3)';
                        ctx.lineWidth = 2;
                        ctx.setLineDash([5, 5]);
                        ctx.beginPath();
                        ctx.moveTo(xStart, yPixel);
                        ctx.lineTo(xEnd, yPixel);
                        ctx.stroke();
                        ctx.restore();
                    }
                }]
            });
        } catch (error) {
            console.error('Error loading syllabus mastery data:', error);
        }
    });
</script>
@endsection
