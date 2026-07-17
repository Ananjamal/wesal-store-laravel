<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useAuthStore } from '@/stores/auth'
import { usePreferenceStore } from '@/stores/preference'
import { useI18n } from 'vue-i18n'
import { useCartStore } from '@/stores/cart'
import { useWishlistStore } from '@/stores/wishlist'
import SearchPopup from '@/Components/SearchPopup.vue'
import CartDrawer from '@/Components/CartDrawer.vue'
import ToastContainer from '@/Components/ToastContainer.vue'

const auth = useAuthStore()
const preference = usePreferenceStore()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()
const { locale } = useI18n()
const page = usePage()

const isMobileMenuOpen = ref(false)
const activeDropdown = ref<number | null>(null)
const isSearchOpen = ref(false)
const isCartOpen = ref(false)

const isLangDropdownOpen = ref(false)
const isUserDropdownOpen = ref(false)
const isCurrencyDropdownOpen = ref(false)

// Dynamic menus from HandleInertiaRequests middleware
const headerMenu = computed(() => page.props.menus?.header || { items: [] })
const footerMenu = computed(() => page.props.menus?.footer || { items: [] })

const currencies = computed(() => (page.props.currencies as any[]) || [])
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'SAR', symbol: 'ر.س' })
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const categories = computed(() => (page.props.categories as any[]) || [])

// Ticker active banners
const banners = computed(() => (page.props.banners as any[]) || [])
const activeBannerIndex = ref(0)

onMounted(() => {
  preference.updateTheme()
  if (banners.value.length > 1) {
    setInterval(() => {
      activeBannerIndex.value = (activeBannerIndex.value + 1) % banners.value.length
    }, 5000)
  }
})

function switchLocale(newLocale: string) {
  isLangDropdownOpen.value = false
  router.visit(`/lang/${newLocale}`, { method: 'get', preserveState: false })
}

function switchCurrency(code: string) {
  isCurrencyDropdownOpen.value = false
  router.visit(`/currency/${code}`, { method: 'get', preserveState: false })
}

function toggleMobileMenu() {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

function toggleDropdown(itemId: number) {
  if (activeDropdown.value === itemId) {
    activeDropdown.value = null
  } else {
    activeDropdown.value = itemId
  }
}
</script>

<template>
  <div 
    class="min-h-screen flex flex-col bg-[#F5F5F7] text-wisal-charcoal dark:bg-wisal-charcoal dark:text-wisal-ivory font-sans transition-colors duration-300 selection:bg-wisal-aqua/20" 
    :dir="activeLocale === 'ar' ? 'rtl' : 'ltr'"
  >
    <!-- 1. Top Promotions Ticker Banner -->
    <div 
      v-if="banners && banners.length > 0" 
      class="bg-gradient-to-r from-wisal-aqua to-[#2a4e62] text-wisal-ivory py-2.5 px-4 text-center text-xs font-semibold relative overflow-hidden z-50 shadow-sm"
    >
      <div 
        v-for="(banner, index) in banners" 
        :key="banner.id"
        v-show="index === activeBannerIndex"
        class="transition-opacity duration-500 flex justify-center items-center gap-2"
      >
        <span>✨ {{ banner.title }}</span>
        <span class="opacity-75 text-[10px] hidden md:inline">— {{ banner.description }}</span>
        <Link 
          v-if="banner.link" 
          :href="banner.link" 
          class="underline text-[10px] hover:text-wisal-beige transition-colors ml-2"
        >
          {{ activeLocale === 'ar' ? 'اكتشف المزيد' : 'Learn More' }}
        </Link>
      </div>
    </div>

    <!-- 2. Sticky Navbar -->
    <header class="bg-white/95 dark:bg-[#323232]/95 backdrop-blur-md shadow-premium-sm sticky top-0 z-40 border-b border-wisal-beige/10 dark:border-gray-800 transition-all duration-300">
      <div class="container mx-auto px-4 md:px-8 py-4 flex items-center justify-between">
        <!-- Logo -->
        <Link href="/" class="flex items-center gap-2.5 hover:scale-102 transition-transform duration-200">
          <img src="/images/logo.png" alt="وِصال" class="h-[60px] object-contain rounded-xl" />
          <!-- <span class="text-2xl font-black text-wisal-aqua tracking-widest">وِصال</span> -->
        </Link>

        <!-- Navigation Links with Mega Menu (Desktop) -->
        <nav class="hidden lg:flex items-center gap-8">
          <div 
            v-for="item in headerMenu.items" 
            :key="item.id" 
            class="relative group"
          >
            <!-- Mega Menu configuration for "المتجر" or "Shop" -->
            <template v-if="item.url === '/products' || item.title === 'المتجر' || item.title === 'Shop'">
              <Link 
                :href="item.url"
                class="hover:text-wisal-aqua transition-colors py-3 font-bold text-sm uppercase tracking-wider flex items-center gap-1"
              >
                {{ item.title }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 group-hover:rotate-180 transition-transform">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
              </Link>
              
              <!-- Simple Categories Dropdown -->
              <div class="absolute right-0 left-auto mt-0 w-52 bg-white dark:bg-[#323232] border border-wisal-beige/25 dark:border-gray-800 rounded-2xl shadow-premium-lg py-2 opacity-0 translate-y-3 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-300 z-50">
                <Link 
                  v-for="cat in categories" 
                  :key="cat.id" 
                  :href="`/products?category_id=${cat.id}`"
                  class="block px-5 py-2.5 text-xs md:text-sm font-extrabold text-right hover:bg-wisal-beige/10 hover:text-wisal-aqua dark:hover:bg-gray-800 transition-colors"
                >
                  {{ cat.name }}
                </Link>
                <div v-if="categories.length === 0" class="px-5 py-3 text-xs text-gray-400 text-center">
                  {{ activeLocale === 'ar' ? 'لا توجد أقسام حالياً' : 'No categories' }}
                </div>
              </div>
            </template>

            <!-- Standard Dropdown for other nested menus -->
            <template v-else-if="item.children && item.children.length > 0">
              <button 
                @click="toggleDropdown(item.id)"
                class="hover:text-wisal-aqua transition-colors py-3 font-bold text-sm uppercase tracking-wider flex items-center gap-1"
              >
                {{ item.title }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 group-hover:rotate-180 transition-transform">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
              </button>
              
              <div class="absolute right-0 left-auto mt-0 w-48 bg-white dark:bg-[#323232] border border-wisal-beige/20 dark:border-gray-800/80 rounded-2xl shadow-premium-lg py-2 opacity-0 translate-y-3 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-300 z-50">
                <Link 
                  v-for="child in item.children" 
                  :key="child.id" 
                  :href="child.url" 
                  :target="child.target"
                  class="block px-4 py-2.5 text-xs font-semibold hover:bg-wisal-beige/10 hover:text-wisal-aqua dark:hover:bg-gray-800 transition-colors"
                >
                  {{ child.title }}
                </Link>
              </div>
            </template>

            <!-- Standard simple link -->
            <template v-else>
              <Link 
                :href="item.url" 
                :target="item.target"
                class="hover:text-wisal-aqua transition-colors py-3 font-bold text-sm uppercase tracking-wider"
              >
                {{ item.title }}
              </Link>
            </template>
          </div>
        </nav>

        <!-- Actions panel -->
        <div class="flex items-center gap-4">
          <!-- Search trigger -->
          <button 
            @click="isSearchOpen = true"
            class="p-2.5 rounded-full hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors text-wisal-charcoal dark:text-wisal-ivory"
            :title="activeLocale === 'ar' ? 'بحث' : 'Search'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z" />
            </svg>
          </button>

          <!-- Wishlist link badge count -->
          <Link 
            href="/wishlist"
            class="p-2.5 rounded-full hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors text-wisal-charcoal dark:text-wisal-ivory relative"
            :title="activeLocale === 'ar' ? 'المفكرة' : 'Wishlist'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span 
              v-if="wishlistStore.totalItems > 0"
              class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-md"
            >
              {{ wishlistStore.totalItems }}
            </span>
          </Link>

          <!-- Cart trigger drawer button badge count -->
          <button 
            @click="isCartOpen = true"
            class="p-2.5 rounded-full hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors text-wisal-charcoal dark:text-wisal-ivory relative"
            :title="activeLocale === 'ar' ? 'السلة' : 'Cart'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
            </svg>
            <span 
              v-if="cartStore.totalItems > 0"
              class="absolute top-1 right-1 w-4 h-4 bg-wisal-aqua text-wisal-ivory text-[9px] font-black rounded-full flex items-center justify-center shadow-md animate-pulse"
            >
              {{ cartStore.totalItems }}
            </span>
          </button>

          <!-- Dark Mode Toggle -->
          <!-- <button 
            @click="preference.toggleDarkMode" 
            class="p-2.5 rounded-full hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors"
            :title="activeLocale === 'ar' ? 'تغيير المظهر' : 'Toggle Theme'"
          >
            <svg v-if="preference.darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5 text-yellow-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5 text-wisal-charcoal">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
          </button> -->

          <!-- Language Selector Dropdown -->
          <!-- <div class="relative">
            <button 
              @click="isLangDropdownOpen = !isLangDropdownOpen"
              class="flex items-center gap-1.5 bg-wisal-beige/10 dark:bg-gray-800 rounded-2xl px-3.5 py-2 text-xs font-bold text-wisal-charcoal dark:text-wisal-ivory border border-wisal-beige/20 dark:border-gray-800"
            >
              <span>{{ activeLocale === 'ar' ? 'العربية' : 'EN' }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </button>
            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0 translate-y-2"
              enter-to-class="transform scale-100 opacity-100 translate-y-0"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100 translate-y-0"
              leave-to-class="transform scale-95 opacity-0 translate-y-2"
            >
              <div v-show="isLangDropdownOpen" class="absolute left-0 mt-2 w-32 bg-white dark:bg-[#323232] border border-wisal-beige/20 dark:border-gray-800/80 rounded-2xl shadow-premium-lg py-1.5 z-50">
                <button @click="switchLocale('ar')" class="w-full text-right px-4 py-2 text-xs font-bold hover:bg-wisal-beige/10 hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'العربية' : 'Arabic' }}</button>
                <button @click="switchLocale('en')" class="w-full text-right px-4 py-2 text-xs font-bold hover:bg-wisal-beige/10 hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'الإنجليزية' : 'English' }}</button>
              </div>
            </transition>
          </div> -->



          <!-- Auth links -->
          <div class="hidden sm:flex items-center gap-2 relative">
            <template v-if="auth.token || page.props.auth?.user">
              <div class="relative">
                <button 
                  @click="isUserDropdownOpen = !isUserDropdownOpen"
                  class="flex items-center gap-2 bg-wisal-beige/10 dark:bg-gray-800 rounded-full px-4 py-2 text-xs font-black text-wisal-charcoal dark:text-wisal-ivory border border-wisal-beige/20 dark:border-gray-800 hover:bg-wisal-beige/20 dark:hover:bg-gray-700/80 transition-colors"
                >
                  <!-- Avatar Icon/Image -->
                  <div class="w-6 h-6 rounded-full overflow-hidden bg-wisal-aqua/10 border border-wisal-aqua/25 flex items-center justify-center">
                    <img v-if="auth.user?.avatar || page.props.auth?.user?.avatar" :src="auth.user?.avatar || page.props.auth?.user?.avatar" alt="" class="w-full h-full object-cover" />
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-wisal-aqua">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                  </div>
                  <span>{{ auth.user?.name || page.props.auth?.user?.name }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                  </svg>
                </button>
                
                <!-- Premium Dropdown Menu -->
                <transition
                  enter-active-class="transition duration-100 ease-out"
                  enter-from-class="transform scale-95 opacity-0 translate-y-2"
                  enter-to-class="transform scale-100 opacity-100 translate-y-0"
                  leave-active-class="transition duration-75 ease-in"
                  leave-from-class="transform scale-100 opacity-100 translate-y-0"
                  leave-to-class="transform scale-95 opacity-0 translate-y-2"
                >
                  <div 
                    v-show="isUserDropdownOpen" 
                    class="absolute left-0 mt-2 w-48 bg-white dark:bg-[#323232] border border-wisal-beige/25 dark:border-gray-800/80 rounded-2xl shadow-premium-lg py-2 z-50 text-right"
                  >
                    <!-- Header info -->
                    <div class="px-4 py-2 border-b border-wisal-beige/10 dark:border-gray-800 text-[10px] text-gray-400 select-none">
                      {{ auth.user?.email || page.props.auth?.user?.email }}
                    </div>
                    <!-- Profile Link -->
                    <Link 
                      href="/profile" 
                      @click="isUserDropdownOpen = false"
                      class="block w-full text-right px-4 py-2.5 text-xs font-bold hover:bg-wisal-beige/10 hover:text-wisal-aqua dark:hover:bg-gray-800 transition-colors"
                    >
                      {{ activeLocale === 'ar' ? 'الملف الشخصي' : 'My Profile' }}
                    </Link>
                    <!-- Admin Panel Link -->
                    <a 
                      v-if="auth.user?.is_admin || page.props.auth?.user?.is_admin" 
                      href="/admin" 
                      target="_blank"
                      @click="isUserDropdownOpen = false"
                      class="block w-full text-right px-4 py-2.5 text-xs font-bold text-wisal-aqua hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors"
                    >
                      {{ activeLocale === 'ar' ? 'مدير النظام' : 'System Admin' }}
                    </a>
                    <!-- Logout -->
                    <button 
                      @click="auth.logout(); isUserDropdownOpen = false;" 
                      class="w-full text-right px-4 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors border-t border-wisal-beige/10 dark:border-gray-800"
                    >
                      {{ activeLocale === 'ar' ? 'خروج' : 'Logout' }}
                    </button>
                  </div>
                </transition>
              </div>
            </template>
            <template v-else>
              <Link href="/login" class="text-xs font-bold hover:text-wisal-aqua transition-colors px-2">
                {{ activeLocale === 'ar' ? 'دخول' : 'Login' }}
              </Link>
              <Link href="/register" class="bg-wisal-aqua text-wisal-ivory text-xs px-4 py-2.5 rounded-2xl hover:bg-wisal-aqua/90 transition-all font-bold shadow-md shadow-wisal-aqua/10 hover:shadow-lg">
                {{ activeLocale === 'ar' ? 'سجل معنا' : 'Register' }}
              </Link>
            </template>
          </div>

          <!-- Mobile Drawer toggle -->
          <button 
            @click="toggleMobileMenu" 
            class="lg:hidden p-2.5 rounded-xl hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors text-wisal-charcoal dark:text-wisal-ivory"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-6 h-6">
              <path v-if="isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile drawer nav menu -->
      <transition 
        enter-active-class="transition duration-200 ease-out" 
        enter-from-class="transform -translate-y-4 opacity-0" 
        enter-to-class="transform translate-y-0 opacity-100" 
        leave-active-class="transition duration-150 ease-in" 
        leave-from-class="transform translate-y-0 opacity-100" 
        leave-to-class="transform -translate-y-4 opacity-0"
      >
        <div 
          v-show="isMobileMenuOpen" 
          class="lg:hidden bg-white dark:bg-[#323232] border-b border-wisal-beige/10 dark:border-gray-800 px-6 py-6 space-y-4 shadow-inner"
        >
          <div v-for="item in headerMenu.items" :key="item.id" class="space-y-2">
            <template v-if="item.children && item.children.length > 0">
              <button 
                @click="toggleDropdown(item.id)"
                class="font-bold flex items-center justify-between w-full py-2 text-sm text-wisal-charcoal dark:text-wisal-ivory"
              >
                <span>{{ item.title }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': activeDropdown === item.id }">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
              </button>
              
              <div v-show="activeDropdown === item.id" class="pl-4 pr-2 border-r-2 border-wisal-beige/30 dark:border-gray-700 py-1 space-y-2">
                <Link 
                  v-for="child in item.children" 
                  :key="child.id" 
                  :href="child.url" 
                  :target="child.target"
                  class="block py-2 text-xs font-semibold text-gray-500 hover:text-wisal-aqua"
                  @click="toggleMobileMenu"
                >
                  {{ child.title }}
                </Link>
              </div>
            </template>
            <template v-else>
              <Link 
                :href="item.url" 
                :target="item.target"
                class="block font-bold py-2 text-sm text-wisal-charcoal dark:text-wisal-ivory hover:text-wisal-aqua"
                @click="toggleMobileMenu"
              >
                {{ item.title }}
              </Link>
            </template>
          </div>

          <hr class="border-wisal-beige/10 dark:border-gray-800" />

          <!-- Mobile switches & auth -->
          <div class="space-y-3 pt-2">
            <template v-if="auth.token || page.props.auth?.user">
              <div class="text-xs font-bold text-gray-400">
                {{ auth.user?.name || page.props.auth?.user?.name }}
              </div>
              <button 
                @click="auth.logout" 
                class="w-full text-center bg-red-50 text-red-600 dark:bg-red-950/20 dark:text-red-400 py-2.5 rounded-2xl text-xs font-bold"
              >
                {{ activeLocale === 'ar' ? 'خروج' : 'Logout' }}
              </button>
            </template>
            <template v-else>
              <Link 
                href="/login" 
                class="block text-center border border-wisal-beige dark:border-gray-700 py-3 rounded-2xl text-xs font-bold text-wisal-charcoal dark:text-wisal-ivory"
                @click="toggleMobileMenu"
              >
                {{ activeLocale === 'ar' ? 'تسجيل الدخول' : 'Login' }}
              </Link>
              <Link 
                href="/register" 
                class="block text-center bg-wisal-aqua text-wisal-ivory py-3 rounded-2xl text-xs font-bold"
                @click="toggleMobileMenu"
              >
                {{ activeLocale === 'ar' ? 'سجل معنا' : 'Register' }}
              </Link>
            </template>
          </div>
        </div>
      </transition>
    </header>

    <!-- 3. Search suggestion overlay popup -->
    <SearchPopup :isOpen="isSearchOpen" @close="isSearchOpen = false" />

    <!-- 4. Main Page Body Slot -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- 5. Footer -->
    <footer class="bg-wisal-charcoal text-wisal-ivory py-16 mt-20 border-t border-white/5 relative z-10 text-right">
      <div class="container mx-auto px-6 md:px-8 grid grid-cols-2 md:grid-cols-4 gap-y-10 gap-x-8">
        <!-- Logo & Brand Info (Spans 2 columns on mobile) -->
        <div class="col-span-2 md:col-span-1 space-y-4 flex flex-col items-center md:items-start text-center md:text-right border-b border-white/5 md:border-b-0 pb-8 md:pb-0">
          <div class="flex items-center gap-2.5">
            <img src="/images/icon_without_bg.png" alt="وِصال" class="w-10 h-10 object-contain brightness-0 invert" />
            <h3 class="text-2xl font-black text-wisal-beige tracking-widest">وِصال</h3>
          </div>
          <p class="text-gray-400 text-xs md:text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
            {{ activeLocale === 'ar' ? 'بين كل هدية وذكرى — نصنع من تفاصيل هداياكم لحظات لا تُنسى تدوم في الذاكرة.' : 'Between every gift and memory — we craft unforgettable moments that remain in memory.' }}
          </p>
          <!-- Social Icons (Premium touch) -->
          <div class="flex gap-4 pt-2">
            <a href="#" class="w-8 h-8 rounded-full bg-white/5 hover:bg-wisal-aqua flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-white/5 hover:bg-wisal-aqua flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
          </div>
        </div>

        <!-- Links Column 1 -->
        <div class="space-y-4 col-span-1">
          <h4 class="font-extrabold text-sm md:text-base text-wisal-beige">{{ footerMenu.name || (activeLocale === 'ar' ? 'روابط سريعة' : 'Quick Links') }}</h4>
          <ul class="space-y-2.5 text-xs md:text-sm text-gray-400">
            <li v-for="item in footerMenu.items" :key="item.id">
              <Link :href="item.url" :target="item.target" class="hover:text-wisal-aqua transition-colors duration-200">
                {{ item.title }}
              </Link>
            </li>
            <template v-if="!footerMenu.items || footerMenu.items.length === 0">
              <li><Link href="/privacy-policy" class="hover:text-wisal-aqua">{{ activeLocale === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</Link></li>
              <li><Link href="/terms" class="hover:text-wisal-aqua">{{ activeLocale === 'ar' ? 'الشروط والأحكام' : 'Terms & Conditions' }}</Link></li>
            </template>
          </ul>
        </div>

        <!-- Links Column 2 -->
        <div class="space-y-4 col-span-1">
          <h4 class="font-extrabold text-sm md:text-base text-wisal-beige">{{ activeLocale === 'ar' ? 'التسوق' : 'Shop' }}</h4>
          <ul class="space-y-2.5 text-xs md:text-sm text-gray-400">
            <li><Link href="/products" class="hover:text-wisal-aqua">{{ activeLocale === 'ar' ? 'جميع المنتجات' : 'All Products' }}</Link></li>
            <li><Link href="/blog" class="hover:text-wisal-aqua">{{ activeLocale === 'ar' ? 'المدونة' : 'Blog' }}</Link></li>
          </ul>
        </div>

        <!-- Links Column 3 (Spans 2 columns on mobile for nice layout width) -->
        <div class="space-y-4 col-span-2 md:col-span-1">
          <h4 class="font-extrabold text-sm md:text-base text-wisal-beige">{{ activeLocale === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h4>
          <ul class="space-y-3 text-xs md:text-sm text-gray-400">
            <li class="flex items-center gap-2 justify-start md:justify-start">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-wisal-aqua">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
              <span>support@wesal.com</span>
            </li>
            <li class="flex items-center gap-2 justify-start md:justify-start">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-wisal-aqua">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.806-5.122-4.11-6.927-6.927l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
              <span dir="ltr">+966 50 000 0000</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="container mx-auto px-4 mt-16 pt-8 border-t border-white/5 text-center text-xs text-gray-500">
        &copy; {{ new Date().getFullYear() }} متجر وِصال. {{ activeLocale === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
      </div>
    </footer>

    <!-- Global Cart Drawer & Toast Containers -->
    <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    <ToastContainer />
  </div>
</template>
