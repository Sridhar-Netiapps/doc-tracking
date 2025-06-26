@extends('layouts.insurance-app')
@section('content')

<div class="container-dashboard py-2">
    <div class="container-dashboard">
    	 <div class="align-items-center m-2">
            <div class="d-flex align-items-center">
                <img src="/images/note.svg">
                <strong>Report</strong> 

                <div class="ms-auto card">
                     <div id="reportrange" class="pull-right datepiker p-2" >
                        <i class="glyphicon glyphicon-calendar fa fa-calendar" max="<?php echo date('Y-m-d');  ?>"></i>&nbsp;
                        <span name="daterange"></span> <b class="caret"></b>
                       
                     </div>  
              
                </div>

            </div>

            <div class="mt-2">
             <form method="GET" action="{{route('leads_report')}}">
               <input type="hidden" id="start" name="start" value="{{$start}}">
               <input type="hidden" id="end" name="end" value="{{ $end}}">
               
               <div class="input-group mb-3">
                <input class="form-control " type="text" name="search" placeholder="Search" >

                <select class="form-control border-0 p-2 ms-3">
                    <option value=""> Region</option>
                    <option value="South">South</option>
                    <option value="North">North</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                </select>

                <select class="form-control border-0 ms-3">
                    <option value="">Branch</option>
                    @foreach($partners as $key=>$val)
                     <option value="{{$val->partner}}">{{$val->partner}}</option>
                    @endforeach
                </select>

                <select class="form-control border-0 ms-3">
                    <option value="">Partner</option>
                    @foreach($partners as $key=>$val)
                     <option value="{{$val->partner}}">{{$val->partner}}</option>
                    @endforeach
                </select>

                <select class="form-control border-0 p-2 ms-3">
                    <option value="">Product</option>
                    @foreach($products as $key=>$val)
                     <option value="{{$val->product}}">{{$val->product}}</option>
                    @endforeach
                </select>

                <select class="form-control border-0 p-2 ms-3">
                    <option value="">Claim Status</option>
                    @foreach($claimstatus as $key=>$val)
                     <option value="{{$val->cliam_status}}">{{$val->claim_status}}</option>
                    @endforeach
                </select>

                <select class="form-control border-0 p-2 ms-3">
                    <option value="">Processed By</option>
                    @foreach($procesedby as $val)
                     <option value="{{$val}}">{{$val}}</option>
                    @endforeach
                </select>



                <button class="btn btn-dark ms-3" type="submit" >Export</button> 

                <div class="input-group-prepend">
                   <button class="btn btn-dark rounded-0 d-none" id="getdata" type="submit" ></button>
                </div>
               </div>
             </form>
            </div>
 
        </div>
	    

	</div>


<div class="mt-3 p-3">
	<div class="table-responsive tablescrollable">
        <table class="table  table-bordered" >
            <thead class="table-dark">
                <th class="text-nowrap">Lead ID</th>
                <th class="text-nowrap">Creation Date</th>
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
                <th class="text-nowrap">CAS Status</th>
                <th class="text-nowrap">RL Status</th>
                <th class="text-nowrap">Recovery Status</th>
                <th class="text-nowrap">Write-Off Status</th>
            </thead>

            <tbody>
                @foreach($data as $key=>$value)
                <tr>
                    <td>{{ $value->utrn}}</td>
                     <td>{{ date('d M,Y H:i',strtotime($value->created_at))}}</td>
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
                    <td>{{ $value->date_of_death}}</td>
                    <td>{{ $value->cliam_status}}</td>
                    <td>{{ $value->cas_status}}</td>
                    <td>{{ $value->rl_status}}</td>
                    <td>{{ $value->recovery_status}}</td>
                    <td>{{ $value->write_off_status}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>

<script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
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
    
});
</script>

@endsection