<input type="hidden" id="app_url" value="{{ env('APP_URL') }}">
<script src=" {{ asset_url('web/js/percent.js') }}"></script>
<script src=" {{ asset_url('web/js/button_robus.js') }}"></script>
<script src="{{ asset_url('web/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset_url('web/js/popper.min.js') }}"></script>
{{-- <script src=" {{ asset_url('web/js/bootstrap.min.js') }}"></script> --}}
<script src="{{ asset_url('js/script-selec2.js') }}"></script>
<script src=" {{ asset_url('web/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset_url('dist/plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset_url('dist/plugins/toastr/js/toastr.init.js') }}"></script>
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
             let timerInterval;
            Swal.fire({
                title: '{!! session('status') !!}',
                html: "I will close in <b></b> milliseconds.",
                timer: 3500,
                icon: 'error',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
        </script>
    @else
        <script>
            let timerInterval;
            Swal.fire({
                title: '{!! session('status') !!}',
                html: "I will close in <b></b> milliseconds.",
                timer: 3500,
                icon: 'success',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
        </script>
    @endif
@endif
