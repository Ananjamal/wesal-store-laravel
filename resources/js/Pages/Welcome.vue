<script setup>
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
  auth: Object,
  locale: String,
  currency: Object,
  currencies: Array,
  translations: Object,
});

const t = (key) => props.translations?.[key] ?? key;

const isRtl = computed(() => props.locale === 'ar');

const switchLocale = (locale) => {
  router.visit(`/lang/${locale}`, { method: 'get', preserveState: false });
};

const switchCurrency = (code) => {
  router.visit(`/currency/${code}`, { method: 'get', preserveState: false });
};
</script>

<template>
  <Head :title="t('home')" />

  <div
    :dir="isRtl ? 'rtl' : 'ltr'"
    class="min-h-screen bg-wisal-ivory text-wisal-charcoal flex flex-col font-sans"
  >
    <!-- Header -->
    <header class="border-b border-wisal-beige/30 py-4 bg-white/50 backdrop-blur-md sticky top-0 z-50">
      <div class="container mx-auto px-4 flex justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-wisal-aqua tracking-widest">✦ وِصال ✦</h1>

        <nav class="flex-1 flex items-center justify-center gap-5 text-sm">
          <a href="#" class="hover:text-wisal-aqua transition-colors duration-200">{{ t('home') }}</a>
          <a href="#" class="hover:text-wisal-aqua transition-colors duration-200">{{ t('catalog') }}</a>
          <a href="#" class="hover:text-wisal-aqua transition-colors duration-200">{{ t('blog') }}</a>
        </nav>

        <!-- Language & Currency Switchers -->
        <div class="flex items-center gap-3 text-sm">
          <!-- Language Switcher -->
          <div class="flex items-center gap-1 bg-white border border-wisal-beige/40 rounded-full px-3 py-1 shadow-sm">
            <button
              @click="switchLocale('ar')"
              :class="locale === 'ar' ? 'text-wisal-aqua font-bold' : 'text-gray-400 hover:text-wisal-aqua'"
              class="transition-colors duration-200"
            >العربية</button>
            <span class="text-gray-300">|</span>
            <button
              @click="switchLocale('en')"
              :class="locale === 'en' ? 'text-wisal-aqua font-bold' : 'text-gray-400 hover:text-wisal-aqua'"
              class="transition-colors duration-200"
            >EN</button>
          </div>

          <!-- Currency Switcher -->
          <div v-if="currencies && currencies.length > 1" class="relative">
            <select
              @change="switchCurrency($event.target.value)"
              :value="currency.code"
              class="appearance-none bg-white border border-wisal-beige/40 rounded-full px-3 py-1 shadow-sm text-sm text-wisal-charcoal focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 cursor-pointer pr-6"
            >
              <option v-for="c in currencies" :key="c.code" :value="c.code">
                {{ c.symbol }} {{ c.code }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-8">
      <div class="text-center max-w-md bg-white p-8 rounded-2xl shadow-sm border border-wisal-beige/20">
        <div class="text-4xl text-wisal-aqua mb-4">✨</div>
        <h2 class="text-2xl font-bold mb-2">{{ t('welcome_text') }}</h2>
        <p class="text-gray-500 mb-6 text-sm">{{ t('tagline') }}</p>
        <div class="flex items-center justify-center gap-2 mb-4 text-xs text-gray-400">
          <span class="bg-wisal-beige/30 rounded px-2 py-1">{{ currency.symbol }} {{ currency.code }}</span>
          <span class="bg-wisal-beige/30 rounded px-2 py-1">{{ locale === 'ar' ? 'العربية' : 'English' }}</span>
        </div>
        <button class="bg-wisal-aqua text-wisal-ivory px-8 py-3 rounded-full shadow-lg hover:bg-wisal-aqua/90 hover:-translate-y-1 transform transition-all duration-200 font-bold text-lg">
          🚀 {{ t('browse_products') }}
        </button>
          <button class="bg-wisal-aqua text-wisal-ivory px-8 py-3 rounded-full shadow-lg hover:bg-wisal-aqua/90 hover:-translate-y-1 transform transition-all duration-200 font-bold text-lg">
          🚀 {{ t('browse_products') }}
        </button>
          <button class="bg-wisal-aqua text-wisal-ivory px-8 py-3 rounded-full shadow-lg hover:bg-wisal-aqua/90 hover:-translate-y-1 transform transition-all duration-200 font-bold text-lg">
          🚀 {{ t('browse_products') }}
        </button>
          <button class="bg-wisal-aqua text-wisal-ivory px-8 py-3 rounded-full shadow-lg hover:bg-wisal-aqua/90 hover:-translate-y-1 transform transition-all duration-200 font-bold text-lg">
          🚀 {{ t('browse_products') }}
        </button>
          <button class="bg-wisal-aqua text-wisal-ivory px-8 py-3 rounded-full shadow-lg hover:bg-wisal-aqua/90 hover:-translate-y-1 transform transition-all duration-200 font-bold text-lg">
          🚀 {{ t('browse_products') }}
        </button>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-wisal-beige/20 py-6 text-center text-xs text-gray-400 bg-white/35">
      <p>&copy; 2026 متجر وِصال. {{ t('all_rights_reserved') }}</p>
    </footer>
  </div>
</template>
