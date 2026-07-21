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
                    :class="{
                      'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': order.status === 'pending',
                      'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': order.status === 'processing',
                      'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': order.status === 'completed',
                      'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': order.status === 'cancelled',
                    }"
                  >
                    {{ activeLocale === 'ar' ? (
                      order.status === 'pending' ? 'قيد الانتظار' :
                      order.status === 'processing' ? 'قيد التنفيذ' :
                      order.status === 'completed' ? 'مكتمل' : 'ملغي'
                    ) : order.status }}
                  </span>
                </div>
                <p class="text-xs font-bold text-gray-400">{{ new Date(order.created_at).toLocaleDateString() }}</p>
              </div>

              <div class="flex flex-row md:flex-col items-center md:items-end justify-between w-full md:w-auto gap-2">
                <span class="text-lg font-black text-wisal-aqua">{{ ((order.total_cents / 100) * (page.props.currency?.exchange_rate || 1)).toFixed(2) }} {{ page.props.currency?.symbol || '₪' }}</span>
                <a :href="`/checkout/success/${order.id}`" class="text-xs font-bold text-gray-500 hover:text-wisal-charcoal dark:hover:text-wisal-ivory underline transition-colors">
                  {{ activeLocale === 'ar' ? 'عرض الفاتورة' : 'View Invoice' }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
