<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'outline', 'ghost'].includes(value),
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    type: {
        type: String,
        default: 'button',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['click']);

const classes = computed(() => {
    const base = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-md disabled:opacity-50 disabled:cursor-not-allowed';
    
    const sizes = {
        sm: 'px-3 py-1.5 text-sm',
        md: 'px-4 py-2 text-base',
        lg: 'px-6 py-3 text-lg',
    };
    
    const variants = {
        primary: 'bg-wisal-aqua text-white hover:bg-wisal-aqua/90 focus:ring-wisal-aqua',
        secondary: 'bg-wisal-beige text-wisal-charcoal hover:bg-wisal-beige/90 focus:ring-wisal-beige',
        outline: 'border-2 border-wisal-aqua text-wisal-aqua hover:bg-wisal-aqua/10 focus:ring-wisal-aqua',
        ghost: 'text-wisal-charcoal hover:bg-wisal-beige/30 focus:ring-wisal-beige',
    };

    return `${base} ${sizes[props.size]} ${variants[props.variant]}`;
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="classes"
        @click="emit('click', $event)"
    >
        <svg
            v-if="loading"
            class="animate-spin -ml-1 mr-2 h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <slot />
    </button>
</template>
