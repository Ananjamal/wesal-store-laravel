<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from '#app'

const auth = useAuthStore()
const router = useRouter()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const loading = ref(false)
const error = ref('')

async function onSubmit() {
  loading.value = true
  error.value = ''
  try {
    await auth.register(form.value)
    router.push('/')
  } catch (e: any) {
    error.value = e.response?._data?.message || 'فشل التسجيل. يرجى المحاولة مرة أخرى.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-wisal-ivory p-4">
    <WCard class="w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-wisal-charcoal mb-2">إنشاء حساب جديد</h1>
        <p class="text-gray-500">انضم إلى عائلة وِصال</p>
      </div>

      <div v-if="error" class="mb-4">
        <WBadge variant="danger" class="w-full justify-center">{{ error }}</WBadge>
      </div>

      <form @submit.prevent="onSubmit" class="space-y-4">
        <WInput
          v-model="form.name"
          label="الاسم الكامل"
          required
        />
        
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
        
        <WInput
          v-model="form.password_confirmation"
          label="تأكيد كلمة المرور"
          type="password"
          required
        />

        <WButton
          type="submit"
          class="w-full mt-6"
          :loading="loading"
        >
          إنشاء حساب
        </WButton>
      </form>
      
      <div class="mt-6 text-center text-sm text-gray-500">
        لديك حساب بالفعل؟
        <NuxtLink to="/login" class="text-wisal-aqua hover:underline font-medium">تسجيل الدخول</NuxtLink>
      </div>
    </WCard>
  </div>
</template>
