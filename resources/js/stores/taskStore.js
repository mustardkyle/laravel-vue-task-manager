import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";

export const useTaskStore = defineStore("tasks", () => {
    // State
    const tasks = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const toast = useToast();

    // Computed Properties
    const taskStats = computed(() => {
        const total = tasks.value.length;
        const completed = tasks.value.filter(
            (task) => task.status === "completed"
        ).length;
        const pending = total - completed;

        return { total, completed, pending };
    });

    const filteredTasks = computed(
        () => (statusFilter, priorityFilter, searchQuery) => {
            return tasks.value.filter((task) => {
                const matchesStatus =
                    statusFilter === "all" || task.status === statusFilter;
                const matchesPriority =
                    priorityFilter === "all" ||
                    task.priority === priorityFilter;
                const matchesSearch = task.title
                    .toLowerCase()
                    .includes(searchQuery.toLowerCase());
                return matchesStatus && matchesPriority && matchesSearch;
            });
        }
    );

    // Actions
    async function fetchTasks(filters = {}) {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.get("/api/backend/tasks", {
                params: filters,
            });
            tasks.value = response.data;
        } catch (err) {
            console.error("Failed to fetch tasks:", err);
            error.value = err;
        } finally {
            loading.value = false;
        }
    }

    async function createTask(taskData) {
        try {
            const response = await axios.post("/api/backend/tasks", taskData);
            tasks.value.push(response.data);
            toast.success("Task created successfully!");
        } catch (err) {
            console.error("Failed to create task:", err);
            toast.error("Failed to create task!");
        }
    }

    async function reorderTasks(dragResult) {
        const { removedIndex, addedIndex, payload } = dragResult;
        if (removedIndex === null && addedIndex === null) return;

        const result = [...tasks.value];
        let itemToAdd = payload;

        if (removedIndex !== null) {
            itemToAdd = result.splice(removedIndex, 1)[0];
        }

        if (addedIndex !== null) {
            result.splice(addedIndex, 0, itemToAdd);
        }

        tasks.value = result;

        const taskIds = result.map((item) => item.id);

        try {
            await axios.post("/api/backend/tasks/reorder", { tasks: taskIds });
            toast.success("Tasks reordered successfully!");
        } catch (err) {
            console.error("Failed to reorder tasks:", err);
            toast.error("Failed to reorder tasks!");
        }
    }

    function toggleComplete(taskId) {
        const taskIndex = tasks.value.findIndex((t) => t.id === taskId);

        if (taskIndex !== -1) {
            const task = tasks.value[taskIndex];
            const newStatus =
                task.status === "pending" ? "completed" : "pending";

            task.status = newStatus;

            axios
                .put(`/api/backend/tasks/${taskId}`, { status: newStatus })
                .then((response) => {
                    tasks.value[taskIndex] = response.data;
                    toast.success("Task status updated successfully!");
                })
                .catch((err) => {
                    console.error("Failed to update task status:", err);
                    toast.error("Failed to update task status!");
                });
        } else {
            console.warn(`Task with id ${taskId} not found.`);
        }
    }

    async function deleteTask(taskId) {
        try {
            await axios.delete(`/api/backend/tasks/${taskId}`);
            tasks.value = tasks.value.filter((task) => task.id !== taskId);
            toast.success("Task deleted successfully!");
        } catch (err) {
            console.error("Failed to delete task:", err);
            toast.error("Failed to delete task!");
        }
    }

    function updateTaskFromBroadcast(updatedTask) {
        const index = tasks.value.findIndex((t) => t.id === updatedTask.id);
        if (index !== -1) {
            tasks.value[index] = updatedTask;
        } else {
            tasks.value.push(updatedTask);
        }
    }

    return {
        tasks,
        loading,
        error,
        fetchTasks,
        createTask,
        reorderTasks,
        toggleComplete,
        deleteTask,
        updateTaskFromBroadcast,
        filteredTasks,
        taskStats,
    };
});
