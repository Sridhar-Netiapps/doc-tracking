@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.create') }}" class="btn btn-primary">Add Vendor</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

   <table class="table mt-3">
       <thead>
           <tr>
               <th>Name</th>
               <th>Location</th>
               <th>Actions</th>
           </tr>
       </thead>
       <tbody>
           @foreach ($vendors as $vendor)
               <tr>
                   <td>{{ $vendor->name }}</td>
                   <td>{{ $vendor->location }}</td>
                   <td>
                       <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                       <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" style="display:inline-block;">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                       </form>
                   </td>
               </tr>
           @endforeach
       </tbody>
   </table>
</div>
@endsection
