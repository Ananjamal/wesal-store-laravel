<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import ProductCard from '@/Components/ProductCard.vue'
import { useCartStore } from '@/stores/cart'

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
  colors?: Array<{
    id: number
    name: string
    hex_code: string
  }>
  sizes?: Array<{
    id: number
    name: string
    label?: string
  }>
  articles?: Array<{
    id: number
    title: string
    slug: string
    summary: string
    image: string
  }>
}

const props = defineProps<{
  product: Product
  similarProducts: Product[]
}>()

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'SAR', symbol: 'ر.س' })

const cartStore = useCartStore()

// Active states
const selectedImageIndex = ref(0)
const selectedColor = ref<number | null>(null)
const selectedSize = ref<number | null>(null)
const quantity = ref(1)

const displayPrice = computed(() => {
  const converted = props.product.price * currentCurrency.value.exchange_rate
  return converted.toFixed(2)
})

const displayComparePrice = computed(() => {
  if (!props.product.compare_at_price) return null
  const converted = props.product.compare_at_price * currentCurrency.value.exchange_rate
  return converted.toFixed(2)
})

const discountPercentage = computed(() => {
  if (!props.product.compare_at_price || props.product.compare_at_price <= props.product.price) return 0
  return Math.round(((props.product.compare_at_price - props.product.price) / props.product.compare_at_price) * 100)
})

const activeImage = computed(() => {
  if (props.product.images && props.product.images.length > 0) {
    return props.product.images[selectedImageIndex.value].original
  }
  return 'https://picsum.photos/seed/product-detail-' + props.product.id + '/800/800'
})

const isOutOfStock = computed(() => props.product.stock_quantity <= 0)

function incrementQuantity() {
  if (quantity.value < props.product.stock_quantity) {
    quantity.value++
  }
}

function decrementQuantity() {
  if (quantity.value > 1) {
    quantity.value--
  }
}

function addToCart() {
  cartStore.addItem(
    props.product,
    quantity.value,
    selectedColor.value,
    selectedSize.value
  )
}
</script>

<template>
  <Head>
    <title>{{ product.name }} - متجر وِصال</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 space-y-20">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs md:text-sm text-gray-400 gap-2 items-center font-semibold">
      <Link href="/" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'الرئيسية' : 'Home' }}</Link>
      <span>/</span>
      <Link href="/products" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'المتجر' : 'Shop' }}</Link>
      <span v-if="product.category">/</span>
      <Link 
        v-if="product.category" 
        :href="`/products?category_id=${product.category.id}`" 
        class="hover:text-wisal-aqua transition-colors"
      >
        {{ product.category.name }}
      </Link>
      <span>/</span>
      <span class="text-wisal-charcoal dark:text-wisal-ivory font-black truncate max-w-[200px]">{{ product.name }}</span>
    </nav>

    <!-- Product Intro Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
      <!-- 1. Gallery Section (Left) -->
      <div class="space-y-6">
        <!-- Main Image with hover magnifier zoom -->
        <div class="relative aspect-square w-full overflow-hidden bg-white dark:bg-wisal-charcoal/30 rounded-[32px] border border-wisal-beige/10 dark:border-gray-800/80 shadow-premium-sm flex items-center justify-center group/zoom">
          <img 
            :src="activeImage" 
            :alt="product.name" 
            class="object-contain w-full h-full max-h-[500px] p-6 hover:scale-108 transition-all duration-500 cursor-zoom-in"
          />

          <!-- Promotional Badging -->
          <div 
            v-if="product.message" 
            class="absolute top-6 right-6 bg-wisal-aqua text-wisal-ivory text-xs px-4 py-2 rounded-xl font-bold shadow-lg"
          >
            {{ product.message }}
          </div>
        </div>

        <!-- Thumbnails gallery -->
        <div 
          v-if="product.images && product.images.length > 1" 
          class="flex gap-4 overflow-x-auto py-2 custom-scrollbar"
        >
          <button 
            v-for="(img, idx) in product.images" 
            :key="idx"
            @click="selectedImageIndex = idx"
            :class="[
              'w-24 h-24 rounded-2xl border-2 overflow-hidden bg-white dark:bg-wisal-charcoal flex-shrink-0 p-1 transition-all',
              idx === selectedImageIndex ? 'border-wisal-aqua scale-102 shadow-premium-sm' : 'border-wisal-beige/25 hover:border-wisal-aqua/50'
            ]"
          >
            <img :src="img.thumb" :alt="product.name" class="object-cover w-full h-full rounded-xl" />
          </button>
        </div>
      </div>

      <!-- 2. Product Information Details (Right) -->
      <div class="space-y-8">
        <!-- Titles & Ratings -->
        <div class="space-y-3">
          <span 
            v-if="product.category" 
            class="inline-block text-xs bg-wisal-aqua/10 text-wisal-aqua px-3.5 py-1.5 rounded-full font-black uppercase tracking-wider"
          >
            {{ product.category.name }}
          </span>
          <h1 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory leading-tight">
            {{ product.name }}
          </h1>
        </div>

        <!-- Pricing Panel Card -->
        <div class="flex items-center gap-6 bg-wisal-beige/10 dark:bg-gray-800/10 p-6 rounded-[24px] w-fit border border-wisal-beige/10 dark:border-gray-800">
          <div class="flex items-baseline gap-1">
            <span class="text-3xl font-black text-wisal-aqua">{{ displayPrice }}</span>
            <span class="text-sm font-bold text-gray-500 ml-0.5">{{ currentCurrency.symbol }}</span>
          </div>

          <div v-if="product.compare_at_price && product.compare_at_price > product.price" class="flex items-center gap-3">
            <span class="text-sm text-gray-400 line-through font-bold">
              {{ displayComparePrice }} {{ currentCurrency.symbol }}
            </span>
            <span class="bg-red-500 text-white text-[10px] px-3 py-1 rounded-full font-bold shadow-md uppercase tracking-wider animate-pulse">
              {{ activeLocale === 'ar' ? `وفّر ${discountPercentage}%` : `Save ${discountPercentage}%` }}
            </span>
          </div>
        </div>

        <!-- Short Description text -->
        <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base leading-relaxed">
          {{ product.short_description || (activeLocale === 'ar' ? 'هدية راقية منسقة يدوياً من تشكيلة وصال الحصرية، تلائم مناسباتكم السعيدة وتصنع ذكريات تدوم.' : 'Elegant manually arranged gift from Wisal exclusive collection, suitable for your happy occasions and creating memories that last.') }}
        </p>

        <!-- Variants Selection options -->
        <div class="space-y-6 pt-6 border-t border-wisal-beige/15 dark:border-gray-800">
          <!-- Colors -->
          <div v-if="product.colors && product.colors.length > 0" class="space-y-3">
            <label class="text-xs font-black text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">
              {{ activeLocale === 'ar' ? 'اللون:' : 'Color:' }}
            </label>
            <div class="flex gap-3.5">
              <button 
                v-for="color in product.colors" 
                :key="color.id"
                @click="selectedColor = color.id"
                :class="[
                  'w-9 h-9 rounded-full border shadow-sm relative transition-all duration-300 hover:scale-110 flex items-center justify-center',
                  selectedColor === color.id ? 'ring-2 ring-wisal-aqua ring-offset-2 dark:ring-offset-[#323232]' : 'border-gray-250 dark:border-gray-800'
                ]"
                :style="{ backgroundColor: color.hex_code }"
                :title="color.name"
              >
                <span v-if="selectedColor === color.id" class="text-white text-xs font-bold mix-blend-difference">✓</span>
              </button>
            </div>
          </div>

          <!-- Sizes -->
          <div v-if="product.sizes && product.sizes.length > 0" class="space-y-3">
            <label class="text-xs font-black text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">
              {{ activeLocale === 'ar' ? 'المقاس:' : 'Size:' }}
            </label>
            <div class="flex gap-2.5">
              <button 
                v-for="size in product.sizes" 
                :key="size.id"
                @click="selectedSize = size.id"
                :class="[
                  'min-w-11 h-11 px-4 rounded-xl border text-xs font-black transition-all duration-300 hover:bg-wisal-beige/15',
                  selectedSize === size.id ? 'bg-wisal-aqua text-white border-wisal-aqua shadow-md shadow-wisal-aqua/15' : 'bg-white dark:bg-wisal-charcoal border-wisal-beige/25 dark:border-gray-800 text-wisal-charcoal dark:text-wisal-ivory'
                ]"
              >
                {{ size.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Add to cart + quantity selections panel -->
        <div class="space-y-5 pt-8 border-t border-wisal-beige/15 dark:border-gray-800">
          <div class="flex items-center gap-4 flex-wrap">
            <!-- Stepper selector -->
            <div class="flex items-center bg-white dark:bg-wisal-charcoal border border-wisal-beige/25 dark:border-gray-800 rounded-2xl overflow-hidden shadow-premium-sm h-14">
              <button 
                @click="decrementQuantity" 
                class="px-5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 h-full font-black transition-colors"
                :disabled="isOutOfStock || quantity <= 1"
              >
                -
              </button>
              <span class="px-4 font-black text-sm select-none dark:text-wisal-ivory min-w-[50px] text-center">{{ quantity }}</span>
              <button 
                @click="incrementQuantity" 
                class="px-5 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 h-full font-black transition-colors"
                :disabled="isOutOfStock || quantity >= product.stock_quantity"
              >
                +
              </button>
            </div>

            <!-- Add to Cart button -->
            <button 
              @click="addToCart"
              :disabled="isOutOfStock"
              class="flex-grow h-14 bg-wisal-aqua text-wisal-ivory font-black rounded-2xl hover:bg-wisal-aqua/90 disabled:bg-gray-100 disabled:text-gray-400 dark:disabled:bg-gray-800 dark:disabled:text-gray-600 disabled:cursor-not-allowed transition-all shadow-md shadow-wisal-aqua/10 hover:shadow-lg hover:shadow-wisal-aqua/20 flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
              </svg>
              <span>{{ activeLocale === 'ar' ? 'أضف للسلة الآن' : 'Add to Cart' }}</span>
            </button>
          </div>

          <!-- Stock availability indicator -->
          <div class="text-xs">
            <span v-if="isOutOfStock" class="text-red-500 font-bold flex items-center gap-1.5">
              ✕ {{ activeLocale === 'ar' ? 'نفذت الكمية من المخزن' : 'Out of stock in inventory' }}
            </span>
            <span v-else class="text-green-600 dark:text-green-500 font-bold flex items-center gap-1.5">
              ✓ {{ activeLocale === 'ar' ? `متوفر في المخزن (متبقي ${product.stock_quantity})` : `In stock (${product.stock_quantity} left)` }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Long Rich Description content -->
    <div v-if="product.description" class="space-y-6 pt-12 border-t border-wisal-beige/10 dark:border-gray-800">
      <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory">
        {{ activeLocale === 'ar' ? 'تفاصيل ووصف المنتج' : 'Description & Details' }}
      </h2>
      <div 
        class="prose prose-lg prose-wisal dark:prose-invert max-w-none text-gray-500 dark:text-gray-400 leading-relaxed font-semibold"
        v-html="product.description"
      ></div>
    </div>

    <!-- Linked blog articles -->
    <div v-if="product.articles && product.articles.length > 0" class="space-y-6 pt-12 border-t border-wisal-beige/10 dark:border-gray-800">
      <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory">
        {{ activeLocale === 'ar' ? 'عن هذا المنتج في مدونتنا' : 'About this product in our blog' }}
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div 
          v-for="article in product.articles" 
          :key="article.id"
          class="flex bg-white dark:bg-wisal-charcoal/20 border border-wisal-beige/10 dark:border-gray-800/80 rounded-3xl overflow-hidden shadow-premium-sm hover:shadow-premium-lg hover:-translate-y-1 transition-all duration-300"
        >
          <img :src="article.image" :alt="article.title" class="w-32 md:w-44 object-cover" />
          <div class="p-6 flex flex-col justify-between">
            <div class="space-y-2">
              <h3 class="font-extrabold text-sm md:text-base text-wisal-charcoal dark:text-wisal-ivory line-clamp-1 hover:text-wisal-aqua transition-colors duration-200">
                <Link :href="`/blog/${article.slug}`">{{ article.title }}</Link>
              </h3>
              <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed font-semibold">{{ article.summary }}</p>
            </div>
            <Link 
              :href="`/blog/${article.slug}`" 
              class="text-xs font-black text-wisal-aqua hover:underline inline-flex items-center gap-0.5"
            >
              <span>{{ activeLocale === 'ar' ? 'اقرأ المقال' : 'Read Article' }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 rtl:rotate-180">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Similar products list -->
    <div v-if="similarProducts && similarProducts.length > 0" class="space-y-8 pt-12 border-t border-wisal-beige/10 dark:border-gray-800">
      <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory">
        {{ activeLocale === 'ar' ? 'منتجات مشابهة قد تعجبك' : 'Similar products you may like' }}
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <ProductCard 
          v-for="sim in similarProducts" 
          :key="sim.id"
          :product="sim"
        />
      </div>
    </div>
  </div>
</template>
