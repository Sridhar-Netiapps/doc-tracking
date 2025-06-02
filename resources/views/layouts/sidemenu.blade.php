
        <div class="treeList h-100">
            <ul>
                <li class="mb-auto"><a href="{{ url('home') }}"><img src="/images/material-symbols-light--dashboard-outline-rounded.svg" /> Back to Dashboard</a></li>
                {{-- <li><a href="/"><img src="/images/material-symbols-light--folder-supervised-outline.svg" /> Group</a></li> --}}
                <li><a href="{{ route('branches.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Branches</a></li>
                <li><a href="{{ route('departments.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Departments</a></li>
                <li><a href="{{ route('users.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Users</a></li>
                <li><a href="{{ route('vendor.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Vendor</a></li>
                <li><a href="{{ route('emails.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Emails</a></li>
                <li><a href="{{ route('process_status.index') }}"><img src="/images/material-symbols-light--folder-supervised-outline.svg" /> Process Status</a></li>
                <li><a href="{{ route('couriers.index') }}"><img src="/images/material-symbols-light--group-add.svg"/> Courier</a></li>
                @role('master')
                <li><a href="{{ route('roles.index') }}"><img src="/images/material-symbols-light--folder-supervised-outline.svg" /> Roles</a></li>
                <li><a href="{{ route('permissions.index') }}"><img src="/images/material-symbols-light--lock-person-outline-rounded.svg"/> Permission</a></li>
                @endrole
                <li class="mt-auto active rotateAni"><a href="/"><img src="/images/material-symbols-light--settings-outline.svg" /> Settings</a></li>
            </ul>
        </div>


