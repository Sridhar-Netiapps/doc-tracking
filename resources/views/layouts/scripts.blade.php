<script>
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $(".datepicker").flatpickr({
            dateFormat: "d-m-Y",
            allowInput: true
        });
        $('.select2').select2();
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d",
            maxDate: "today",         
            allowInput: false,         
            clickOpens: true
        });
        @if(session('success'))
            Swal.fire({
                title: "Success!",
                text: '{!! session("success") !!}',
                icon: "success",
                confirmButtonText: "OK"
            });
        @endif
    
        @if(session('error'))
            Swal.fire({
                title: "Error!",
                text: '{!! session("error") !!}',
                icon: "error",
                confirmButtonText: "OK"
            });
        @endif
    });
</script>