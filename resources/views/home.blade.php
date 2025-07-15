@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container mt-3 ">
    <div class="bigCard">
        <div class="row justify-content-center align-items-center text-center">
            <div class="col">
                <h2>{{$total_doc}}</h2>
                <p>Total Documents</p>
            </div>
            <div class="col">
                <h2>{{$total_selected}}</h2>
                <p>Pending to Proceed</p>
            </div>
            <div class="col">
                <h2>{{$total_dispatch}}</h2>
                <p>Awaiting Checker Approval</p>
            </div>
            <div class="col">
                <h2>{{$total_transist}}</h2>
                <p>In Transit</p>
            </div>
            <div class="col">
                <h2>{{$total_rejected}}</h2>
                <p>Rejected By RO</p>
            </div>
            <div class="col">
                <h2>{{$total_received}}</h2>
                <p>Received Documents</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>MB Loan Docs</h3>
                    <div class="value invert">{{array_sum($loan_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending to Proceed</div>
                    <div class="value">{{ ($loan_total[1] ?? 0) + ($loan_total[2] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <div class="label">Awaiting Checker Approval</div>
                    <div class="value">{{($loan_total[3] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">In Transit</div>
                    <div class="value">{{($loan_total[4] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Rejected By RO</div>
                    <div class="value">{{($loan_total[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Received Documents</div>
                    <div class="value">{{($loan_total[5] ?? 0) + ($loan_total[7] ?? 0)}}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Gold Loan Docs</h3>
                    <div class="value invert">{{array_sum($gold_loan_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending to Proceed</div>
                    <div class="value">{{ ($gold_loan_total[1] ?? 0) + ($gold_loan_total[2] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <div class="label">Awaiting Checker Approval</div>
                    <div class="value">{{($gold_loan_total[3] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">In Transit</div>
                    <div class="value">{{($gold_loan_total[4] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Rejected By RO</div>
                    <div class="value">{{($gold_loan_total[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Received Documents</div>
                    <div class="value">{{($gold_loan_total[5] ?? 0) + ($gold_loan_total[7] ?? 0)}}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Liablities Docs</h3>
                    <div class="value invert">{{array_sum($aof_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending to Proceed</div>
                    <div class="value">{{ ($aof_total[1] ?? 0) + ($aof_total[2] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <div class="label">Awaiting Checker Approval</div>
                    <div class="value">{{($aof_total[3] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">In Transit</div>
                    <div class="value">{{($aof_total[4] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Rejected By RO</div>
                    <div class="value">{{($aof_total[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Received Documents</div>
                    <div class="value">{{($aof_total[5] ?? 0) + ($aof_total[7] ?? 0)}}</div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>DTR Files</h3>
                    <div class="value invert">{{array_sum($dtrf_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending to Proceed</div>
                    <div class="value">{{ ($dtrf_total[1] ?? 0) + ($dtrf_total[2] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <div class="label">Awaiting Checker Approval</div>
                    <div class="value">{{($dtrf_total[3] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">In Transit</div>
                    <div class="value">{{($dtrf_total[4] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Rejected By RO</div>
                    <div class="value">{{($dtrf_total[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Received Documents</div>
                    <div class="value">{{($dtrf_total[5] ?? 0) + ($dtrf_total[7] ?? 0)}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        {{-- <div class="col-1"></div>
        <div class="col-10"> --}}
        <div class="dashboardCards mt-4">
            <div class="row">
                <!-- Line Chart -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Submissions Over Time</h5>
                            <div style="height: 300px;">
                                <canvas id="lineChart" style="width: 100%; height: 100% !important;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Category Breakdown</h5>
                            <div style="height: 300px; margin: auto">
                                <canvas id="doughnutChart" style="width: 100%; height: 100% !important;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Verifications</h5>
                            <div style="height: 300px;">
                                <canvas id="barChart" style="width: 100%; height: 100% !important;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <footer class="py-3 my-4">

        <p class="text-center text-muted">© 2025 Ujjivan Small Finance Bank Ltd</p>
    </footer>
</div>


    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Chart Scripts --}}
    <script>
        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Submissions',
                    data: [1200, 1900, 3000, 2500, 2800],

                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Doughnut Chart
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Account Creation', 'Voucher', 'Insurance'],
                datasets: [{
                    label: 'Categories',
                    data: [55, 25, 20],

                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

                // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Verified',
                    data: [300, 500, 800, 600, 900],

                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>
    </div>
</div>
@endsection
