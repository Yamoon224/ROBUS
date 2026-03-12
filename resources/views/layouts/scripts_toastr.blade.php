<script>
    // Configuration avec images de fond
    const notificationConfigWithImages = {
        success: {
            icon: 'success',
            iconColor: '#10b981',
            background: '#eff3f7 url("/robus/public/dist/images/transaction.gif") center/cover',
            sound: '',
            timer: 3000,
            animation: 'slideInRight'
        },
        error: {
            icon: 'error',
            iconColor: '#ef4444',
            background: '#eff3f7 url("/robus/public/dist/images/payment-error.gif") center/cover',
            sound: '/public/sounds/soundError.mp3',
            timer: 4000,
            animation: 'shake'
        },
        warning: {
            icon: 'warning',
            iconColor: '#f59e0b',
            background: '#eff3f7 url("/robus/public/dist/images/payment-error.gif") center/cover',
            sound: '/public/sounds/soundError.mp3',
            timer: 5000,
            animation: 'pulse'
        },
        info: {
            icon: 'info',
            iconColor: '#3b82f6',
            background: '#eff3f7',
           sound: '',
            timer: 4000,
            animation: 'slideInRight'
        },
        question: {
            icon: 'question',
            iconColor: '#8b5cf6',
            background: '#eff3f7',
           sound: '',
            timer: 4000,
            animation: 'slideInRight'
        }
    };

    const playNotificationSound = (type = 'success') => {
        const audio = new Audio(notificationConfigWithImages[type].sound);
        audio.volume = 0.7;
        audio.play().catch(() => {
            console.warn('Son non joué');
        });
    };

    const showNotification = (type, msg) => {
        const config = notificationConfigWithImages[type];
        
        Swal.fire({
            title: msg,
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
            padding: '2em',
            customClass: {
                popup: `animated-${config.animation} notification-with-bg`,
                title: 'text-base font-semibold bg-white/80 backdrop-blur-sm rounded px-2 py-1',
                closeButton: 'text-gray-600 hover:text-gray-900'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        playNotificationSound(type);
    };

    // Écouteurs Livewire
    Livewire.on('success', msg => showNotification('success', msg));
    Livewire.on('error', msg => showNotification('error', msg));
    Livewire.on('warning', msg => showNotification('warning', msg));
    Livewire.on('info', msg => showNotification('info', msg));
    Livewire.on('question', msg => showNotification('question', msg));
</script>

<style>
.notification-with-bg {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.swal2-title {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Le reste des styles d'animation reste identique à la première version */
.animated-slideInRight {
    animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.animated-shake {
    animation: shake 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>