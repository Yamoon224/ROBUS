<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">
<!-- jQuery-2.2.4 js -->
<!-- All Plugins js -->
<!-- Plugin js for this page -->
<script src="{{ asset('dist/js/jquery.js') }}"></script>
{{-- <script src="//cd  n.jsdelivr.net/npm/sweetalert2@11"></script> --}}
{{-- <link rel="stylesheet" href="https://unpkg.com/vue-toastr-2/dist/vue-toastr-2.min.css"> --}}
<!-- Toastr -->
<script src="{{ asset('js/script-selec2.js') }}"></script>
<script src="{{ asset('dist/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('dist/plugins/toastr/js/toastr.init.js') }}"></script>
{{-- <script type="module"  src="{{ asset('bootstrap/ssr/app2.mjs') }}"></script> --}}
<script type="module" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
{{-- <script nomodule src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine-ie11.min.js" defer></script> --}}
{{-- <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script> --}}

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script src="https://kit.fontawesome.com/612bf07328.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

{{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script> --}}
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
