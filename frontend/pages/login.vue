<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from '#app'

const auth = useAuthStore()
const router = useRouter()
const form = ref({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

async function onSubmit() {
  loading.value = true
  error.value = ''
  try {
    await auth.login(form.value)
    router.push('/')
  } catch (e: any) {
    error.value = e.response?._data?.message || 'فشل تسجيل الدخول. تأكد من بياناتك.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-wisal-ivory p-4">
    <WCard class="w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-wisal-charcoal mb-2">مرحباً بك في وِصال</h1>
        <p class="text-gray-500">سجل دخولك للمتابعة</p>
      </div>

      <div v-if="error" class="mb-4">
        <WBadge variant="danger" class="w-full justify-center">{{ error }}</WBadge>
      </div>

      <form @submit.prevent="onSubmit" class="space-y-4">
        <WInput
          v-model="form.email"
          label="البريد الإلكتروني"
          type="email"
          required
        />
        
        <WInput
          v-model="form.password"
          label="كلمة المرور"
          type="password"
          required
        />

        <WButton
          type="submit"
          class="w-full mt-6"
          :loading="loading"
        >
          تسجيل الدخول
        </WButton>
      </form>
      
      <div class="mt-6 text-center text-sm text-gray-500">
        ليس لديك حساب؟
        <NuxtLink to="/register" class="text-wisal-aqua hover:underline font-medium">إنشاء حساب جديد</NuxtLink>
      </div>
    </WCard>
  </div>
</template>
