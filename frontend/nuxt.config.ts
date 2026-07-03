// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@vueuse/nuxt',
    '@nuxt/image',
    '@nuxtjs/i18n',
  ],
  i18n: {
    locales: [
      { code: 'ar', name: 'العربية', file: 'ar.json', dir: 'rtl' },
      { code: 'en', name: 'English', file: 'en.json', dir: 'ltr' }
    ],
    defaultLocale: 'ar',
    lazy: true,
    langDir: 'locales/',
    strategy: 'prefix_except_default',
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    },
  },
  ssr: true, // SSR for SEO
  typescript: { strict: true },
})
