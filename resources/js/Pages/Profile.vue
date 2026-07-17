<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user || {})
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

const activeTab = ref('info') // info, security

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
      </div>
    </div>
  </div>
</template>
