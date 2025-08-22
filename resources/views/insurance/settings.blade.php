 
@extends('layouts.insurance-app')
@section('content')

<div class="container-dashboard py-2">
    <div class="container-dashboard">
    	 <div class="d-flex align-items-center m-2">
            <div class="d-flex align-items-center">
                <img src="/images/note.svg">
                <strong>Settings</strong> 
            </div>

           
        </div>
	    

	</div>

  @if(session('success'))
    <script nonce='{{ env("CSP_NONCE") }}'>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                Swal.fire({
                    title: 'Message',
                    text: @json(session('success')),
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                   
                });
            }, 300); // Delay to ensure full render
        });
    </script>
    @php
        session()->forget('success');
    @endphp
    @endif
    
    @if(Session::has('failure'))
     <script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
      var mesage = '{{ session('failure') }}';
      Swal.fire({
            title: 'Message',
            text: mesage,
            icon: 'error',  
            confirmButtonText: 'OK'
        });
     </script>
     
    @endif


<div class="mt-3 p-4">


<div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsesix" aria-expanded="false" aria-controls="flush-collapsesix" id="region">Region
      </button>
    </h2>
    <div id="flush-collapsesix" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      	<div class="d-flex">
      		<button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Region">Add New Region</button>
      	</div>
      	<div class="row py-4">
          @foreach($region as $key => $val)
             <div class="col-3  mb-3">
             	<div class="shadow p-2 mb-2 bg-white rounded border border-dark">
             	  <h6 class="card-header text-center">{{$val->name}}</h6>
             	</div>
             </div>
          @endforeach
        </div>  
      </div>
    </div>
  </div>

<div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo" id="products">
        Products
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
         <div class="d-flex">
          <button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Product">Add New Product</button>
        </div>
        <div class="row py-4">
          @foreach($products as $key => $val)
             <div class="col-3  mb-3">
              <div class="shadow p-2 mb-2 bg-white rounded border border-dark">
                <h6 class="card-header text-center">{{$val->product}}</h6>
              </div>
             </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo" id="products">
        Products
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      	 <div class="d-flex">
      		<button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Product">Add New Product</button>
      	</div>
      	<div class="row py-4">
          @foreach($products as $key => $val)
             <div class="col-3  mb-3">
             	<div class="shadow p-2 mb-2 bg-white rounded border border-dark">
             	  <h6 class="card-header text-center">{{$val->product}}</h6>
             	</div>
             </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree" id="place_death">
        Place Of Death
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      	<div class="d-flex">
      		<button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Place Of Death">Add New Place of Death</button>
      	</div>
      	<div class="row py-4">
          @foreach($placeofdeath as $key => $val)
             <div class="col-3  mb-3">
             	<div class="shadow p-2 mb-2 bg-white rounded border border-dark">
             	  <h6 class="card-header text-center">{{$val->place}}</h6>
             	</div>
             </div>
          @endforeach
        </div> 
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour" id="cause_death">
        Cause Of Death
      </button>
    </h2>
    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      	<div class="d-flex">
      		<button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Cause of Death">Add New Cause of Death</button>
      	</div>
      	<div class="row py-4">
          @foreach($deathcause as $key => $val)
             <div class="col-3  mb-3">
             	<div class="shadow p-2 mb-2 bg-white rounded border border-dark">
             	  <h6 class="card-header text-center">{{$val->cause}}</h6>
             	</div>
             </div>
          @endforeach
        </div> 
      </div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed settings-bg text-white label-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive" id="claim_status">
        Claim Status
      </button>
    </h2>
    <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
        <div class="d-flex">
          <button type="button" class="ms-auto btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="Claim Status">Add New Status</button>
        </div>
        <div class="row py-4">
          @foreach($claimstatus as $key => $val)
             <div class="col-3  mb-3">
              <div class="shadow p-2 mb-2 bg-white rounded border border-dark">
                <h6 class="card-header text-center">{{$val->claim_status}}</h6>
              </div>
             </div>
          @endforeach
        </div> 
      </div>
    </div>
  </div>

</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('add_new_insurance_item') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-warning">
          <h5 class="modal-title label-bold text-white" id="exampleModalLabel"></h5>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control" name="modulename" id="recipient-name">
          <div class="form-group">
            <label>Title</label>
            <input class="form-control form-control-design" type="text" name="title" placeholder="Enter text here" required>
          </div>

          <div class="form-group d-none mt-4" id="product">
             <div class="form-group">
              <label>Partner Name</label>
              <select class="form-control form-select form-control-design" name="partner" id="partner">
                <option value="">Select</option>
                @foreach($partners as $key=>$partner)
                  <option value="{{ $partner->id}}">{{ $partner->partner}}</option>
                @endforeach
              </select>
            </div>
             
            <input class="form-control mt-4" type="file" name="files[]" id="claimForms" multiple>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="btn_submit_modal">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

	
</div>
</div>



<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
	var exampleModal = document.getElementById('exampleModal')
	exampleModal.addEventListener('show.bs.modal', function (event) {
	  // Button that triggered the modal
	  var button = event.relatedTarget
	  // Extract info from data-bs-* attributes
	  var recipient = button.getAttribute('data-bs-whatever')
	  // If necessary, you could initiate an AJAX request here
	  // and then do the updating in a callback.
	  //
	  // Update the modal's content.
	  var modalTitle = exampleModal.querySelector('.modal-title')
	  var modalBodyInput = exampleModal.querySelector('.modal-body input')

	  modalTitle.textContent = 'Add New ' + recipient
	  modalBodyInput.value = recipient

	  if(recipient == 'Product'){
	  	$('#product').removeClass('d-none');
	  	$('#product').addClass('d-block');
      
      $('#partner').prop('required',true);
      $('#claimForms').prop('required',true);

	  }
	  else{
	  	$('#product').removeClass('d-block');
	  	$('#product').addClass('d-none');
      $('#partner').prop('required',false);
      $('#claimForms').prop('required',false);
	  }
	})

  document.addEventListener("DOMContentLoaded", function () {
    var myCollapse = document.getElementById('collapseOne');
    var bsCollapse = new bootstrap.Collapse(myCollapse, {
        toggle: true
    });
});
</script>


@endsection