<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useI18n } from 'vue-i18n'

const auth = useAuthStore()
const { setLocale, locale } = useI18n()

function toggleLanguage() {
  setLocale(locale.value === 'ar' ? 'en' : 'ar')
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-wisal-ivory text-wisal-charcoal font-sans" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
    <!-- Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
      <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <Link href="/" class="text-2xl font-bold text-wisal-aqua">
          {{ $t('layout.logo') }}
        </Link>

        <!-- Navigation Links -->
        <nav class="hidden md:flex items-center gap-6">
          <Link href="/" class="hover:text-wisal-aqua transition-colors">{{ $t('layout.home') }}</Link>
          <Link href="/products" class="hover:text-wisal-aqua transition-colors">{{ $t('layout.products') }}</Link>
          <Link href="/about" class="hover:text-wisal-aqua transition-colors">{{ $t('layout.about') }}</Link>
        </nav>

        <!-- Actions -->
        <div class="flex items-center gap-4">
          <button @click="toggleLanguage" class="text-sm font-medium text-gray-500 hover:text-wisal-charcoal">
            {{ locale === 'ar' ? 'English' : 'العربية' }}
          </button>

          <template v-if="auth.token">
            <span class="text-sm font-medium">{{ auth.user?.name }}</span>
            <button @click="auth.logout" class="text-sm text-red-500 hover:underline">{{ $t('layout.logout') }}</button>
          </template>
          <template v-else>
            <Link href="/login">
              <WButton variant="ghost" size="sm">{{ $t('auth.login_btn') }}</WButton>
            </Link>
            <Link href="/register">
              <WButton variant="primary" size="sm">{{ $t('auth.register_btn') }}</WButton>
            </Link>
          </template>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-wisal-charcoal text-wisal-ivory py-12 mt-12">
      <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <h3 class="text-xl font-bold mb-4 text-wisal-beige">{{ $t('layout.logo') }}</h3>
          <p class="text-gray-400 text-sm">{{ $t('layout.footer_desc') }}</p>
        </div>
        <div>
          <h4 class="font-bold mb-4">{{ $t('layout.quick_links') }}</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><Link href="/" class="hover:text-wisal-beige">{{ $t('layout.home') }}</Link></li>
            <li><Link href="/products" class="hover:text-wisal-beige">{{ $t('layout.products') }}</Link></li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold mb-4">{{ $t('layout.contact') }}</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li>support@wesal.com</li>
            <li>+966 50 000 0000</li>
          </ul>
        </div>
      </div>
      <div class="container mx-auto px-4 mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
        &copy; {{ new Date().getFullYear() }} {{ $t('layout.logo') }}. {{ $t('layout.rights_reserved') }}
      </div>
    </footer>
  </div>
</template>
