import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";

const routes: RouteRecordRaw[] = [
  {
    path: "/",
    name: "Home",
    component: () => import("./pages/Home.vue"),
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("./pages/Login.vue"),
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("./pages/Register.vue"),
  },
  {
    path: "/eeg-upload",
    name: "EEGUpload",
    component: () => import("./pages/EEGUpload.vue"),
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("./pages/Profile.vue"),
  },
  {
    path: "/match",
    name: "Match",
    component: () => import("./pages/Match.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
