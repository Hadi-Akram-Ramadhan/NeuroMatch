import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import { useAuthStore } from '@/stores/auth';

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
    meta: { requiresAuth: true },
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("./pages/Profile.vue"),
    meta: { requiresAuth: true },
  },
  {
    path: "/match",
    name: "Match",
    component: () => import("./pages/Match.vue"),
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// eslint-disable-next-line @typescript-eslint/no-unused-vars
router.beforeEach((to, _from, next) => {
  const auth = useAuthStore();
  if (to.meta.requiresAuth && !auth.token) {
    next('/login');
  } else {
    next();
  }
});

export default router;
