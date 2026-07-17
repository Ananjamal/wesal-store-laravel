<script setup lang="ts">
interface Props {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger' | 'outline'
  size?: 'sm' | 'md' | 'lg'
  loading?: boolean
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  loading: false,
  disabled: false,
})

const emit = defineEmits<{ click: [event: MouseEvent] }>()

const variantClasses: Record<NonNullable<Props['variant']>, string> = {
  primary: 'bg-wisal-aqua text-wisal-ivory hover:bg-wisal-aqua/90 shadow-md shadow-wisal-aqua/10 hover:shadow-lg hover:shadow-wisal-aqua/20 hover:-translate-y-0.5 active:translate-y-0',
  secondary: 'bg-wisal-beige text-wisal-charcoal hover:bg-wisal-beige/80 hover:-translate-y-0.5 active:translate-y-0',
  ghost: 'bg-transparent text-wisal-charcoal dark:text-wisal-ivory hover:bg-wisal-beige/10 dark:hover:bg-gray-800',
  danger: 'bg-red-600 text-white hover:bg-red-700 shadow-md shadow-red-500/10 hover:shadow-red-500/20 hover:-translate-y-0.5 active:translate-y-0',
  outline: 'bg-transparent text-wisal-aqua border-2 border-wisal-aqua hover:bg-wisal-aqua/5 hover:-translate-y-0.5 active:translate-y-0'
}

const sizeClasses: Record<NonNullable<Props['size']>, string> = {
  sm: 'px-4 py-2 text-xs md:text-sm rounded-xl',
  md: 'px-6 py-3 text-sm md:text-base rounded-2xl',
  lg: 'px-8 py-4 text-base md:text-lg rounded-2xl',
}

function handleClick(event: MouseEvent) {
  if (props.disabled || props.loading) return
  emit('click', event)
}
</script>

<template>
  <button
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center font-bold tracking-wide transition-all duration-300 transform disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none',
      variantClasses[variant],
      sizeClasses[size],
    ]"
    @click="handleClick"
  >
    <!-- Shimmer loading effect -->
    <svg 
      v-if="loading" 
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <slot />
  </button>
</template>
