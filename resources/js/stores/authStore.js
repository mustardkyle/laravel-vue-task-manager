import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";

export const useAuthStore = defineStore(
    "auth",
    () => {
        const user = ref(null);
        const token = ref(null);
        const loading = ref(false);

        const isAdmin = computed(() => user.value?.is_admin === 1);

        const setAuthorizationHeader = (token) => {
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        };

        async function login(credentials) {
            try {
                const response = await axios.post(
                    "api/backend/login",
                    credentials
                );
                token.value = response.data.token;
                user.value = response.data.user;
                setAuthorizationHeader(token.value);
            } catch (error) {
                throw error.response?.data?.message || "Login failed";
            }
        }

        async function getUser(savedToken) {
            try {
                setAuthorizationHeader(savedToken);
                const response = await axios.get("api/backend/user");
                token.value = savedToken;
                user.value = response.data.user;
            } catch (error) {
                throw error.response?.data?.message || "Failed to fetch user";
            }
        }

        async function register(data) {
            try {
                const response = await axios.post("api/backend/register", data);
                token.value = response.data.token;
                user.value = response.data.user;
                setAuthorizationHeader(token.value);
            } catch (error) {
                throw error.response?.data?.message || "Registration failed";
            }
        }

        async function logout() {
            try {
                await axios.post("api/backend/logout");
            } catch (error) {
                console.warn(
                    "Logout failed:",
                    error.response?.data?.message || error.message
                );
            } finally {
                user.value = null;
                token.value = null;
                delete axios.defaults.headers.common["Authorization"];
            }
        }

        return {
            user,
            token,
            loading,
            isAdmin,
            login,
            register,
            getUser,
            logout,
        };
    },
    {
        persist: {
            key: "auth",
            storage: localStorage,
            pick: ["token"],
        },
    }
);
