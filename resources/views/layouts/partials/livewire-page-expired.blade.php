{{-- Session ou jeton CSRF expiré (419) : recharge la page au lieu d'afficher la boîte « This page has expired ». --}}
<script>
    (function () {
        var reloading = false;

        function reloadOnce() {
            if (reloading) return;
            reloading = true;
            window.location.reload();
        }

        function register() {
            if (window.Livewire && typeof window.Livewire.onPageExpired === 'function') {
                window.Livewire.onPageExpired(reloadOnce);
            }
        }

        if (window.Livewire) {
            register();
        } else {
            document.addEventListener('livewire:load', register);
        }
    })();
</script>
