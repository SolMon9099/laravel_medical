@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <script>
            toastr.error( "{{ $error }}", 'Error!', { "showDuration": 500 , positionClass: 'toast-top-right'});
        </script>
    @endforeach
@endif

@if(Session::has('flash_error'))
    <script>
        toastr.error( '{{Session::get('flash_error')}}', 'Error!', { "showDuration": 500, positionClass: 'toast-top-right' },);
    </script>
@endif


@if(Session::has('flash_success'))
    <script>
        toastr.success( '{{Session::get('flash_success')}}', 'Success!', { "showDuration": 500, positionClass: 'toast-top-right' });        
    </script>
@endif
