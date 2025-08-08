@php
    $currentTab = Request::segment(2); // gets 'pending', 'all', etc.
    $isActive = request()->is('home');
@endphp

<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                @unless(auth()->user()->hasAnyRole(['super_admin']))
                <li class="nav-item">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ url('/home') }}">
                        <img src="{{ $isActive ? '/images/home1.svg' : '/images/home2.svg' }}" />
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentTab === 'all' ? 'active-tab' : '' }}" href="{{ route('accounts.index',['type' => 'all','dtype' => 'loan']) }}">All</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $currentTab === 'pending' ? 'active-tab' : '' }}" href="{{ route('accounts.index',['type' => 'pending','dtype' => 'loan']) }}">Pending</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ Request::is('documents/proceed') ? 'active-tab' : '' }}" href="{{ route('accounts.proceed') }}">In Draft</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ Request::segment(1) === 'dispatches' ? 'active-tab' : '' }}" href="@hasanyrole('master|ro-officer'){{ route('dispatches','list') }}@else{{ route('dispatches','ready') }}@endhasanyrole">Dispatches</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $currentTab === 'rejected' ? 'active-tab' : '' }}" href="{{ route('accounts.index',['type' => 'rejected','dtype' => 'loan']) }}">Rejected</a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link {{ $currentTab === 'received' ? 'active-tab' : '' }}" href="{{ route('accounts.index',['type' => 'received','dtype' => 'loan']) }}">Received</a>
                </li>
                @hasrole('ro-officer|ro-supervisor|ro-user|ho-user|admin|super_admin|master')
                <li class="nav-item ">
                    <a class="nav-link {{ $currentTab === 'moved' ? 'active-tab' : '' }}" href="{{ route('accounts.index',['type' => 'moved','dtype' => 'loan']) }}">Moved to RMA</a>
                </li>
                @endhasrole
                @hasrole('ro-officer|ro-supervisor|ho-user|admin|super_admin|master')
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'reports' ? 'active-tab' : '' }}" href="{{ url('reports') }}">Reports</a>
                </li>
                @endhasrole
                @endunless
            </ul>
        </div>
    </div>
</div>
