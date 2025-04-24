import { createRouter, createWebHistory } from "vue-router";
import Task from "../views/Task.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import AdminDashboard from "../views/admin/AdminDashboard.vue";
import { useAuthStore } from "../stores/authStore";

const routes = [
    {
        path: "/",
        name: "task",
        component: Task,
        meta: { requiresAuth: true },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/register",
        name: "register",
        component: Register,
    },
    {
        path: "/admin",
        name: "admin",
        component: AdminDashboard,
        meta: { requiresAuth: true, adminOnly: true },
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Ensure user info is loaded if token exists
    if (!authStore.user && authStore.token) {
        try {
            await authStore.getUser(authStore.token);
        } catch (error) {
            console.error("Failed to fetch user:", error);
            return next("/login");
        }
    }

    // Redirect to login if route requires authentication and no token exists
    if (to.meta.requiresAuth && !authStore.token) {
        return next("/login");
    }

    // Redirect to home if route is admin-only and user is not an admin
    if (to.meta.adminOnly && !authStore.isAdmin) {
        return next("/");
    }

    return next();
});

export default router;
