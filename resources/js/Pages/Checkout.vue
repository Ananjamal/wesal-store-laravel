<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'ILS', symbol: '₪', exchange_rate: 1 })
const user = computed(() => page.props.auth.user)

const cartStore = useCartStore()

onMounted(() => {
  if (cartStore.items.length === 0) {
    router.visit('/cart')
  }
})

const formatPrice = (price: number) => {
  return (price * currentCurrency.value.exchange_rate).toFixed(2)
}

const shippingCost = 25

const form = useForm({
  shipping_name: user.value?.name || '',
  shipping_phone: user.value?.phone || '',
  shipping_alt_phone: '',
  shipping_city: 'Jerusalem',
  shipping_area: '',
  shipping_address: '',
  shipping_landmark: '',
  notes: '',
  payment_method: 'cash_on_delivery',
  coupon_code: cartStore.coupon ? cartStore.coupon.code : '',
})

const submit = () => {
  form.post(route('checkout.store'), {
    onSuccess: () => {
      cartStore.clearCart() // Clear Pinia cart upon successful DB order creation
    }
  })
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'إتمام الطلب - متجر وِصال' : 'Checkout - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 space-y-12">
    <!-- Breadcrumbs -->
    <nav class="flex text-xs md:text-sm text-gray-400 gap-2 items-center font-semibold">
      <Link href="/" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'الرئيسية' : 'Home' }}</Link>
      <span>/</span>
      <Link href="/cart" class="hover:text-wisal-aqua transition-colors">{{ activeLocale === 'ar' ? 'سلة المشتريات' : 'Cart' }}</Link>
      <span>/</span>
      <span class="text-wisal-charcoal dark:text-wisal-ivory font-black">{{ activeLocale === 'ar' ? 'إتمام الطلب' : 'Checkout' }}</span>
    </nav>

    <div class="flex flex-col-reverse lg:grid lg:grid-cols-12 gap-12">
      <!-- Shipping & Payment Form -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Contact & Shipping -->
        <div class="bg-white dark:bg-wisal-charcoal/40 p-8 rounded-[32px] border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm space-y-8">
          <div class="flex items-center gap-4 border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
            <div class="w-10 h-10 rounded-full bg-wisal-aqua text-wisal-ivory flex items-center justify-center font-black">1</div>
            <h2 class="text-xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'معلومات التوصيل الدقيقة' : 'Precise Shipping Information' }}</h2>
          </div>

          <form @submit.prevent="submit" class="space-y-6" id="checkout-form">
            <!-- Names and Phones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-red-500">*</span></label>
                <input v-model="form.shipping_name" type="text" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_name" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_name }}</span>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'رقم الهاتف الأساسي' : 'Primary Phone' }} <span class="text-red-500">*</span></label>
                <input v-model="form.shipping_phone" type="text" placeholder="059xxxxxxx" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_phone" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_phone }}</span>
              </div>
            </div>

            <!-- Alt Phone -->
            <div class="space-y-2">
              <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'رقم هاتف بديل (اختياري، يفضّل كتابته لضمان وصول الطلب)' : 'Alternative Phone (Optional)' }}</label>
              <input v-model="form.shipping_alt_phone" type="text" placeholder="056xxxxxxx" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
              <span v-if="form.errors.shipping_alt_phone" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_alt_phone }}</span>
            </div>
            
            <!-- City and Area -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'المدينة / المحافظة' : 'City / Governorate' }} <span class="text-red-500">*</span></label>
                <div class="relative">
                  <select v-model="form.shipping_city" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors appearance-none">
                    <option value="Jerusalem">{{ activeLocale === 'ar' ? 'القدس' : 'Jerusalem' }}</option>
                    <option value="Ramallah">{{ activeLocale === 'ar' ? 'رام الله والبيرة' : 'Ramallah & Al-Bireh' }}</option>
                    <option value="Nablus">{{ activeLocale === 'ar' ? 'نابلس' : 'Nablus' }}</option>
                    <option value="Hebron">{{ activeLocale === 'ar' ? 'الخليل' : 'Hebron' }}</option>
                    <option value="Jenin">{{ activeLocale === 'ar' ? 'جنين' : 'Jenin' }}</option>
                    <option value="Tulkarm">{{ activeLocale === 'ar' ? 'طولكرم' : 'Tulkarm' }}</option>
                    <option value="Qalqilya">{{ activeLocale === 'ar' ? 'قلقيلية' : 'Qalqilya' }}</option>
                    <option value="Bethlehem">{{ activeLocale === 'ar' ? 'بيت لحم' : 'Bethlehem' }}</option>
                    <option value="Jericho">{{ activeLocale === 'ar' ? 'أريحا' : 'Jericho' }}</option>
                    <option value="Salfit">{{ activeLocale === 'ar' ? 'سلفيت' : 'Salfit' }}</option>
                    <option value="Tubas">{{ activeLocale === 'ar' ? 'طوباس' : 'Tubas' }}</option>
                    <option value="Gaza">{{ activeLocale === 'ar' ? 'غزة' : 'Gaza' }}</option>
                    <option value="North Gaza">{{ activeLocale === 'ar' ? 'شمال غزة' : 'North Gaza' }}</option>
                    <option value="Deir al-Balah">{{ activeLocale === 'ar' ? 'دير البلح' : 'Deir al-Balah' }}</option>
                    <option value="Khan Yunis">{{ activeLocale === 'ar' ? 'خان يونس' : 'Khan Yunis' }}</option>
                    <option value="Rafah">{{ activeLocale === 'ar' ? 'رفح' : 'Rafah' }}</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                  </div>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'المنطقة / البلدة / المخيم' : 'Area / Village / Camp' }} <span class="text-red-500">*</span></label>
                <input v-model="form.shipping_area" type="text" placeholder="مثال: البيرة، حوارة، بيرزيت" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_area" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_area }}</span>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'العنوان التفصيلي الدقيق (الشارع، البناية، الطابق)' : 'Detailed Address (Street, Building, Floor)' }} <span class="text-red-500">*</span></label>
              <textarea v-model="form.shipping_address" required rows="2" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors resize-none"></textarea>
              <span v-if="form.errors.shipping_address" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_address }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'نقطة دالة / أقرب مَعلَم (اختياري)' : 'Nearest Landmark (Optional)' }}</label>
                <input v-model="form.shipping_landmark" type="text" placeholder="مثال: بجانب مسجد كذا، مقابل سوبرماركت..." class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_landmark" class="text-xs text-red-500 font-bold">{{ form.errors.shipping_landmark }}</span>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'ملاحظات للتوصيل (اختياري)' : 'Delivery Notes (Optional)' }}</label>
                <input v-model="form.notes" type="text" placeholder="مثال: التوصيل بعد الساعة 3 عصراً" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.notes" class="text-xs text-red-500 font-bold">{{ form.errors.notes }}</span>
              </div>
            </div>
          </form>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-wisal-charcoal/40 p-8 rounded-[32px] border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm space-y-8">
          <div class="flex items-center gap-4 border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
            <div class="w-10 h-10 rounded-full bg-wisal-aqua text-wisal-ivory flex items-center justify-center font-black">2</div>
            <h2 class="text-xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Mada -->
            <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-3xl cursor-pointer transition-all duration-300"
              :class="form.payment_method === 'mada' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
              <input type="radio" v-model="form.payment_method" value="mada" class="sr-only" />
              <div class="w-12 h-8 rounded bg-gradient-to-r from-blue-500 to-green-400 mb-3 flex items-center justify-center text-white font-black italic text-xs">mada</div>
              <span class="font-bold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'مدى' : 'Mada' }}</span>
              <div v-if="form.payment_method === 'mada'" class="absolute top-3 right-3 text-wisal-aqua">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
              </div>
            </label>

            <!-- Credit Card -->
            <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-3xl cursor-pointer transition-all duration-300"
              :class="form.payment_method === 'credit_card' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
              <input type="radio" v-model="form.payment_method" value="credit_card" class="sr-only" />
              <div class="w-12 h-8 rounded bg-gray-800 mb-3 flex items-center justify-center text-white font-bold text-xs gap-1">
                <div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-yellow-500 -ml-1.5"></div>
              </div>
              <span class="font-bold text-sm text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'بطاقة ائتمانية' : 'Credit Card' }}</span>
              <div v-if="form.payment_method === 'credit_card'" class="absolute top-3 right-3 text-wisal-aqua">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
              </div>
            </label>

            <!-- Cash on Delivery -->
            <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-3xl cursor-pointer transition-all duration-300"
              :class="form.payment_method === 'cash_on_delivery' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
              <input type="radio" v-model="form.payment_method" value="cash_on_delivery" class="sr-only" />
              <div class="w-12 h-8 rounded bg-green-100 dark:bg-green-900/30 mb-3 flex items-center justify-center text-green-600 font-bold text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              </div>
              <span class="font-bold text-sm text-wisal-charcoal dark:text-wisal-ivory text-center">{{ activeLocale === 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery' }}</span>
              <div v-if="form.payment_method === 'cash_on_delivery'" class="absolute top-3 right-3 text-wisal-aqua">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
              </div>
            </label>
          </div>
          <span v-if="form.errors.payment_method" class="text-xs text-red-500 font-bold block">{{ form.errors.payment_method }}</span>
        </div>

      </div>

      <!-- Order Summary Sidebar -->
      <div class="lg:col-span-4 relative">
        <div class="bg-wisal-beige/5 dark:bg-wisal-charcoal/50 border border-wisal-beige/20 dark:border-gray-800 rounded-[32px] p-8 space-y-6 sticky top-32 shadow-premium-sm">
          <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory border-b border-wisal-beige/20 dark:border-gray-800 pb-4">
            {{ activeLocale === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
          </h2>

          <!-- Mini Cart Items List -->
          <div class="max-h-60 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
            <div v-for="item in cartStore.items" :key="item.key" class="flex gap-4">
              <img :src="item.image" :alt="item.name" class="w-16 h-16 rounded-xl object-cover bg-gray-50 dark:bg-gray-900 border border-wisal-beige/10" />
              <div class="flex-grow flex flex-col justify-center">
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory line-clamp-1">{{ item.name }}</span>
                <span class="text-xs text-gray-400 font-semibold mt-0.5">{{ item.quantity }} × {{ formatPrice(item.price) }} {{ currentCurrency.symbol }}</span>
                <span v-if="item.colorName || item.sizeLabel" class="text-[10px] text-wisal-aqua font-bold mt-1">
                  {{ item.colorName }} <span v-if="item.sizeLabel">- {{ item.sizeLabel }}</span>
                </span>
              </div>
            </div>
          </div>

          <div class="space-y-4 text-sm font-semibold border-t border-wisal-beige/20 dark:border-gray-800 pt-6">
            <div class="flex justify-between items-center text-gray-500">
              <span>{{ activeLocale === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
              <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(cartStore.subtotal) }} {{ currentCurrency.symbol }}</span>
            </div>

            <div class="flex justify-between items-center text-gray-500">
              <span>{{ activeLocale === 'ar' ? 'تكلفة الشحن' : 'Shipping' }}</span>
              <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(shippingCost) }} {{ currentCurrency.symbol }}</span>
            </div>

            <div v-if="cartStore.coupon" class="flex justify-between items-center text-red-500">
              <span class="font-bold">{{ activeLocale === 'ar' ? 'الخصم (' + cartStore.coupon.code + ')' : 'Discount (' + cartStore.coupon.code + ')' }}</span>
              <span class="font-bold">- {{ formatPrice(cartStore.discountAmount) }} {{ currentCurrency.symbol }}</span>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t border-wisal-beige/20 dark:border-gray-800 pt-6">
            <div class="flex justify-between items-end">
              <div>
                <span class="block text-gray-500 text-sm font-bold mb-1">{{ activeLocale === 'ar' ? 'الإجمالي الكلي' : 'Total' }}</span>
              </div>
              <div class="text-right">
                <span class="text-3xl font-black text-wisal-aqua">{{ formatPrice(cartStore.total + shippingCost) }}</span>
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory ml-1">{{ currentCurrency.symbol }}</span>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit"
            form="checkout-form"
            :disabled="form.processing"
            class="w-full bg-wisal-charcoal dark:bg-wisal-ivory text-wisal-ivory dark:text-wisal-charcoal hover:bg-wisal-aqua dark:hover:bg-wisal-aqua hover:text-white py-4 rounded-2xl font-black text-lg transition-all shadow-lg hover:-translate-y-1 mt-6 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
          >
            <span v-if="!form.processing">{{ activeLocale === 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}</span>
            <span v-else class="flex items-center gap-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ activeLocale === 'ar' ? 'جاري المعالجة...' : 'Processing...' }}
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #E5E7EB;
  border-radius: 4px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: #374151;
}
</style>
