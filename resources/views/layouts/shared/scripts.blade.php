<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">
<!-- jQuery-2.2.4 js -->
<!-- All Plugins js -->
<!-- Plugin js for this page -->
<script src="{{ asset_url('dist/js/jquery.js') }}"></script>
{{-- <script src="//cd  n.jsdelivr.net/npm/sweetalert2@11"></script> --}}
{{-- <link rel="stylesheet" href="https://unpkg.com/vue-toastr-2/dist/vue-toastr-2.min.css"> --}}
<!-- Toastr -->
<script src="{{ asset_url('js/script-selec2.js') }}"></script>
<script src="{{ asset_url('dist/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset_url('dist/plugins/toastr/js/toastr.init.js') }}"></script>
{{-- <script type="module"  src="{{ asset('bootstrap/ssr/app2.mjs') }}"></script> --}}
<script type="module" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
{{-- <script nomodule src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine-ie11.min.js" defer></script> --}}
{{-- <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script> --}}

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset_url('js/script.js') }}"></script>
<script src="{{ asset_url('js/app.js') }}"></script>

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
    <script>
        // Configuration des types de notifications
        const notificationConfig = {
            danger: {
                icon: 'error',
                iconColor: '#ef4444',
                background: '#fef2f2',
                sound: 'sounds/soundError.mp3',
                timer: 4000
            },
            warning: {
                icon: 'warning',
                iconColor: '#f59e0b',
                background: '#fffbeb',
                sound: 'sounds/soundWarning.mp3',
                timer: 5000
            },
            info: {
                icon: 'info',
                iconColor: '#3b82f6',
                background: '#eff6ff',
                sound: 'sounds/soundInfo.mp3',
                timer: 4000
            },
            success: {
                icon: 'success',
                iconColor: '#10b981',
                background: '#f0fdf4',
                sound: 'sounds/notification.wav',
                timer: 3000
            }
        };

        const config = notificationConfig['{{ session('color') }}'] || notificationConfig.success;

        // Jouer le son
        const playNotificationSound = () => {
            const audio = new Audio(config.sound);
            audio.play().catch(() => {
                console.warn('Son non joué (peut-être bloqué par le navigateur)');
            });
        };

        // Afficher la notification
        Swal.fire({
            title: '{!! session('status') !!}',
            icon: config.icon,
            iconColor: config.iconColor,
            background: config.background,
            color: '#1f2937',
            position: 'top-end',
            toast: true,
            showConfirmButton: false,
            showCloseButton: true,
            timer: config.timer,
            timerProgressBar: true,
            width: 500,
            padding: '1.5em',
            customClass: {
                popup: 'animated-slide-in-right',
                title: 'text-lg font-semibold',
                closeButton: `text-${config.iconColor.split('-')[1]}-500 hover:text-${config.iconColor.split('-')[1]}-700`
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        playNotificationSound();
    </script>
@endif

