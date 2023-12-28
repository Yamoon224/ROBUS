<template>
    <li>
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-bell"></i>

            <span id="notify_num" style="color:red;" v-show="notifications.length > 0">{{ notifications.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right justify-content-center notify-drop-div"
            aria-labelledby="dropdownMenuButton">
            <ul class="notify-bloc side-menu__sub-open" v-if="notifications.length">
                <div v-for="(msg, index) in notifications" @click="mark_as_read" v-if="index < 2" class="notifi__item">
                    <div class="bg-c2 img-cir img-40">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="content">
                        <p>
                            <a href="/admin/notifications">
                                {{ msg.data['message'] }}
                            </a>
                        </p>
                        <span class="date">{{ msg.created_at }}</span>
                    </div>
                </div>
            </ul>
    <li v-else>
        <h5 class="text-danger">Aucune notification trouvée</h5>
    </li>
    </div>
    </li>
</template>

<script>
export default {
    props: ['unreads', 'userid'],
    data() {
        return {
            notifications: this.unreads
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
            axios.post('/account/mark_notifications_as_read', {})
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
                if (e.notification) {
                    Swal.fire({
                        // position: 'top-end',
                        icon: 'success',
                        timer: 2000,
                        title: '!!Vous avez une nouvelle notification',
                        showConfirmButton: true,
                    });
                }
                this.notifications = e.notification;
            });
    }
}
</script>
<style scoped>
.notify-drop-div {
    width: 300px;
    padding: 2px !important;
    margin-top: 10px;
}

.notify-bloc {
    padding: 1px !important;
}

.notify-item {
    margin: 0 !important;
}
</style>

