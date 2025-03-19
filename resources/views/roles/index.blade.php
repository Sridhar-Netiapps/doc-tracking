@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Roles</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
    </div>


    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif


    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h2>Role Name</h2>
                    <p>34 Total Users</p>

                    <div class="d-flex justify-content-between">
                        <a href="/">Delete Users</a>
                        <img src="/images/material-symbols--edit.svg" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h2>Role Name</h2>
                    <p>34 Total Users</p>

                    <div class="d-flex justify-content-between">
                        <a href="/">Delete Users</a>
                        <img src="/images/material-symbols--edit.svg" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h2>Role Name</h2>
                    <p>34 Total Users</p>

                    <div class="d-flex justify-content-between">
                        <a href="/">Delete Users</a>
                        <img src="/images/material-symbols--edit.svg" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h2>Role Name</h2>
                    <p>34 Total Users</p>

                    <div class="d-flex justify-content-between">
                        <a href="/">Delete Users</a>
                        <img src="/images/material-symbols--edit.svg" />
                    </div>
                </div>
            </div>
        </div>

    </div>

{{--    <table class="table mt-3">--}}
{{--        <thead>--}}
{{--            <tr>--}}
{{--                <th>Name</th>--}}
{{--                <th>Actions</th>--}}
{{--            </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--            @foreach ($roles as $role)--}}
{{--                <tr>--}}
{{--                    <td>{{ $role->name }}</td>--}}
{{--                    <td>--}}
{{--                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>--}}
{{--                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">--}}
{{--                            @csrf--}}
{{--                            @method('DELETE')--}}
{{--                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>--}}
{{--                        </form>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
</div>
@endsection
