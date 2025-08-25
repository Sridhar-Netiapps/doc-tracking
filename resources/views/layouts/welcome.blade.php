<div class="bg">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <div class="col-1"></div>
            <div class="col-7">
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
                <select id="search_type" name="search_type" class="form-select">
                    <option value="">-- Select --</option>
                    <option value="region" {{ $selectedSearchType == 'region' ? 'selected' : '' }}>Region</option>
                    <option value="tat" {{ $selectedSearchType == 'tat' ? 'selected' : '' }}>TAT</option>
                </select>               
            </div>
            <div class="col-1" id="dynamic-dropdown"  class="d-none"></div>
            <div class="col-1" id="reset-btn-container"  class="d-none">
                <button type="button" id="reset-btn" class="btn btn-secondary w-100">Reset</button>
            </div>
            @endrole
            <div class="col-1"></div>
        </div>
        <div id="template-region"  class="d-none">
            <select id="region" name="region" class="form-select">
                <option value="">-- Region --</option>
                <option value="South" {{ $selectedRegion == 'South' ? 'selected' : '' }}>South</option>
                <option value="North" {{ $selectedRegion == 'North' ? 'selected' : '' }}>North</option>
                <option value="East" {{ $selectedRegion == 'East' ? 'selected' : '' }}>East</option>
                <option value="West" {{ $selectedRegion == 'West' ? 'selected' : '' }}>West</option>
            </select>
        </div>
        
        <div id="template-tat" class="d-none">
            <select id="tat" name="tat" class="form-select">
                <option value="">-- TAT --</option>
                <option value="30" {{ $selectedTat == '30' ? 'selected' : '' }}>30 days</option>
                <option value="60" {{ $selectedTat == '60' ? 'selected' : '' }}>60 days</option>
            </select>
        </div>
            
            <div id="result-container"></div>
                       
    </div>
</div>
<script>
    document.getElementById('search_type').addEventListener('change', function () {
        let value = this.value;
        let container = document.getElementById('dynamic-dropdown');

        container.innerHTML = ''; // Clear previous
        if (value === 'region') {
            container.innerHTML = document.getElementById('template-region').innerHTML;
            container.style.display = 'block';
        } else if (value === 'tat') {
            container.innerHTML = document.getElementById('template-tat').innerHTML;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }

        toggleResetButton(); // update reset visibility when switching dropdown type
    });

    document.addEventListener('DOMContentLoaded', function () {
        let selectedSearchType = "{{ $selectedSearchType }}"
        let selectedRegion = "{{ $selectedRegion }}";
        let selectedTat = "{{ $selectedTat }}";
        let container = document.getElementById('dynamic-dropdown');
        let resetContainer = document.getElementById('reset-btn-container');

        if (selectedSearchType) {
            document.getElementById('search_type').dispatchEvent(new Event('change'));
        }

        if (selectedRegion) {
            container.innerHTML = document.getElementById('template-region').innerHTML;
            container.style.display = 'block';
        } else if (selectedTat) {
            container.innerHTML = document.getElementById('template-tat').innerHTML;
            container.style.display = 'block';
        }

        toggleResetButton();

        // Click -> RESET (clear session filters then reload)
        $(document).on('click', '#reset-btn', function () {
            $.ajax({
                url: "{{ route('tat.data') }}",
                type: 'POST',
                data: {
                    reset: true, // <-- explicit reset flag
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        // Clear UI instantly (optional)
                        $('#search_type').val('');
                        $('#dynamic-dropdown').hide().empty();
                        $('#reset-btn-container').hide();

                        // Reload to fetch original data
                        location.reload();
                    }
                }
            });
        });
    });

    // Show/hide reset if any filter currently has a value
    function toggleResetButton() {
        const hasRegion = $('#region').length && $('#region').val();
        const hasTat    = $('#tat').length && $('#tat').val();
        if (hasRegion || hasTat) {
            $('#reset-btn-container').show();
        } else {
            $('#reset-btn-container').hide();
        }
    }

    $(document).on('change', '#region, #tat', function () {
        toggleResetButton();

        let tat = $('#tat').val();
        let region = $('#region').val();
        let searchType = $('#search_type').val(); 

        // If filtering by region, clear tat; if filtering by tat, clear region
        if ($(this).attr('id') === 'region') {
            tat = ''; // clear TAT
        } else if ($(this).attr('id') === 'tat') {
            region = ''; // clear Region
        }

        $.ajax({
            url: "{{ route('tat.data') }}",
            type: 'POST',
            data: {
                tat: tat,
                region: region,
                search_type: searchType,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); 
                }
            }
        });
    });
</script>