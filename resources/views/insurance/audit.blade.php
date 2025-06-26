@extends('layouts.insurance-app')
@section('content')

<div class="container-dashboard py-2">
    <div class="container-dashboard">
    	 <div class="d-flex align-items-center m-2">
            <div class="d-flex align-items-center">
                <img src="/images/note.svg">
                <strong>Security Logs</strong> 
            </div>

            <div class="ms-auto">
             <form method="GET" action="{{route('audit')}}">
               <div class="input-group mb-3">
                <input class="form-control" type="text" name="search" placeholder="Search here" value="{{$search}}">
                <div class="input-group-prepend">
                   <button class="btn btn-dark rounded-0 d-none" type="submit" >Search</button>
                </div>
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


@endsection