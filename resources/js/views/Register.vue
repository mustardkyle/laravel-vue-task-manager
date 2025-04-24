<template>
    <div class="min-h-screen flex items-center justify-center bg-[#fffcfc]">
        <div class="w-full max-w-sm bg-[#383838] rounded-xl p-8">
            <h1 class="text-2xl font-bold text-center mb-6 text-[#F6E27F]">
                Register
            </h1>
            <form @submit.prevent="handleRegister" class="space-y-4">
                <!-- Name Input -->
                <InputField
                    v-model="form.name"
                    type="text"
                    placeholder="Name"
                    icon="M5.121 17.804A4 4 0 016 15h12a4 4 0 01.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                />

                <!-- Email Input -->
                <InputField
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    icon="M16 12H8m8 0a4 4 0 10-8 0 4 4 0 008 0z"
                />

                <!-- Password Input -->
                <InputField
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    icon="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v1h1a2 2 0 012 2v5H6v-5a2 2 0 012-2h1v-1c0-1.657 1.343-3 3-3s3 1.343 3 3z"
                />

                <!-- Confirm Password Input -->
                <InputField
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Confirm Password"
                    icon="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v1h1a2 2 0 012 2v5H6v-5a2 2 0 012-2h1v-1c0-1.657 1.343-3 3-3s3 1.343 3 3z"
                />

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
                        authStore.loading ? "Registering..." : "REGISTER"
                    }}</span>
                </button>

                <!-- Error Message -->
                <p v-if="error" class="text-red-500 text-center mt-2">
                    {{ error }}
                </p>
            </form>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-400 mt-4">
                Already have an account?
                <RouterLink
                    to="/login"
                    class="text-blue-400 hover:underline font-medium"
                    >Login</RouterLink
                >
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, RouterLink } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import InputField from "@/components/InputField.vue"; // Reusable input field component

const router = useRouter();
const authStore = useAuthStore();
const error = ref(null);

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const handleRegister = async () => {
    error.value = null;
    authStore.loading = true;
    try {
        await authStore.register(form.value);
        router.push("/"); // Redirect to home after successful registration
    } catch (err) {
        error.value = err;
    } finally {
        authStore.loading = false;
    }
};
</script>
