<template>
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <div class="dropdown-toggle notification cursor-pointer" aria-expanded="false" data-tw-toggle="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <i class="badge badge-danger">
                    {{ notifications?.length }}
                </i>
            </a>
        </div>
        <div class="notification-content pt-2 dropdown-menu">
            <div class="notification-content__box dropdown-content">
                <div class="notification-content__title">Notifications</div>
                <ul class="notify-bloc" v-if="notifications?.length">
                    <li class="notify-item mb-3" v-for="(msg, index) in notifications" @click="markAsRead"
                        v-if="index < 2">
                        <a class="text-primary">{{ msg.data['title'] }}</a>
                    </li>
                    <li><a href="/account/notifications" class="text-danger">Toutes les
                            notifications...</a></li>
                </ul>

                <div class="w-full truncate text-slate-500 mt-0.5 text-danger" v-else>
                    Aucune notification trouvée
                </div>

            </div>

        </div>
    </div>

</template>

<script>

const playNotificationSound = () => {
        const audio = new Audio('public/sounds/notification.wav');
        audio.play().catch(() => {
            console.warn('Son non joué (peut-être bloqué par le navigateur)');
        });
};

export default {
    props: ['unreads', 'userid'],
    data() {
        return {
            notifications: []
        }
    },
    methods: {
        getNotifications() {
            axios.get('/account/get-notifications')
                .then(response => {
                    this.notifications = response.data.unread_notifications;

                })
                .catch(error => {
                    console.log(error);
                });
        },
        markAsRead() {
            axios.get('/account/make_read_all', {})
                .then(response => {
                    this.getNotifications();
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    created() {
        this.getNotifications();
        Echo.channel('admin-all')
            .listen('.Events_all_admin', (e) => {
                if (e.notifications) {
                    Swal.fire({
                        toast: true,
                         width: 600,
                         hight: 600,
                        position: 'top-end',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        title: '!!Vous avez une nouvelle notification',
                        showConfirmButton: false,
                    });
                    this.getNotifications();
                    playNotificationSound();
                }
                this.notifications = e.notifications;
            });
    }
}
</script>
<style scoped>
.notify-drop-div {
    width: 300px;
    padding: 2px !important;
    margin-top: 1000rem;
}

.mb-3 {
    margin-top: 12px;
}

.notify-bloc {
    padding: 1px !important;
}

.notify-item {
    margin: 0 !important;
}

.badge-danger {
    color: #fff;
    background-color: #dc3545;
}

.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.ritekhela-user-strip li i {
    float: left;
    margin: 8px 8px 0px 0px;
}
</style>



