<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics Dashboard</h1>
    </div>

    <!-- User Growth Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Growth (Last 30 Days)</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quiz Performance Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Quiz Performance (Last 30 Days)</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="quizPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Domain Popularity Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Domain Popularity</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="domainPopularityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Application Stats Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Application Status</h6>
        </div>
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
                <canvas id="applicationStatsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    const userGrowthChart = new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($data['user_growth']->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($data['user_growth']->pluck('count')) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointHoverRadius: 3,
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Quiz Performance Chart
    const quizPerformanceCtx = document.getElementById('quizPerformanceChart').getContext('2d');
    const quizPerformanceChart = new Chart(quizPerformanceCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($data['quiz_performance']->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
            datasets: [{
                label: 'Average Score (%)',
                data: {!! json_encode($data['quiz_performance']->pluck('avg_score')) !!},
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderColor: 'rgba(28, 200, 138, 1)',
                pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(28, 200, 138, 1)',
                pointRadius: 3,
                pointHoverRadius: 3,
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });

    // Domain Popularity Chart
    const domainPopularityCtx = document.getElementById('domainPopularityChart').getContext('2d');
    const domainPopularityChart = new Chart(domainPopularityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['domain_popularity']->pluck('text')) !!},
            datasets: [{
                label: 'Slide Attempts',
                data: {!! json_encode($data['domain_popularity']->pluck('slides_count')) !!},
                backgroundColor: 'rgba(54, 185, 204, 0.5)',
                borderColor: 'rgba(54, 185, 204, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Application Stats Chart
    const applicationStatsCtx = document.getElementById('applicationStatsChart').getContext('2d');
    const applicationStatsChart = new Chart(applicationStatsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($data['application_stats']->pluck('status')->map(function($status) { return ucfirst($status); })) !!},
            datasets: [{
                data: {!! json_encode($data['application_stats']->pluck('count')) !!},
                backgroundColor: [
                    'rgba(246, 194, 62, 0.8)',  // Pending (yellow)
                    'rgba(28, 200, 138, 0.8)',   // Accepted (green)
                    'rgba(231, 74, 59, 0.8)',    // Rejected (red)
                    'rgba(54, 185, 204, 0.8)'    // Reviewed (blue)
                ],
                borderColor: [
                    'rgba(246, 194, 62, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(231, 74, 59, 1)',
                    'rgba(54, 185, 204, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endsection