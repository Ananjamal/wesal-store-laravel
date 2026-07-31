<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'ILS', symbol: '₪', exchange_rate: 1 })

const cartStore = useCartStore()

const formatPrice = (price: number) => {
  return (price * currentCurrency.value.exchange_rate).toFixed(2)
}

// Order Summary Calculations
const shippingCost = computed(() => cartStore.subtotal > 0 ? 25 : 0) // Example fixed shipping cost
const total = computed(() => cartStore.total + shippingCost.value)

function incrementQuantity(item: any) {
  cartStore.updateQuantity(item.key, item.quantity + 1)
}

function decrementQuantity(item: any) {
  cartStore.updateQuantity(item.key, item.quantity - 1)
}

function removeItem(key: string) {
  cartStore.removeItem(key)
}

// Coupon Logic
import { ref } from 'vue'
import { useToastStore } from '@/stores/toast'
const toastStore = useToastStore()
const couponCode = ref('')
const isApplyingCoupon = ref(false)

async function applyCoupon() {
  if (!couponCode.value.trim()) return
  
  isApplyingCoupon.value = true
  try {
    const res = await fetch('/api/coupon/apply', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ code: couponCode.value, subtotal: cartStore.subtotal })
    })
    const data = await res.json()
    if (!res.ok) throw new Error(data.message || 'Error')
    
    cartStore.setCoupon({
      code: data.coupon.code,
      discount_amount: data.coupon.discount_amount,
      type: data.coupon.type,
      formatted_value: data.coupon.formatted_value,
      formula_text: data.coupon.formula_text
    })
    toastStore.addToast(data.message, 'success')
    couponCode.value = ''
  } catch (err: any) {
    toastStore.addToast(err.message, 'error')
  } finally {
    isApplyingCoupon.value = false
  }
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'سلة المشتريات - متجر وِصال' : 'Cart - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 space-y-12">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs md:text-sm text-gray-400 gap-2 items-center font-semibold">
      <Link href="/" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'الرئيسية' : 'Home' }}</Link>
      <span>/</span>
      <span class="text-wisal-charcoal dark:text-wisal-ivory font-black">{{ activeLocale === 'ar' ? 'سلة المشتريات' : 'Cart' }}</span>
    </nav>

    <!-- Header -->
    <div class="flex items-end justify-between border-b border-wisal-beige/10 dark:border-gray-800 pb-6">
      <div class="space-y-1">
        <h1 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory leading-none">
          {{ activeLocale === 'ar' ? 'سلة المشتريات' : 'Shopping Cart' }}
        </h1>
        <p class="text-gray-400 text-sm font-semibold">
          {{ activeLocale === 'ar' ? `لديك ${cartStore.totalItems} منتجات في السلة` : `You have ${cartStore.totalItems} items in your cart` }}
        </p>
      </div>
      <button 
        v-if="cartStore.items.length > 0"
        @click="cartStore.clearCart()"
        class="text-xs md:text-sm text-red-500 hover:text-red-600 font-bold flex items-center gap-1 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
        <span>{{ activeLocale === 'ar' ? 'إفراغ السلة' : 'Clear Cart' }}</span>
      </button>
    </div>

    <!-- Empty State -->
    <div v-if="cartStore.items.length === 0" class="flex flex-col items-center justify-center py-20 text-center space-y-6">
      <div class="w-40 h-40 bg-wisal-beige/10 dark:bg-gray-800 rounded-full flex items-center justify-center text-wisal-aqua">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
      </div>
      <div class="space-y-2">
        <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'سلتك فارغة حالياً' : 'Your cart is currently empty' }}</h2>
        <p class="text-gray-500 max-w-md mx-auto">{{ activeLocale === 'ar' ? 'يبدو أنك لم تقم بإضافة أي منتجات إلى سلتك بعد. اكتشف مجموعاتنا الرائعة وابدأ التسوق.' : 'Looks like you haven\'t added any items to your cart yet. Discover our wonderful collections and start shopping.' }}</p>
      </div>
      <Link href="/products" class="inline-flex items-center gap-2 bg-wisal-aqua text-wisal-ivory px-8 py-4 rounded-2xl font-bold hover:bg-wisal-aqua/90 transition-all shadow-lg hover:-translate-y-1">
        <span>{{ activeLocale === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" :class="activeLocale === 'ar' ? 'rotate-180' : ''">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
        </svg>
      </Link>
    </div>

    <!-- Cart Layout -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-12">
      
      <!-- Cart Items (Left side / Top on mobile) -->
      <div class="lg:col-span-8 space-y-6">
        <div 
          v-for="item in cartStore.items" 
          :key="item.key"
          class="flex flex-col sm:flex-row items-start sm:items-center gap-6 bg-white dark:bg-wisal-charcoal/40 p-4 rounded-3xl border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm"
        >
          <!-- Product Image -->
          <Link :href="`/products/${item.slug}`" class="block w-full sm:w-32 aspect-square flex-shrink-0 bg-gray-50 dark:bg-gray-900 rounded-2xl overflow-hidden group">
            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
          </Link>

          <!-- Product Details -->
          <div class="flex-grow space-y-2 w-full">
            <div class="flex justify-between items-start gap-4">
              <div>
                <Link :href="`/products/${item.slug}`" class="text-lg font-black text-wisal-charcoal dark:text-wisal-ivory hover:text-wisal-aqua transition-colors">
                  {{ item.name }}
                </Link>
                <!-- Variants -->
                <div v-if="item.colorName || item.sizeLabel" class="flex flex-wrap gap-2 mt-2 text-xs font-semibold text-gray-500">
                  <span v-if="item.colorName" class="bg-wisal-beige/20 dark:bg-gray-800 px-2 py-1 rounded-md">{{ activeLocale === 'ar' ? 'اللون:' : 'Color:' }} {{ item.colorName }}</span>
                  <span v-if="item.sizeLabel" class="bg-wisal-beige/20 dark:bg-gray-800 px-2 py-1 rounded-md">{{ activeLocale === 'ar' ? 'المقاس:' : 'Size:' }} {{ item.sizeLabel }}</span>
                </div>
              </div>
              
              <!-- Delete Button (Mobile hidden, shown on Desktop right side) -->
              <button @click="removeItem(item.key)" class="text-gray-400 hover:text-red-500 transition-colors hidden sm:block p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Price and Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-wisal-beige/10 dark:border-gray-800/50">
              <!-- Price -->
              <div class="font-bold text-wisal-charcoal dark:text-wisal-ivory">
                <span class="text-lg">{{ formatPrice(item.price) }}</span>
                <span class="text-xs text-gray-500 ml-1">{{ currentCurrency.symbol }}</span>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center gap-1 bg-gray-50 dark:bg-gray-900 rounded-full p-1 border border-wisal-beige/20 dark:border-gray-800">
                <button 
                  @click="decrementQuantity(item)"
                  class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-wisal-charcoal text-gray-600 dark:text-gray-300 hover:text-wisal-aqua shadow-sm transition-colors"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                  </svg>
                </button>
                <span class="w-10 text-center text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory select-none">
                  {{ item.quantity }}
                </span>
                <button 
                  @click="incrementQuantity(item)"
                  :disabled="item.quantity >= item.stock_quantity"
                  class="w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-wisal-charcoal text-gray-600 dark:text-gray-300 hover:text-wisal-aqua shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                </button>
              </div>

              <!-- Item Subtotal & Mobile Delete -->
              <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                <div class="text-right flex flex-col">
                  <span class="text-[10px] text-gray-400 font-semibold uppercase">{{ activeLocale === 'ar' ? 'المجموع' : 'Subtotal' }}</span>
                  <span class="font-black text-wisal-aqua text-lg">
                    {{ formatPrice(item.price * item.quantity) }}
                    <span class="text-xs font-bold">{{ currentCurrency.symbol }}</span>
                  </span>
                </div>
                
                <button @click="removeItem(item.key)" class="text-gray-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 p-2.5 rounded-xl sm:hidden">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary (Right side) -->
      <div class="lg:col-span-4 relative">
        <div class="bg-wisal-beige/5 dark:bg-wisal-charcoal/50 border border-wisal-beige/20 dark:border-gray-800 rounded-[32px] p-8 space-y-8 sticky top-32 shadow-premium-sm">
          <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory border-b border-wisal-beige/20 dark:border-gray-800 pb-4">
            {{ activeLocale === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
          </h2>

          <div class="space-y-4 text-sm font-semibold">
            <!-- Subtotal -->
            <div class="flex justify-between items-center text-gray-500">
              <span>{{ activeLocale === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
              <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(cartStore.subtotal) }} {{ currentCurrency.symbol }}</span>
            </div>

            <!-- Shipping -->
            <div class="flex justify-between items-center text-gray-500">
              <span>{{ activeLocale === 'ar' ? 'تكلفة الشحن' : 'Shipping' }}</span>
              <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(shippingCost) }} {{ currentCurrency.symbol }}</span>
            </div>

            <!-- Discount Card -->
            <div v-if="cartStore.coupon" class="bg-wisal-beige/20 dark:bg-gray-800/50 border border-wisal-beige/40 dark:border-gray-700 rounded-2xl p-3.5 space-y-2.5 shadow-sm">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="bg-wisal-charcoal text-wisal-ivory dark:bg-wisal-ivory dark:text-wisal-charcoal text-xs font-black px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-xs">
                    {{ cartStore.coupon.code }}
                  </span>
                  <span v-if="cartStore.coupon.formatted_value" class="text-[11px] font-extrabold text-wisal-aqua bg-wisal-aqua/10 dark:bg-wisal-aqua/20 px-2 py-0.5 rounded-md">
                    {{ cartStore.coupon.formatted_value }}
                  </span>
                </div>
                
                <button @click="cartStore.removeCoupon()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/30" title="إزالة الكوبون">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-wisal-beige/20 dark:border-gray-700/50 text-xs font-bold">
                <span class="text-gray-500">{{ activeLocale === 'ar' ? 'خصم الكوبون' : 'Discount Applied' }}</span>
                <span class="text-red-500 font-black text-sm dir-ltr">
                  - {{ formatPrice(cartStore.discountAmount) }} {{ currentCurrency.symbol }}
                </span>
              </div>
            </div>
            
            <!-- Coupon Input -->
            <div v-else class="pt-2">
              <label class="block text-xs font-bold text-gray-500 mb-2">{{ activeLocale === 'ar' ? 'هل لديك كود خصم؟' : 'Have a promo code?' }}</label>
              <div class="flex gap-2">
                <input 
                  type="text" 
                  v-model="couponCode"
                  @keydown.enter="applyCoupon"
                  :placeholder="activeLocale === 'ar' ? 'أدخل الكود هنا' : 'Enter code here'"
                  class="flex-grow bg-white dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-wisal-aqua transition-colors font-bold uppercase"
                />
                <button 
                  @click="applyCoupon"
                  :disabled="isApplyingCoupon || !couponCode.trim()"
                  class="bg-wisal-charcoal text-wisal-ivory dark:bg-gray-700 hover:bg-wisal-aqua px-4 py-2 rounded-xl text-sm font-bold transition-all disabled:opacity-50"
                >
                  {{ isApplyingCoupon ? '...' : (activeLocale === 'ar' ? 'تطبيق' : 'Apply') }}
                </button>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t border-wisal-beige/20 dark:border-gray-800 pt-6">
            <div class="flex justify-between items-end">
              <div>
                <span class="block text-gray-500 text-sm font-bold mb-1">{{ activeLocale === 'ar' ? 'الإجمالي الكلي' : 'Total' }}</span>
                <span class="text-xs text-gray-400">{{ activeLocale === 'ar' ? 'شامل ضريبة القيمة المضافة' : 'Including VAT' }}</span>
              </div>
              <div class="text-right">
                <span class="text-3xl font-black text-wisal-aqua">{{ formatPrice(total) }}</span>
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory ml-1">{{ currentCurrency.symbol }}</span>
              </div>
            </div>
          </div>

          <!-- Checkout Button -->
          <Link href="/checkout" class="w-full bg-wisal-charcoal dark:bg-wisal-ivory text-wisal-ivory dark:text-wisal-charcoal hover:bg-wisal-aqua dark:hover:bg-wisal-aqua hover:text-white py-4 rounded-2xl font-black text-lg transition-all shadow-lg hover:-translate-y-1 mt-6 flex items-center justify-center gap-2">
            <span>{{ activeLocale === 'ar' ? 'إتمام الطلب' : 'Proceed to Checkout' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5" :class="activeLocale === 'ar' ? 'rotate-180' : ''">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
          
          <div class="text-center pt-2">
            <Link href="/products" class="text-xs font-bold text-gray-400 hover:text-wisal-aqua transition-colors inline-flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3" :class="activeLocale === 'ar' ? 'rotate-180' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
              </svg>
              {{ activeLocale === 'ar' ? 'أو متابعة التسوق' : 'or Continue Shopping' }}
            </Link>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>
