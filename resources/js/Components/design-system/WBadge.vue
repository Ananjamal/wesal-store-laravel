<script setup lang="ts">
interface Props {
  variant?: 'success' | 'warning' | 'danger' | 'info' | 'neutral'
  size?: 'sm' | 'md'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'neutral',
  size: 'md',
})

const variantClasses = {
  success: 'bg-green-100 text-green-800',
  warning: 'bg-yellow-100 text-yellow-800',
  danger: 'bg-red-100 text-red-800',
  info: 'bg-blue-100 text-blue-800',
  neutral: 'bg-wisal-beige/30 text-wisal-charcoal',
}

const sizeClasses = {
  sm: 'px-2 py-0.5 text-xs',
  md: 'px-2.5 py-1 text-sm',
}
</script>

<template>
  <span
    :class="[
      'inline-flex items-center justify-center rounded-full font-medium',
      variantClasses[variant],
      sizeClasses[size]
    ]"
  >
    <slot />
  </span>
</template>
