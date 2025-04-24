<template>
    <div class="container mx-auto text-right">
        <!-- Create Task Button -->
        <button
            @click="toggleModal(true)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
            Create Task
        </button>

        <!-- Task List -->
        <TaskList />

        <!-- Modal with Task Form -->
        <Modal :show="showModal" @close="toggleModal(false)">
            <TaskForm @taskCreated="toggleModal(false)" />
        </Modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import TaskList from "@/components/TaskList.vue";
import TaskForm from "@/components/TaskForm.vue";
import Modal from "@/components/Template/Modal.vue";

const showModal = ref(false);

/**
 * Toggles the modal visibility.
 *
 * @param {boolean} state - The desired visibility state of the modal.
 */
function toggleModal(state) {
    showModal.value = state;
}
</script>
