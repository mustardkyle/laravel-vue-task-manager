<script setup>
import { computed } from "vue";
import { useRoute, RouterLink, useRouter, RouterView } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

// Determine if the navigation bar should be shown
const showNav = computed(() => route.meta.requiresAuth);

// Handle user logout
const handleLogout = async () => {
    await authStore.logout();
    router.push("/login"); // Redirect to login page after logout
};
</script>

<template>
    <header v-if="showNav" class="bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <!-- Navigation Links -->
                <nav class="flex items-center space-x-8">
                    <RouterLink
                        to="/"
                        class="text-gray-700 font-semibold hover:text-blue-600 transition-colors"
                        :class="{
                            'text-blue-600 border-b-2 border-blue-600':
                                $route.path === '/',
                        }"
                    >
                        My Task
                    </RouterLink>
                    <RouterLink
                        to="/admin"
                        class="text-gray-700 font-semibold hover:text-blue-600 transition-colors"
                        :class="{
                            'text-blue-600 border-b-2 border-blue-600':
                                $route.path === '/admin',
                        }"
                    >
                        Admin
                    </RouterLink>
                </nav>

                <!-- Logout Button -->
                <div class="flex items-center space-x-4">
                    <button
                        v-if="authStore.user"
                        @click="handleLogout"
                        class="text-gray-700 font-semibold hover:text-red-600 transition-colors"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="bg-gray-50 min-h-screen py-6">
        <RouterView />
    </main>
</template>
