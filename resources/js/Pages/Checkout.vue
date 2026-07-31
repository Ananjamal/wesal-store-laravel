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
  payment_method: 'stripe',
  coupon_code: cartStore.coupon ? cartStore.coupon.code : '',
  cart_items: cartStore.items,
  // Credit card details
  card_name: user.value?.name || '',
  card_number: '',
  card_expiry: '',
  card_cvc: '',
  // PayPal details
  paypal_email: user.value?.email || '',
  // PalPay details
  palpay_phone: user.value?.phone || '',
  palpay_provider: 'jawwal_pay',
})

const submit = () => {
  form.post(route('checkout.store'), {
    onSuccess: () => {
      cartStore.clearCart() // Clear Pinia cart upon successful DB order creation
    },
    onError: () => {
      window.scrollTo({ top: 0, behavior: 'smooth' })
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

    <!-- Error Summary Banner -->
    <div v-if="form.hasErrors" class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 p-5 rounded-2xl flex items-center gap-3 text-red-600 dark:text-red-400 text-sm font-bold">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
      <div>
        <span>{{ activeLocale === 'ar' ? 'يرجى مراجعة وتعبئة كافة البيانات المطلوبة قبل تأكيد الطلب.' : 'Please fill all required fields before confirming your order.' }}</span>
      </div>
    </div>

    <div class="flex flex-col-reverse lg:grid lg:grid-cols-12 gap-12">
      <!-- Shipping & Payment Form -->
      <div class="lg:col-span-8">
        
        <form @submit.prevent="submit" id="checkout-form" class="space-y-8">
          
          <!-- Step 1: Contact & Shipping -->
          <div class="bg-white dark:bg-wisal-charcoal/40 p-8 rounded-[32px] border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm space-y-8">
            <div class="flex items-center gap-4 border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
              <div class="w-10 h-10 rounded-full bg-wisal-aqua text-wisal-ivory flex items-center justify-center font-black">1</div>
              <h2 class="text-xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'معلومات التوصيل الدقيقة' : 'Precise Shipping Information' }}</h2>
            </div>

            <!-- Names and Phones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-red-500">*</span></label>
                <input v-model="form.shipping_name" type="text" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_name" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_name }}</span>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'رقم الهاتف الأساسي' : 'Primary Phone' }} <span class="text-red-500">*</span></label>
                <input v-model="form.shipping_phone" type="text" placeholder="059xxxxxxx" required class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_phone" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_phone }}</span>
              </div>
            </div>

            <!-- Alt Phone -->
            <div class="space-y-2">
              <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'رقم هاتف بديل (اختياري، يفضّل كتابته لضمان وصول الطلب)' : 'Alternative Phone (Optional)' }}</label>
              <input v-model="form.shipping_alt_phone" type="text" placeholder="056xxxxxxx" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
              <span v-if="form.errors.shipping_alt_phone" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_alt_phone }}</span>
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
                <span v-if="form.errors.shipping_area" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_area }}</span>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'العنوان التفصيلي الدقيق (الشارع، البناية، الطابق)' : 'Detailed Address (Street, Building, Floor)' }} <span class="text-red-500">*</span></label>
              <textarea v-model="form.shipping_address" required rows="2" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors resize-none"></textarea>
              <span v-if="form.errors.shipping_address" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_address }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'نقطة دالة / أقرب مَعلَم (اختياري)' : 'Nearest Landmark (Optional)' }}</label>
                <input v-model="form.shipping_landmark" type="text" placeholder="مثال: بجانب مسجد كذا، مقابل سوبرماركت..." class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.shipping_landmark" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.shipping_landmark }}</span>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'ملاحظات للتوصيل (اختياري)' : 'Delivery Notes (Optional)' }}</label>
                <input v-model="form.notes" type="text" placeholder="مثال: التوصيل بعد الساعة 3 عصراً" class="w-full bg-gray-50 dark:bg-gray-900 border border-wisal-beige/30 dark:border-gray-700 rounded-2xl px-5 py-4 focus:outline-none focus:border-wisal-aqua font-semibold text-wisal-charcoal dark:text-wisal-ivory transition-colors" />
                <span v-if="form.errors.notes" class="text-xs text-red-500 font-bold block mt-1">{{ form.errors.notes }}</span>
              </div>
            </div>
          </div>

          <!-- Step 2: Payment Methods & Dynamic Payment Details -->
          <div class="bg-white dark:bg-wisal-charcoal/40 p-8 rounded-[32px] border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm space-y-8">
            <div class="flex items-center gap-4 border-b border-wisal-beige/10 dark:border-gray-800 pb-4">
              <div class="w-10 h-10 rounded-full bg-wisal-aqua text-wisal-ivory flex items-center justify-center font-black">2</div>
              <h2 class="text-xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'طريقة الدفع وبيانات العملية' : 'Payment Method & Details' }}</h2>
            </div>

            <!-- Payment Method Radio Buttons Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <!-- Stripe / Credit Card -->
              <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-3xl cursor-pointer transition-all duration-300"
                :class="(form.payment_method === 'stripe' || form.payment_method === 'credit_card' || form.payment_method === 'mada') ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
                <input type="radio" v-model="form.payment_method" value="stripe" class="sr-only" />
                <div class="w-10 h-7 rounded bg-gray-800 mb-2 flex items-center justify-center text-white font-bold text-xs gap-1">
                  <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div><div class="w-2.5 h-2.5 rounded-full bg-yellow-500 -ml-1.5"></div>
                </div>
                <span class="font-bold text-xs text-wisal-charcoal dark:text-wisal-ivory text-center">{{ activeLocale === 'ar' ? 'بطاقات ائتمانية / Stripe' : 'Credit Card / Stripe' }}</span>
                <div v-if="form.payment_method === 'stripe' || form.payment_method === 'credit_card' || form.payment_method === 'mada'" class="absolute top-2 right-2 text-wisal-aqua">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                </div>
              </label>

              <!-- PayPal -->
              <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-3xl cursor-pointer transition-all duration-300"
                :class="form.payment_method === 'paypal' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
                <input type="radio" v-model="form.payment_method" value="paypal" class="sr-only" />
                <div class="w-10 h-7 rounded bg-blue-600 mb-2 flex items-center justify-center text-white font-black italic text-xs">PayPal</div>
                <span class="font-bold text-xs text-wisal-charcoal dark:text-wisal-ivory text-center">PayPal</span>
                <div v-if="form.payment_method === 'paypal'" class="absolute top-2 right-2 text-wisal-aqua">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                </div>
              </label>

              <!-- PalPay (Palestine) -->
              <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-3xl cursor-pointer transition-all duration-300"
                :class="form.payment_method === 'palpay' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
                <input type="radio" v-model="form.payment_method" value="palpay" class="sr-only" />
                <div class="w-10 h-7 rounded bg-emerald-600 mb-2 flex items-center justify-center text-white font-black text-xs">PalPay</div>
                <span class="font-bold text-xs text-wisal-charcoal dark:text-wisal-ivory text-center">{{ activeLocale === 'ar' ? 'بال باي' : 'PalPay' }}</span>
                <div v-if="form.payment_method === 'palpay'" class="absolute top-2 right-2 text-wisal-aqua">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                </div>
              </label>

              <!-- Cash on Delivery -->
              <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-3xl cursor-pointer transition-all duration-300"
                :class="form.payment_method === 'cash_on_delivery' ? 'border-wisal-aqua bg-wisal-aqua/5 shadow-md' : 'border-gray-200 dark:border-gray-700 hover:border-wisal-aqua/50'">
                <input type="radio" v-model="form.payment_method" value="cash_on_delivery" class="sr-only" />
                <div class="w-10 h-7 rounded bg-amber-100 dark:bg-amber-900/30 mb-2 flex items-center justify-center text-amber-700 dark:text-amber-300 font-bold text-xs">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <span class="font-bold text-xs text-wisal-charcoal dark:text-wisal-ivory text-center">{{ activeLocale === 'ar' ? 'الدفع نقداً' : 'Cash' }}</span>
                <div v-if="form.payment_method === 'cash_on_delivery'" class="absolute top-2 right-2 text-wisal-aqua">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                </div>
              </label>
            </div>

            <!-- Dynamic Interactive Payment Details Panels -->
            
            <!-- Panel 1: Stripe / Credit Card Inputs -->
            <div v-if="form.payment_method === 'stripe' || form.payment_method === 'credit_card' || form.payment_method === 'mada'" 
              class="bg-gray-50 dark:bg-gray-900/60 p-6 rounded-3xl border border-wisal-beige/30 dark:border-gray-700 space-y-4"
            >
              <div class="flex items-center justify-between border-b border-wisal-beige/20 dark:border-gray-800 pb-3">
                <span class="text-xs font-black text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-wisal-aqua" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                  {{ activeLocale === 'ar' ? 'إدخال بيانات البطاقة الائتمانية الآمنة' : 'Secure Card Information' }}
                </span>
                <span class="text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-0.5 rounded-md">256-bit SSL</span>
              </div>

              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-bold text-gray-500 mb-1">{{ activeLocale === 'ar' ? 'الاسم المدون على البطاقة' : 'Cardholder Name' }}</label>
                  <input v-model="form.card_name" type="text" placeholder="FULL NAME" class="w-full bg-white dark:bg-gray-800 border border-wisal-beige/30 dark:border-gray-700 rounded-xl px-4 py-3 text-xs font-bold focus:outline-none focus:border-wisal-aqua" />
                </div>
                <div>
                  <label class="block text-xs font-bold text-gray-500 mb-1">{{ activeLocale === 'ar' ? 'رقم البطاقة (16 رقم)' : 'Card Number' }}</label>
                  <div class="relative">
                    <input v-model="form.card_number" type="text" placeholder="4000 1234 5678 9010" class="w-full bg-white dark:bg-gray-800 border border-wisal-beige/30 dark:border-gray-700 rounded-xl px-4 py-3 text-xs font-bold dir-ltr focus:outline-none focus:border-wisal-aqua tracking-widest" />
                    <div class="absolute inset-y-0 left-3 flex items-center gap-1 opacity-60 pointer-events-none">
                      <div class="w-4 h-3 bg-red-500 rounded-xs"></div><div class="w-4 h-3 bg-yellow-500 rounded-xs -ml-2"></div>
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">{{ activeLocale === 'ar' ? 'تاريخ الانتهاء' : 'Expiry' }}</label>
                    <input v-model="form.card_expiry" type="text" placeholder="MM/YY" class="w-full bg-white dark:bg-gray-800 border border-wisal-beige/30 dark:border-gray-700 rounded-xl px-4 py-3 text-xs font-bold text-center focus:outline-none focus:border-wisal-aqua" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">{{ activeLocale === 'ar' ? 'رمز الأمان CVC' : 'CVC Code' }}</label>
                    <input v-model="form.card_cvc" type="password" maxlength="4" placeholder="123" class="w-full bg-white dark:bg-gray-800 border border-wisal-beige/30 dark:border-gray-700 rounded-xl px-4 py-3 text-xs font-bold text-center focus:outline-none focus:border-wisal-aqua" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Panel 2: PayPal Inputs -->
            <div v-else-if="form.payment_method === 'paypal'" 
              class="bg-blue-50/70 dark:bg-blue-950/30 p-6 rounded-3xl border border-blue-200 dark:border-blue-900 space-y-4"
            >
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-black italic flex items-center justify-center text-xs">P</div>
                <h4 class="font-black text-sm text-blue-900 dark:text-blue-200">{{ activeLocale === 'ar' ? 'الدفع السريع عبر حساب PayPal' : 'PayPal Fast Checkout' }}</h4>
              </div>
              
              <div class="space-y-2 pt-2 border-t border-blue-200/50 dark:border-blue-900/50">
                <label class="block text-xs font-bold text-blue-900 dark:text-blue-200">{{ activeLocale === 'ar' ? 'البريد الإلكتروني المسجل في حساب PayPal' : 'PayPal Account Email' }} <span class="text-red-500">*</span></label>
                <input v-model="form.paypal_email" type="email" placeholder="example@paypal.com" required class="w-full bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-800 rounded-xl px-4 py-3 text-xs font-bold focus:outline-none focus:border-blue-600" />
              </div>

              <p class="text-[11px] text-blue-800 dark:text-blue-300 font-semibold">
                {{ activeLocale === 'ar' ? 'عند تأكيد الطلب، سيتم التحقق من البريد وإتمام التفويض المالي فورياً عبر خوادم PayPal الرسمية.' : 'Your order will be linked to your verified PayPal email.' }}
              </p>
            </div>

            <!-- Panel 3: PalPay Inputs -->
            <div v-else-if="form.payment_method === 'palpay'" 
              class="bg-emerald-50/70 dark:bg-emerald-950/30 p-6 rounded-3xl border border-emerald-200 dark:border-emerald-900 space-y-4"
            >
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-black flex items-center justify-center text-xs">PAL</div>
                <h4 class="font-black text-sm text-emerald-900 dark:text-emerald-200">{{ activeLocale === 'ar' ? 'بيانات محفظة PalPay الفلسطينية' : 'PalPay Wallet Information' }}</h4>
              </div>

              <div class="space-y-3 pt-2 border-t border-emerald-200/50 dark:border-emerald-900/50">
                <div>
                  <label class="block text-xs font-bold text-emerald-900 dark:text-emerald-200 mb-1">{{ activeLocale === 'ar' ? 'رقم محفظة PalPay / رقم المحمول المسجل' : 'PalPay Wallet / Mobile Number' }} <span class="text-red-500">*</span></label>
                  <input v-model="form.palpay_phone" type="tel" placeholder="059xxxxxxx أو 056xxxxxxx" required class="w-full bg-white dark:bg-gray-800 border border-emerald-300 dark:border-emerald-800 rounded-xl px-4 py-3 text-xs font-bold focus:outline-none focus:border-emerald-600" />
                </div>
              </div>

              <p class="text-[11px] text-emerald-800 dark:text-emerald-300 font-semibold">
                {{ activeLocale === 'ar' ? 'سيتم إرسال طلب خصم فوري ومباشر إلى محفظتك الإلكترونية للتأكيد بنقرة واحدة.' : 'An instant authorization request will be sent to your mobile wallet app.' }}
              </p>
            </div>

            <!-- Panel 4: Cash on Delivery Instructions -->
            <div v-else-if="form.payment_method === 'cash_on_delivery'" 
              class="bg-amber-50/70 dark:bg-amber-950/30 p-6 rounded-3xl border border-amber-200 dark:border-amber-900 space-y-3"
            >
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-600 text-white font-black flex items-center justify-center text-xs">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <h4 class="font-black text-sm text-amber-900 dark:text-amber-200">{{ activeLocale === 'ar' ? 'الدفع نقداً عند استلام الشحنة' : 'Cash on Delivery (COD)' }}</h4>
              </div>
              <p class="text-xs text-amber-800 dark:text-amber-300 font-semibold leading-relaxed">
                {{ activeLocale === 'ar' ? 'سيتم تسليم الطلب وتحصيل المبلغ الإجمالي نقداً بواسطة سائق الشحنة عند وصوله لعنوانك المعين.' : 'Pay the exact total amount in cash to the courier upon parcel arrival.' }}
              </p>
            </div>

          </div>

        </form>
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
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-wisal-beige/20 dark:border-gray-700/50 text-xs font-bold">
                <span class="text-gray-500">{{ activeLocale === 'ar' ? 'خصم الكوبون' : 'Discount Applied' }}</span>
                <span class="text-red-500 font-black text-sm dir-ltr">
                  - {{ formatPrice(cartStore.discountAmount) }} {{ currentCurrency.symbol }}
                </span>
              </div>
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
            class="w-full bg-wisal-charcoal dark:bg-wisal-ivory text-wisal-ivory dark:text-wisal-charcoal hover:bg-wisal-aqua dark:hover:bg-wisal-aqua hover:text-white py-4 rounded-2xl font-black text-lg transition-all shadow-lg hover:-translate-y-1 mt-6 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 cursor-pointer"
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
