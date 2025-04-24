<script setup>
import { ref } from "vue";
import { useTaskStore } from "@/stores/taskStore";
import { useToast } from "vue-toast-notification";

const taskStore = useTaskStore();
const toast = useToast();
const emit = defineEmits(["taskCreated"]);

const form = ref({
    title: "",
    description: "",
    status: "pending",
    priority: "medium",
});

const resetForm = () => {
    form.value = {
        title: "",
        description: "",
        status: "pending",
        priority: "medium",
    };
};

const handleSubmit = async () => {
    try {
        await taskStore.createTask({ ...form.value });
        toast.success("Task created successfully!");
        resetForm();
        emit("taskCreated");
    } catch (error) {
        toast.error("Failed to create task");
    }
};
</script>

<template>
    <div class="w-11/12 max-w-xl mx-auto">
        <h2 class="text-center text-2xl font-semibold text-gray-700 mb-6">
            Create Task
        </h2>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label for="title" class="block text-gray-700 font-medium mb-1"
                    >Title</label
                >
                <input
                    id="title"
                    v-model="form.title"
                    type="text"
                    placeholder="Task title"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                />
            </div>

            <div>
                <label
                    for="description"
                    class="block text-gray-700 font-medium mb-1"
                    >Description</label
                >
                <textarea
                    id="description"
                    v-model="form.description"
                    placeholder="Task description"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label
                        for="status"
                        class="block text-gray-700 font-medium mb-1"
                        >Status</label
                    >
                    <select
                        id="status"
                        v-model="form.status"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                    >
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label
                        for="priority"
                        class="block text-gray-700 font-medium mb-1"
                        >Priority</label
                    >
                    <select
                        id="priority"
                        v-model="form.priority"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                    >
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
            >
                Create Task
            </button>
        </form>
    </div>
</template>
