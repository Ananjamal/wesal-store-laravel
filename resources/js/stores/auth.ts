import { ref } from 'vue';
import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3'
import { useApi } from '@/composables/useApi'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(null) // Mocked cookie for now
  const api = useApi()

  async function fetchUser() {
    if (!token.value) return
    try {
      const response = await api('/me')
      user.value = response.data
    } catch (e) {
      token.value = null
      user.value = null
    }
  }

  async function login(credentials: any) {
    const response = await api('/auth/login', {
      method: 'POST',
      body: credentials
    })
    token.value = response.data.token
    user.value = response.data.user
  }

  async function register(data: any) {
    const response = await api('/auth/register', {
      method: 'POST',
      body: data
    })
    token.value = response.data.token
    user.value = response.data.user
  }

  async function logout() {
    try {
      await api('/auth/logout', { method: 'POST' })
    } catch (e) {}
    token.value = null
    user.value = null
    router.visit('/login')
  }

  return { user, token, fetchUser, login, register, logout }
})
