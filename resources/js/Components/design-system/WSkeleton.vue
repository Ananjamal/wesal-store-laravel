<script setup lang="ts">
withDefaults(defineProps<{
  width?: string
  height?: string
  rounded?: string
}>(), {
  width: 'w-full',
  height: 'h-4',
  rounded: 'rounded-xl',
})
</script>

<template>
  <div 
    :class="[
      'shimmer-bg',
      width,
      height,
      rounded
    ]"
  ></div>
</template>
