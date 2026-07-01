<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="block text-sm font-medium text-wisal-charcoal mb-1">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>
        
        <input
            :type="type"
            :value="modelValue"
            @input="emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :disabled="disabled"
            :class="[
                'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 sm:text-sm bg-white disabled:bg-gray-100 disabled:cursor-not-allowed transition-colors',
                error 
                    ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' 
                    : 'border-gray-300 text-wisal-charcoal focus:ring-wisal-aqua focus:border-wisal-aqua'
            ]"
        >
        
        <p v-if="error" class="mt-1 text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>
