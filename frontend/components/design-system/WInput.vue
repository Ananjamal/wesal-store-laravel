<script setup lang="ts">
interface Props {
  modelValue: string | number
  label?: string
  type?: 'text' | 'email' | 'password' | 'number' | 'search'
  placeholder?: string
  error?: string
  disabled?: boolean
  required?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  error: '',
  disabled: false,
  required: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
}>()

function onInput(event: Event) {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', target.value)
}
</script>

<template>
  <div class="flex flex-col gap-1.5">
    <label v-if="label" class="text-sm font-medium text-wisal-charcoal">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="[
        'px-4 py-2 rounded-lg border bg-white focus:outline-none focus:ring-2 transition-all duration-200',
        error 
          ? 'border-red-500 focus:ring-red-200 focus:border-red-500' 
          : 'border-gray-200 focus:border-wisal-aqua focus:ring-wisal-aqua/20',
        disabled ? 'opacity-50 bg-gray-50 cursor-not-allowed' : ''
      ]"
      @input="onInput"
    >
    
    <span v-if="error" class="text-xs text-red-500 mt-0.5">{{ error }}</span>
  </div>
</template>
