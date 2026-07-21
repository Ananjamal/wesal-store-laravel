<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import ProductCard from '@/Components/ProductCard.vue'
import WSkeleton from '@/Components/ui/WSkeleton.vue'

interface Product {
  id: number
  name: string
  slug: string
  short_description?: string
  description?: string
  price: number
  compare_at_price?: number | null
  stock_quantity: number
  message?: string | null
  images: Array<{
    original: string
    medium: string
    thumb: string
  }>
  category?: {
    id: number
    name: string
    slug: string
  }
}

interface Category {
  id: number
  name: string
  slug: string
  children?: Category[]
}

interface Color {
  id: number
  name: string
  hex_code: string
}

interface Size {
  id: number
  name: string
  label?: string
}

const props = defineProps<{
  products: {
    data: Product[]
    meta: {
      current_page: number
      last_page: number
      per_page: number
      total: number
      links: Array<{
        url: string | null
        label: string
        active: boolean
      }>
    }
  }
  category: Category
  colors: Color[]
  sizes: Size[]
  filters: {
    color_id?: string
    size_id?: string
    search?: string
    price_min?: string
    price_max?: string
    sort?: string
  }
}>()

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

// Local filter states
const search = ref(props.filters.search || '')
const colorId = ref(props.filters.color_id || '')
const sizeId = ref(props.filters.size_id || '')
const priceMin = ref(props.filters.price_min || '')
const priceMax = ref(props.filters.price_max || '')
const sort = ref(props.filters.sort || 'latest')

const isFilterDrawerOpen = ref(false)
const isPageLoading = ref(false)

function applyFilters() {
  isPageLoading.value = true
  router.get('/category/' + props.category.slug, {
    search: search.value,
    color_id: colorId.value,
    size_id: sizeId.value,
    price_min: priceMin.value,
    price_max: priceMax.value,
    sort: sort.value,
  }, {
    preserveState: true,
    replace: true,
    onFinish: () => {
      isPageLoading.value = false
    }
  })
}

function selectColor(id: number | '') {
  colorId.value = colorId.value === String(id) ? '' : String(id)
  applyFilters()
}

function selectSize(id: number | '') {
  sizeId.value = sizeId.value === String(id) ? '' : String(id)
  applyFilters()
}

function clearFilters() {
  search.value = ''
  colorId.value = ''
  sizeId.value = ''
  priceMin.value = ''
  priceMax.value = ''
  sort.value = 'latest'
  applyFilters()
}

watch(sort, () => {
  applyFilters()
})
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'المتجر - متجر وِصال' : 'Shop - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 space-y-10">
    <!-- Header Page Info -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-wisal-beige/10 dark:border-gray-800 pb-8">
      <div class="space-y-1">
        <h1 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory leading-none">
          {{ props.category.name }}
        </h1>
        <p class="text-gray-400 text-xs md:text-sm font-semibold">
          {{ activeLocale === 'ar' ? `نتائج (${products.meta.total})` : `Results (${products.meta.total})` }}
        </p>
      </div>

      <!-- Search & Sort Actions Bar -->
      <div class="flex flex-wrap items-center gap-4">
        <!-- Live search -->
        <div class="relative w-full sm:w-72">
          <input 
            v-model="search"
            @keyup.enter="applyFilters"
            type="text" 
            :placeholder="activeLocale === 'ar' ? 'ابحث عن منتج...' : 'Search for product...'"
            class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-2xl py-3.5 px-5 pl-12 pr-5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/20 dark:text-wisal-ivory transition-all shadow-premium-sm"
          />
          <button 
            @click="applyFilters"
            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-wisal-aqua"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
            </svg>
          </button>
        </div>

        <!-- Sorting -->
        <div class="relative">
          <select 
            v-model="sort"
            class="appearance-none bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-2xl py-3.5 pl-12 pr-5 text-xs md:text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory focus:outline-none focus:ring-2 focus:ring-wisal-aqua/20 cursor-pointer min-w-[180px] shadow-premium-sm"
          >
            <option value="latest">{{ activeLocale === 'ar' ? 'الأحدث' : 'Latest' }}</option>
            <option value="price_asc">{{ activeLocale === 'ar' ? 'السعر: من الأقل للأعلى' : 'Price: Low to High' }}</option>
            <option value="price_desc">{{ activeLocale === 'ar' ? 'السعر: من الأعلى للأقل' : 'Price: High to Low' }}</option>
            <option value="oldest">{{ activeLocale === 'ar' ? 'الأقدم' : 'Oldest' }}</option>
          </select>
          <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
          </div>
        </div>

        <!-- Mobile filter toggle button -->
        <button 
          @click="isFilterDrawerOpen = !isFilterDrawerOpen"
          class="lg:hidden bg-wisal-aqua text-wisal-ivory px-5 py-3.5 rounded-2xl text-xs font-black flex items-center gap-2 hover:bg-wisal-aqua/90 transition-colors shadow-md"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
          </svg>
          <span>{{ activeLocale === 'ar' ? 'تصفية المنتجات' : 'Filters' }}</span>
        </button>
      </div>
    </div>

    <!-- Main Section Content -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-start">
      <!-- 1. Sidebar Filter panel (Desktop) -->
      <aside class="hidden lg:block space-y-8 bg-white dark:bg-[#323232]/50 p-7 rounded-[32px] border border-wisal-beige/10 dark:border-gray-800/80 shadow-premium-sm">
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
          <h2 class="font-black text-lg text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'تصفية المنتجات' : 'Filter Products' }}</h2>
          <button @click="clearFilters" class="text-xs text-gray-400 hover:text-red-500 font-bold transition-colors">
            {{ activeLocale === 'ar' ? 'تفريغ الكل' : 'Clear All' }}
          </button>
        </div>

        <!-- Color filter swatches -->
        <div v-if="colors && colors.length > 0" class="space-y-4">
          <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'الألوان' : 'Colors' }}</h3>
          <div class="flex flex-wrap gap-3">
            <button 
              v-for="color in colors" 
              :key="color.id"
              @click="selectColor(color.id)"
              :class="[
                'w-8 h-8 rounded-full border shadow-sm relative transition-all duration-300 hover:scale-110 flex items-center justify-center',
                colorId === String(color.id) ? 'ring-2 ring-wisal-aqua ring-offset-2 dark:ring-offset-wisal-charcoal' : 'border-gray-200 dark:border-gray-850'
              ]"
              :style="{ backgroundColor: color.hex_code }"
              :title="color.name"
            >
              <span v-if="colorId === String(color.id)" class="text-white text-xs font-bold mix-blend-difference">✓</span>
            </button>
          </div>
        </div>

        <!-- Size badges -->
        <div v-if="sizes && sizes.length > 0" class="space-y-4">
          <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'المقاسات' : 'Sizes' }}</h3>
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="size in sizes" 
              :key="size.id"
              @click="selectSize(size.id)"
              :class="[
                'min-w-10 h-10 px-3 rounded-xl border text-xs font-black transition-all duration-300 hover:bg-wisal-beige/10',
                sizeId === String(size.id) ? 'bg-wisal-aqua text-white border-wisal-aqua shadow-sm shadow-wisal-aqua/20' : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/25 dark:border-gray-800 text-wisal-charcoal dark:text-wisal-ivory'
              ]"
            >
              {{ size.name }}
            </button>
          </div>
        </div>

        <!-- Price range filters -->
        <div class="space-y-4">
          <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'نطاق السعر (ر.س)' : 'Price (SAR)' }}</h3>
          <div class="flex items-center gap-2">
            <input 
              v-model="priceMin"
              @keyup.enter="applyFilters"
              type="number" 
              :placeholder="activeLocale === 'ar' ? 'من' : 'Min'"
              class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-xl py-2 px-3 text-xs focus:outline-none dark:text-wisal-ivory transition-all"
            />
            <span class="text-gray-400 text-xs">-</span>
            <input 
              v-model="priceMax"
              @keyup.enter="applyFilters"
              type="number" 
              :placeholder="activeLocale === 'ar' ? 'إلى' : 'Max'"
              class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-xl py-2 px-3 text-xs focus:outline-none dark:text-wisal-ivory transition-all"
            />
          </div>
          <button 
            @click="applyFilters"
            class="w-full bg-wisal-aqua text-wisal-ivory text-xs py-3 rounded-2xl font-black hover:bg-wisal-aqua/90 transition-all hover:shadow-md hover:shadow-wisal-aqua/15"
          >
            {{ activeLocale === 'ar' ? 'تطبيق السعر' : 'Apply Price' }}
          </button>
        </div>
      </aside>

      <!-- 2. Products grid section -->
      <div class="lg:col-span-3 space-y-12">
        <div v-if="isPageLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
          <div v-for="i in 6" :key="i" class="space-y-4">
            <WSkeleton height="h-[280px]" rounded="rounded-3xl" />
            <WSkeleton width="w-2/3" height="h-5" />
            <WSkeleton width="w-1/2" height="h-4" />
            <div class="flex justify-between items-center pt-2">
              <WSkeleton width="w-1/3" height="h-6" />
              <WSkeleton width="w-10" height="h-10" rounded="rounded-xl" />
            </div>
          </div>
        </div>

        <template v-else>
          <!-- Empty State -->
          <div 
            v-if="products.data.length === 0" 
            class="text-center py-24 bg-white dark:bg-[#323232]/20 rounded-[32px] border border-dashed border-wisal-beige/20 dark:border-gray-800 max-w-xl mx-auto space-y-4"
          >
            <div class="text-5xl text-gray-300">🎁</div>
            <h3 class="font-extrabold text-lg text-wisal-charcoal dark:text-wisal-ivory">
              {{ activeLocale === 'ar' ? 'لم نجد أي هدايا تطابق اختيارك!' : 'No gifts found matching your search' }}
            </h3>
            <p class="text-gray-400 text-sm max-w-xs mx-auto">
              {{ activeLocale === 'ar' ? 'جرب تعديل خيارات الفلاتر أو تفريغها بالكامل للبدء مجدداً.' : 'Try adjusting filters or clear them to start over.' }}
            </p>
            <button 
              @click="clearFilters"
              class="bg-wisal-aqua text-wisal-ivory px-6 py-3 rounded-2xl text-xs font-black hover:bg-wisal-aqua/90 transition-all shadow-md shadow-wisal-aqua/10"
            >
              {{ activeLocale === 'ar' ? 'تفريغ الفلاتر' : 'Clear Filters' }}
            </button>
          </div>

          <!-- Active Products -->
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <ProductCard 
              v-for="product in products.data" 
              :key="product.id"
              :product="product"
            />
          </div>

          <!-- Pagination -->
          <div 
            v-if="products.meta.last_page > 1" 
            class="flex flex-wrap justify-center gap-2 pt-8 border-t border-wisal-beige/10 dark:border-gray-800"
          >
            <template v-for="(link, index) in products.meta.links" :key="index">
              <span 
                v-if="link.url === null && link.label === '...'"
                class="px-4 py-2.5 text-sm text-gray-400 select-none"
              >
                ...
              </span>

              <Link
                v-else-if="link.url"
                :href="link.url"
                :class="[
                  'px-4 py-2.5 text-xs font-extrabold rounded-xl transition-all duration-200 border',
                  link.active 
                    ? 'bg-wisal-aqua text-white border-wisal-aqua shadow-sm' 
                    : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/25 dark:border-gray-800 hover:bg-wisal-beige/10 dark:text-wisal-ivory'
                ]"
                v-html="link.label"
              ></Link>
            </template>
          </div>
        </template>
      </div>
    </div>

    <!-- 3. Mobile Filter Drawer -->
    <transition 
      enter-active-class="transition duration-300 ease-out" 
      enter-from-class="opacity-0" 
      enter-to-class="opacity-100" 
      leave-active-class="transition duration-200 ease-in" 
      leave-from-class="opacity-100" 
      leave-to-class="opacity-0"
    >
      <div 
        v-show="isFilterDrawerOpen" 
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 lg:hidden flex justify-end"
      >
        <div 
          class="w-80 h-full bg-white dark:bg-[#323232] p-6 flex flex-col justify-between shadow-premium-lg relative overflow-y-auto animate-slide-left"
          :class="activeLocale === 'ar' ? 'mr-auto ml-0' : 'ml-auto mr-0'"
        >
          <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
              <h2 class="font-black text-lg text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'تصفية المنتجات' : 'Filter Products' }}</h2>
              <button 
                @click="isFilterDrawerOpen = false"
                class="p-2 rounded-xl hover:bg-wisal-beige/10 dark:hover:bg-gray-850"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Categories -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'الأقسام' : 'Categories' }}</h3>
              <div class="flex flex-col gap-1.5">
                <button 
                  @click="selectCategory('')" 
                  :class="categoryId === '' ? 'text-wisal-aqua font-extrabold bg-wisal-aqua/5 dark:bg-gray-800' : 'text-gray-500 hover:text-wisal-aqua'"
                  class="text-right text-sm py-2 px-3.5 rounded-xl transition-all duration-200 w-full"
                >
                  {{ activeLocale === 'ar' ? 'كل الأقسام' : 'All Categories' }}
                </button>
                <button 
                  v-for="cat in categories" 
                  :key="cat.id"
                  @click="selectCategory(cat.id)" 
                  :class="categoryId === String(cat.id) ? 'text-wisal-aqua font-extrabold bg-wisal-aqua/5 dark:bg-gray-800' : 'text-gray-500 hover:text-wisal-aqua'"
                  class="text-right text-sm py-2 px-3.5 rounded-xl transition-all duration-200 w-full"
                >
                  {{ cat.name }}
                </button>
              </div>
            </div>

            <!-- Colors -->
            <div v-if="colors && colors.length > 0" class="space-y-4">
              <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'الألوان' : 'Colors' }}</h3>
              <div class="flex flex-wrap gap-3">
                <button 
                  v-for="color in colors" 
                  :key="color.id"
                  @click="selectColor(color.id)"
                  :class="[
                    'w-8 h-8 rounded-full border shadow-sm relative transition-all duration-300 hover:scale-110 flex items-center justify-center',
                    colorId === String(color.id) ? 'ring-2 ring-wisal-aqua ring-offset-2' : 'border-gray-200'
                  ]"
                  :style="{ backgroundColor: color.hex_code }"
                >
                  <span v-if="colorId === String(color.id)" class="text-white text-xs font-bold mix-blend-difference">✓</span>
                </button>
              </div>
            </div>

            <!-- Sizes -->
            <div v-if="sizes && sizes.length > 0" class="space-y-4">
              <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'المقاسات' : 'Sizes' }}</h3>
              <div class="flex flex-wrap gap-2">
                <button 
                  v-for="size in sizes" 
                  :key="size.id"
                  @click="selectSize(size.id)"
                  :class="[
                    'min-w-10 h-10 px-3 rounded-xl border text-xs font-black transition-all duration-300',
                    sizeId === String(size.id) ? 'bg-wisal-aqua text-white border-wisal-aqua' : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/25 dark:border-gray-800 text-wisal-charcoal dark:text-wisal-ivory'
                  ]"
                >
                  {{ size.name }}
                </button>
              </div>
            </div>

            <!-- Price -->
            <div class="space-y-4">
              <h3 class="font-extrabold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'نطاق السعر (ر.س)' : 'Price Range (SAR)' }}</h3>
              <div class="flex items-center gap-2">
                <input 
                  v-model="priceMin"
                  type="number" 
                  :placeholder="activeLocale === 'ar' ? 'من' : 'Min'"
                  class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-xl py-2 px-3 text-xs dark:text-wisal-ivory"
                />
                <span class="text-gray-400 text-xs">-</span>
                <input 
                  v-model="priceMax"
                  type="number" 
                  :placeholder="activeLocale === 'ar' ? 'إلى' : 'Max'"
                  class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-xl py-2 px-3 text-xs dark:text-wisal-ivory"
                />
              </div>
            </div>
          </div>

          <!-- Bottom Actions -->
          <div class="pt-6 border-t border-wisal-beige/10 dark:border-gray-800 flex gap-4">
            <button 
              @click="clearFilters(); isFilterDrawerOpen = false" 
              class="w-1/2 border border-wisal-beige dark:border-gray-700 py-3 rounded-2xl text-xs font-black hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              {{ activeLocale === 'ar' ? 'تفريغ' : 'Clear' }}
            </button>
            <button 
              @click="applyFilters(); isFilterDrawerOpen = false" 
              class="w-1/2 bg-wisal-aqua text-wisal-ivory py-3 rounded-2xl text-xs font-black hover:bg-wisal-aqua/90 transition-colors shadow-md shadow-wisal-aqua/10"
            >
              {{ activeLocale === 'ar' ? 'تطبيق الفلاتر' : 'Apply' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
