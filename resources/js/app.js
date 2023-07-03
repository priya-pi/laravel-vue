import "./bootstrap";

import { createApp } from "vue";
import App from "./components/App.vue";

import "bootstrap/dist/css/bootstrap.min.css";
import router from "./router";
import VueAxios from "vue-axios";
import axios from "axios";
import Store from "./Store/Store.js";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

import userMixin from './mixin.js';

const app = createApp(App);
app.mixin(userMixin)
app.use(Store);
app.use(VueSweetalert2);
app.use(ToastPlugin);
app.use(VueAxios, axios);
app.use(router);
app.mount("#app");

