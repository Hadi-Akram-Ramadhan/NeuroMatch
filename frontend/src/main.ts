import { createApp } from "vue";
import "./style.css";
import "./assets/index.css";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import { useAuthStore } from "@/stores/auth";

const app = createApp(App);
const pinia = createPinia();
app.use(pinia);

// Persistent login
useAuthStore().init();

app.use(router);
app.mount("#app");
