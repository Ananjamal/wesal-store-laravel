<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, usePage, Link } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user || {})
const activeLocale = computed(() => (page.props.locale as string) || 'ar')
const orders = computed(() => (page.props.orders as any[]) || [])

const activeTab = ref(typeof window !== 'undefined' && window.location.hash === '#orders' ? 'orders' : 'info') // info, security, orders

// Listen to hash changes in case they click link from outside
if (typeof window !== 'undefined') {
  window.addEventListener('hashchange', () => {
    if (window.location.hash === '#orders') {
      activeTab.value = 'orders'
    }
  })
}

// Order details modal
const selectedOrder = ref<any>(null)
const showOrderModal = ref(false)

function openOrderModal(order: any) {
  selectedOrder.value = order
  showOrderModal.value = true
  document.body.style.overflow = 'hidden'
}

function closeOrderModal() {
  showOrderModal.value = false
  selectedOrder.value = null
  document.body.style.overflow = ''
}

function getStatusLabel(status: string) {
  if (activeLocale.value === 'ar') {
    switch (status) {
      case 'pending': return 'قيد الانتظار'
      case 'processing': return 'جاري التجهيز'
      case 'shipped': return 'تم الشحن'
      case 'delivered': return 'تم التوصيل'
      case 'completed': return 'مكتمل'
      case 'cancelled': return 'ملغي'
      case 'refunded': return 'مسترجع'
      default: return status
    }
  }
  return status
}

function getStatusClass(status: string) {
  return {
    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': status === 'pending',
    'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': status === 'processing',
    'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': status === 'shipped',
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': status === 'delivered' || status === 'completed',
    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': status === 'cancelled',
    'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400': status === 'refunded',
  }
}

function getPaymentMethodLabel(method: string) {
  if (!method) return ''
  if (activeLocale.value === 'ar') {
    switch (method.toLowerCase()) {
      case 'cash_on_delivery':
      case 'cod':
        return 'الدفع عند الاستلام'
      case 'credit_card':
        return 'بطاقة ائتمانية'
      case 'mada':
        return 'بطاقة مدى'
      case 'paypal':
        return 'بايبال'
      case 'palpay':
        return 'بال باي'
      case 'stripe':
        return 'سترايب'
      default:
        return method.replace(/_/g, ' ')
    }
  }
  return method.replace(/_/g, ' ').toUpperCase()
}

function getPaymentStatusLabel(paymentStatus: string, method?: string) {
  if (paymentStatus === 'paid') {
    return activeLocale.value === 'ar' ? 'تم الدفع ✔' : 'Paid ✔'
  }
  if (activeLocale.value === 'ar') {
    if (method === 'cash_on_delivery' || method === 'cod') {
      return 'بانتظار التحصيل عند الاستلام'
    }
    return 'بانتظار الدفع'
  }
  return 'Awaiting Payment'
}

// Account info form
const infoForm = useForm({
  _method: 'POST',
  name: user.value.name || '',
  email: user.value.email || '',
  phone: user.value.phone || '',
  avatar: null as File | null,
})

// Password form
const passwordForm = useForm({
  _method: 'POST',
  name: user.value.name || '',
  email: user.value.email || '',
  phone: user.value.phone || '',
  password: '',
  password_confirmation: '',
})

const avatarPreview = ref<string | null>(user.value.avatar || null)

function handleAvatarChange(e: Event) {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    infoForm.avatar = file
    avatarPreview.value = URL.createObjectURL(file)
  }
}

function updateInfo() {
  infoForm.post('/profile', {
    preserveScroll: true,
    onSuccess: () => {
      // Success toast is handled globally by Laravel flash session
    }
  })
}

function updatePassword() {
  passwordForm.post('/profile', {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset('password', 'password_confirmation')
    }
  })
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'الملف الشخصي' : 'My Profile' }}</title>
  </Head>

  <div class="container mx-auto px-4 md:px-8 py-12 max-w-4xl space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-wisal-beige/10 pb-6">
      <div>
        <h1 class="text-3xl font-black text-wisal-charcoal dark:text-wisal-ivory">
          {{ activeLocale === 'ar' ? 'الملف الشخصي' : 'Account Profile' }}
        </h1>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
          {{ activeLocale === 'ar' ? 'إدارة بيانات حسابك وتحديث كلمة المرور' : 'Manage your account details and security settings' }}
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <!-- Side Navigation / Tabs -->
      <div class="md:col-span-1 flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-x-visible pb-3 md:pb-0">
        <button
          @click="activeTab = 'info'"
          :class="[
            'px-5 py-3 rounded-2xl text-xs md:text-sm font-extrabold whitespace-nowrap transition-all duration-200 text-right w-full flex items-center gap-2.5',
            activeTab === 'info'
              ? 'bg-wisal-aqua text-wisal-ivory shadow-lg shadow-wisal-aqua/10'
              : 'hover:bg-wisal-beige/10 text-gray-500 dark:text-gray-400'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
          <span>{{ activeLocale === 'ar' ? 'بيانات الحساب' : 'Account Info' }}</span>
        </button>

        <button
          @click="activeTab = 'security'"
          :class="[
            'px-5 py-3 rounded-2xl text-xs md:text-sm font-extrabold whitespace-nowrap transition-all duration-200 text-right w-full flex items-center gap-2.5',
            activeTab === 'security'
              ? 'bg-wisal-aqua text-wisal-ivory shadow-lg shadow-wisal-aqua/10'
              : 'hover:bg-wisal-beige/10 text-gray-500 dark:text-gray-400'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
          <span>{{ activeLocale === 'ar' ? 'الأمان والحماية' : 'Security' }}</span>
        </button>

        <button
          @click="activeTab = 'orders'; window.location.hash = 'orders'"
          :class="[
            'px-5 py-3 rounded-2xl text-xs md:text-sm font-extrabold whitespace-nowrap transition-all duration-200 text-right w-full flex items-center gap-2.5',
            activeTab === 'orders'
              ? 'bg-wisal-aqua text-wisal-ivory shadow-lg shadow-wisal-aqua/10'
              : 'hover:bg-wisal-beige/10 text-gray-500 dark:text-gray-400'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
          </svg>
          <span>{{ activeLocale === 'ar' ? 'سجل الطلبات' : 'Orders' }}</span>
        </button>
      </div>

      <!-- Tab Contents -->
      <div class="md:col-span-3">
        <!-- 1. Account Info Tab -->
        <div v-show="activeTab === 'info'" class="bg-white dark:bg-wisal-charcoal/30 border border-wisal-beige/10 dark:border-gray-800 rounded-3xl p-6 md:p-8 shadow-premium-sm space-y-8">
          <form @submit.prevent="updateInfo" class="space-y-6">
            <!-- Avatar Upload Row -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-wisal-beige/10">
              <div class="relative w-24 h-24 rounded-3xl overflow-hidden bg-wisal-beige/10 border border-wisal-beige/25 flex items-center justify-center shadow-inner group">
                <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar" class="w-full h-full object-cover" />
                <span v-else class="text-4xl text-wisal-aqua font-black">{{ user.name?.charAt(0) }}</span>
              </div>
              <div class="space-y-2 text-center sm:text-right">
                <h3 class="text-sm font-extrabold text-wisal-charcoal dark:text-wisal-ivory">
                  {{ activeLocale === 'ar' ? 'الصورة الشخصية' : 'Profile Picture' }}
                </h3>
                <label class="inline-block cursor-pointer bg-wisal-beige hover:bg-wisal-ivory text-wisal-charcoal text-xs font-black px-4 py-2.5 rounded-xl shadow-sm transition-colors duration-200">
                  <span>{{ activeLocale === 'ar' ? 'تغيير الصورة' : 'Change Image' }}</span>
                  <input type="file" @change="handleAvatarChange" class="hidden" accept="image/*" />
                </label>
              </div>
            </div>

            <!-- Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-xs font-extrabold text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'الاسم بالكامل' : 'Full Name' }}</label>
                <input
                  type="text"
                  v-model="infoForm.name"
                  required
                  class="w-full bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl px-4 py-3.5 text-sm text-wisal-charcoal dark:text-wisal-ivory focus:outline-none focus:border-wisal-aqua transition-colors"
                />
                <span v-if="infoForm.errors.name" class="text-xs text-red-500 font-bold block">{{ infoForm.errors.name }}</span>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-extrabold text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                <input
                  type="email"
                  v-model="infoForm.email"
                  required
                  class="w-full bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl px-4 py-3.5 text-sm text-wisal-charcoal dark:text-wisal-ivory focus:outline-none focus:border-wisal-aqua transition-colors"
                />
                <span v-if="infoForm.errors.email" class="text-xs text-red-500 font-bold block">{{ infoForm.errors.email }}</span>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-extrabold text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'رقم الجوال' : 'Phone Number' }}</label>
                <input
                  type="text"
                  v-model="infoForm.phone"
                  class="w-full bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl px-4 py-3.5 text-sm text-wisal-charcoal dark:text-wisal-ivory"
                />
                <span v-if="infoForm.errors.phone" class="text-xs text-red-500 font-bold block">{{ infoForm.errors.phone }}</span>
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                :disabled="infoForm.processing"
                class="bg-wisal-aqua hover:bg-wisal-aqua/90 text-wisal-ivory text-xs font-black px-6 py-3.5 rounded-2xl transition-all shadow-md shadow-wisal-aqua/10 hover:shadow-lg disabled:opacity-50"
              >
                {{ activeLocale === 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- 2. Security Tab -->
        <div v-show="activeTab === 'security'" class="bg-white dark:bg-wisal-charcoal/30 border border-wisal-beige/10 dark:border-gray-800 rounded-3xl p-6 md:p-8 shadow-premium-sm space-y-8">
          <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-xs font-extrabold text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                <input
                  type="password"
                  v-model="passwordForm.password"
                  required
                  class="w-full bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl px-4 py-3.5 text-sm text-wisal-charcoal dark:text-wisal-ivory focus:outline-none focus:border-wisal-aqua transition-colors"
                />
                <span v-if="passwordForm.errors.password" class="text-xs text-red-500 font-bold block">{{ passwordForm.errors.password }}</span>
              </div>

              <div class="space-y-2">
                <label class="text-xs font-extrabold text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                <input
                  type="password"
                  v-model="passwordForm.password_confirmation"
                  required
                  class="w-full bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl px-4 py-3.5 text-sm text-wisal-charcoal dark:text-wisal-ivory focus:outline-none focus:border-wisal-aqua transition-colors"
                />
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                :disabled="passwordForm.processing"
                class="bg-wisal-aqua hover:bg-wisal-aqua/90 text-wisal-ivory text-xs font-black px-6 py-3.5 rounded-2xl transition-all shadow-md shadow-wisal-aqua/10 hover:shadow-lg disabled:opacity-50"
              >
                {{ activeLocale === 'ar' ? 'تحديث كلمة المرور' : 'Update Password' }}
              </button>
            </div>
          </form>
        </div>

        <!-- 3. Orders Tab -->
        <div v-show="activeTab === 'orders'" class="bg-white dark:bg-wisal-charcoal/30 border border-wisal-beige/10 dark:border-gray-800 rounded-3xl p-6 md:p-8 shadow-premium-sm space-y-6">
          <h3 class="text-xl font-black text-wisal-charcoal dark:text-wisal-ivory mb-6">
            {{ activeLocale === 'ar' ? 'سجل الطلبات' : 'Order History' }}
          </h3>
          
          <div v-if="orders.length === 0" class="text-center py-10 bg-wisal-beige/5 dark:bg-gray-800/10 rounded-2xl border border-wisal-beige/20 dark:border-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400 mx-auto mb-3 opacity-50">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5h6.75" />
            </svg>
            <p class="text-sm font-bold text-gray-500">{{ activeLocale === 'ar' ? 'لا توجد طلبات سابقة' : 'No previous orders' }}</p>
          </div>

          <div v-else class="space-y-4">
            <div v-for="order in orders" :key="order.id" class="bg-wisal-beige/5 dark:bg-gray-800/10 border border-wisal-beige/20 dark:border-gray-800 rounded-2xl p-5 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center hover:border-wisal-aqua/50 transition-colors">
              <div class="space-y-1">
                <div class="flex items-center gap-3">
                  <span class="text-sm font-black text-wisal-charcoal dark:text-wisal-ivory">#{{ order.order_number }}</span>
                  <span 
                    class="px-2.5 py-1 text-[10px] font-black rounded-lg"
                    :class="getStatusClass(order.status)"
                  >
                    {{ getStatusLabel(order.status) }}
                  </span>
                </div>
                <p class="text-xs font-bold text-gray-400">{{ new Date(order.created_at).toLocaleDateString() }}</p>
              </div>

              <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto gap-2">
                <span class="text-lg font-black text-wisal-aqua">{{ ((order.total_cents / 100) * (page.props.currency?.exchange_rate || 1)).toFixed(2) }} {{ page.props.currency?.symbol || '₪' }}</span>
                <div class="flex items-center gap-3">
                  <a :href="`/checkout/invoice/${order.id}`" target="_blank" class="text-xs font-bold text-wisal-aqua hover:underline transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    PDF
                  </a>
                  <button
                    @click="openOrderModal(order)"
                    class="text-xs font-bold text-gray-500 hover:text-wisal-charcoal dark:hover:text-wisal-ivory underline transition-colors cursor-pointer"
                  >
                    {{ activeLocale === 'ar' ? 'التفاصيل' : 'Details' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========= Order Details Modal ========= -->
  <Teleport to="body">
    <Transition name="modal-fade">
      <div
        v-if="showOrderModal && selectedOrder"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @click.self="closeOrderModal"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeOrderModal"></div>

        <!-- Modal Card -->
        <div
          class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white dark:bg-wisal-charcoal rounded-3xl shadow-2xl"
          dir="rtl"
        >
          <!-- Header -->
          <div class="sticky top-0 z-10 bg-white dark:bg-wisal-charcoal flex items-center justify-between px-6 py-4 border-b border-wisal-beige/20 dark:border-gray-700 rounded-t-3xl">
            <div>
              <h3 class="text-lg font-black text-wisal-charcoal dark:text-wisal-ivory">
                {{ activeLocale === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
              </h3>
              <p class="text-xs font-bold text-gray-400 mt-0.5">#{{ selectedOrder.order_number }}</p>
            </div>
            <div class="flex items-center gap-3">
              <!-- PDF Download -->
              <a
                :href="`/checkout/invoice/${selectedOrder.id}`"
                target="_blank"
                class="flex items-center gap-1.5 text-xs font-bold text-wisal-aqua hover:underline transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                PDF
              </a>
              <!-- Close -->
              <button
                @click="closeOrderModal"
                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
          </div>

          <!-- Body -->
          <div class="px-6 py-5 space-y-5">

            <!-- Status + Date Row -->
            <div class="flex items-center justify-between">
              <span
                class="px-3 py-1.5 text-xs font-black rounded-xl"
                :class="getStatusClass(selectedOrder.status)"
              >{{ getStatusLabel(selectedOrder.status) }}</span>
              <span class="text-xs font-bold text-gray-400">{{ new Date(selectedOrder.created_at).toLocaleDateString() }}</span>
            </div>

            <!-- Payment & Shipping Info Grid -->
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-wisal-beige/5 dark:bg-gray-800/20 rounded-2xl p-4 border border-wisal-beige/20 dark:border-gray-700">
                <p class="text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-wider">{{ activeLocale === 'ar' ? 'معلومات التوصيل' : 'Shipping Info' }}</p>
                <p class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ selectedOrder.shipping_name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ selectedOrder.shipping_phone }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ selectedOrder.shipping_city }} - {{ selectedOrder.shipping_address }}</p>
              </div>
              <div class="bg-wisal-beige/5 dark:bg-gray-800/20 rounded-2xl p-4 border border-wisal-beige/20 dark:border-gray-700">
                <p class="text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-wider">{{ activeLocale === 'ar' ? 'طريقة وحالة الدفع' : 'Payment Details' }}</p>
                <p class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory">{{ getPaymentMethodLabel(selectedOrder.payment_method) }}</p>
                <p class="text-xs mt-1" :class="selectedOrder.payment_status === 'paid' ? 'text-green-500 font-bold' : 'text-amber-600 dark:text-amber-400 font-bold'">
                  {{ getPaymentStatusLabel(selectedOrder.payment_status, selectedOrder.payment_method) }}
                </p>
              </div>
            </div>

            <!-- Order Items -->
            <div>
              <p class="text-xs font-black text-wisal-charcoal dark:text-wisal-ivory mb-3 uppercase tracking-wider">{{ activeLocale === 'ar' ? 'المنتجات' : 'Items' }}</p>
              <div class="space-y-3">
                <div
                  v-for="item in selectedOrder.items"
                  :key="item.id"
                  class="flex items-center gap-3 bg-wisal-beige/5 dark:bg-gray-800/20 rounded-xl p-3 border border-wisal-beige/15 dark:border-gray-700"
                >
                  <!-- Product image -->
                  <img
                    v-if="item.product?.image_url"
                    :src="item.product.image_url"
                    class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-wisal-beige/20"
                    :alt="item.product?.name"
                  />
                  <div v-else class="w-12 h-12 rounded-xl bg-wisal-beige/20 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" /></svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory truncate">{{ item.product?.name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                      {{ item.color?.name ? item.color.name : '' }}{{ item.color?.name && item.size?.name ? ' · ' : '' }}{{ item.size?.name ? item.size.name : '' }}
                    </p>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p class="text-sm font-black text-wisal-aqua">
                      {{ ((item.price_cents / 100) * (page.props.currency?.exchange_rate || 1) * item.quantity).toFixed(2) }} {{ page.props.currency?.symbol || '₪' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">× {{ item.quantity }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Totals Summary -->
            <div class="border-t border-wisal-beige/20 dark:border-gray-700 pt-4 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-wisal-aqua font-black text-base">
                  {{ ((selectedOrder.total_cents / 100) * (page.props.currency?.exchange_rate || 1)).toFixed(2) }} {{ page.props.currency?.symbol || '₪' }}
                </span>
                <span class="font-bold text-gray-400">{{ activeLocale === 'ar' ? 'الإجمالي الكلي' : 'Grand Total' }}</span>
              </div>
              <div v-if="selectedOrder.discount_cents > 0" class="flex justify-between text-xs">
                <span class="font-bold text-red-500">- {{ ((selectedOrder.discount_cents / 100) * (page.props.currency?.exchange_rate || 1)).toFixed(2) }} {{ page.props.currency?.symbol || '₪' }}</span>
                <span class="font-bold text-gray-400">{{ activeLocale === 'ar' ? 'خصم' : 'Discount' }}</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="font-bold text-gray-500">
                  {{ selectedOrder.shipping_cents === 0 ? (activeLocale === 'ar' ? 'مجاني' : 'Free') : ((selectedOrder.shipping_cents / 100) * (page.props.currency?.exchange_rate || 1)).toFixed(2) + ' ' + (page.props.currency?.symbol || '₪') }}
                </span>
                <span class="font-bold text-gray-400">{{ activeLocale === 'ar' ? 'الشحن' : 'Shipping' }}</span>
              </div>
            </div>

          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.25s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
.modal-fade-enter-active .relative,
.modal-fade-enter-from .relative {
  transform: scale(0.95);
  transition: transform 0.25s ease;
}
</style>
