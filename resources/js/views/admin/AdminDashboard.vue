<script setup>
import { onMounted, ref, computed, reactive } from "vue";
import axios from "axios";

const usersWithTasks = ref([]);
const showTasks = reactive({}); // Track open/close state per user

const currentPage = ref(1);
const perPage = 5;

// Fetch users with tasks on mount
onMounted(async () => {
    try {
        const response = await axios.get("/api/backend/admin/users-tasks");
        usersWithTasks.value = response.data;

        // Initialize collapsed state
        response.data.forEach((user) => {
            showTasks[user.id] = false;
        });
    } catch (err) {
        console.error("Failed to fetch user tasks:", err);
    }
});

// Computed properties for pagination
const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return usersWithTasks.value.slice(start, start + perPage);
});

const totalPages = computed(() =>
    Math.ceil(usersWithTasks.value.length / perPage)
);

// Pagination controls
function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
}

// Toggle task visibility for a user
function toggleTasks(userId) {
    showTasks[userId] = !showTasks[userId];
}
</script>

<template>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Admin Dashboard</h1>

        <!-- User List -->
        <div
            v-for="user in paginatedUsers"
            :key="user.id"
            class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-200"
        >
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ user.name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                </div>
                <button
                    @click="toggleTasks(user.id)"
                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm"
                >
                    {{ showTasks[user.id] ? "Hide Tasks" : "Show Tasks" }}
                </button>
            </div>

            <!-- User Task Statistics -->
            <div class="flex gap-4 text-sm mb-4">
                <div
                    class="bg-green-100 text-green-800 px-3 py-2 rounded shadow-sm"
                >
                    ✅ Completed: <strong>{{ user.stats.completed }}</strong>
                </div>
                <div
                    class="bg-yellow-100 text-yellow-800 px-3 py-2 rounded shadow-sm"
                >
                    🕓 Pending: <strong>{{ user.stats.pending }}</strong>
                </div>
                <div
                    class="bg-gray-100 text-gray-800 px-3 py-2 rounded shadow-sm"
                >
                    📋 Total: <strong>{{ user.stats.total }}</strong>
                </div>
            </div>

            <!-- Task List -->
            <div
                :class="[
                    'transition-all duration-500 overflow-hidden',
                    showTasks[user.id] ? 'max-h-[1000px]' : 'max-h-0',
                ]"
            >
                <ul
                    class="list-disc list-inside text-sm text-gray-700 space-y-1 mt-2 pl-2 border-t pt-2"
                >
                    <li v-for="task in user.tasks" :key="task.id">
                        <strong>{{ task.title }}</strong> —
                        <span
                            :class="
                                task.status === 'completed'
                                    ? 'text-green-600'
                                    : 'text-yellow-700'
                            "
                        >
                            {{ task.status }}
                        </span>
                        <em class="ml-2 text-gray-500"
                            >({{ task.priority }})</em
                        >
                    </li>
                </ul>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6 gap-2">
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
            >
                Prev
            </button>

            <button
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                    'px-3 py-1 rounded',
                    page === currentPage
                        ? 'bg-blue-500 text-white'
                        : 'bg-gray-100 hover:bg-gray-200',
                ]"
            >
                {{ page }}
            </button>

            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>
</template>
