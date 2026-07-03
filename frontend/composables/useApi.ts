import { useRuntimeConfig } from '#app'

export const useApi = () => {
  const config = useRuntimeConfig()
  
  return $fetch.create({
    baseURL: config.public.apiBase + '/api',
    onRequest({ options }) {
      const token = useCookie('auth_token').value
      if (token) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token}`
        }
      }
      options.headers = {
        ...options.headers,
        Accept: 'application/json'
      }
    }
  })
}
