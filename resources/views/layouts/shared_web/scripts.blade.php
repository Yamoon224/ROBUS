<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">

<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/revolution_web.js') }}"></script>
<script src="{{ asset('js/fancybox.js') }}"></script>
<script src="{{ asset('js/fancybox-media.js') }}"></script>
<script src="{{ asset('js/owl.js') }}"></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script src="{{ asset('js/isotope.js') }}"></script>
<script src="{{ asset('js/validate.js') }}"></script>
<script src="{{ asset('js/script_web.js') }}"></script>

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
