@extends('layouts.app')

@section('content')
    <div class="bg-new">
        <div class="container">
            <div class="row justify-content-start align-items-center">
                <div class="col-3">
                    <select class="form-select" data-bs-placement="Select Category">
                        <option>Account Creation</option>
                        <option>Voucher</option>
                        <option>Insurance</option>
                    </select>


                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row justify-content-center">
        <div class="d-flex mb-3"><h2>Account Creation</h2> <div class="ms-2"><a href="/sample">View All</a></div></div>

        <div class="headerCards">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-1.svg" /></div>
                            <p class="card-text">Total Submitted</p>
                            <h3>12,393</h3>

                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-2.svg" /></div>
                            <p class="card-text">Pending Approvals</p>
                            <h3>9,393</h3>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-3.svg" /></div>
                            <p class="card-text">Verified Accounts</p>
                            <h3>1,393</h3>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-4.svg" /></div>
                            <p class="card-text">Rejected Requests</p>
                            <h3>5,211</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="dashboardCards mt-4">
                <div class="row">
                    <!-- Line Chart -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Submissions Over Time</h5>
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Doughnut Chart -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Category Breakdown</h5>
                                <canvas id="doughnutChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
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
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
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
    </script>





{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    @if (session('status'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('status') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    {{ __('You are logged in!') }}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>
@endsection
