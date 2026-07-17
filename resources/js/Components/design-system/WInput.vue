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
  <div class="flex flex-col gap-2">
    <label 
      v-if="label" 
      class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory transition-colors duration-200"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="[
        'w-full px-5 py-3.5 rounded-2xl border bg-white dark:bg-wisal-charcoal/40 text-wisal-charcoal dark:text-wisal-ivory placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-0 transition-all duration-300',
        error 
          ? 'border-red-500 focus:ring-red-200/50 focus:border-red-500' 
          : 'border-wisal-beige/30 dark:border-gray-800 focus:border-wisal-aqua focus:ring-wisal-aqua/20 dark:focus:ring-wisal-aqua/10',
        disabled ? 'opacity-50 bg-gray-50 dark:bg-gray-900 cursor-not-allowed' : ''
      ]"
      @input="onInput"
    >
    
    <span v-if="error" class="text-xs text-red-500 font-medium mt-0.5">{{ error }}</span>
  </div>
</template>
