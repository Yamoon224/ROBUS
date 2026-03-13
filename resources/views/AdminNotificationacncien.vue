<template>
    <div
        class="intro-x dropdown mr-auto sm:mr-6"
        v-click-outside="closeDropdown"
    >
        <!-- Bouton de la cloche -->
        <div
            class="dropdown-toggle notification cursor-pointer"
            @click="toggleDropdown"
            :class="{ active: isOpen }"
            id="notificationDropdown"
        >
            <div
                class="notification-bell"
                :class="{ ringing: hasNewNotification }"
            >
                <i class="fas fa-bell"></i>
                <span class="badge-notification" v-show="unreadCount > 0">
                    {{ unreadCount > 9 ? "9+" : unreadCount }}
                </span>
            </div>
        </div>
        <button
            v-if="isDevelopment"
            @click="testNotification"
            class="test-notification-btn"
        >
            <i class="fas fa-bell"></i>
            Tester Notification
        </button>
        <!-- Dropdown des notifications -->
        <transition name="slide-fade">
            <div class="dropdown-menu" v-show="isOpen">
                <div class="notification-content__box">
                    <!-- En-tête avec actions -->
                    <div class="notification-header">
                        <div class="header-left">
                            <h5 class="notification-title">Notifications</h5>
                            <span class="notification-count" v-if="unreadCount">
                                {{ unreadCount }} non lue(s)
                            </span>
                        </div>
                        <div class="header-actions">
                            <button
                                v-if="unreadCount"
                                @click="markAllAsRead"
                                class="btn-mark-read"
                                title="Tout marquer comme lu"
                            >
                                <i class="fas fa-check-double"></i>
                            </button>
                            <button
                                @click="refreshNotifications"
                                class="btn-refresh"
                                :class="{ rotating: isLoading }"
                                title="Rafraîchir"
                            >
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Loading state -->
                    <div class="notification-loading" v-if="isLoading">
                        <div class="spinner"></div>
                        <span>Chargement...</span>
                    </div>

                    <!-- Liste des notifications -->
                    <template v-else>
                        <div
                            class="notification-list"
                            v-if="notifications.length"
                        >
                            <div
                                v-for="notification in displayedNotifications"
                                :key="notification.id"
                                class="notification-item"
                                @click="handleNotificationClick(notification)"
                                :class="{
                                    'is-new': !notification.read_at,
                                    highlight: notification.highlight,
                                }"
                            >
                                <div
                                    class="notification-icon"
                                    :class="
                                        getNotificationIcon(notification).class
                                    "
                                >
                                    <i
                                        :class="
                                            getNotificationIcon(notification)
                                                .icon
                                        "
                                    ></i>
                                </div>

                                <div class="notification-content-wrapper">
                                    <div class="notification-message">
                                        <span class="notification-title-text">
                                            {{
                                                notification.data.title ||
                                                "Nouvelle notification"
                                            }}
                                        </span>
                                        <p class="notification-body">
                                            {{ notification.data.message }}
                                        </p>
                                    </div>
                                    <div class="notification-meta">
                                        <span class="notification-time">
                                            <i class="far fa-clock"></i>
                                            {{
                                                formatDate(
                                                    notification.created_at,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="notification-status"
                                    v-if="!notification.read_at"
                                >
                                    <span class="status-dot"></span>
                                </div>
                            </div>

                            <!-- Voir plus -->
                            <div
                                class="notification-footer"
                                v-if="notifications.length > 5"
                            >
                                <a
                                    href="/account/notifications"
                                    class="view-all-link"
                                >
                                    Voir les
                                    {{ notifications.length }} notifications
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>

                        <!-- État vide -->
                        <div class="notification-empty" v-else>
                            <div class="empty-animation">
                                <lottie-player
                                    src="https://assets2.lottiefiles.com/packages/lf20_p1qiuawe.json"
                                    background="transparent"
                                    speed="1"
                                    style="width: 150px; height: 150px"
                                    loop
                                    autoplay
                                >
                                </lottie-player>
                            </div>
                            <p class="empty-text">Aucune notification</p>
                            <span class="empty-subtext"
                                >Vous serez notifié quand il y aura du
                                nouveau</span
                            >
                        </div>
                    </template>
                </div>
            </div>
        </transition>

        <!-- Notification toast personnalisée -->
        <transition name="toast">
            <div
                v-if="showToast"
                class="custom-toast"
                @click="openNotifications"
            >
                <div class="toast-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="toast-content">
                    <strong>Nouvelle notification</strong>
                    <span>{{
                        lastNotification?.data?.message || "Cliquez pour voir"
                    }}</span>
                </div>
                <button class="toast-close" @click.stop="showToast = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </transition>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
    name: "NotificationBell",

    props: {
        userid: {
            type: [Number, String],
            required: true,
        },
        initialUnreads: {
            type: Number,
            default: 0,
        },
    },

    data() {
        return {
            notifications: [],
            isOpen: false,
            isLoading: false,
            showToast: false,
            lastNotification: null,
            hasNewNotification: false,
            toastTimeout: null,
            notificationSound: null,
            unreadCountCache: 0,
        };
    },

    computed: {
        unreadCount() {
            return this.notifications.filter((n) => !n.read_at).length;
        },

        displayedNotifications() {
            return this.notifications.slice(0, 5);
        },
    },

    watch: {
        unreadCount(newVal, oldVal) {
            if (newVal > oldVal) {
                this.hasNewNotification = true;
                setTimeout(() => {
                    this.hasNewNotification = false;
                }, 3000);
            }

            // Émettre le changement pour d'autres composants
            this.$emit("unread-count-change", newVal);
        },
    },

    created() {
        this.initNotificationSound();
        this.loadNotifications();
        this.initRealtimeListener();
    },

    mounted() {
        // Ajouter la directive click-outside
        this.setupClickOutside();
    },

    beforeUnmount() {
        if (this.toastTimeout) {
            clearTimeout(this.toastTimeout);
        }
    },

    methods: {
        testNotification() {
            const fakeNotification = {
                id: Date.now(),
                type: "App\\Notifications\\TestNotification",
                notifiable_type: "App\\Models\\User",
                notifiable_id: this.userid,
                data: {
                    title: "🔔 Notification de test",
                    message:
                        "Ceci est une notification de test générée à " +
                        new Date().toLocaleTimeString(),
                    type: "info",
                    link: "/account/notifications",
                    icon: "fas fa-bell",
                },
                read_at: null,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            };

            // Simuler la réception d'une notification
            this.handleNewNotification(fakeNotification);
        },

        // Test avec différents types de notifications
        testMultipleNotifications() {
            const types = ["success", "warning", "error", "info"];
            const titles = {
                success: "✅ Opération réussie",
                warning: "⚠️ Attention requise",
                error: "❌ Erreur détectée",
                info: "📢 Information",
            };

            const messages = {
                success: "Votre action a été effectuée avec succès",
                warning: "Veuillez vérifier vos informations",
                error: "Une erreur est survenue lors du traitement",
                info: "Une nouvelle mise à jour est disponible",
            };

            types.forEach((type, index) => {
                setTimeout(() => {
                    const fakeNotification = {
                        id: Date.now() + index,
                        data: {
                            title: titles[type],
                            message: messages[type],
                            type: type,
                            link: "/account/notifications",
                        },
                        read_at: null,
                        created_at: new Date().toISOString(),
                    };
                    this.handleNewNotification(fakeNotification);
                }, index * 2000); // Espacement de 2 secondes
            });
        },

        // Test de charge (10 notifications d'affilée)
        testBulkNotifications() {
            for (let i = 0; i < 10; i++) {
                setTimeout(() => {
                    const fakeNotification = {
                        id: Date.now() + i,
                        data: {
                            title: `Notification #${i + 1}`,
                            message: `Ceci est la notification numéro ${i + 1}`,
                            type: i % 2 === 0 ? "info" : "success",
                        },
                        read_at: null,
                        created_at: new Date().toISOString(),
                    };
                    this.handleNewNotification(fakeNotification);
                }, i * 500);
            }
        },
        // Initialisation du son
        initNotificationSound() {
            try {
                this.notificationSound = new Audio(
                    "public/sounds/notification.mp3",
                );
                this.notificationSound.volume = 0.3;
            } catch (e) {
                console.warn("Audio non supporté");
            }
        },

        // Configuration du click outside
        setupClickOutside() {
            const handler = (e) => {
                if (!this.$el.contains(e.target)) {
                    this.isOpen = false;
                }
            };
            document.addEventListener("click", handler);
            this.$once("hook:beforeUnmount", () => {
                document.removeEventListener("click", handler);
            });
        },

        // Initialisation de l'écouteur en temps réel
        initRealtimeListener() {
            if (typeof window.Echo !== "undefined") {
                window.Echo.channel("admin-all")
                    .listen(".Events_all_admin", (e) => {
                        this.handleNewNotification(e);
                    })
                    .listen(".AdminNotifEvent", (e) => {
                        this.handleNewNotification(e);
                    });
            }
        },

        // Gestionnaire de nouvelle notification
        handleNewNotification(event) {
            const notification = event.notifications || event;

            // Ajouter la nouvelle notification en tête de liste
            notification.highlight = true;
            this.notifications = [notification, ...this.notifications];

            // Jouer le son
            this.playNotificationSound();

            // Afficher le toast
            this.lastNotification = notification;
            this.showToast = true;

            // Animer la cloche
            this.ringBell();

            // Auto-cacher le toast après 5 secondes
            if (this.toastTimeout) {
                clearTimeout(this.toastTimeout);
            }
            this.toastTimeout = setTimeout(() => {
                this.showToast = false;
            }, 5000);

            // Enlever le highlight après 3 secondes
            setTimeout(() => {
                const index = this.notifications.findIndex(
                    (n) => n.id === notification.id,
                );
                if (index !== -1) {
                    this.notifications[index].highlight = false;
                }
            }, 3000);

            // Émettre l'événement pour d'autres composants
            this.$emit("new-notification", notification);
        },

        // Sonner la cloche
        ringBell() {
            this.hasNewNotification = true;
            setTimeout(() => {
                this.hasNewNotification = false;
            }, 2000);
        },

        // Jouer le son
        playNotificationSound() {
            if (this.notificationSound && this.notificationSound.play) {
                this.notificationSound
                    .play()
                    .catch((e) => console.log("Son bloqué"));
            }

            // Vibrer si supporté
            if (window.navigator.vibrate) {
                window.navigator.vibrate([200, 100, 200]);
            }
        },

        // Charger les notifications
        async loadNotifications() {
            this.isLoading = true;
            try {
                const response = await axios.get("/account/get-notifications");
                this.notifications = response.data.unread_notifications || [];
                this.unreadCountCache = this.unreadCount;
            } catch (error) {
                console.error("Erreur de chargement:", error);
                this.showErrorToast("Erreur de chargement");
            } finally {
                this.isLoading = false;
            }
        },

        // Rafraîchir les notifications
        async refreshNotifications() {
            await this.loadNotifications();
        },

        // Gérer le clic sur une notification
        async handleNotificationClick(notification) {
            if (!notification.read_at) {
                await this.markAsRead(notification.id);
            }

            // Navigation si un lien est fourni
            if (notification.data.link) {
                window.location.href = notification.data.link;
            } else {
                window.location.href = "/account/notifications";
            }
        },

        // Marquer comme lu
        async markAsRead(notificationId) {
            try {
                await axios.post("/account/mark-as-read", {
                    id: notificationId,
                });
                const index = this.notifications.findIndex(
                    (n) => n.id === notificationId,
                );
                if (index !== -1) {
                    this.notifications[index].read_at =
                        new Date().toISOString();
                }
            } catch (error) {
                console.error("Erreur:", error);
            }
        },

        // Marquer tout comme lu
        async markAllAsRead() {
            try {
                await axios.get("/account/make_read_all");
                this.notifications = this.notifications.map((n) => ({
                    ...n,
                    read_at: new Date().toISOString(),
                }));
                this.showSuccessToast("Toutes lues");
            } catch (error) {
                console.error("Erreur:", error);
                this.showErrorToast("Erreur lors du marquage");
            }
        },

        // Basculer le dropdown
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.hasNewNotification = false;
            }
        },

        // Fermer le dropdown
        closeDropdown() {
            this.isOpen = false;
        },

        // Ouvrir les notifications
        openNotifications() {
            window.location.href = "/account/notifications";
        },

        // Obtenir l'icône appropriée pour la notification
        getNotificationIcon(notification) {
            const type = notification.data.type || "info";
            const icons = {
                success: { icon: "fas fa-check-circle", class: "success" },
                warning: {
                    icon: "fas fa-exclamation-triangle",
                    class: "warning",
                },
                error: { icon: "fas fa-times-circle", class: "error" },
                info: { icon: "fas fa-info-circle", class: "info" },
                default: { icon: "fas fa-bell", class: "default" },
            };
            return icons[type] || icons.default;
        },

        // Formater la date
        formatDate(date) {
            if (!date) return "";

            try {
                const now = new Date();
                const notifDate = new Date(date);

                const diffMs = now - notifDate;
                const diffMinutes = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMinutes < 1) {
                    return "À l'instant";
                } else if (diffMinutes < 60) {
                    return `Il y a ${diffMinutes} min`;
                } else if (diffHours < 24) {
                    const time = notifDate.toLocaleTimeString("fr-FR", {
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                    return `Aujourd'hui à ${time}`;
                } else if (diffDays < 2) {
                    const time = notifDate.toLocaleTimeString("fr-FR", {
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                    return `Hier à ${time}`;
                } else {
                    return notifDate.toLocaleString("fr-FR", {
                        day: "2-digit",
                        month: "2-digit",
                        year: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                }
            } catch (e) {
                return date;
            }
        },
        // Afficher un toast de succès
        showSuccessToast(message) {
            if (window.toastr) {
                window.toastr.success(message, "Succès");
            }
        },

        // Afficher un toast d'erreur
        showErrorToast(message) {
            if (window.toastr) {
                window.toastr.error(message, "Erreur");
            }
        },
    },
});
</script>

<style scoped>
/* Container */
.intro-x.dropdown {
    position: relative;
}

/* Cloche de notification */
.notification {
    position: relative;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.notification:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.notification.active {
    background-color: rgba(102, 126, 234, 0.1);
}

.notification-bell {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-bell i {
    font-size: 1.4rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.notification:hover .notification-bell i {
    color: #667eea;
}

/* Animation de la cloche */
.notification-bell.ringing i {
    animation: ring 0.5s ease-in-out;
}

@keyframes ring {
    0% {
        transform: rotate(0);
    }
    20% {
        transform: rotate(15deg);
    }
    40% {
        transform: rotate(-15deg);
    }
    60% {
        transform: rotate(10deg);
    }
    80% {
        transform: rotate(-10deg);
    }
    100% {
        transform: rotate(0);
    }
}

/* Badge */
.badge-notification {
    position: absolute;
    top: -5px;
    right: -5px;
    background: linear-gradient(135deg, #f97316, #ef4444);
    color: white;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 5px;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    animation: bounceIn 0.3s ease;
}

@keyframes bounceIn {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

/* Dropdown */
.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    width: 380px;
    margin-top: 10px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    z-index: 1000;
}

/* Transition */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(-10px);
    opacity: 0;
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

.header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.notification-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.btn-mark-read,
.btn-refresh {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-mark-read:hover,
.btn-refresh:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
}

.btn-refresh.rotating i {
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Loading */
.notification-loading {
    padding: 40px;
    text-align: center;
    background: #f8fafc;
}

.spinner {
    width: 40px;
    height: 40px;
    margin: 0 auto 10px;
    border: 3px solid #e2e8f0;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Liste */
.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

/* Élément */
.notification-item {
    display: flex;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    background: white;
}

.notification-item:hover {
    background-color: #f8fafc;
    transform: translateX(5px);
}

.notification-item.is-new {
    background: linear-gradient(to right, #f0f9ff, white);
}

.notification-item.highlight {
    animation: highlight 1s ease;
}

@keyframes highlight {
    0%,
    100% {
        background: white;
    }
    50% {
        background: #e0f2fe;
    }
}

/* Icône */
.notification-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.notification-icon.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.notification-icon.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.notification-icon.info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.notification-icon.default {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

.notification-icon i {
    color: white;
    font-size: 1.2rem;
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
    font-size: 0.95rem;
    display: block;
    margin-bottom: 4px;
}

.notification-body {
    margin: 0;
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-time {
    font-size: 0.75rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}

.notification-time i {
    font-size: 0.7rem;
}

/* Statut */
.notification-status {
    margin-left: 10px;
    display: flex;
    align-items: center;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #3b82f6;
    box-shadow: 0 0 0 rgba(59, 130, 246, 0.4);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}

/* Footer */
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
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.view-all-link:hover {
    color: #764ba2;
    gap: 8px;
}

/* État vide */
.notification-empty {
    padding: 40px 20px;
    text-align: center;
    background: #f8fafc;
}

.empty-animation {
    margin-bottom: 20px;
}

.empty-text {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 1.1rem;
}

.empty-subtext {
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Toast personnalisé */
.custom-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    max-width: 400px;
    cursor: pointer;
    z-index: 9999;
    border-left: 4px solid #667eea;
    animation: slideInRight 0.3s ease;
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.toast-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.toast-icon i {
    color: white;
    font-size: 1.2rem;
}

.toast-content {
    flex: 1;
}

.toast-content strong {
    display: block;
    color: #1e293b;
    font-size: 0.95rem;
    margin-bottom: 4px;
}

.toast-content span {
    color: #64748b;
    font-size: 0.85rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.toast-close {
    width: 24px;
    height: 24px;
    border: none;
    background: transparent;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.toast-close:hover {
    background: #f1f5f9;
    color: #ef4444;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Scrollbar */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive */
@media (max-width: 768px) {
    .dropdown-menu {
        position: fixed;
        top: 60px;
        left: 10px;
        right: 10px;
        width: auto;
        max-height: calc(100vh - 80px);
    }

    .notification-item:hover {
        transform: none;
    }

    .custom-toast {
        left: 10px;
        right: 10px;
        min-width: auto;
        max-width: none;
    }
}
</style>
