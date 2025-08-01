<div class="bg">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <div class="col-1"></div>
            <div class="col-10">
                {{-- <p>Hi! Welcome {{ auth()->user()->first_name }} (ID: {{ auth()->user()->id }})</p> --}}
                <p>
                    Hi! Welcome
                   <b> {{ Auth::user()->first_name }}
                    @if(Auth::user()->middle_name)
                        {{ Auth::user()->middle_name }}
                    @endif
                    {{ Auth::user()->last_name }}</b>
                    ({{ Auth::user()->branch_id }})
                </p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>