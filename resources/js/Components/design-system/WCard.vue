<script setup lang="ts">
interface Props {
  padding?: 'none' | 'sm' | 'md' | 'lg'
  shadow?: 'none' | 'sm' | 'md' | 'lg'
  border?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  padding: 'md',
  shadow: 'sm',
  border: true,
})

const paddingClasses = {
  none: 'p-0',
  sm: 'p-4',
  md: 'p-6',
  lg: 'p-8',
}

const shadowClasses = {
  none: 'shadow-none',
  sm: 'shadow-sm',
  md: 'shadow-md',
  lg: 'shadow-lg',
}
</script>

<template>
  <div
    :class="[
      'bg-white rounded-xl overflow-hidden',
      paddingClasses[padding],
      shadowClasses[shadow],
      { 'border border-gray-100': border }
    ]"
  >
    <slot />
  </div>
</template>
