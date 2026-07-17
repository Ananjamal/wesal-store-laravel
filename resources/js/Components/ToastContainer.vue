<script setup lang="ts">
import { computed } from 'vue'
import { useToastStore } from '@/stores/toast'

const toastStore = useToastStore()
const toasts = computed(() => toastStore.toasts)

const typeClasses = {
  success: 'bg-white dark:bg-[#323232] border-green-500 text-green-600 dark:text-green-400',
  danger: 'bg-white dark:bg-[#323232] border-red-500 text-red-600 dark:text-red-400',
  warning: 'bg-white dark:bg-[#323232] border-yellow-500 text-yellow-600 dark:text-yellow-400',
  info: 'bg-white dark:bg-[#323232] border-blue-500 text-blue-600 dark:text-blue-400',
}
</script>

<template>
  <div class="fixed bottom-6 left-6 z-50 flex flex-col gap-3 w-full max-w-[340px] pointer-events-none md:max-w-sm">
    <transition-group 
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-6 opacity-0 scale-95"
      enter-to-class="transform translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100 scale-100"
      leave-to-class="transform translate-y-2 opacity-0 scale-95"
    >
      <div 
        v-for="toast in toasts" 
        :key="toast.id"
        :class="[
          'pointer-events-auto border-r-4 shadow-premium-lg rounded-2xl p-4 flex items-start gap-3 backdrop-blur-md bg-white/95 dark:bg-[#242424]/95 border border-wisal-beige/10 dark:border-gray-800 transition-all',
          typeClasses[toast.type]
        ]"
      >
        <!-- Icon based on type -->
        <span class="flex-shrink-0 mt-0.5">
          <!-- Success -->
          <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-green-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <!-- Danger -->
          <svg v-else-if="toast.type === 'danger'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-red-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
          </svg>
          <!-- Warning -->
          <svg v-else-if="toast.type === 'warning'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-yellow-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
          </svg>
          <!-- Info -->
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
          </svg>
        </span>

        <!-- Message Body -->
        <div class="flex-grow min-w-0">
          <p class="text-xs md:text-sm font-extrabold text-wisal-charcoal dark:text-wisal-ivory leading-relaxed">
            {{ toast.message }}
          </p>
        </div>

        <!-- Manual Dismiss Button -->
        <button 
          @click="toastStore.removeToast(toast.id)"
          class="flex-shrink-0 p-1 hover:bg-wisal-beige/10 dark:hover:bg-gray-800 rounded-lg text-gray-400 hover:text-wisal-charcoal dark:hover:text-wisal-ivory transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </transition-group>
  </div>
</template>
