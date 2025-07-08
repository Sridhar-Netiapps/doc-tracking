@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid mt-3 ">
    <div class="row justify-content-center">
        {{-- <div class="col-1"></div>
        <div class="col-10"> --}}
        {{-- <div class="d-flex mb-3"><div class="ms-2"><a href="/accounts/accounts">Filters</a></div></div> --}}
            <div class="headerCards">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-2.svg" /></div>
                                <p class="card-text">Total Documents</p>
                                <h3>{{$total_doc}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-4.svg" /></div>
                                <p class="card-text">Total Pending Documents</p>
                                <h3>{{$total_pending}}</h3>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-2.svg" /></div>
                                <p class="card-text">Pending to Proceed</p>
                                <h3>{{$total_selected}}/{{$total_doc}}</h3>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-2.svg" /></div>
                                <p class="card-text">Pending for Dispatch</p>
                                <h3>{{$total_dispatch}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-3.svg" /></div>
                                <p class="card-text">In Transit</p>
                                <h3>{{$total_transist}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-4.svg" /></div>
                                <p class="card-text">Rejected By RO</p>
                                <h3>{{$total_rejected}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="icon"><img src="/images/icon-4.svg" /></div>
                                <p class="card-text">Received Documents</p>
                                <h3>{{$total_received}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">Pending Documents</p>
                                <h3>{{array_sum($loan_total)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">Pending to Proceed</p>
                                <h3>{{ ($loan_total[1] ?? 0) + ($loan_total[2] ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">Pending for Dispatch</p>
                                <h3>{{($loan_total[3] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">In Transit</p>
                                <h3>{{($loan_total[4] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">Rejected By RO</p>
                                <h3>{{($loan_total[6] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-primary">MB Loan</span></div>
                                </div>
                                <p class="card-text">Received Documents</p>
                                {{-- <h3>{{($loan_total[5] ?? 0)}}</h3> --}}
                                <h3>{{ ($loan_total[5] ?? 0) + ($loan_total[7] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">Pending Documents</p>
                                <h3>{{array_sum($gold_loan_total)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">Pending to Proceed</p>
                                <h3>{{ ($gold_loan_total[1] ?? 0) + ($gold_loan_total[2] ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">Pending for Dispatch</p>
                                <h3>{{($gold_loan_total[3] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">In Transit</p>
                                <h3>{{($gold_loan_total[4] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">Rejected By RO</p>
                                <h3>{{($gold_loan_total[6] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-secondary">Gold Loan</span></div>
                                </div>
                                <p class="card-text">Received Documents</p>
                                <h3>{{($gold_loan_total[5] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">Pending Documents</p>
                                <h3>{{array_sum($dtrf_total)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">Pending to Proceed</p>
                                <h3>{{ ($dtrf_total[1] ?? 0) + ($dtrf_total[2] ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">Pending for Dispatch</p>
                                <h3>{{($dtrf_total[3] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">In Transit</p>
                                <h3>{{($dtrf_total[4] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">Rejected By RO</p>
                                <h3>{{($dtrf_total[6] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-warning">DTR Files</span></div>
                                </div>
                                <p class="card-text">Received Documents</p>
                                <h3>{{($dtrf_total[5] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">Pending Documents</p>
                                <h3>{{array_sum($aof_total)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">Pending to Proceed</p>
                                <h3>{{ ($aof_total[1] ?? 0) + ($aof_total[2] ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">Pending for Dispatch</p>
                                <h3>{{($aof_total[3] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-2.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">In Transit</p>
                                <h3>{{($aof_total[4] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">Rejected By RO</p>
                                <h3>{{($aof_total[6] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between"> 
                                    <div class="icon"><img src="/images/icon-4.svg" /></div>
                                    <div class="text-right"><span class="badge rounded-pill bg-info">Liablities Documents</span></div>
                                </div>
                                <p class="card-text">Received Documents</p>
                                <h3>{{($aof_total[5] ?? 0)}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="headerCards">
                    <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon"><img src="/images/icon-1.svg" /></div>
                                    <p class="card-text">Loan Documents</p>
                                    <h3>{{ $loan_total[1] }}</h3>  <!-- Display dynamic total here -->
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon"><img src="/images/icon-3.svg" /></div>
                                    <p class="card-text">Gold Loan Documents</p>
                                    <h3>{{ $gold_loan_total[1] }}</h3>  <!-- Display dynamic total here -->
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon"><img src="/images/icon-3.svg" /></div>
                                    <p class="card-text">AOF</p>
                                    <h3>{{ $aof_total[1] }}</h3>  <!-- Display dynamic total here -->
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon"><img src="/images/icon-3.svg" /></div>
                                    <p class="card-text">DTRF</p>
                                    <h3>{{ $dtrf_total[1] }}</h3>  <!-- Display dynamic total here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
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
        {{-- </div>
        <div class="col-1"></div> --}}
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
                    borderColor: '#229e82',
                    backgroundColor: '#d6f5e8',
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
                    backgroundColor: ['#b0ead5', '#46c1a1', '#229e82'],
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
                    backgroundColor: '#46c1a1'
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
