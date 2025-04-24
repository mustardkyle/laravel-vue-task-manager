<template>
    <div
        v-if="show"
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative"
            @click.stop
        >
            <button
                @click="$emit('close')"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
                aria-label="Close modal"
            >
                ✖
            </button>
            <slot />
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
});
</script>
