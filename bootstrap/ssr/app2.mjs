import _ from "lodash";
import "bootstrap";
import axios$1 from "axios";
import Echo$1 from "laravel-echo";
import Pusher from "pusher-js";
import { mergeProps, useSSRContext, createApp } from "vue";
import { ssrRenderAttrs, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
window._ = _;
window.axios = axios$1;
try {
  window.Popper = require("@popperjs/core").default;
  window.$ = window.jQuery = require("jquery");
} catch (e) {
}
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
  window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
  console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.Pusher = Pusher;
window.Echo = new Echo$1({
  broadcaster: "pusher",
  key: "6def2bf59050804f7478",
  cluster: "eu",
  forceTLS: true,
  encryption: true
});
const vueToastr2_min = "";
const style = "";
const AdminNotification_vue_vue_type_style_index_0_scoped_8beb5777_lang = "";
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main = {
  props: ["unreads", "userid"],
  data() {
    return {
      notifications: []
    };
  },
  methods: {
    getNotifications() {
      axios.get("/account/get-notifications").then((response) => {
        this.notifications = response.data.unread_notifications;
      }).catch((error) => {
        console.log(error);
      });
    },
    markAsRead() {
      axios.get("/account/make_read_all", {}).then((response) => {
        this.getNotifications();
      }).catch((error) => {
        console.log(error);
      });
    }
  },
  created() {
    this.getNotifications();
    Echo.channel("admin-all").listen(".Events_all_admin", (e) => {
      if (e.notifications) {
        Swal.fire({
          position: "top-end",
          icon: "success",
          timer: 2500,
          title: "!!Vous avez une nouvelle notification",
          showConfirmButton: false
        });
        this.getNotifications();
      }
      this.notifications = e.notifications;
    });
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  var _a, _b;
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "intro-x dropdown mr-auto sm:mr-6" }, _attrs))} data-v-8beb5777><div class="dropdown-toggle notification cursor-pointer" aria-expanded="false" data-tw-toggle="dropdown" data-v-8beb5777><a href="#" class="dropdown-toggle" data-toggle="dropdown" data-v-8beb5777><i class="fas fa-bell" data-v-8beb5777></i><i class="badge badge-danger" data-v-8beb5777>${ssrInterpolate((_a = $data.notifications) == null ? void 0 : _a.length)}</i></a></div><div class="notification-content pt-2 dropdown-menu" data-v-8beb5777><div class="notification-content__box dropdown-content" data-v-8beb5777><div class="notification-content__title" data-v-8beb5777>Notifications</div>`);
  if ((_b = $data.notifications) == null ? void 0 : _b.length) {
    _push(`<ul class="notify-bloc" data-v-8beb5777>`);
    if (_ctx.index < 2) {
      _push(`<!--[-->`);
      ssrRenderList($data.notifications, (msg, index) => {
        _push(`<li class="notify-item mb-3" data-v-8beb5777><a class="text-primary" data-v-8beb5777>${ssrInterpolate(msg.data["title"])}</a></li>`);
      });
      _push(`<!--]-->`);
    } else {
      _push(`<!---->`);
    }
    _push(`<li data-v-8beb5777><a href="/account/notifications" class="text-danger" data-v-8beb5777>Toutes les notifications...</a></li></ul>`);
  } else {
    _push(`<div class="w-full truncate text-slate-500 mt-0.5 text-danger" data-v-8beb5777> Aucune notification trouv\xE9e </div>`);
  }
  _push(`</div></div></div>`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/components/AdminNotification.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const App = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender], ["__scopeId", "data-v-8beb5777"]]);
createApp(App).mount("#app");
let remSlidrLodrInt = setInterval(function() {
  let loader = $("#ritekhela-loader");
  if (typeof loader !== "undefined") {
    loader.remove();
  }
  clearInterval(remSlidrLodrInt);
}, 2e3);
