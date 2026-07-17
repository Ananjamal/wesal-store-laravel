<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

interface Product {
  id: number
  name: string
  slug: string
  price: number
  images: Array<{ thumb: string }>
}

interface Article {
  id: number
  title: string
  slug: string
  summary: string
  content: string
  published_at?: string
  category_name?: string
  author_name: string
  image: string
  products?: Product[]
}

interface Similar {
  id: number
  title: string
  slug: string
  summary: string
  published_at?: string
  image: string
}

const props = defineProps<{
  post: Article
  similarPosts: Similar[]
}>()

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'SAR', symbol: 'ر.س' })
</script>

<template>
  <Head>
    <title>{{ post.title }} - مدونة وِصال</title>
  </Head>

  <div class="space-y-12 pb-20">
    <!-- Header Banner Cover -->
    <div class="relative w-full h-[300px] md:h-[450px] bg-wisal-charcoal overflow-hidden">
      <!-- Background Image -->
      <img 
        :src="post.image" 
        :alt="post.title" 
        class="w-full h-full object-cover opacity-60"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-wisal-charcoal via-wisal-charcoal/60 to-transparent"></div>

      <!-- Info Details overlay -->
      <div class="absolute inset-0 flex items-end">
        <div class="container mx-auto px-4 md:px-8 pb-8 text-white space-y-3">
          <span 
            v-if="post.category_name" 
            class="inline-block bg-wisal-aqua/90 text-wisal-ivory text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow"
          >
            {{ post.category_name }}
          </span>
          <h1 class="text-2xl md:text-5xl font-extrabold leading-tight text-white max-w-4xl drop-shadow-md">
            {{ post.title }}
          </h1>
          <div class="flex items-center gap-4 text-xs md:text-sm text-gray-300 font-sans">
            <span>{{ post.published_at }}</span>
            <span>|</span>
            <span>{{ activeLocale === 'ar' ? 'بواسطة:' : 'By:' }} {{ post.author_name }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Grid: Article content + Sidebar -->
    <div class="container mx-auto px-4 md:px-8 grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
      <!-- 1. Left Side: Article Content Body -->
      <article class="lg:col-span-2 bg-white dark:bg-wisal-charcoal/50 p-6 md:p-10 rounded-3xl border border-wisal-beige/20 dark:border-gray-800 shadow-sm space-y-6">
        <!-- Summary -->
        <p class="text-gray-500 dark:text-gray-300 font-medium text-base md:text-lg border-r-4 border-wisal-aqua pr-4 pl-2 leading-relaxed">
          {{ post.summary }}
        </p>

        <!-- Rich Text content -->
        <div 
          class="prose prose-lg prose-wisal dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed pt-4 border-t border-wisal-beige/10 dark:border-gray-800"
          v-html="post.content"
        ></div>
      </article>

      <!-- 2. Right Side: Sidebar -->
      <aside class="space-y-10">
        <!-- Linked Products (المنتجات المرتبطة بالمقال) -->
        <div v-if="post.products && post.products.length > 0" class="bg-white dark:bg-wisal-charcoal/50 p-6 rounded-2xl border border-wisal-beige/20 dark:border-gray-800 shadow-sm space-y-4">
          <h3 class="font-extrabold text-base text-wisal-charcoal dark:text-wisal-ivory border-b border-wisal-beige/10 dark:border-gray-800 pb-3">
            {{ activeLocale === 'ar' ? 'منتجات مذكورة في المقال' : 'Products in this article' }}
          </h3>
          <div class="space-y-4">
            <div 
              v-for="prod in post.products" 
              :key="prod.id"
              class="flex gap-4 items-center bg-gray-50 dark:bg-gray-800/40 p-3 rounded-xl hover:shadow-md transition-all duration-300"
            >
              <img 
                :src="prod.images && prod.images.length > 0 ? prod.images[0].thumb : 'https://picsum.photos/seed/product-' + prod.id + '/200/200'" 
                :alt="prod.name" 
                class="w-16 h-16 rounded-lg object-cover bg-white" 
              />
              <div class="flex-grow min-w-0">
                <h4 class="font-bold text-sm text-wisal-charcoal dark:text-wisal-ivory truncate hover:text-wisal-aqua">
                  <Link :href="`/products/${prod.slug}`">{{ prod.name }}</Link>
                </h4>
                <div class="flex items-baseline gap-1 mt-1">
                  <span class="text-sm font-extrabold text-wisal-aqua">
                    {{ (prod.price * currentCurrency.exchange_rate).toFixed(2) }}
                  </span>
                  <span class="text-[10px] font-bold text-gray-500">{{ currentCurrency.symbol }}</span>
                </div>
              </div>
              <Link 
                :href="`/products/${prod.slug}`"
                class="p-2 rounded-lg bg-wisal-beige/20 hover:bg-wisal-aqua hover:text-wisal-ivory text-wisal-aqua transition-colors flex-shrink-0"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 rtl:rotate-180">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>

        <!-- Similar Articles (مقالات أخرى) -->
        <div v-if="similarPosts && similarPosts.length > 0" class="bg-white dark:bg-wisal-charcoal/50 p-6 rounded-2xl border border-wisal-beige/20 dark:border-gray-800 shadow-sm space-y-4">
          <h3 class="font-extrabold text-base text-wisal-charcoal dark:text-wisal-ivory border-b border-wisal-beige/10 dark:border-gray-800 pb-3">
            {{ activeLocale === 'ar' ? 'مقالات مشابهة' : 'Similar articles' }}
          </h3>
          <div class="space-y-4">
            <div 
              v-for="item in similarPosts" 
              :key="item.id"
              class="group flex gap-4"
            >
              <img :src="item.image" :alt="item.title" class="w-20 h-16 rounded-xl object-cover" />
              <div class="min-w-0">
                <span class="text-[10px] text-gray-400 font-sans block">{{ item.published_at }}</span>
                <h4 class="font-bold text-xs md:text-sm text-wisal-charcoal dark:text-wisal-ivory line-clamp-2 hover:text-wisal-aqua transition-colors duration-200 mt-0.5">
                  <Link :href="`/blog/${item.slug}`">{{ item.title }}</Link>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</template>
