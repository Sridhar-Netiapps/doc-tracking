<script>
    $(document).ready(function () {
        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
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