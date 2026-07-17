<script setup lang="ts">
interface Props {
  padding?: 'none' | 'sm' | 'md' | 'lg'
  shadow?: 'none' | 'sm' | 'md' | 'lg'
  border?: boolean
  glass?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  padding: 'md',
  shadow: 'sm',
  border: true,
  glass: false,
})

const paddingClasses = {
  none: 'p-0',
  sm: 'p-4 md:p-5',
  md: 'p-6 md:p-8',
  lg: 'p-8 md:p-10',
}

const shadowClasses = {
  none: 'shadow-none',
  sm: 'shadow-premium-sm',
  md: 'shadow-premium-md',
  lg: 'shadow-premium-lg',
}
</script>

<template>
  <div
    :class="[
      glass 
        ? 'glass-premium rounded-3xl' 
        : 'bg-white dark:bg-wisal-charcoal/40 rounded-3xl',
      paddingClasses[padding],
      shadowClasses[shadow],
      border ? 'border border-wisal-beige/20 dark:border-gray-800/80' : 'border-none',
      'overflow-hidden transition-all duration-300'
    ]"
  >
    <slot />
  </div>
</template>
