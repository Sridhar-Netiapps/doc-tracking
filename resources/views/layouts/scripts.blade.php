<script>
    $(document).ready(function () {
        $('a.logout').click(function(e){
            e.preventDefault();
            $('#logout-form').submit();
        })
        $('[data-bs-toggle="tooltip"]').tooltip();
        @if(session('success'))
            Swal.fire({
                title: "Success!",
                html: '{!! session("success") !!}',
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