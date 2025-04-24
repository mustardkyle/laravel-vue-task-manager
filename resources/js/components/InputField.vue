<template>
    <div class="relative">
        <!-- Input Field -->
        <input
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :type="type"
            :placeholder="placeholder"
            class="w-full px-10 py-2 rounded-md bg-[#151515] text-white focus:outline-none focus:ring-2 focus:ring-[#F6E27F] placeholder-gray-400"
        />

        <!-- Icon -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg
                v-if="icon && modelValue.length == 0"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="h-5 w-5 text-gray-400"
            >
                <path :d="icon" />
            </svg>
        </div>
    </div>
</template>

<script>
export default {
    name: "InputField",
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            default: "",
        },
        icon: {
            type: String,
            default: null,
        },
    },
    emits: ["update:modelValue"],
};
</script>

<style scoped>
/* Add any additional styling here if needed */
</style>