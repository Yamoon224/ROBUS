<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">
<!-- jQuery-2.2.4 js -->
<!-- All Plugins js -->
<!-- Plugin js for this page -->
<script src="{{ asset('public/public/dist/js/jquery.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('public/public/js/script-selec2.js') }}"></script>
<script src="{{ asset('public/public/dist/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('public/public/dist/plugins/toastr/js/toastr.init.js') }}"></script>
<script type="module" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('public/public/js/script.js') }}"></script>
<script src="{{ asset('public/public/js/app.js') }}"></script>

<script src="https://kit.fontawesome.com/612bf07328.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=[" your-google-map-api"]&libraries=places"></script>
<!-- End custom js for this page -->

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
