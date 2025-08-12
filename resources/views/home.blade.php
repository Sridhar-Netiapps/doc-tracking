@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
@include('layouts.welcome')

<div class="container-fluid mt-3 ">
    <div class="bigCard">
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-2 cardBox">
                <h2>{{$total_doc}}</h2>
                <p>Total Documents</p>
            </div>
            <div class="col-2 cardBox Yellow">
                <h2>{{($total_pending) + ($total_dispatch) + ($total_transist) + ($total_rejected)}}</h2>
                <p>Total Pending</p>
            </div>
            <div class="col-6 cardBox">
                <div class="row justify-content-center align-items-center text-center">
                    <div class="col-3">
                        <h2>{{$total_pending}}</h2>
                        <p>Pending to Proceed</p>
                    </div>
                    <div class="col-4">
                        <h2>{{$total_dispatch}}</h2>
                        <p>Awaiting Checker Approval</p>
                    </div>
                    <div class="col-2">
                        <h2>{{$total_transist}}</h2>
                        <p>In Transit</p>
                    </div>
                    <div class="col-3">
                        <h2>{{$total_rejected}}</h2>
                        <p>Rejected By RO</p>
                    </div>
                </div>
            </div>
            <div class="col-2 cardBox Yellow">
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
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($loan_total[1] ?? 0) + ($loan_total[2] ?? 0) + ($loan_total[3] ?? 0) + ($loan_total[4] ?? 0) + ($loan_total[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed<span class="value">{{($loan_total[1] ?? 0) + ($loan_total[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($loan_total[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($loan_total[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($loan_total[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($loan_total[5] ?? 0) + ($loan_total[7] ?? 0) + ($loan_total[8] ?? 0) + ($loan_total[9] ?? 0) + ($loan_total[10] ?? 0) + ($loan_total[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($loan_total[5] ?? 0) + ($loan_total[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($loan_total[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($loan_total[5] ?? 0) + ($loan_total[8] ?? 0) + ($loan_total[9] ?? 0) + ($loan_total[10] ?? 0) + ($loan_total[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($loan_total[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($loan_total[8] ?? 0) + ($loan_total[9] ?? 0) + ($loan_total[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($loan_total[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($loan_total[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($loan_total[9] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($loan_total[11] ?? 0)}}</div>
                </div>
                @endunless

            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Gold Loan Docs</h3>
                    <div class="value invert">{{array_sum($gold_loan_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($gold_loan_total[1] ?? 0) + ($gold_loan_total[2] ?? 0) + ($gold_loan_total[3] ?? 0) + ($gold_loan_total[4] ?? 0) + ($gold_loan_total[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($gold_loan_total[1] ?? 0) + ($gold_loan_total[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($gold_loan_total[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($gold_loan_total[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($gold_loan_total[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($gold_loan_total[5] ?? 0) + ($gold_loan_total[7] ?? 0) + ($gold_loan_total[8] ?? 0) + ($gold_loan_total[9] ?? 0) + ($gold_loan_total[10] ?? 0) + ($gold_loan_total[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($gold_loan_total[5] ?? 0) + ($gold_loan_total[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($gold_loan_total[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($gold_loan_total[5] ?? 0) + ($gold_loan_total[8] ?? 0) + ($gold_loan_total[9] ?? 0) + ($gold_loan_total[10] ?? 0) + ($gold_loan_total[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($gold_loan_total[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($gold_loan_total[8] ?? 0) + ($gold_loan_total[9] ?? 0) + ($gold_loan_total[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($gold_loan_total[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($gold_loan_total[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($gold_loan_total[9] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($gold_loan_total[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Liablities Docs</h3>
                    <div class="value invert">{{array_sum($aof_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($aof_total[1] ?? 0) + ($aof_total[2] ?? 0) + ($aof_total[3] ?? 0) + ($aof_total[4] ?? 0) + ($aof_total[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($aof_total[1] ?? 0) + ($aof_total[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($aof_total[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($aof_total[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($aof_total[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($aof_total[5] ?? 0) + ($aof_total[7] ?? 0) + ($aof_total[8] ?? 0) + ($aof_total[9] ?? 0) + ($aof_total[10] ?? 0) + ($aof_total[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($aof_total[5] ?? 0) + ($aof_total[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($aof_total[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($aof_total[5] ?? 0) + ($aof_total[8] ?? 0) + ($aof_total[9] ?? 0) + ($aof_total[10] ?? 0) + ($aof_total[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($aof_total[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($aof_total[8] ?? 0) + ($aof_total[9] ?? 0) + ($aof_total[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($aof_total[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($aof_total[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($aof_total[9] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($aof_total[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>DTR Files</h3>
                    <div class="value invert">{{array_sum($dtrf_total)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($dtrf_total[1] ?? 0) + ($dtrf_total[2] ?? 0) + ($dtrf_total[3] ?? 0) + ($dtrf_total[4] ?? 0) + ($dtrf_total[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($dtrf_total[1] ?? 0) + ($dtrf_total[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($dtrf_total[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($dtrf_total[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($dtrf_total[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($dtrf_total[5] ?? 0) + ($dtrf_total[7] ?? 0) + ($dtrf_total[8] ?? 0) + ($dtrf_total[9] ?? 0) + ($dtrf_total[10] ?? 0) + ($dtrf_total[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($dtrf_total[5] ?? 0) + ($dtrf_total[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($dtrf_total[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($dtrf_total[5] ?? 0) + ($dtrf_total[8] ?? 0) + ($dtrf_total[9] ?? 0) + ($dtrf_total[10] ?? 0) + ($dtrf_total[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($dtrf_total[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($dtrf_total[8] ?? 0) + ($dtrf_total[9] ?? 0) + ($dtrf_total[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($dtrf_total[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($dtrf_total[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($dtrf_total[9] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($dtrf_total[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3 ">
    <div class="bigCard">
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-2 cardBox">
                <h2>{{$total_doc_today}}</h2>
                <p>Today's Activity</p>
            </div>
            <div class="col-2 cardBox Yellow">
                <h2>{{($total_pending_today) + ($total_dispatch_today) + ($total_transist_today) + ($total_rejected_today)}}</h2>
                <p>Total Pending</p>
            </div>
            <div class="col-6 cardBox">
                <div class="row justify-content-center align-items-center text-center">
                    <div class="col-3">
                        <h2>{{$total_pending_today}}</h2>
                        <p>Pending to Proceed</p>
                    </div>
                    <div class="col-4">
                        <h2>{{$total_dispatch_today}}</h2>
                        <p>Awaiting Checker Approval</p>
                    </div>
                    <div class="col-2">
                        <h2>{{$total_transist_today}}</h2>
                        <p>In Transit</p>
                    </div>
                    <div class="col-3">
                        <h2>{{$total_rejected_today}}</h2>
                        <p>Rejected By RO</p>
                    </div>
                </div>
            </div>
            <div class="col-2 cardBox Yellow">
                <h2>{{$total_received_today}}</h2>
                <p>Received Documents</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>MB Loan Docs</h3>
                    <div class="value invert">{{array_sum($loan_today)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($loan_today[1] ?? 0) + ($loan_today[2] ?? 0) + ($loan_today[3] ?? 0) + ($loan_today[4] ?? 0) + ($loan_today[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed<span class="value">{{($loan_today[1] ?? 0) + ($loan_today[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($loan_today[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($loan_today[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($loan_today[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($loan_today[5] ?? 0) + ($loan_today[7] ?? 0) + ($loan_today[8] ?? 0) + ($loan_today[9] ?? 0) + ($loan_today[10] ?? 0) + ($loan_today[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($loan_today[5] ?? 0) + ($loan_today[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($loan_today[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($loan_today[5] ?? 0) + ($loan_today[8] ?? 0) + ($loan_today[9] ?? 0) + ($loan_today[10] ?? 0) + ($loan_today[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($loan_today[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($loan_today[8] ?? 0) + ($loan_today[9] ?? 0) + ($loan_today[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($loan_today[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($loan_today[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($loan_today[10] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($loan_today[11] ?? 0)}}</div>
                </div>
                @endunless

            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Gold Loan Docs</h3>
                    <div class="value invert">{{array_sum($gold_loan_today)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($gold_loan_today[1] ?? 0) + ($gold_loan_today[2] ?? 0) + ($gold_loan_today[3] ?? 0) + ($gold_loan_today[4] ?? 0) + ($gold_loan_today[6] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($gold_loan_today[1] ?? 0) + ($gold_loan_today[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($gold_loan_today[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($gold_loan_today[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($gold_loan_today[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($gold_loan_today[5] ?? 0) + ($gold_loan_today[7] ?? 0) + ($gold_loan_today[8] ?? 0) + ($gold_loan_today[9] ?? 0) + ($gold_loan_today[10] ?? 0) + ($gold_loan_today[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($gold_loan_today[5] ?? 0) + ($gold_loan_today[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($gold_loan_today[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($gold_loan_today[5] ?? 0) + ($gold_loan_today[8] ?? 0) + ($gold_loan_today[9] ?? 0) + ($gold_loan_today[10] ?? 0) + ($gold_loan_today[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($gold_loan_today[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($gold_loan_today[8] ?? 0) + ($gold_loan_today[9] ?? 0) + ($gold_loan_today[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($gold_loan_today[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($gold_loan_today[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($gold_loan_today[10] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($gold_loan_today[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>Liablities Docs</h3>
                    <div class="value invert">{{array_sum($aof_today)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($aof_today[1] ?? 0) + ($aof_today[2] ?? 0) + ($aof_today[3] ?? 0) + ($aof_today[4] ?? 0) + ($aof_today[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($aof_today[1] ?? 0) + ($aof_today[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($aof_today[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($aof_today[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($aof_today[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($aof_today[5] ?? 0) + ($aof_today[7] ?? 0) + ($aof_today[8] ?? 0) + ($aof_today[9] ?? 0) + ($aof_today[10] ?? 0) + ($aof_today[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($aof_today[5] ?? 0) + ($aof_today[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($aof_today[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($aof_today[5] ?? 0) + ($aof_today[8] ?? 0) + ($aof_today[9] ?? 0) + ($aof_today[10] ?? 0) + ($aof_today[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($aof_today[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($aof_today[8] ?? 0) + ($aof_today[9] ?? 0) + ($aof_today[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($aof_today[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($aof_today[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($aof_today[10] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($aof_today[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
        <div class="col">
            <div class="smallCard">
                <div class="listView">
                    <h3>DTR Files</h3>
                    <div class="value invert">{{array_sum($dtrf_today)}}</div>
                </div>
                <div class="listView">
                    <div class="label">Pending Docs</div>
                    <div class="value">{{ ($dtrf_today[1] ?? 0) + ($dtrf_today[2] ?? 0) + ($dtrf_today[3] ?? 0) + ($dtrf_today[4] ?? 0) + ($dtrf_today[6] ?? 0) }}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Pending to Proceed <span class="value">{{($dtrf_today[1] ?? 0) + ($dtrf_today[2] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Awaiting Checker Approval <span class="value">{{($dtrf_today[3] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">In Transit <span class="value">{{($dtrf_today[4] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Rejected By RO <span class="value">{{($dtrf_today[6] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Received Docs</div>
                    @hasrole('bo-maker|bo-checker')
                    <div class="value">{{($dtrf_today[5] ?? 0) + ($dtrf_today[7] ?? 0) + ($dtrf_today[8] ?? 0) + ($dtrf_today[9] ?? 0) + ($dtrf_today[10] ?? 0) + ($dtrf_today[11] ?? 0) }}</div>
                    @else
                    <div class="value">{{($dtrf_today[5] ?? 0) + ($dtrf_today[7] ?? 0)}}</div>
                    @endhasrole
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">Received with Query<span class="value">{{($dtrf_today[7] ?? 0)}}</span></div>
                        </li>
                        <li>
                            @hasrole('bo-maker|bo-checker')
                            <div class="label">Received <span class="value">{{($dtrf_today[5] ?? 0) + ($dtrf_today[8] ?? 0) + ($dtrf_today[9] ?? 0) + ($dtrf_today[10] ?? 0) + ($dtrf_today[11] ?? 0) }}</span></div>
                            @else
                            <div class="label">Received <span class="value">{{($dtrf_today[5] ?? 0)}}</span></div>
                            @endhasrole
                        </li>
                    </ul>
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="listView">
                    <div class="label">Moved to RMA</div>
                    <div class="value">{{($dtrf_today[8] ?? 0) + ($dtrf_today[9] ?? 0) + ($dtrf_today[10] ?? 0)}}</div>
                </div>
                <div class="listView">
                    <ul>
                        <li>
                            <div class="label">IN <span class="value text-end">{{($dtrf_today[8] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">OUT <span class="value">{{($dtrf_today[9] ?? 0)}}</span></div>
                        </li>
                        <li>
                            <div class="label">Permout <span class="value">{{($dtrf_today[10] ?? 0)}}</span></div>
                        </li>
                    </ul>
                </div>
                <div class="listView">
                    <div class="label">Destroyed </div>
                    <div class="value">{{($dtrf_today[11] ?? 0)}}</div>
                </div>
                @endunless
            </div>
        </div>
    </div>
</div>

<div class="container">
    <footer class="py-3 my-4">
        <p class="text-center text-muted">© 2025 Ujjivan Small Finance Bank Ltd</p>
    </footer>
</div>

@endsection
