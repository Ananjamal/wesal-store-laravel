<script setup lang="ts">
interface Props {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger'
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
  primary: 'bg-wisal-aqua text-wisal-ivory hover:bg-wisal-aqua/90',
  secondary: 'bg-wisal-beige text-wisal-charcoal hover:bg-wisal-beige/80',
  ghost: 'bg-transparent text-wisal-charcoal hover:bg-wisal-beige/30',
  danger: 'bg-red-600 text-white hover:bg-red-700',
}

const sizeClasses: Record<NonNullable<Props['size']>, string> = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-base',
  lg: 'px-6 py-3 text-lg',
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
      'rounded-lg font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed',
      variantClasses[variant],
      sizeClasses[size],
    ]"
    @click="handleClick"
  >
    <span v-if="loading" class="inline-block animate-spin mr-2">⏳</span>
    <slot />
  </button>
</template>
