<template>
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <div class="dropdown-toggle notification cursor-pointer" 
             aria-expanded="false" 
             data-tw-toggle="dropdown"
             id="notificationDropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="badge-notification" v-show="notifications.length > 0">
                    {{ notifications.length }}
                </span>
            </a>
        </div>
        
        <div class="notification-content dropdown-menu dropdown-menu-right">
            <div class="notification-content__box">
                <!-- En-tête -->
                <div class="notification-header">
                    <h5 class="notification-title">Notifications</h5>
                    <span class="notification-count" v-if="notifications.length">
                        {{ notifications.length }} non lue(s)
                    </span>
                </div>

                <!-- Liste des notifications -->
                <div class="notification-list" v-if="notifications.length">
                    <div v-for="(msg, index) in notifications.slice(0, 5)" 
                         :key="index"
                         class="notification-item"
                         @click="markAsRead(msg.id)"
                         :class="{ 'is-new': !msg.read_at }">
                        
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        
                        <div class="notification-content-wrapper">
                            <div class="notification-message">
                                <span class="notification-title-text">
                                    {{ msg.data.title || 'Nouvelle notification' }}
                                </span>
                                <p class="notification-body">{{ msg.data.message }}</p>
                            </div>
                            <span class="notification-time">
                                {{ formatDate(msg.created_at) }}
                            </span>
                        </div>
                        
                        <div class="notification-status" v-if="!msg.read_at">
                            <span class="status-dot"></span>
                        </div>
                    </div>

                    <!-- Lien vers toutes les notifications -->
                    <div class="notification-footer">
                        <a href="/account/notifications" class="view-all-link">
                            Voir toutes les notifications
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- État vide -->
                <div class="notification-empty" v-else>
                    <div class="empty-icon">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <p class="empty-text">Aucune notification</p>
                    <span class="empty-subtext">Vous serez notifié quand il y aura du nouveau</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import 'moment/locale/fr';

moment.locale('fr');

export default {
    props: {
        unreads: {
            type: Number,
            default: 0
        },
        userid: {
            type: [Number, String],
            required: true
        }
    },

    data() {
        return {
            notifications: [],
            notificationSound: null,
            isLoading: false,
            toastrOptions: {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-bottom-right",
                timeOut: 4000,
                extendedTimeOut: 1000,
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                preventDuplicates: true,
                newestOnTop: true
            }
        };
    },

    computed: {
        unreadCount() {
            return this.notifications.filter(n => !n.read_at).length;
        }
    },

    created() {
        this.initNotificationSound();
        this.initEventListeners();
        this.getNotifications();
        this.listenForRealtimeNotifications();
    },

    mounted() {
        this.initDropdown();
    },

    methods: {
        // Initialisation du son
        initNotificationSound() {
            this.notificationSound = new Audio('public/sounds/notification.wav');
            this.notificationSound.volume = 0.5;
        },

        // Initialisation des événements Livewire
        initEventListeners() {
            Livewire.on('show-toast-notification', (data) => {
                this.showToastNotification(data.type, data.message, data.menu);
            });

            Livewire.on('refresh-notifications', () => {
                this.getNotifications();
            });
        },

        // Initialisation du dropdown Bootstrap
        initDropdown() {
            if (typeof $ !== 'undefined') {
                $('[data-toggle="dropdown"]').dropdown();
            }
        },

        // Écoute des notifications en temps réel
        listenForRealtimeNotifications() {
            if (typeof Echo !== 'undefined') {
                Echo.channel('admin-all')
                    .listen('.Events_all_admin', (e) => {
                        if (e.notifications) {
                            this.handleNewNotification(e.notifications);
                        }
                    })
                    .listen('.AdminNotifEvent', (e) => {
                        if (e.notifications) {
                            this.handleNewNotification(e.notifications);
                        }
                    });
            }
        },

        // Gestionnaire de nouvelles notifications
        handleNewNotification(notifications) {
            // Mettre à jour la liste
            this.notifications = notifications;
            
            // Jouer le son
            this.playNotificationSound();
            
            // Afficher une notification toast
            this.showNotificationDiscrète();
            
            // Animer le badge
            this.animateBadge();
            
            // Émettre un événement pour mettre à jour le compteur global
            this.$emit('notification-updated', this.notifications.length);
        },

        // Jouer le son de notification
        playNotificationSound() {
            if (this.notificationSound) {
                this.notificationSound.play().catch(error => {
                    console.log('Lecture du son impossible:', error);
                });
            }
        },

        // Notification discrète (fallback)
        showNotificationDiscrète() {
            if (typeof toastr !== 'undefined') {
                toastr.info('Nouvelle notification reçue', 'Notification', {
                    timeOut: 4000,
                    extendedTimeOut: 1000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-bottom-right',
                    onclick: () => {
                        window.location.href = '/account/notifications';
                    }
                });
            } else {
                this.showFallbackNotification();
            }
        },

        // Notification Toast avec options avancées
        showToastNotification(type, message, menu = null) {
            if (typeof toastr !== 'undefined') {
                const toastrMethod = {
                    'success': toastr.success,
                    'info': toastr.info,
                    'warning': toastr.warning,
                    'error': toastr.error
                }[type] || toastr.info;

                const title = this.getToastTitle(type);
                
                toastrMethod(message, title, {
                    ...this.toastrOptions,
                    onclick: () => {
                        if (menu && typeof Livewire !== 'undefined') {
                            Livewire.emit('menu-' + menu + '-active');
                        }
                    }
                });
            }
        },

        // Titre des toasts
        getToastTitle(type) {
            const titles = {
                'success': '✅ Succès',
                'info': '📬 Notification',
                'warning': '⚠️ Attention',
                'error': '❌ Erreur'
            };
            return titles[type] || 'Notification';
        },

        // Fallback notification (si toastr n'est pas chargé)
        showFallbackNotification() {
            const notification = document.createElement('div');
            notification.className = 'notification-floating';
            notification.innerHTML = `
                <div class="floating-content">
                    <i class="fas fa-bell"></i>
                    <div class="floating-text">
                        <strong>Nouvelle notification</strong>
                        <small>Cliquez pour voir</small>
                    </div>
                </div>
            `;
            
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                z-index: 9999;
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
                cursor: pointer;
                animation: slideInUp 0.3s ease;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            `;

            // Ajouter l'animation si elle n'existe pas
            if (!document.querySelector('#notification-animations')) {
                const style = document.createElement('style');
                style.id = 'notification-animations';
                style.textContent = `
                    @keyframes slideInUp {
                        from {
                            transform: translateY(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateY(0);
                            opacity: 1;
                        }
                    }
                    @keyframes slideOutDown {
                        from {
                            transform: translateY(0);
                            opacity: 1;
                        }
                        to {
                            transform: translateY(100%);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            document.body.appendChild(notification);

            // Disparition automatique
            setTimeout(() => {
                notification.style.animation = 'slideOutDown 0.3s ease forwards';
                setTimeout(() => notification.remove(), 300);
            }, 4000);

            // Clic pour aller aux notifications
            notification.onclick = () => {
                window.location.href = '/account/notifications';
            };
        },

        // Récupérer les notifications
        getNotifications() {
            this.isLoading = true;
            axios.get('/account/get-notifications')
                .then(response => {
                    this.notifications = response.data.unread_notifications || [];
                })
                .catch(error => {
                    console.error('Erreur de chargement:', error);
                    this.showToastNotification('error', 'Erreur de chargement des notifications');
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        // Marquer une notification comme lue
        markAsRead(notificationId) {
            if (!notificationId) return;

            axios.post('/account/mark-as-read', { id: notificationId })
                .then(response => {
                    this.getNotifications();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        },

        // Marquer tout comme lu
        markAllAsRead() {
            axios.get('/account/make_read_all')
                .then(response => {
                    this.getNotifications();
                    this.showToastNotification('success', 'Toutes les notifications ont été marquées comme lues');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    this.showToastNotification('error', 'Erreur lors du marquage');
                });
        },

        // Formater la date
        formatDate(date) {
            if (!date) return '';
            
            try {
                const now = moment();
                const notifDate = moment(date);
                
                if (now.diff(notifDate, 'minutes') < 1) {
                    return 'À l\'instant';
                } else if (now.diff(notifDate, 'hours') < 1) {
                    return `Il y a ${now.diff(notifDate, 'minutes')} min`;
                } else if (now.diff(notifDate, 'days') < 1) {
                    return `Aujourd'hui à ${notifDate.format('HH:mm')}`;
                } else if (now.diff(notifDate, 'days') < 2) {
                    return `Hier à ${notifDate.format('HH:mm')}`;
                } else {
                    return notifDate.format('DD/MM/YYYY HH:mm');
                }
            } catch (e) {
                return date;
            }
        },

        // Animer le badge
        animateBadge() {
            const badge = document.querySelector('.badge-notification');
            if (badge) {
                badge.classList.add('pulse');
                setTimeout(() => {
                    badge.classList.remove('pulse');
                }, 1000);
            }
        },

        // Rafraîchir les notifications
        refreshNotifications() {
            this.getNotifications();
        }
    }
};
</script>

<style scoped>
/* Container principal */
.intro-x.dropdown {
    position: relative;
}

/* Bouton de notification */
.notification {
    position: relative;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.notification:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.notification a {
    color: #6c757d;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification i {
    font-size: 1.2rem;
}

/* Badge de notification */
.badge-notification {
    position: absolute;
    top: 0;
    right: 0;
    background: linear-gradient(135deg, #f97316, #ef4444);
    color: white;
    font-size: 10px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Animation pulse */
.pulse {
    animation: pulse-animation 1s cubic-bezier(0.4, 0, 0.6, 1);
}

@keyframes pulse-animation {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
}

/* Dropdown menu */
.dropdown-menu {
    width: 380px;
    padding: 0 !important;
    margin-top: 10px !important;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    overflow: hidden;
}

/* En-tête */
.notification-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.notification-count {
    background: rgba(255,255,255,0.2);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

/* Liste des notifications */
.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

/* Élément de notification */
.notification-item {
    display: flex;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.notification-item:hover {
    background-color: #f8fafc;
    transform: translateX(5px);
}

.notification-item.is-new {
    background-color: #f0f9ff;
}

/* Icône de notification */
.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.notification-icon i {
    color: white;
    font-size: 1.1rem;
}

/* Contenu */
.notification-content-wrapper {
    flex: 1;
    min-width: 0;
}

.notification-message {
    margin-bottom: 5px;
}

.notification-title-text {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 3px;
}

.notification-body {
    margin: 0;
    color: #64748b;
    font-size: 0.8rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Date */
.notification-time {
    font-size: 0.7rem;
    color: #94a3b8;
    display: block;
}

/* Statut (point rouge pour non lu) */
.notification-status {
    margin-left: 10px;
    display: flex;
    align-items: center;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #3b82f6;
    animation: pulse 2s infinite;
}

/* Pied de page */
.notification-footer {
    padding: 15px 20px;
    text-align: center;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.view-all-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.view-all-link:hover {
    color: #764ba2;
    gap: 10px;
}

/* État vide */
.notification-empty {
    padding: 40px 20px;
    text-align: center;
    background: #f8fafc;
}

.empty-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.empty-icon i {
    font-size: 1.8rem;
    color: #94a3b8;
}

.empty-text {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 5px;
}

.empty-subtext {
    color: #94a3b8;
    font-size: 0.8rem;
}

/* Scrollbar personnalisée */
.notification-list::-webkit-scrollbar {
    width: 5px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 5px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation de la notification flottante */
.notification-floating {
    animation: slideInUp 0.3s ease;
    transition: all 0.3s;
}

.floating-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.floating-text {
    display: flex;
    flex-direction: column;
}

.floating-text small {
    font-size: 0.8rem;
    opacity: 0.9;
}

/* Responsive */
@media (max-width: 768px) {
    .dropdown-menu {
        width: 320px;
        right: -50px !important;
    }
    
    .notification-item {
        padding: 12px 15px;
    }
    
    .notification-icon {
        width: 35px;
        height: 35px;
    }
}

/* Support RTL si nécessaire */
[dir="rtl"] .notification-item:hover {
    transform: translateX(-5px);
}

[dir="rtl"] .view-all-link i {
    transform: rotate(180deg);
}
</style>