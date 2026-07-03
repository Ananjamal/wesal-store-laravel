<script setup lang="ts">
import { useScrollLock } from '@vueuse/core'
import { watch, ref, onMounted } from 'vue'

interface Props {
  modelValue: boolean
  title?: string
  maxWidth?: 'sm' | 'md' | 'lg' | 'xl'
}

const props = withDefaults(defineProps<Props>(), {
  maxWidth: 'md',
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const modalRef = ref<HTMLElement | null>(null)
const isLocked = useScrollLock(typeof window !== 'undefined' ? document.body : null)

watch(() => props.modelValue, (isOpen) => {
  isLocked.value = isOpen
})

function close() {
  emit('update:modelValue', false)
}

const maxWidthClasses = {
  sm: 'max-w-sm',
  md: 'max-w-md',
  lg: 'max-w-lg',
  xl: 'max-w-xl',
}
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-wisal-charcoal/40 backdrop-blur-sm" @click="close"></div>
        
        <!-- Modal Panel -->
        <Transition
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="opacity-0 scale-95 translate-y-4"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 scale-100 translate-y-0"
          leave-to-class="opacity-0 scale-95 translate-y-4"
        >
          <div
            v-if="modelValue"
            ref="modalRef"
            :class="[
              'relative bg-white rounded-2xl shadow-xl w-full flex flex-col max-h-[90vh]',
              maxWidthClasses[maxWidth]
            ]"
          >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
              <h3 v-if="title" class="text-xl font-bold text-wisal-charcoal">{{ title }}</h3>
              <div v-else><slot name="header" /></div>
              
              <button 
                @click="close"
                class="p-2 -mr-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors"
              >
                <Icon name="heroicon-o-x-mark" class="w-5 h-5" />
              </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto">
              <slot />
            </div>
            
            <!-- Footer -->
            <div v-if="$slots.footer" class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
              <slot name="footer" />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>
