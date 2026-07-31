<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import confetti from 'canvas-confetti'

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const currentCurrency = computed(() => (page.props.currency as any) || { code: 'ILS', symbol: '₪', exchange_rate: 1 })

const props = defineProps<{
  order: any
}>()

const formatPrice = (cents: number) => {
  return ((cents / 100) * currentCurrency.value.exchange_rate).toFixed(2)
}

onMounted(() => {
  // Fire confetti
  const duration = 3 * 1000
  const end = Date.now() + duration
  const colors = ['#05d4b5', '#1F2937', '#F3F4F6']

  ;(function frame() {
    confetti({
      particleCount: 3,
      angle: 60,
      spread: 55,
      origin: { x: 0 },
      colors: colors
    })
    confetti({
      particleCount: 3,
      angle: 120,
      spread: 55,
      origin: { x: 1 },
      colors: colors
    })

    if (Date.now() < end) {
      requestAnimationFrame(frame)
    }
  }())
})
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'تم الطلب بنجاح - متجر وِصال' : 'Order Successful - Wisal Store' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 lg:py-20 flex flex-col items-center justify-center">
    
    <div class="w-full max-w-3xl bg-white dark:bg-wisal-charcoal/40 rounded-[32px] border border-wisal-beige/20 dark:border-gray-800 shadow-premium overflow-hidden">
      
      <!-- Header -->
      <div class="bg-gradient-to-br from-wisal-aqua to-teal-400 p-10 text-center text-white">
        <div class="w-20 h-20 mx-auto bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-10 h-10">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </div>
        <h1 class="text-3xl font-black mb-2">{{ activeLocale === 'ar' ? 'شكراً لك! تم استلام طلبك.' : 'Thank you! Your order has been received.' }}</h1>
        <p class="text-white/80 font-bold text-sm">
          {{ activeLocale === 'ar' ? 'سيتم تحضير طلبك قريباً. احتفظ برقم الطلب للمتابعة.' : 'Your order will be prepared soon. Keep your order number for tracking.' }}
        </p>
      </div>

      <!-- Order Details -->
      <div class="p-8 md:p-12 space-y-8">
        
        <div class="flex flex-col md:flex-row gap-8 justify-between items-start md:items-center bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-wisal-beige/30 dark:border-gray-800">
          <div>
            <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">{{ activeLocale === 'ar' ? 'رقم الطلب' : 'Order Number' }}</span>
            <span class="text-xl font-black text-wisal-aqua">{{ order.order_number }}</span>
          </div>
          <div>
            <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">{{ activeLocale === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}</span>
            <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory uppercase">
              {{ 
                activeLocale === 'ar' ? (
                  order.payment_method === 'cash_on_delivery' ? 'الدفع عند الاستلام' : 
                  order.payment_method === 'credit_card' ? 'بطاقة ائتمانية' : 
                  order.payment_method === 'mada' ? 'بطاقة مدى' : 
                  order.payment_method
                ) : order.payment_method.replace(/_/g, ' ') 
              }}
            </span>
          </div>
          <div>
            <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">{{ activeLocale === 'ar' ? 'تاريخ الطلب' : 'Date' }}</span>
            <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ new Date(order.created_at).toLocaleDateString() }}</span>
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="text-lg font-black text-wisal-charcoal dark:text-wisal-ivory border-b border-wisal-beige/10 dark:border-gray-800 pb-2">
            {{ activeLocale === 'ar' ? 'تفاصيل الفاتورة' : 'Invoice Details' }}
          </h3>
          
          <div class="space-y-4">
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4">
              <img :src="item.product?.image_url || 'https://picsum.photos/seed/p/50/50'" class="w-12 h-12 rounded-xl object-cover border border-wisal-beige/30" />
              <div class="flex-grow">
                <p class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ item.product?.name || 'منتج محذوف' }}</p>
                <p class="text-xs font-semibold text-gray-400">
                  <span v-if="item.color">{{ item.color.name }} - </span>
                  <span v-if="item.size">{{ item.size.label }}</span>
                </p>
              </div>
              <div class="text-right">
                <span class="block text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(item.price_cents * item.quantity) }} {{ currentCurrency.symbol }}</span>
                <span class="text-[10px] text-gray-400 font-bold">Qty: {{ item.quantity }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t border-wisal-beige/20 dark:border-gray-800 pt-6 space-y-3">
          <div class="flex justify-between text-sm font-bold text-gray-500">
            <span>{{ activeLocale === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
            <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(order.subtotal_cents) }} {{ currentCurrency.symbol }}</span>
          </div>
          <div class="flex justify-between text-sm font-bold text-gray-500">
            <span>{{ activeLocale === 'ar' ? 'تكلفة الشحن' : 'Shipping' }}</span>
            <span class="text-wisal-charcoal dark:text-wisal-ivory">{{ formatPrice(order.shipping_cents) }} {{ currentCurrency.symbol }}</span>
          </div>
          <div v-if="order.discount_cents > 0" class="flex justify-between text-sm font-bold text-red-500">
            <span>{{ activeLocale === 'ar' ? 'الخصم' : 'Discount' }}</span>
            <span>- {{ formatPrice(order.discount_cents) }} {{ currentCurrency.symbol }}</span>
          </div>
          
          <div class="flex justify-between items-end border-t border-wisal-beige/10 dark:border-gray-800 pt-4 mt-4">
            <span class="text-lg font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'الإجمالي الكلي' : 'Total' }}</span>
            <div class="text-right">
              <span class="text-2xl font-black text-wisal-aqua">{{ formatPrice(order.total_cents) }}</span>
              <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory ml-1">{{ currentCurrency.symbol }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex flex-wrap gap-4 justify-center">
      <a :href="`/checkout/invoice/${order.id}`" target="_blank" class="bg-wisal-aqua text-white px-8 py-3.5 rounded-xl font-bold shadow-md hover:-translate-y-1 transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        {{ activeLocale === 'ar' ? 'تحميل الفاتورة PDF' : 'Download Invoice PDF' }}
      </a>
      <a href="/profile#orders" class="bg-white dark:bg-gray-800 text-wisal-charcoal dark:text-wisal-ivory border border-wisal-beige/30 dark:border-gray-700 px-8 py-3.5 rounded-xl font-bold shadow-md hover:-translate-y-1 transition-all">
        {{ activeLocale === 'ar' ? 'متابعة الطلب في حسابي' : 'Track Order in My Account' }}
      </a>
      <a href="/products" class="bg-wisal-charcoal dark:bg-wisal-ivory text-wisal-ivory dark:text-wisal-charcoal px-8 py-3.5 rounded-xl font-bold shadow-md hover:-translate-y-1 transition-all">
        {{ activeLocale === 'ar' ? 'مواصلة التسوق' : 'Continue Shopping' }}
      </a>
    </div>

  </div>
</template>
