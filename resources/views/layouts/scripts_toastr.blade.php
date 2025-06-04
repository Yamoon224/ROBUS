<script>

    const playNotificationSound = () => {
        const audio = new Audio('public/sounds/notification.wav');
        audio.play().catch(() => {
            console.warn('Son non joué (peut-être bloqué par le navigateur)');
        });
    };

    const playNotificationErrorSound = () => {
        const audio = new Audio('public/sounds/soundError.mp3');
        audio.play().catch(() => {
            console.warn('Son non joué (peut-être bloqué par le navigateur)');
        });
    };


    Livewire.on('success', msg => {
        Swal.fire({
            icon: 'success',
            width: 600,
            hight: 600,
            timer: 2000,
            toast: true,
            position: 'top-end',
            color: '#1f2a3c',
            title: msg,
            showConfirmButton: false,
            padding: '3em',
            timerProgressBar: true,
            background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/transaction.gif")',
            // backdrop: false
        });

        playNotificationSound();
    });

    Livewire.on('warning', msg => {
        Swal.fire({
            icon: 'warning',
            color: '#1f2a3c',
            toast: true,
            position: 'top-end',
            width: 600,
            timer: 2000,
            title: msg,
            padding: '3em',
            showConfirmButton: false,
            timerProgressBar: true,
            background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/payment-error.gif")',
            // backdrop: false
        });
        playNotificationErrorSound();
    });

    Livewire.on('error', msg => {
        Swal.fire({
            width: 600,
            toast: true,
            position: 'top-end',
            icon: 'error',
            timer: 2000,
            color: '#1f2a3c',
            title: msg,
            padding: '3em',
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/payment-error.gif")',
            // backdrop: false
        });

        playNotificationErrorSound();

    });


    Livewire.on('info', msg => {
        Swal.fire({
            icon: 'info',
            width: 600,
            timer: 2000,
            toast: true,
            position: 'top-end',
            title: msg,
            color: '#1f2a3c',
            padding: '3em',
            showConfirmButton: false,
            timerProgressBar: true,
            background: '#eff3f7',
            // backdrop: false
        });
        playNotificationErrorSound();
    });

    Livewire.on('question', msg => {
        Swal.fire({
            icon: 'question',
            toast: true,
            position: 'top-end',
            width: 600,
            timer: 2000,
            title: msg,
            color: '#1f2a3c',
            padding: '3em',
            showConfirmButton: false,
            timerProgressBar: true,
            background: '#eff3f7',
            // backdrop: false
        });
        playNotificationErrorSound();
    });
</script>
