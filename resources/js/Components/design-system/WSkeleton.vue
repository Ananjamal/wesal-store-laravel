<script setup lang="ts">
interface Props {
  type?: 'text' | 'circular' | 'rectangular'
  width?: string
  height?: string
}

withDefaults(defineProps<Props>(), {
  type: 'text',
  width: '100%',
  height: 'auto',
})
</script>

<template>
  <div
    :class="[
      'animate-pulse bg-gray-200',
      {
        'rounded-md h-4': type === 'text',
        'rounded-full': type === 'circular',
        'rounded-xl': type === 'rectangular'
      }
    ]"
    :style="{ width, height: type === 'text' && height === 'auto' ? '1rem' : height }"
  />
</template>
