<script setup lang="ts">
import { useScrollLock } from '@vueuse/core'
import { watch, ref } from 'vue'

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
        <!-- Backdrop with rich blur -->
        <div class="absolute inset-0 bg-wisal-charcoal/60 backdrop-blur-md" @click="close"></div>
        
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
              'relative bg-white dark:bg-wisal-charcoal border border-wisal-beige/20 dark:border-gray-800/80 rounded-3xl shadow-premium-lg w-full flex flex-col max-h-[85vh] overflow-hidden',
              maxWidthClasses[maxWidth]
            ]"
          >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-wisal-beige/10 dark:border-gray-800">
              <h3 v-if="title" class="text-xl font-extrabold text-wisal-charcoal dark:text-wisal-ivory">{{ title }}</h3>
              <div v-else class="flex-grow"><slot name="header" /></div>
              
              <button 
                @click="close"
                class="p-2 -mr-2 text-gray-400 hover:text-wisal-aqua rounded-xl hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-all duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar">
              <slot />
            </div>
            
            <!-- Footer -->
            <div v-if="$slots.footer" class="p-6 border-t border-wisal-beige/10 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/30">
              <slot name="footer" />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>
