<script setup>
import { onMounted, ref, computed } from "vue";
import { useTaskStore } from "@/stores/taskStore";
import { useAuthStore } from "@/stores/authStore";
import { storeToRefs } from "pinia";
import { Container, Draggable } from "vue-dndrop";
import { initEcho } from "@/plugins/echo";

const taskStore = useTaskStore();
const authStore = useAuthStore();

const { tasks } = storeToRefs(taskStore);
const { toggleComplete, updateTaskOrder, deleteTask } = taskStore;

const searchQuery = ref("");
const priorityFilter = ref("all");
const statusFilter = ref("all");

// Fetch tasks and initialize Echo
onMounted(async () => {
    await taskStore.fetchTasks({ user_id: authStore.user.id });

    const echo = initEcho(authStore.token);
    if (!echo) {
        console.warn("Echo not initialized: no token");
        return;
    }

    echo.channel(`tasks.${authStore.user.id}`).listen("TaskUpdated", (e) => {
        console.log("Task updated:", e.task);
        taskStore.updateTaskFromBroadcast(e.task);
    });
});

// Priority colors helper
const priorityColor = (priority) => {
    const colors = {
        high: "bg-red-100 text-red-900",
        medium: "bg-yellow-100 text-yellow-900",
        low: "bg-green-100 text-green-900",
    };
    return colors[priority] || "bg-gray-100 text-gray-900";
};

// Handle drag-and-drop reordering
const onDrop = async (dropResult) => {
    await taskStore.reorderTasks(dropResult);
};

// Filtered tasks computed property
const filteredTasks = computed(() => {
    return tasks.value.filter((task) => {
        const matchesSearch =
            task.title
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            task.description
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase());
        const matchesPriority =
            priorityFilter.value === "all" ||
            task.priority === priorityFilter.value;
        const matchesStatus =
            statusFilter.value === "all" || task.status === statusFilter.value;

        return matchesSearch && matchesPriority && matchesStatus;
    });
});

// Task statistics computed property
const taskStats = computed(() => {
    const completed = filteredTasks.value.filter(
        (t) => t.status === "completed"
    ).length;
    const pending = filteredTasks.value.filter(
        (t) => t.status === "pending"
    ).length;
    const total = filteredTasks.value.length;

    return { completed, pending, total };
});
</script>

<template>
    <div class="container mx-auto my-8 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">
            My Tasks
        </h2>

        <!-- Filters -->
        <div class="flex justify-between items-center mb-4 gap-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search tasks..."
                class="border border-gray-300 rounded px-3 py-2 flex flex-grow"
            />

            <select
                v-model="priorityFilter"
                class="border border-gray-300 rounded px-3 py-2"
            >
                <option value="all">All Priorities</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>

            <select
                v-model="statusFilter"
                class="border border-gray-300 rounded px-3 py-2"
            >
                <option value="all">All Status</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <!-- Task Statistics -->
        <div class="flex justify-center gap-6 mb-4 text-sm text-gray-700">
            <div
                class="bg-green-100 text-green-800 px-4 py-2 rounded shadow-sm"
            >
                ✅ Completed: <strong>{{ taskStats.completed }}</strong>
            </div>
            <div
                class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded shadow-sm"
            >
                🕓 Pending: <strong>{{ taskStats.pending }}</strong>
            </div>
            <div class="bg-gray-100 text-gray-800 px-4 py-2 rounded shadow-sm">
                📋 Total: <strong>{{ taskStats.total }}</strong>
            </div>
        </div>

        <!-- Task List -->
        <Container
            v-if="filteredTasks.length"
            :group-name="'tasks'"
            @drop="onDrop"
            drag-class="opacity-50"
            drop-class="border-2 border-blue-500"
            drag-handle-selector=".column-drag-handle"
        >
            <Draggable
                v-for="task in filteredTasks"
                :key="task.id"
                :item="task"
            >
                <li
                    class="flex bg-gray-50 border border-gray-200 rounded-lg hover:shadow transition list-none mb-4"
                >
                    <div
                        :class="`flex items-center p-3 rounded-s-lg ${priorityColor(
                            task.priority
                        )}`"
                    >
                        <span
                            class="column-drag-handle cursor-grab active:cursor-grabbing"
                            >&#x2630;</span
                        >
                    </div>
                    <div class="flex-grow p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3
                                class="text-lg font-semibold text-gray-800"
                                :class="{
                                    'line-through text-gray-500':
                                        task.status === 'completed',
                                }"
                            >
                                {{ task.title }}
                            </h3>
                            <span
                                :key="task.status"
                                class="text-xs font-bold py-1 px-2 rounded"
                                :class="
                                    task.status === 'completed'
                                        ? 'bg-green-200 text-green-800'
                                        : 'bg-yellow-200 text-yellow-800'
                                "
                            >
                                {{
                                    task.status === "completed"
                                        ? "✓ Completed"
                                        : "○ Pending"
                                }}
                            </span>
                        </div>

                        <p class="text-gray-600 mb-3">{{ task.description }}</p>

                        <div
                            class="flex items-center justify-between text-sm text-gray-500"
                        >
                            <div>
                                <span class="font-medium text-gray-700"
                                    >Priority:</span
                                >
                                <span
                                    :class="`py-0.5 px-2 rounded ${priorityColor(
                                        task.priority
                                    )}`"
                                >
                                    {{ task.priority }}
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <button
                                    class="py-1 px-3 rounded text-xs transition"
                                    :class="
                                        task.status === 'pending'
                                            ? 'bg-blue-200 text-blue-800 hover:bg-blue-300'
                                            : 'bg-yellow-200 text-yellow-800 hover:bg-yellow-300'
                                    "
                                    @click="toggleComplete(task.id)"
                                >
                                    {{
                                        task.status === "completed"
                                            ? "Mark as Pending"
                                            : "Mark as Done"
                                    }}
                                </button>

                                <button
                                    v-if="authStore.isAdmin"
                                    class="py-1 px-3 bg-red-500 hover:bg-red-600 text-white rounded text-xs transition"
                                    @click="deleteTask(task.id)"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            </Draggable>
        </Container>

        <!-- No Tasks Message -->
        <div v-else class="text-center text-gray-500 p-4">No tasks yet!</div>
    </div>
</template>
