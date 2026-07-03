<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { router } from '@inertiajs/vue3'

const auth = useAuthStore()

const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const loading = ref(false)
const error = ref('')

async function onSubmit() {
  loading.value = true
  error.value = ''
  try {
    await auth.register(form.value)
    router.visit('/')
  } catch (e: any) {
    error.value = e.response?._data?.message || $t('auth.register_failed')
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-wisal-ivory p-4">
    <WCard class="w-full max-w-md">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-wisal-charcoal mb-2">{{ $t('auth.register_title') }}</h1>
        <p class="text-gray-500">{{ $t('auth.register_subtitle') }}</p>
      </div>

      <div v-if="error" class="mb-4">
        <WBadge variant="danger" class="w-full justify-center">{{ error }}</WBadge>
      </div>

      <form @submit.prevent="onSubmit" class="space-y-4">
        <WInput
          v-model="form.name"
          :label="$t('auth.full_name')"
          required
        />
        
        <WInput
          v-model="form.email"
          :label="$t('auth.email')"
          type="email"
          required
        />
        
        <WInput
          v-model="form.password"
          :label="$t('auth.password')"
          type="password"
          required
        />
        
        <WInput
          v-model="form.password_confirmation"
          :label="$t('auth.password_confirmation')"
          type="password"
          required
        />

        <WButton
          type="submit"
          class="w-full mt-6"
          :loading="loading"
        >
          {{ $t('auth.register_btn') }}
        </WButton>
      </form>
      
      <div class="mt-6 text-center text-sm text-gray-500">
        {{ $t('auth.have_account') }}
        <Link href="/login" class="text-wisal-aqua hover:underline font-medium">{{ $t('auth.login_link') }}</Link>
      </div>
    </WCard>
  </div>
</template>
