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
            const playNotificationErrorSound = () => {
                const audio = new Audio('sounds/soundError.mp3');
                audio.play().catch(() => {
                    console.warn('Son non joué (peut-être bloqué par le navigateur)');
                });
            };
            Swal.fire({
                // position: 'top-end',
                width: 600,
                toast: true,
                position: 'top-end',
                icon: 'error',
                timer: 2000,
                color: '#1f2a3c',
                title: '{!! session('status') !!}',
                showConfirmButton: true,
            })

            playNotificationErrorSound();
        </script>
    @else
        <script>
            const playNotificationSound = () => {
                const audio = new Audio('sounds/notification.wav');
                audio.play().catch(() => {
                    console.warn('Son non joué (peut-être bloqué par le navigateur)');
                });
            };

            Swal.fire({
                // position: 'top-end',
                icon: 'success',
                width: 600,
                hight: 600,
                timer: 2000,
                toast: true,
                position: 'top-end',
                color: '#1f2a3c',
                padding: '3em',
                title: '{!! session('status') !!}',
                showConfirmButton: true,
            })
            playNotificationSound();
        </script>
    @endif
@endif
