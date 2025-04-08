@extends('layouts.app')
@section('content')
<link href="{{ asset('css/styledoc.css') }}" rel="stylesheet">

<div class="bg-new">
    <div class="container">
        <div class="row justify-content-start align-items-center">
            <div class="col-3">
                <h4>Customer Account Creation</h4>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-6">
            <h3>Customer Accounts</h3>
        </div>


    </div>
</div>

    <div class="container mt-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">New Accounts <span class="badge text-bg-warning">2300</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Accounts list <span class="badge text-bg-warning">200</span></button>
            </li>

        </ul>
        <div class="tab-content bg-white" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">


                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" /> </th>
                        <th scope="col">Unique Number</th>
                        <th scope="col">CIF ID</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Account Creation Date</th>
                        <th scope="col">Channel</th>
                        <th scope="col">PGK No. / Glow application ID</th>
                        <th scope="col" class="border-start">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr><tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" /> </th>
                        <th scope="col">Unique Number</th>
                        <th scope="col">CIF ID</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Account Creation Date</th>
                        <th scope="col">Channel</th>
                        <th scope="col">PGK No. / Glow application ID</th>
                        <th scope="col" class="border-start">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr><tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" /></td>
                        <td>UJJ029921</td>
                        <td>UJJ029921</td>
                        <td>6283830405022</td>
                        <td>Cali</td>
                        <td>14-05-2024</td>
                        <td>GL</td>
                        <td>UJJ029921</td>
                        <td class="border-start"><button  class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Update</button> </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content rounded-3 shadow">
                <form id="update-courier" method="POST">
                    <div class="modal-header p-4 text-center">
                        <h5 class="mb-0 text-primary">Update Details</h5>
                    </div>
                    <div class="modal-body p-4 row">
                        <div class="col-4 pb-4">
                            <label>Unique Number</label>
                            <h5 class="unique_number">UJJ029921</h5>
                        </div>
                        <div class="col-4 pb-4">
                            <label>Customer Name</label>
                            <h5 class="customer_name">Cali</h5>
                        </div>
                        <div class="col-4 pb-4">
                            <label>Channel</label>
                            <h5 class="channel">GL</h5>
                        </div>
                        <div class="col-4 pb-2">
                            <label for="status" class="form-label">Courier Name</label>
                            <input type="text" name="courier_name" class="form-control">
                        </div>
                        <div class="col-4 pb-2">
                            <label for="status" class="form-label">AWD/POD</label>
                            <input type="text" name="awb_pod" class="form-control">
                        </div>
                        <div class="col-4 pb-2">
                            <label for="status" class="form-label">Dispatch Date</label>
                            <input type="Date" name="dispatch_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
