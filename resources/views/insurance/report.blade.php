@extends('layouts.insurance-app')
@section('content')

<div class="container-dashboard py-2">
    <div class="container-dashboard">
       <div class="align-items-center m-2">
            <div class="d-flex align-items-center">
                <img src="/images/note.svg">
                <strong>Report</strong> 

                <div class="ms-auto ">
                    <div class="d-flex">
                       <a class="nav-link" href="{{route('leads_report')}}"><i class="fa fa-sync m-3"></i></a>
                       <div class="ms-auto ">
                            <div class="card">
                             <div id="reportrange" class="pull-right datepiker p-2" >
                                <i class="glyphicon glyphicon-calendar fa fa-calendar" max="<?php echo date('Y-m-d');  ?>"></i>&nbsp;
                                <span name="daterange"></span> <b class="caret"></b>
                               
                             </div> 
                           </div>
                       </div>
                     
                     </div> 

              
                </div>

                <a target="_blank" href="{{ asset('insurance_exports/insurance_reports.csv')}}"  <button
                    type="button"
                    class="btn btn-dark rounded-2 ms-3"
                    >
                    Export ALL Leads
                </button> </a> 

            </div>

            <div class="mt-2">
             <form method="GET" action="{{route('leads_report')}}">
               <input type="hidden" id="start" name="start" value="{{$start}}">
               <input type="hidden" id="end" name="end" value="{{ $end}}">
               
               <div class="input-group mb-3">
                <input class="form-control clsAlphaNoOnly" type="text" name="search" placeholder="Search" value="{{ $search}}">

                <select class="form-control form-select border-0 p-2 ms-3" name="region">
                    <option value=""> All Regions</option>
                    <option {{($region == 'South')?'selected':''}} value="South">South</option>
                    <option {{($region == 'North')?'selected':''}} value="North">North</option>
                    <option {{($region == 'East')?'selected':''}} value="East">East</option>
                    <option {{($region == 'West')?'selected':''}} value="West">West</option>
                </select>

                <select class="select2 form-control border-0" name="branch" id="branch2">
                    <option value="">All Branch</option>
                    @foreach($branches as $key=>$val)
                       <option {{ ($branch == ($val->code."-".$val->name) ) ? 'selected':''}} value="{{ $val->code}}-{{ $val->name}}">{{ $val->code}}-{{ $val->name}}</option>
                    @endforeach
                    
                </select>

                <select class="form-control form-select border-0 ms-3" name="partner" required >
                    <option value="">All Partner</option>
                    @foreach($partners as $key=>$val)
                     <option {{ ($val->partner == $partner)?'selected':''}} value="{{$val->partner}}">{{$val->partner}}</option>
                    @endforeach
                </select>

                <select class="form-control form-select border-0 p-2 ms-3" name="product">
                    <option value="">All Products</option>
                    @foreach($products as $key=>$val)
                     <option {{ ($val->product == $product)?'selected':''}} value="{{$val->product}}">{{$val->product}}</option>
                    @endforeach
                </select>

                <select class="form-control form-select border-0 p-2 ms-3" name="status">
                    <option value="">All Claim Status</option>
                    @foreach($claimstatus as $key=>$val)
                     <option {{ ($val->claim_status == $claim_status)?'selected':''}} value="{{$val->claim_status}}">{{$val->claim_status}}</option>
                    @endforeach
                </select>

                <select class="form-control form-select border-0 p-2 ms-3" name="proccesed">
                    <option value="">Processed By</option>
                    @foreach($procesedby as $val)
                     <option {{ ($val == $proccesed)?'selected':''}} value="{{$val}}">{{$val}}</option>
                    @endforeach
                </select>

               

                <div class="input-group-prepend ms-3">
                   <button class="btn btn-success rounded-2" id="getdata"  name="action" value="filter">Filter</button>

                    <button class="btn btn-warning rounded-2 ms-3" id="btn_export"  name="action" value="export" value="export">Export</button> 
                </div>
               </div>
             </form>
            </div>
 
        </div>
          

      </div>

<span class="ms-3 text-danger"> NOTE : Please use multiple filters if data export is failed </span>
<div class="mt-3 p-3">
      <div class="table-responsive tablescrollable">
        <table class="table  table-bordered" >
            <thead class="table-dark">
                <th class="text-nowrap">Created Date</th>
                <th class="text-nowrap">Last Modified Date</th>
                <th class="text-nowrap">Lead ID</th>
                <th class="text-nowrap">Intimation Date</th>
                <th class="text-nowrap">Region</th>
                <th class="text-nowrap">Branch</th> 
                <th class="text-nowrap">Partner</th>
                <th class="text-nowrap">Product</th>
                <th class="text-nowrap">CIF ID</th>
                <th class="text-nowrap">Deceased Name</th>
                <th class="text-nowrap">Deceased Type</th>
                <th class="text-nowrap">Gender</th>
                <th class="text-nowrap">Loan Acc No</th>
                <th class="text-nowrap">Cause of Death</th>
                <th class="text-nowrap">Loan Tenure</th>
                <th class="text-nowrap">Date of Death</th>
                <th class="text-nowrap">Claim Status</th>
                <th class="text-nowrap">Processed By</th>
                <th class="text-nowrap">CAS Status</th>
                <th class="text-nowrap">RL Status</th>
                <th class="text-nowrap">Recovery Status</th>
                <th class="text-nowrap">Write-Off Status</th>
            </thead>

            <tbody>
                @foreach($data as $key=>$value)
                <tr>
                    <td>{{ date('d-m-Y',strtotime($value->created_at))}}</td>
                    <td>{{ date('d-m-Y',strtotime($value->updated_at))}}</td>
                    <td>{{ $value->utrn}}</td>
                    <td>{{ date('d-m-Y',strtotime($value->intimation_date))}}</td>
                    <td>{{ $value->region}}</td>
                    <td>{{ $value->branch}}</td> 
                    <td>{{ $value->partner}}</td>
                    <td>{{ $value->product}}</td>
                    <td>{{ $value->cust_id}}</td>
                    <td>{{ $value->deceased_name}}</td>
                    <td>{{ $value->deceased}}</td>
                    <td>{{ $value->gender}}</td>
                    <td>{{ $value->load_acc_id}}</td>
                    
                    <td>{{ $value->cause_of_death}}</td>
                    <td>{{ $value->loan_tenure}}</td>
                    <td>{{ date('d-m-Y',strtotime($value->date_of_death))}}</td>
                    <td>{{ $value->cliam_status}}</td>
                    <td>{{ $value->processed_by}}</td>
                    <td>{{ $value->cas_status}}</td>
                    <td>{{ $value->rl_status}}</td>
                    <td>{{ $value->recovery_status }}</td>
                    <td>{{ $value->write_off_status}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>

<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
$(function() {  
   
    var startdate = $('#start').val();
    var enddate = $('#end').val();


    var today = moment(); // Current date

    if(startdate === ''){
    var start = moment().month() < 3 
    ? moment().subtract(1, 'year').startOf('year').month(3).date(1) // Start of last year's April
    : moment().startOf('year').month(3).date(1); // Start of this year's April
     var end = moment();
   }
   else{
    var start = moment(startdate);
    var end = moment(enddate);
   }

   
    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        const formattedStart = start.format('YYYY-MM-DD');
        const formattedEnd = end.format('YYYY-MM-DD');

        $('#start').val(formattedStart);
        $('#end').val(formattedEnd);
        
        
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);


    $('select').on('change', function() {
      cb(start, end);
     
    });

$('#btn_export').on('click', function (e) {
    e.preventDefault();

    let btn = $(this);
    btn.prop('disabled', true).text('Generating...');

    $.post('{{ route("insurance.leads_export") }}',
        $('form').serialize(),
        function (res) {

            let file = res.file;

            let interval = setInterval(function () {

                $.get('/insurance/export/status/' + file, function (status) {

                    if (status.ready) {
                        clearInterval(interval);

                        let a = document.createElement('a');
                        a.href = '/insurance/export/download/' + file;
                        a.download = file;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);

                        btn.prop('disabled', false).text('Export');
                    }
                });

            }, 3000);
        }
    ).fail(function (xhr) {
        console.error(xhr.responseText);
        alert('Export failed');
        btn.prop('disabled', false).text('Export');
    });
});

$('#btn_export_full').click(function (e) {
    e.preventDefault();

    let btn = $(this);
    btn.prop('disabled', true).text('Generating...');

    $.post('/insurance/export-all', {}, function (res) {

        if (!res.file) {
            alert('Export failed');
            btn.prop('disabled', false).text('Export');
            return;
        }

        let file = res.file;

        let timer = setInterval(function () {
            $.get('/insurance/export/status/' + file, function (r) {

                if (r.ready) {
                    clearInterval(timer);

                    window.location.href =
                        '/insurance/export/download/' + file;

                    btn.prop('disabled', false).text('Export');
                }
            });
        }, 4000);
    }).fail(function () {
        alert('Server error');
        btn.prop('disabled', false).text('Export');
    });
});

$('#btn_export_full2').on('click', function () {

    let btn = $(this);
    btn.prop('disabled', true).text('Generating...');

    $.ajax({
        url: "{{ route('insurance.export.all') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {

            console.log(res);

            if (!res.file) {
                alert('File not generated');
                btn.prop('disabled', false).text('Export');
                return;
            }

            window.location.href =
                '/insurance/export/download/' + res.file;

            btn.prop('disabled', false).text('Export');
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Export failed');
            btn.prop('disabled', false).text('Export');
        }
    });
});
    
});




</script>

@endsection