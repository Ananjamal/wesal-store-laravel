<script setup>
defineProps({
    shadow: {
        type: String,
        default: 'md', // sm, md, lg, none
    },
    padding: {
        type: String,
        default: 'p-6',
    },
});
</script>

<template>
    <div
        :class="[
            'bg-wisal-ivory rounded-lg border border-wisal-beige/30 overflow-hidden',
            {
                'shadow-sm': shadow === 'sm',
                'shadow-md': shadow === 'md',
                'shadow-lg': shadow === 'lg',
                'shadow-none': shadow === 'none',
            }
        ]"
    >
        <div v-if="$slots.header" class="px-6 py-4 border-b border-wisal-beige/30 bg-wisal-beige/10">
            <slot name="header" />
        </div>
        
        <div :class="padding">
            <slot />
        </div>
        
        <div v-if="$slots.footer" class="px-6 py-4 border-t border-wisal-beige/30 bg-gray-50/50">
            <slot name="footer" />
        </div>
    </div>
</template>
