@php
    $filters = session('filters', []);
    $selectedTat = $filters['tat'] ?? '';
    $selectedRegion = $filters['region'] ?? '';
    $selectedSearchType = $filters['search_type'] ?? '';
@endphp

<div class="bg">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <div class="col-1"></div>
            <div class="col-7">
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
            @role('ho-user|master')
            <div class="col-1">
                {{-- <label for="search_type">Search</label> --}}
                <select id="search_type" name="search_type" class="form-select">
                    <option value="">-- Select --</option>
                    <option value="region" {{ $selectedSearchType == 'region' ? 'selected' : '' }}>Region</option>
                    <option value="tat" {{ $selectedSearchType == 'tat' ? 'selected' : '' }}>TAT</option>
                </select>               
            </div>
            @endrole
            <div class="col-1" id="dynamic-dropdown" class="d-none"></div>
            <div class="col-1" id="reset-btn-container" class="d-none">
                <button type="button" id="reset-btn" class="btn btn-secondary w-100">Reset</button>
            </div>
            <div class="col-1"></div>
        </div>
        <div id="template-region" class="d-none">
            {{-- <label>Region</label> --}}
            <select id="region" name="region" class="form-select">
                <option value="">-- Region --</option>
                <option value="South" {{ $selectedRegion == 'South' ? 'selected' : '' }}>South</option>
                <option value="North" {{ $selectedRegion == 'North' ? 'selected' : '' }}>North</option>
                <option value="East" {{ $selectedRegion == 'East' ? 'selected' : '' }}>East</option>
                <option value="West" {{ $selectedRegion == 'West' ? 'selected' : '' }}>West</option>
            </select>
        </div>
        
        <div id="template-tat" class="d-none">
            {{-- <label>TAT</label> --}}
            <select id="tat" name="tat" class="form-select">
                <option value="">-- TAT --</option>
                <option value="30" {{ $selectedTat == '30' ? 'selected' : '' }}>30 days</option>
                <option value="60" {{ $selectedTat == '60' ? 'selected' : '' }}>60 days</option>
            </select>
        </div>
            
            <!-- Placeholder for results -->
            <div id="result-container"></div>
                       
    </div>
</div>
