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
        <div class="d-flex mb-3"><h2>Account Creation</h2> <div class="ms-2"><a href="/">View All</a></div></div>

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
                            <p class="card-text">Total Submitted</p>
                            <h3>12,393</h3>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-3.svg" /></div>
                            <p class="card-text">Total Submitted</p>
                            <h3>12,393</h3>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="icon"><img src="/images/icon-4.svg" /></div>
                            <p class="card-text">Total Submitted</p>
                            <h3>12,393</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="dashboardCards">
            <div class="row">
                <div class="col-6">
                    <div class="card border-0">
                        <div class="card-body"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0">
                        <div class="card-body"></div>
                    </div>
                </div>
            </div>
        </div>






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
