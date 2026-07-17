<script setup lang="ts">
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'
import { useWishlistStore } from '@/stores/wishlist'

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
}

const props = defineProps<{
  product: Product
}>()

const page = usePage()
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'SAR', symbol: 'ر.س' })
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

const cartStore = useCartStore()
const wishlistStore = useWishlistStore()

const isHovered = ref(false)
const isFavorite = computed(() => wishlistStore.hasItem(props.product.id))

const displayPrice = computed(() => {
  const converted = props.product.price * currentCurrency.value.exchange_rate
  return converted.toFixed(2)
})

const displayComparePrice = computed(() => {
  if (!props.product.compare_at_price) return null
  const converted = props.product.compare_at_price * currentCurrency.value.exchange_rate
  return converted.toFixed(2)
})

const isOutOfStock = computed(() => props.product.stock_quantity <= 0)

const isOnSale = computed(() => {
  return props.product.compare_at_price && props.product.compare_at_price > props.product.price
})

const discountPercentage = computed(() => {
  if (!props.product.compare_at_price || props.product.compare_at_price <= props.product.price) return 0
  return Math.round(((props.product.compare_at_price - props.product.price) / props.product.compare_at_price) * 100)
})

// Multi-image toggle on Hover
const displayedImage = computed(() => {
  if (props.product.images && props.product.images.length > 0) {
    if (isHovered.value && props.product.images.length > 1) {
      return props.product.images[1].medium
    }
    return props.product.images[0].medium
  }
  return 'https://picsum.photos/seed/product-' + props.product.id + '/400/400'
})

function toggleFavorite() {
  wishlistStore.toggleItem(props.product)
}
</script>

<template>
  <div 
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
    class="group relative bg-white dark:bg-wisal-charcoal/30 rounded-3xl border border-wisal-beige/10 dark:border-gray-800/80 shadow-premium-sm hover:shadow-premium-lg hover:-translate-y-1.5 transition-all duration-500 flex flex-col overflow-hidden"
  >
    <!-- Image Display & Floating Elements -->
    <div class="relative aspect-square w-full overflow-hidden bg-gray-50 dark:bg-gray-900/10">
      <Link :href="`/products/${product.slug}`" class="block w-full h-full">
        <img 
          :src="displayedImage" 
          :alt="product.name" 
          class="object-cover w-full h-full transition-transform duration-700 ease-out" 
          :class="isHovered ? 'scale-103' : 'scale-100'"
        />
      </Link>

      <!-- Float Top-Left: Status/Sale badging -->
      <div class="absolute top-4 right-4 flex flex-col gap-2 z-10">
        <span 
          v-if="isOutOfStock" 
          class="bg-red-500 text-white text-[10px] md:text-xs px-3 py-1 rounded-full font-bold shadow-md uppercase tracking-wider"
        >
          {{ activeLocale === 'ar' ? 'نفذت الكمية' : 'Out of Stock' }}
        </span>
        <span 
          v-else-if="isOnSale" 
          class="bg-red-500 text-white text-[10px] md:text-xs px-3 py-1 rounded-full font-bold shadow-md uppercase tracking-wider"
        >
          {{ activeLocale === 'ar' ? `خصم ${discountPercentage}%` : `-${discountPercentage}%` }}
        </span>
      </div>

      <!-- Float Top-Right: Quick Wishlist Actions -->
      <button 
        @click.stop.prevent="toggleFavorite"
        class="absolute top-4 left-4 p-2 rounded-full bg-white/70 dark:bg-wisal-charcoal/60 hover:bg-white dark:hover:bg-wisal-charcoal text-gray-500 dark:text-gray-400 shadow-md backdrop-blur-md transition-all duration-300 hover:scale-105"
        :class="{ 'text-red-500 dark:text-red-500': isFavorite }"
      >
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          :fill="isFavorite ? 'currentColor' : 'none'" 
          viewBox="0 0 24 24" 
          stroke-width="2" 
          stroke="currentColor" 
          class="w-4 h-4"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
        </svg>
      </button>

      <!-- Float bottom promo message -->
      <div 
        v-if="product.message" 
        class="absolute bottom-4 right-4 bg-wisal-aqua/90 text-wisal-ivory text-[9px] md:text-xs px-3 py-1 rounded-xl font-bold shadow-md backdrop-blur-sm"
      >
        {{ product.message }}
      </div>

      <!-- Hover floating quick Add to Cart overlay -->
      <div 
        class="absolute inset-x-4 bottom-4 z-20 flex justify-center translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out"
      >
        <button 
          @click.stop.prevent="cartStore.addItem(product)"
          :disabled="isOutOfStock"
          class="w-full bg-wisal-aqua/90 text-wisal-ivory hover:bg-wisal-aqua py-3 px-4 rounded-2xl text-xs font-bold shadow-premium-lg backdrop-blur-md transition-all flex items-center justify-center gap-2 hover:scale-102"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
          </svg>
          <span>{{ activeLocale === 'ar' ? 'أضف للسلة سريعاً' : 'Quick Add to Cart' }}</span>
        </button>
      </div>
    </div>

    <!-- Product Text Details -->
    <div class="p-6 flex-grow flex flex-col justify-between">
      <div class="space-y-1.5">
        <span 
          v-if="product.category" 
          class="text-[10px] font-black text-wisal-aqua uppercase tracking-widest"
        >
          {{ product.category.name }}
        </span>
        <h3 class="font-extrabold text-base md:text-lg text-wisal-charcoal dark:text-wisal-ivory line-clamp-1 hover:text-wisal-aqua transition-colors duration-200">
          <Link :href="`/products/${product.slug}`">
            {{ product.name }}
          </Link>
        </h3>
        
        <!-- Short variations preview -->
        <div v-if="product.colors && product.colors.length > 0" class="flex gap-1 py-1">
          <span 
            v-for="color in product.colors.slice(0, 5)" 
            :key="color.id" 
            class="w-2.5 h-2.5 rounded-full border border-gray-200 dark:border-gray-700" 
            :style="{ backgroundColor: color.hex_code }"
            :title="color.name"
          ></span>
          <span v-if="product.colors.length > 5" class="text-[9px] text-gray-400 font-bold ml-1">
            +{{ product.colors.length - 5 }}
          </span>
        </div>

        <p class="text-gray-400 dark:text-gray-500 text-xs line-clamp-2 leading-relaxed">
          {{ product.short_description || product.description?.replace(/<[^>]*>/g, '') || (activeLocale === 'ar' ? 'تنسيق فاخر مميز...' : 'Premium manual wrapping...') }}
        </p>
      </div>

      <!-- Pricing section -->
      <div class="flex items-center justify-between mt-6 pt-4 border-t border-wisal-beige/10 dark:border-gray-800">
        <div class="flex flex-col">
          <div class="flex items-baseline gap-1">
            <span class="text-lg font-black text-wisal-aqua">{{ displayPrice }}</span>
            <span class="text-[9px] font-bold text-gray-500">{{ currentCurrency.symbol }}</span>
          </div>
          <span 
            v-if="isOnSale" 
            class="text-xs text-gray-400 line-through font-semibold"
          >
            {{ displayComparePrice }} {{ currentCurrency.symbol }}
          </span>
        </div>

        <!-- Small View detail indicator -->
        <Link 
          :href="`/products/${product.slug}`"
          class="text-xs font-bold text-wisal-aqua hover:underline inline-flex items-center gap-0.5"
        >
          <span>{{ activeLocale === 'ar' ? 'تفاصيل' : 'Details' }}</span>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 rtl:rotate-180">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
          </svg>
        </Link>
      </div>
    </div>
  </div>
</template>
