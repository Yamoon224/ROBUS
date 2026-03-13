import { createApp } from 'vue'
import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import Swal from 'sweetalert2'

// Configuration globale
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.Swal = Swal

// Token CSRF
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

// Configuration Pusher/Echo
window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || '6def2bf59050804f7478',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'eu',
    forceTLS: true,
    encrypted: true
})

// Import du composant
import AdminNotification from './components/AdminNotification.vue'

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
    // Chercher le nouvel élément de montage
    const mountElement = document.getElementById('vue-notifications-app')
    
    if (mountElement) {
        // Créer l'application Vue
        const app = createApp({})
        
        // Enregistrer le composant
        app.component('admin-notification', AdminNotification)
        
        // Monter l'application
        app.mount('#vue-notifications-app')
        
        console.log('Vue app mounted successfully')
    } else {
        console.error('Element #vue-notifications-app not found')
    }
})