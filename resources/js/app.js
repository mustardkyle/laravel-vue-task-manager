import { createApp } from "vue";
import { createPinia } from "pinia";
import axios from "axios";

import App from "./app.vue";
import router from "./router";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import { useAuthStore } from "@/stores/authStore";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

async function initializeAuth(authStore) {
    if (authStore.token) {
        axios.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${authStore.token}`;
        try {
            await authStore.getUser(authStore.token);
        } catch (err) {
            console.error("Failed to fetch user:", err);
        }
    }
}

const app = createApp(App);

// Initialize Pinia with persisted state plugin
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia);

// Initialize authentication
const authStore = useAuthStore();
await initializeAuth(authStore);

// Use plugins and mount the app
app.use(router);
app.use(ToastPlugin);
app.mount("#app");
