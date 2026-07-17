import { ref } from 'vue'
import { defineStore } from 'pinia'

export interface Toast {
  id: string
  message: string
  type: 'success' | 'danger' | 'warning' | 'info'
}

export const useToastStore = defineStore('toast', () => {
  const toasts = ref<Toast[]>([])

  function addToast(message: string, type: Toast['type'] = 'success') {
    const id = Math.random().toString(36).substring(2, 9)
    toasts.value.push({ id, message, type })
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
      removeToast(id)
    }, 4000)
  }

  function removeToast(id: string) {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  return { toasts, addToast, removeToast }
})
