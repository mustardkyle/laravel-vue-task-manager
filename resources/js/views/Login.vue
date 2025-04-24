<template>
    <div class="min-h-screen flex items-center justify-center bg-[#fffcfc]">
        <div class="w-full max-w-sm bg-[#383838] rounded-xl p-8">
            <h1 class="text-2xl font-bold text-center mb-6 text-[#F6E27F]">
                Log In
            </h1>
            <form @submit.prevent="handleLogin" class="space-y-4">
                <!-- Email Input -->
                <div class="relative">
                    <span
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#F6E27F]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 12H8m8 0a4 4 0 10-8 0 4 4 0 008 0z"
                            />
                        </svg>
                    </span>
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Email"
                        required
                        class="w-full pl-10 pr-4 py-2 bg-[#2A2A2A] text-[#F6E27F] placeholder-[#F6E27F] rounded-md focus:outline-none focus:ring-2 focus:ring-[#F6E27F] border-none"
                    />
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <span
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#F6E27F]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v1h1a2 2 0 012 2v5H6v-5a2 2 0 012-2h1v-1c0-1.657 1.343-3 3-3s3 1.343 3 3z"
                            />
                        </svg>
                    </span>
                    <input
                        v-model="password"
                        type="password"
                        placeholder="Password"
                        required
                        class="w-full pl-10 pr-4 py-2 bg-[#2A2A2A] text-[#F6E27F] placeholder-[#F6E27F] rounded-md focus:outline-none focus:ring-2 focus:ring-[#F6E27F] border-none"
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="authStore.loading"
                    class="w-full bg-[#F6E27F] text-[#1E1E1E] font-bold py-2 rounded-md hover:bg-[#e5d266] transition duration-200 flex items-center justify-center"
                >
                    <svg
                        v-if="authStore.loading"
                        class="animate-spin h-5 w-5 text-[#1E1E1E] mr-2"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v8H4z"
                        />
                    </svg>
                    <span>{{
                        authStore.loading ? "Logging in..." : "LOGIN"
                    }}</span>
                </button>

                <!-- Error Message -->
                <p v-if="error" class="text-red-500 text-center mt-2">
                    {{ error }}
                </p>
            </form>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Register
                <RouterLink
                    to="/register"
                    class="text-blue-600 hover:underline font-medium"
                    >here</RouterLink
                >
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";

const email = ref("");
const password = ref("");
const error = ref(null);
const authStore = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
    error.value = null;
    authStore.loading = true;

    try {
        await authStore.login({ email: email.value, password: password.value });
        router.push("/");
    } catch (err) {
        error.value = err;
    } finally {
        authStore.loading = false;
    }
};
</script>
