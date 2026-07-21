<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'

const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'SAR', symbol: 'ر.س' })

const cartStore = useCartStore()
const cartItems = computed(() => cartStore.items)

const formattedSubtotal = computed(() => {
  const converted = cartStore.subtotal * currentCurrency.value.exchange_rate
  return converted.toFixed(2)
})

function closeDrawer() {
  emit('close')
}
</script>

<template>
  <transition 
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div 
      v-show="isOpen" 
      class="fixed inset-0 z-50 flex"
      :class="activeLocale === 'ar' ? 'justify-start' : 'justify-end'"
    >
      <!-- Backdrop overlay -->
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeDrawer"></div>

      <!-- Drawer Panel -->
      <div 
        v-show="isOpen"
        class="relative w-full max-w-md h-full bg-white dark:bg-wisal-charcoal border-wisal-beige/20 dark:border-gray-800 shadow-2xl flex flex-col justify-between z-10 overflow-hidden"
        :class="[
          activeLocale === 'ar' 
            ? 'left-0 border-l animate-slide-right' 
            : 'right-0 border-r animate-slide-left'
        ]"
        :dir="activeLocale === 'ar' ? 'rtl' : 'ltr'"
      >
        <!-- Header -->
        <div class="p-6 border-b border-wisal-beige/10 dark:border-gray-800 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-wisal-aqua">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
            </svg>
            <h2 class="text-lg font-black text-wisal-charcoal dark:text-wisal-ivory">
              {{ activeLocale === 'ar' ? 'سلة المشتريات' : 'Shopping Cart' }}
            </h2>
            <span class="text-xs bg-wisal-aqua/10 text-wisal-aqua px-2.5 py-0.5 rounded-full font-bold">
              {{ cartStore.totalItems }}
            </span>
          </div>

          <button 
            @click="closeDrawer"
            class="p-2 hover:bg-wisal-beige/10 dark:hover:bg-gray-800 rounded-xl transition-colors text-gray-400"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Scrollable items list -->
        <div class="flex-grow overflow-y-auto custom-scrollbar p-6 space-y-4">
          <!-- Empty State -->
          <div 
            v-if="cartItems.length === 0" 
            class="text-center py-24 space-y-4 text-gray-400 dark:text-gray-500"
          >
            <div class="text-5xl">🛒</div>
            <h3 class="font-extrabold text-sm md:text-base text-wisal-charcoal dark:text-wisal-ivory">
              {{ activeLocale === 'ar' ? 'سلتك فارغة حالياً' : 'Your cart is empty' }}
            </h3>
            <p class="text-xs max-w-xs mx-auto">
              {{ activeLocale === 'ar' ? 'أضف بعض الهدايا الفاخرة لتسعد بها أحبابك!' : 'Add some premium gifts to share love with your close ones!' }}
            </p>
            <Link 
              href="/products"
              @click="closeDrawer"
              class="inline-block bg-wisal-aqua text-wisal-ivory px-6 py-2.5 rounded-xl text-xs font-black shadow-md hover:bg-wisal-aqua/90 transition-all mt-2"
            >
              {{ activeLocale === 'ar' ? 'تصفح المتجر' : 'Browse Shop' }}
            </Link>
          </div>

          <!-- Items list -->
          <div v-else class="space-y-4">
            <div 
              v-for="item in cartItems" 
              :key="item.key"
              class="flex gap-4 items-center bg-gray-50/50 dark:bg-gray-800/10 p-3 rounded-2xl border border-wisal-beige/10 dark:border-gray-800/80 shadow-sm"
            >
              <!-- Thumbnail -->
              <img :src="item.image" :alt="item.name" class="w-16 h-16 rounded-xl object-cover bg-white" />

              <!-- Details -->
              <div class="flex-grow min-w-0 space-y-1">
                <h4 class="font-bold text-xs md:text-sm text-wisal-charcoal dark:text-wisal-ivory truncate hover:text-wisal-aqua">
                  <Link :href="`/products/${item.slug}`" @click="closeDrawer">{{ item.name }}</Link>
                </h4>
                
                <!-- Variant details selection info -->
                <div class="flex gap-2 flex-wrap text-[10px] text-gray-400 font-bold">
                  <span v-if="item.colorName" class="bg-wisal-beige/20 dark:bg-gray-800 px-2 py-0.5 rounded">
                    {{ activeLocale === 'ar' ? 'اللون: ' : 'Color: ' }} {{ item.colorName }}
                  </span>
                  <span v-if="item.sizeLabel" class="bg-wisal-beige/20 dark:bg-gray-800 px-2 py-0.5 rounded">
                    {{ activeLocale === 'ar' ? 'المقاس: ' : 'Size: ' }} {{ item.sizeLabel }}
                  </span>
                </div>

                <div class="flex items-center justify-between pt-1">
                  <!-- Pricing -->
                  <div class="flex items-baseline gap-0.5">
                    <span class="text-xs md:text-sm font-extrabold text-wisal-aqua">
                      {{ (item.price * currentCurrency.exchange_rate).toFixed(2) }}
                    </span>
                    <span class="text-[9px] text-gray-500 font-bold ml-0.5">{{ currentCurrency.symbol }}</span>
                  </div>

                  <!-- Stepper quantity control -->
                  <div class="flex items-center border border-wisal-beige/25 dark:border-gray-800 bg-white dark:bg-[#323232] rounded-lg overflow-hidden h-7">
                    <button 
                      @click="cartStore.updateQuantity(item.key, item.quantity - 1)" 
                      class="px-2 text-gray-500 hover:bg-gray-150 h-full font-extrabold"
                    >
                      -
                    </button>
                    <span class="px-2 text-xs font-bold min-w-[20px] text-center dark:text-wisal-ivory">{{ item.quantity }}</span>
                    <button 
                      @click="cartStore.updateQuantity(item.key, item.quantity + 1)" 
                      class="px-2 text-gray-500 hover:bg-gray-150 h-full font-extrabold"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>

              <!-- Remove btn -->
              <button 
                @click="cartStore.removeItem(item.key)"
                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all flex-shrink-0"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Footer containing subtotal & actions -->
        <div v-if="cartItems.length > 0" class="p-6 border-t border-wisal-beige/10 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 space-y-4">
          <div class="flex items-center justify-between text-sm md:text-base font-extrabold text-wisal-charcoal dark:text-wisal-ivory">
            <span>{{ activeLocale === 'ar' ? 'المجموع:' : 'Total:' }}</span>
            <div class="flex items-baseline gap-0.5 text-lg font-black text-wisal-aqua">
              <span>{{ (cartStore.total * currentCurrency.exchange_rate).toFixed(2) }}</span>
              <span class="text-xs font-bold">{{ currentCurrency.symbol }}</span>
            </div>
          </div>
          <div v-if="cartStore.coupon" class="text-xs font-bold text-red-500 text-center">
            {{ activeLocale === 'ar' ? 'تم تطبيق خصم الكوبون!' : 'Coupon applied!' }}
          </div>

          <div class="flex flex-col gap-2">
            <!-- Checkout button -->
            <Link 
              href="/checkout" 
              class="w-full bg-wisal-aqua text-wisal-ivory py-3 rounded-2xl text-xs md:text-sm font-black text-center shadow-md shadow-wisal-aqua/10 hover:bg-wisal-aqua/90 transition-all block"
              @click="closeDrawer"
            >
              {{ activeLocale === 'ar' ? 'إتمام عملية الشراء' : 'Proceed to Checkout' }}
            </Link>

            <Link 
              href="/cart" 
              class="w-full border-2 border-wisal-charcoal text-wisal-charcoal dark:border-wisal-ivory dark:text-wisal-ivory py-3 rounded-2xl text-xs md:text-sm font-black text-center hover:bg-wisal-charcoal hover:text-wisal-ivory dark:hover:bg-wisal-ivory dark:hover:text-wisal-charcoal transition-all block mt-1"
              @click="closeDrawer"
            >
              {{ activeLocale === 'ar' ? 'عرض السلة والتعديل' : 'View Cart' }}
            </Link>
            
            <button 
              @click="closeDrawer"
              class="w-full py-2 text-xs font-bold text-center text-gray-500 hover:text-wisal-charcoal dark:hover:text-wisal-ivory transition-all mt-1"
            >
              {{ activeLocale === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
