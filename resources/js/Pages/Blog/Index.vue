<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

interface Article {
  id: number
  title: string
  slug: string
  summary: string
  published_at?: string
  category_name?: string
  image: string
}

interface Category {
  id: number
  name: string
  slug: string
}

const props = defineProps<{
  posts: {
    data: Article[]
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
  categories: Category[]
  filters: {
    category_id?: string
    search?: string
  }
}>()

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

const search = ref(props.filters.search || '')
const categoryId = ref(props.filters.category_id || '')
const isPageLoading = ref(false)

function applyFilters() {
  isPageLoading.value = true
  router.get('/blog', {
    search: search.value,
    category_id: categoryId.value,
  }, {
    preserveState: true,
    replace: true,
    onFinish: () => {
      isPageLoading.value = false
    }
  })
}

function selectCategory(id: number | '') {
  categoryId.value = id ? String(id) : ''
  applyFilters()
}

function clearFilters() {
  search.value = ''
  categoryId.value = ''
  applyFilters()
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'المدونة - متجر وِصال' : 'Blog - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-10 space-y-12">
    <!-- Header Page Info -->
    <div class="text-center max-w-xl mx-auto space-y-4">
      <h1 class="text-3xl md:text-5xl font-extrabold text-wisal-charcoal dark:text-wisal-ivory">
        {{ activeLocale === 'ar' ? 'مدونة وِصال' : 'Wisal Blog' }}
      </h1>
      <p class="text-gray-500 text-sm md:text-base leading-relaxed">
        {{ activeLocale === 'ar' ? 'اكتشف أسرار اختيار الهدايا، مقالات ملهمة لتنسيق اللحظات المميزة وتدوين الذكريات.' : 'Discover the secrets of gifting, inspiring stories to arrange special moments and write down memories.' }}
      </p>
    </div>

    <!-- Search & Categories Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-wisal-beige/20 dark:border-gray-800 pb-8">
      <!-- Categories links selector -->
      <div class="flex flex-wrap gap-2 justify-center">
        <button 
          @click="selectCategory('')"
          :class="[
            'px-4 py-2 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 border',
            categoryId === '' 
              ? 'bg-wisal-aqua text-white border-wisal-aqua shadow-sm' 
              : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/20 dark:border-gray-800 text-gray-500 hover:bg-wisal-beige/10 dark:text-wisal-ivory'
          ]"
        >
          {{ activeLocale === 'ar' ? 'كل المقالات' : 'All Articles' }}
        </button>
        <button 
          v-for="cat in categories" 
          :key="cat.id"
          @click="selectCategory(cat.id)"
          :class="[
            'px-4 py-2 text-xs md:text-sm font-bold rounded-xl transition-all duration-300 border',
            categoryId === String(cat.id)
              ? 'bg-wisal-aqua text-white border-wisal-aqua shadow-sm' 
              : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/20 dark:border-gray-800 text-gray-500 hover:bg-wisal-beige/10 dark:text-wisal-ivory'
          ]"
        >
          {{ cat.name }}
        </button>
      </div>

      <!-- Search bar -->
      <div class="relative w-full md:w-80">
        <input 
          v-model="search"
          @keyup.enter="applyFilters"
          type="text" 
          :placeholder="activeLocale === 'ar' ? 'ابحث في المدونة...' : 'Search articles...'"
          class="w-full bg-white dark:bg-wisal-charcoal border border-wisal-beige/30 dark:border-gray-800 rounded-xl py-2.5 px-4 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory"
        />
        <button 
          @click="applyFilters"
          class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-wisal-aqua"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Articles Layout grid list -->
    <div class="space-y-12">
      <!-- Empty State -->
      <div 
        v-if="posts.data.length === 0" 
        class="text-center py-20 bg-white dark:bg-wisal-charcoal/30 rounded-3xl border border-dashed border-wisal-beige/30 dark:border-gray-800 max-w-xl mx-auto space-y-4"
      >
        <div class="text-4xl text-gray-300">✍️</div>
        <h3 class="font-bold text-lg text-wisal-charcoal dark:text-wisal-ivory">
          {{ activeLocale === 'ar' ? 'لا توجد مقالات تطابق بحثك!' : 'No articles match your search' }}
        </h3>
        <button 
          @click="clearFilters"
          class="bg-wisal-aqua text-wisal-ivory px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-wisal-aqua/90 transition-colors shadow-md"
        >
          {{ activeLocale === 'ar' ? 'عرض كل المقالات' : 'View All Articles' }}
        </button>
      </div>

      <!-- Articles Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
        <div 
          v-for="post in posts.data" 
          :key="post.id"
          class="group bg-white dark:bg-wisal-charcoal/50 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full"
        >
          <!-- Image Cover -->
          <div class="relative aspect-[16/10] overflow-hidden bg-gray-100 dark:bg-gray-800">
            <Link :href="`/blog/${post.slug}`" class="block w-full h-full">
              <img 
                :src="post.image || 'https://picsum.photos/seed/post-' + post.id + '/800/600'" 
                :alt="post.title" 
                class="object-cover w-full h-full group-hover:scale-103 transition-transform duration-500 ease-out" 
              />
            </Link>
            <div 
              v-if="post.category_name"
              class="absolute top-3 right-3 bg-wisal-beige/90 text-wisal-charcoal text-[10px] px-2.5 py-1 rounded font-bold shadow-sm"
            >
              {{ post.category_name }}
            </div>
          </div>
          
          <!-- Content Details -->
          <div class="p-6 flex-grow flex flex-col justify-between">
            <div>
              <span class="text-[10px] text-gray-400 font-semibold font-sans">{{ post.published_at }}</span>
              <h3 class="font-bold text-base md:text-lg text-wisal-charcoal dark:text-wisal-ivory mt-1 line-clamp-2 hover:text-wisal-aqua transition-colors duration-200">
                <Link :href="`/blog/${post.slug}`">
                  {{ post.title }}
                </Link>
              </h3>
              <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm mt-2 line-clamp-2 leading-relaxed">
                {{ post.summary }}
              </p>
            </div>
            
            <div class="pt-4 mt-4 border-t border-wisal-beige/10 dark:border-gray-800 flex justify-between items-center">
              <Link 
                :href="`/blog/${post.slug}`"
                class="text-xs font-bold text-wisal-aqua hover:underline inline-flex items-center gap-1"
              >
                <span>{{ activeLocale === 'ar' ? 'اقرأ المزيد' : 'Read More' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 rtl:rotate-180">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination links -->
      <div 
        v-if="posts.meta.last_page > 1" 
        class="flex flex-wrap justify-center gap-2 pt-6 border-t border-wisal-beige/10 dark:border-gray-800"
      >
        <template v-for="(link, index) in posts.meta.links" :key="index">
          <span 
            v-if="link.url === null && link.label === '...'"
            class="px-4 py-2 text-sm text-gray-400 select-none"
          >
            ...
          </span>
          <Link
            v-else-if="link.url"
            :href="link.url"
            :class="[
              'px-4 py-2 text-sm font-bold rounded-xl transition-all duration-200 border',
              link.active 
                ? 'bg-wisal-aqua text-white border-wisal-aqua' 
                : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/20 dark:border-gray-800 hover:bg-wisal-beige/10 dark:text-wisal-ivory'
            ]"
            v-html="link.label"
          ></Link>
        </template>
      </div>
    </div>
  </div>
</template>
