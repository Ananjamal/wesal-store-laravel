<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import WSkeleton from '@/Components/ui/WSkeleton.vue'

const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

const page = usePage()
const activeLocale = ref(page.props.locale as string || 'ar')

const query = ref('')
const results = ref<any[]>([])
const isLoading = ref(false)
let debounceTimer: any = null

function search() {
  if (debounceTimer) clearTimeout(debounceTimer)
  
  if (!query.value.trim()) {
    results.value = []
    return
  }

  isLoading.value = true
  debounceTimer = setTimeout(async () => {
    try {
      const response = await axios.get('/api/catalog/products', {
        params: { search: query.value }
      })
      results.value = response.data.data || []
    } catch (e) {
      console.error(e)
    } finally {
      isLoading.value = false
    }
  }, 400)
}

function handleClose() {
  query.value = ''
  results.value = []
  emit('close')
}

function visitProduct(slug: string) {
  handleClose()
  router.visit(`/products/${slug}`)
}

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Escape' && props.isOpen) {
    handleClose()
  }
}

onMounted(() => {
  window.addEventListener('keydown', onKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <transition 
    enter-active-class="transition duration-200 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-150 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div 
      v-if="isOpen" 
      class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-20 md:pt-28"
    >
      <!-- Overlay Backdrop -->
      <div class="absolute inset-0 bg-wisal-charcoal/60 backdrop-blur-md" @click="handleClose"></div>

      <!-- Main Panel Box -->
      <div 
        class="relative w-full max-w-2xl bg-white dark:bg-wisal-charcoal border border-wisal-beige/20 dark:border-gray-800 rounded-3xl shadow-premium-lg flex flex-col overflow-hidden max-h-[70vh] z-10"
        :dir="activeLocale === 'ar' ? 'rtl' : 'ltr'"
      >
        <!-- Input Header -->
        <div class="p-5 border-b border-wisal-beige/10 dark:border-gray-800 flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-wisal-aqua">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
          </svg>
          <input 
            v-model="query"
            @input="search"
            type="text"
            autofocus
            :placeholder="activeLocale === 'ar' ? 'اكتب اسم المنتج أو الكلمات المفتاحية...' : 'Type product name or keywords...'"
            class="flex-grow bg-transparent border-none text-base md:text-lg focus:outline-none focus:ring-0 dark:text-wisal-ivory"
          />
          <button 
            @click="handleClose"
            class="p-1.5 hover:bg-wisal-beige/10 dark:hover:bg-gray-800 rounded-xl transition-colors text-gray-400"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Scrollable Suggestions Content -->
        <div class="overflow-y-auto custom-scrollbar p-5 space-y-4">
          <!-- Loading state -->
          <div v-if="isLoading" class="space-y-3">
            <div v-for="i in 3" :key="i" class="flex gap-4 items-center">
              <WSkeleton width="w-12" height="h-12" rounded="rounded-xl" />
              <div class="flex-grow space-y-1.5">
                <WSkeleton width="w-1/2" height="h-4" />
                <WSkeleton width="w-1/4" height="h-3" />
              </div>
            </div>
          </div>

          <template v-else>
            <!-- Empty state -->
            <div 
              v-if="!query.trim()" 
              class="text-center py-8 text-gray-400 text-sm"
            >
              {{ activeLocale === 'ar' ? 'ابحث عن هدايا، منظمات وأجندات وصال المميزة...' : 'Search for gifts, planners and special agendas...' }}
            </div>

            <!-- No results -->
            <div 
              v-else-if="results.length === 0" 
              class="text-center py-8 text-gray-400 text-sm"
            >
              {{ activeLocale === 'ar' ? 'لم نجد أي نتائج مطابقة.' : 'No matching results found.' }}
            </div>

            <!-- Suggestion Results -->
            <div v-else class="space-y-3">
              <div 
                v-for="item in results" 
                :key="item.id"
                @click="visitProduct(item.slug)"
                class="flex gap-4 items-center p-3 rounded-2xl bg-gray-50/50 hover:bg-wisal-beige/10 dark:bg-gray-800/20 dark:hover:bg-gray-800/50 cursor-pointer transition-all duration-200"
              >
                <!-- Thumbnail -->
                <img 
                  :src="item.images && item.images.length > 0 ? item.images[0].thumb : 'https://picsum.photos/seed/product-' + item.id + '/100/100'" 
                  :alt="item.name" 
                  class="w-12 h-12 rounded-xl object-cover bg-white"
                />
                
                <!-- Info -->
                <div class="flex-grow min-w-0">
                  <h4 class="font-bold text-sm text-wisal-charcoal dark:text-wisal-ivory truncate">
                    {{ item.name }}
                  </h4>
                  <span v-if="item.category" class="text-xs text-wisal-aqua font-semibold">
                    {{ item.category.name }}
                  </span>
                </div>

                <!-- Price -->
                <div class="text-right flex-shrink-0">
                  <span class="text-sm font-extrabold text-wisal-aqua">
                    {{ item.price }}
                  </span>
                  <span class="text-[10px] text-gray-500 font-bold ml-0.5">ر.س</span>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </transition>
</template>
