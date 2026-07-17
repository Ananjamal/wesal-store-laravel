<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { useWishlistStore } from '@/stores/wishlist'
import ProductCard from '@/Components/ProductCard.vue'

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

const wishlistStore = useWishlistStore()
const wishlistItems = computed(() => wishlistStore.items)
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'المفضلة - متجر وِصال' : 'Wishlist - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 space-y-10">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs md:text-sm text-gray-400 gap-2 items-center font-semibold">
      <Link href="/" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'الرئيسية' : 'Home' }}</Link>
      <span>/</span>
      <span class="text-wisal-charcoal dark:text-wisal-ivory font-black">{{ activeLocale === 'ar' ? 'المفضلة' : 'Wishlist' }}</span>
    </nav>

    <!-- Header Info -->
    <div class="border-b border-wisal-beige/10 dark:border-gray-800 pb-8 space-y-1">
      <h1 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory leading-none">
        {{ activeLocale === 'ar' ? 'المنتجات المفضلة' : 'My Wishlist' }}
      </h1>
      <p class="text-gray-400 text-xs md:text-sm font-semibold">
        {{ activeLocale === 'ar' ? `قائمة بالهدايا التي نالت إعجابك وتود العودة إليها` : `A list of gifts you liked and want to check later` }}
      </p>
    </div>

    <!-- Content -->
    <div>
      <!-- Empty State -->
      <div 
        v-if="wishlistItems.length === 0" 
        class="text-center py-20 bg-white dark:bg-[#323232]/25 rounded-[32px] border border-dashed border-wisal-beige/25 dark:border-gray-800 max-w-xl mx-auto flex flex-col items-center justify-center p-8 space-y-6"
      >
        <!-- Logo -->
        <img 
          src="/images/icon_without_bg.png" 
          alt="وِصال" 
          class="w-16 h-16 object-contain opacity-60 dark:opacity-40 animate-float" 
        />
        
        <div class="space-y-2">
          <h3 class="font-extrabold text-lg text-wisal-charcoal dark:text-wisal-ivory">
            {{ activeLocale === 'ar' ? 'قائمة المفضلة فارغة حالياً' : 'Your wishlist is empty' }}
          </h3>
          <p class="text-gray-400 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">
            {{ activeLocale === 'ar' ? 'تصفح تشكيلة هدايا وصال الفريدة وقم بإضافة ما تحب لمفضلتك!' : 'Browse Wisal unique gifts collection and add what you love to your favorite list!' }}
          </p>
        </div>

        <Link 
          href="/products"
          class="bg-wisal-aqua text-wisal-ivory px-8 py-3.5 rounded-2xl text-xs font-black hover:bg-wisal-aqua/90 transition-all shadow-md shadow-wisal-aqua/10 hover:shadow-lg"
        >
          {{ activeLocale === 'ar' ? 'تصفح الهدايا' : 'Browse Gifts' }}
        </Link>
      </div>

      <!-- Favorites Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <ProductCard 
          v-for="product in wishlistItems" 
          :key="product.id"
          :product="product"
        />
      </div>
    </div>
  </div>
</template>
