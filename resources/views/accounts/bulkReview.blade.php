@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <h3>Review Selected Accounts</h3>
    <form method="POST" action="{{ route('accounts.bulkUpdate') }}">
        @csrf
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" id="selectAllReview" /> </th>
                    <th scope="col">Unique Number</th>
                    <th scope="col">Branch Code</th>
                    <th scope="col">Branch Name</th>
                    <th scope="col">CIF ID</th>
                    <th scope="col">Account Number</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Account Creation Date</th>
                    <th scope="col">Channel</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($selectedAccounts as $account)
                    <tr>
                        <td><input type="checkbox" name="account_ids[]" value="{{ $account->id }}" checked></td>
                        <td>{{ $account->unique_ref_no }}</td>
                        <td>{{ $account->branch_code }}</td>
                        <td>{{ $account->branch_name }}</td>
                        <td>{{ $account->cif_id }}</td>
                        <td>{{ $account->account_number }}</td>
                        <td>{{ $account->customer_name }}</td>
                        <td>{{ $account->account_creation_date }}</td>
                        <td>{{ $account->channel }}</td>
                        <td>{{ $account->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-success btn-sm" type="submit">Proceed with Update</button>
    </form>
</div>


@endsection
