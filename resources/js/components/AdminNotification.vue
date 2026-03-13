<template>
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <div
            class="dropdown-toggle notification cursor-pointer"
            aria-expanded="false"
            data-tw-toggle="dropdown"
        >
            <a
                href="#"
                class="dropdown-toggle"
                data-toggle="dropdown"
                @click.prevent
            >
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger" v-if="unreadCount > 0">
                    {{ unreadCount }}
                </span>
            </a>
        </div>

        <div class="notification-content pt-2 dropdown-menu">
            <div class="notification-content__box dropdown-content">
                <div class="notification-content__title">Notifications</div>

                <!-- Liste des notifications -->
                <ul class="notify-bloc" v-if="notifications.length > 0">
                    <li
                        class="notify-item mb-3"
                        v-for="(notification, index) in displayedNotifications"
                        :key="notification.id"
                        @click="markAsRead(notification.id)"
                    >
                        <a class="text-primary">{{
                            notification.data.message
                        }}</a>
                    </li>

                    <!-- Lien vers toutes les notifications -->
                    <li v-if="notifications.length > 2">
                        <a href="/account/notifications" class="text-danger">
                            Toutes les notifications...
                        </a>
                    </li>
                </ul>

                <!-- Message si aucune notification -->
                <div
                    class="w-full truncate text-slate-500 mt-0.5 text-danger"
                    v-else
                >
                    Aucune notification trouvée
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    props: {
        userid: {
            type: [Number, String],
            required: true,
        },
        unreads: {
            type: Array,
            default: () => [],
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
            return this.notifications.length;
        },
        displayedNotifications() {
            return this.notifications.slice(0, 2);
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

    created() {
        // Charger les notifications initiales
        this.initNotificationSound();
        this.loadNotifications();
        this.initRealtimeListener();
    },
};
</script>

<style scoped>
.notify-drop-div {
    width: 300px;
    padding: 2px !important;
    margin-top: 1000rem;
}

.mb-3 {
    margin-top: 12px;
    margin-bottom: 0;
}

.notify-bloc {
    padding: 1px !important;
    list-style: none;
}

.notify-item {
    margin: 0 !important;
    padding: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notify-item:hover {
    background-color: #f3f4f6;
}

.badge-danger {
    color: #fff;
    background-color: #dc3545;
    position: absolute;
    top: -5px;
    right: -5px;
    border-radius: 50%;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}

.badge {
    display: inline-flex;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}

.ritekhela-user-strip li i {
    float: left;
    margin: 8px 8px 0px 0px;
}

.notification {
    position: relative;
}
</style>
