<template>
    <li class="dropdown">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <span id="notify_num" class="badge-notification" v-show="notifications.length > 0">
                {{ notifications.length }}
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right notify-drop-div" aria-labelledby="dropdownMenuButton">
            <div class="dropdown-header">
                <h6 class="dropdown-title">Notifications</h6>
                <a href="/admin/notifications" class="view-all">Voir tout</a>
            </div>
            
            <div class="notify-list" v-if="notifications.length">
                <div v-for="(msg, index) in notifications.slice(0, 5)" :key="index" 
                     class="notify-item" @click="markAsRead">
                    <div class="notify-icon">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="notify-content">
                        <p class="notify-message">
                            <a href="/admin/notifications">
                                {{ msg.data.message || msg.data.title || 'Nouvelle notification' }}
                            </a>
                        </p>
                        <span class="notify-date">{{ formatDate(msg.created_at) }}</span>
                    </div>
                </div>
                <div class="dropdown-footer" v-if="notifications.length > 5">
                    <a href="/admin/notifications" class="view-all-link">
                        Voir les {{ notifications.length - 5 }} autres...
                    </a>
                </div>
            </div>
            
            <div class="notify-empty" v-else>
                <p class="text-danger mb-0">Aucune notification trouvée</p>
            </div>
        </div>
    </li>
</template>

<script>
import moment from 'moment'; // Si vous voulez formater les dates

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
            toastrOptions: {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-bottom-right",
                timeOut: 4000,
                extendedTimeOut: 1000,
                preventDuplicates: true,
                newestOnTop: true
            }
        }
    },
    
    created() {
        this.initNotificationSound();
        this.getNotifications();
        this.listenForNotifications();
        
        // Écouter les événements Livewire pour les notifications toast
        Livewire.on('show-toast-notification', (data) => {
            this.showToastNotification(data.type, data.message, data.menu);
        });
    },
    
    mounted() {
        // Initialiser Bootstrap dropdown si nécessaire
        if (typeof $ !== 'undefined') {
            $('.dropdown-toggle').dropdown();
        }
    },
    
    methods: {
        // Initialiser le son de notification
        initNotificationSound() {
            this.notificationSound = new Audio('public/sounds/notification.wav');
        },
        
        // Jouer le son
        playNotificationSound() {
            if (this.notificationSound) {
                this.notificationSound.play().catch(() => {
                    console.log('Son bloqué par le navigateur');
                });
            }
        },
        
        // Récupérer les notifications
        getNotifications() {
            axios.get('/account/get-notifications')
                .then(response => {
                    this.notifications = response.data.unread_notifications || [];
                    this.updateNotificationCount();
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des notifications:', error);
                    this.showToastNotification('error', 'Erreur de chargement des notifications');
                });
        },
        
        // Écouter les notifications en temps réel
        listenForNotifications() {
            if (typeof Echo !== 'undefined') {
                Echo.channel('admin-all')
                    .listen('.Events_all_admin', (e) => {
                        if (e.notifications) {
                            // Mettre à jour la liste
                            this.getNotifications();
                            
                            // Jouer le son
                            this.playNotificationSound();
                            
                            // Afficher une notification toast
                            this.showToastNotification('info', 'Nouvelle notification reçue');
                            
                            // Animation du badge
                            this.animateBadge();
                        }
                    });
            }
        },
        
        // Afficher une notification toast
        showToastNotification(type, message, menu = null) {
            if (typeof toastr !== 'undefined') {
                const toastrMethod = {
                    'success': toastr.success,
                    'info': toastr.info,
                    'warning': toastr.warning,
                    'error': toastr.error
                }[type] || toastr.info;
                
                const toast = toastrMethod(message, this.getToastTitle(type), this.toastrOptions);
                
                // Ajouter un gestionnaire de clic si un menu est spécifié
                if (menu && toast) {
                    setTimeout(() => {
                        $(toast).click(() => {
                            if (typeof Livewire !== 'undefined') {
                                Livewire.emit('menu-' + menu + '-active');
                            }
                        });
                    }, 100);
                }
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
        
        // Marquer comme lu
        markAsRead() {
            axios.get('/account/make_read_all')
                .then(response => {
                    this.getNotifications();
                })
                .catch(error => {
                    console.error('Erreur lors du marquage des notifications:', error);
                });
        },
        
        // Formater la date
        formatDate(date) {
            if (!date) return '';
            
            try {
                const d = new Date(date);
                const now = new Date();
                const diff = now - d;
                
                // Moins d'une minute
                if (diff < 60000) {
                    return 'À l\'instant';
                }
                // Moins d'une heure
                if (diff < 3600000) {
                    const minutes = Math.floor(diff / 60000);
                    return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
                }
                // Aujourd'hui
                if (d.toDateString() === now.toDateString()) {
                    return `Aujourd'hui à ${d.getHours()}:${d.getMinutes().toString().padStart(2, '0')}`;
                }
                // Cette semaine
                const weekAgo = new Date(now - 7 * 86400000);
                if (d > weekAgo) {
                    const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                    return days[d.getDay()] + ` à ${d.getHours()}:${d.getMinutes().toString().padStart(2, '0')}`;
                }
                // Plus ancien
                return `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
            } catch (e) {
                return date;
            }
        },
        
        // Mettre à jour le compteur
        updateNotificationCount() {
            // Émettre un événement pour mettre à jour le compteur global si nécessaire
            this.$emit('notification-count-updated', this.notifications.length);
        },
        
        // Animer le badge
        animateBadge() {
            const badge = document.getElementById('notify_num');
            if (badge) {
                badge.classList.add('pulse-animation');
                setTimeout(() => {
                    badge.classList.remove('pulse-animation');
                }, 1000);
            }
        }
    }
}
</script>

<style scoped>
/* Style du dropdown */
.notify-drop-div {
    width: 350px;
    padding: 0 !important;
    margin-top: 10px;
    border: none;
    border-radius: 8px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    max-height: 500px;
    overflow-y: auto;
}

/* En-tête du dropdown */
.dropdown-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
}

.dropdown-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.view-all {
    color: rgba(255,255,255,0.9);
    font-size: 12px;
    text-decoration: none;
}

.view-all:hover {
    color: white;
    text-decoration: underline;
}

/* Liste des notifications */
.notify-list {
    max-height: 350px;
    overflow-y: auto;
}

.notify-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notify-item:hover {
    background-color: #f8f9fa;
}

.notify-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.notify-icon i {
    color: white;
    font-size: 18px;
}

.notify-content {
    flex: 1;
    min-width: 0;
}

.notify-message {
    margin: 0 0 5px 0;
    font-size: 13px;
    line-height: 1.4;
    word-wrap: break-word;
}

.notify-message a {
    color: #333;
    text-decoration: none;
}

.notify-message a:hover {
    color: #667eea;
}

.notify-date {
    font-size: 11px;
    color: #999;
}

/* État vide */
.notify-empty {
    padding: 30px 20px;
    text-align: center;
    background: #f8f9fa;
}

/* Badge de notification */
.badge-notification {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #f97316;
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
}

/* Animation du badge */
.pulse-animation {
    animation: pulse 1s ease;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
        background-color: #ef4444;
    }
    100% {
        transform: scale(1);
    }
}

/* Pied de page du dropdown */
.dropdown-footer {
    padding: 10px 20px;
    text-align: center;
    background: #f8f9fa;
    border-top: 1px solid #f0f0f0;
}

.view-all-link {
    color: #667eea;
    font-size: 12px;
    text-decoration: none;
}

.view-all-link:hover {
    text-decoration: underline;
}

/* Position du dropdown */
li.dropdown {
    position: relative;
    list-style: none;
}

li.dropdown > a {
    position: relative;
    display: block;
    padding: 10px;
    color: #666;
}

li.dropdown > a:hover {
    color: #333;
}

/* Personnalisation du dropdown Bootstrap */
.dropdown-menu-right {
    right: 0 !important;
    left: auto !important;
}

/* Scrollbar personnalisée */
.notify-list::-webkit-scrollbar {
    width: 5px;
}

.notify-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notify-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 5px;
}

.notify-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive */
@media (max-width: 768px) {
    .notify-drop-div {
        width: 300px;
    }
}
</style>