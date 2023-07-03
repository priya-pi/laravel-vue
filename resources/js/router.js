import { createWebHistory, createRouter } from "vue-router";
import store from './Store/Store.js';

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("./components/Home.vue"),
    },
    {
        path: "/register",
        name: "register",
        component: () => import("./components/Register.vue"),
        meta: {
            requiredAuth: false,
        },
    },
    {
        path: "/login",
        name: "login",
        component: () => import("./components/Login.vue"),
        meta: {
            requiredAuth: false,
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("./components/Dashboard.vue"),
        meta: {
            requiredAuth: true,
        },
    },
    {
        path: "/profileEdit/:id",
        name: "profileEdit",
        component: () => import("./components/ProfileEdit.vue"),
        meta: {
            requiredAuth: true,
        },
    },
    {
        path: "/companies",
        name: "companies",
        component: () => import("./components/Company.vue"),
        meta: {
            requiredAuth: true,
        },
    },
    {
        path: "/companies/add",
        name: "companies.add",
        component: () => import("./components/CompanyAdd.vue"),
        meta: {
            requiredAuth: true,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from) => {
    if (to.meta.requiredAuth &&  store.getters.getToken == 0) {
        return { name: "login" };
    }
    if (to.meta.requiredAuth == false && store.getters.getToken != 0) {
        return { name: "dashboard" };
    }
});

export default router;
