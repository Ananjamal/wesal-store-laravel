<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Notification {
  id: string
  data: {
    message: string
    order_number?: string
    url?: string
  }
  created_at: string
  read_at: string | null
}

const isOpen = ref(false)
const notifications = ref<Notification[]>([])
const unreadCount = ref(0)

const fetchNotifications = async () => {
  try {
    const res = await fetch('/api/v1/customer/notifications', {
      headers: { 'Accept': 'application/json' }
    })
    if (res.ok) {
      const data = await res.json()
      notifications.value = data.data || []
      unreadCount.value = notifications.value.filter(n => !n.read_at).length
    }
  } catch (e) {
    // Ignore offline/fallback
  }
}

onMounted(() => {
  fetchNotifications()
})
</script>

<template>
  <div class="relative">
    <button 
      @click="isOpen = !isOpen" 
      class="relative p-2 rounded-xl text-wisal-charcoal dark:text-wisal-ivory hover:bg-wisal-beige/20 dark:hover:bg-gray-800 transition-colors"
      title="الإشعارات"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
      </svg>
      
      <span v-if="unreadCount > 0" class="absolute top-1 right-1 w-4 h-4 bg-wisal-aqua text-white text-[10px] font-black rounded-full flex items-center justify-center animate-pulse">
        {{ unreadCount }}
      </span>
    </button>

    <!-- Dropdown Menu -->
    <div 
      v-if="isOpen" 
      class="absolute left-0 mt-3 w-80 bg-white dark:bg-wisal-charcoal border border-wisal-beige/20 dark:border-gray-800 rounded-2xl shadow-2xl p-4 z-50 space-y-3"
    >
      <div class="flex items-center justify-between border-b border-wisal-beige/10 pb-2">
        <h4 class="font-black text-sm text-wisal-charcoal dark:text-wisal-ivory">الإشعارات</h4>
        <span class="text-xs text-wisal-aqua font-bold">{{ unreadCount }} غير مقروء</span>
      </div>

      <div v-if="notifications.length === 0" class="text-center py-6 text-xs text-gray-400 font-bold">
        لا توجد إشعارات جديدة حالياً
      </div>

      <div v-else class="max-h-64 overflow-y-auto space-y-2 pr-1">
        <a 
          v-for="item in notifications" 
          :key="item.id" 
          :href="item.data.url || '#'" 
          class="block p-3 rounded-xl bg-wisal-beige/5 dark:bg-gray-800/40 hover:bg-wisal-beige/10 transition-colors border border-wisal-beige/10"
        >
          <p class="text-xs font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ item.data.message }}</p>
          <span class="text-[10px] text-gray-400 block mt-1">{{ new Date(item.created_at).toLocaleTimeString() }}</span>
        </a>
      </div>
    </div>
  </div>
</template>
