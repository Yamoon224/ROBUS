import './bootstrap'
import { createApp } from 'vue'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
import './style.css'


import App from './components/AdminNotification.vue';

createApp(App).mount('#app')

let remSlidrLodrInt = setInterval(function () {
  let loader = $('#ritekhela-loader')
  if (typeof loader !== 'undefined') {
    loader.remove()
  }
  clearInterval(remSlidrLodrInt)
}, 2000)

