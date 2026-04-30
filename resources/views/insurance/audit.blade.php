@extends('layouts.insurance-app')
@section('content')

<div class="container-dashboard py-2">
    <div class="container-dashboard">
    	 <div class="d-flex align-items-center m-2">
            <div class="d-flex align-items-center">
                <img src="/images/note.svg">
                <strong>Audit Logs</strong> 
            </div>

            <div class="ms-auto d-flex align-items-center">
              <!-- Date Range Card -->
              <a class="nav-link" href="{{route('audit')}}"><i class="fa fa-sync m-3"></i></a>
                  <div class="card me-3 p-2">
                    <div id="reportrange" class="datepiker">
                      <img src="/images/calend.png" class="syncimg glyphicon glyphicon-calendar fa fa-calendar justify-content-center " max="<?php echo date('Y-m-d'); ?>">&nbsp;
                      <span name="daterange"></span> <b class="caret"></b>
                    </div>
                  </div>

                  <!-- Search Form -->
                  <form class="d-flex" method="GET" action="{{ route('audit') }}">
                    <div class="input-group">
                      <input class="form-control clsAlphaNoOnly" 
                             type="text" 
                             name="search" 
                             placeholder="Search here" 
                             value="{{ $search }}">

                      <input type="hidden" name="start" id="start" value="{{$start_date}}">  
                      <input type="hidden" name="end" id="end" value="{{$end_date}}">   

                      <button class="btn btn-secondary me-1" name="type" type="submit" value="filter">GO</button>
                      <button class="btn btn-dark" name="type" value="export" id="btn_export_audits">Export</button>
                    </div>
                  </form>
                </div>
        </div>
	    

	</div>


<div class="mt-3">
	
    <div class="row py-4 p-4">
    	<div class=" border border-white " >
            <div class="table-responsive p-3">
    		<table class="table table-scrollable table-bordered ">
                <thead class="bg-card-active text-white">
    				<tr class="table-dark">
                        <th class="small">Date</th>
                        <th class="small">Action</th>
                        <th class="small">User Name</th>
                        <th class="small">Employee ID</th>
                        <th class="small">Link</th>
    				</tr>
    			</thead>
    			<tbody>
                    @foreach($data as $key=>$value)
                    <tr>
                        <td>{{ date('d,M Y H:i:s',strtotime($value->created_at)) }}</td>
                        <td width="30%">{{$value->note}}</td>
                        <td>{{$value->user->first_name}} {{$value->user->middle_name}} {{$value->user->last_name}}</td>
                        <td>{{$value->user->employee_id}}</td>
                        <th><a target="_blank" href="{{$value->link}}"> {{ ($value->link == '') ?'':'click me' }}</a></th>
                    </tr>

                    @endforeach
    				
    			</tbody>
    		</table>
           </div>
             <label class="bold">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }}
                        of {{$data->total()}} results</label>

                    {!! $data->links('pagination::bootstrap-4') !!}
    	</div>
    	
    </div>
	
</div>
</div>

<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
$(function() {  
   
    var startdate = $('#start').val();
    var enddate = $('#end').val();


    var today = moment(); // Current date

    if(startdate === ''){
   var start = moment().startOf('month'); // 1st day of current month
    var end = moment().endOf('month');     // Last day of current month
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