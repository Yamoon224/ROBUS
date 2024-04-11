<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">

<script src=" {{ asset_url('web/js/percent.js') }}"></script>
<script src="{{ asset_url('web/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset_url('web/js/popper.min.js') }}"></script>
{{-- <script src=" {{ asset_url('web/js/bootstrap.min.js') }}"></script> --}}
<script src=" {{ asset_url('web/js/bootstrap.bundle.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@if (session('status'))
    @if (session('color') === 'danger')
        <script>
            Swal.fire({
                // position: 'top-end',
                icon: 'error',
                timer: 2000,
                title: '{!! session('status') !!}',
                showConfirmButton: true,
            })
        </script>
    @else
        <script>
            Swal.fire({
                // position: 'top-end',
                icon: 'success',
                timer: 2000,
                title: '{!! session('status') !!}',
                showConfirmButton: true,
            })
        </script>
    @endif
@endif
