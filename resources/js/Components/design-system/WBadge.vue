<script setup lang="ts">
interface Props {
  variant?: 'success' | 'warning' | 'danger' | 'info' | 'neutral' | 'primary'
  size?: 'sm' | 'md'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'neutral',
  size: 'md',
})

const variantClasses = {
  success: 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20',
  warning: 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-500/20',
  danger: 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
  info: 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20',
  neutral: 'bg-wisal-beige/20 text-wisal-charcoal dark:text-wisal-ivory border border-wisal-beige/30 dark:border-gray-800',
  primary: 'bg-wisal-aqua/10 text-wisal-aqua dark:text-wisal-aqua border border-wisal-aqua/20',
}

const sizeClasses = {
  sm: 'px-2.5 py-1 text-[10px] md:text-xs font-bold rounded-lg',
  md: 'px-3 py-1.5 text-xs md:text-sm font-bold rounded-xl',
}
</script>

<template>
  <span
    :class="[
      'inline-flex items-center justify-center transition-colors duration-200',
      variantClasses[variant],
      sizeClasses[size]
    ]"
  >
    <slot />
  </span>
</template>
