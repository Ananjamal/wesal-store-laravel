<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'

const page = usePage()
const activeLocale = computed(() => (page.props.locale as string) || 'ar')

const form = ref({ name: '', email: '', phone: '', subject: '', message: '' })
const submitted = ref(false)
const sending = ref(false)
const errors = ref<Record<string, string>>({})

function validateForm() {
  errors.value = {}
  if (!form.value.name.trim()) errors.value.name = activeLocale.value === 'ar' ? 'الاسم مطلوب' : 'Name is required'
  if (!form.value.email.trim()) errors.value.email = activeLocale.value === 'ar' ? 'البريد الإلكتروني مطلوب' : 'Email is required'
  if (!form.value.subject.trim()) errors.value.subject = activeLocale.value === 'ar' ? 'الموضوع مطلوب' : 'Subject is required'
  if (!form.value.message.trim()) errors.value.message = activeLocale.value === 'ar' ? 'الرسالة مطلوبة' : 'Message is required'
  return Object.keys(errors.value).length === 0
}

function submitForm() {
  if (!validateForm()) return
  sending.value = true
  // Simulate sending (replace with actual API call if backend exists)
  setTimeout(() => {
    sending.value = false
    submitted.value = true
    form.value = { name: '', email: '', phone: '', subject: '', message: '' }
  }, 1200)
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'تواصل معنا - متجر وِصال' : 'Contact Us - Wisal Store' }}</title>
    <meta name="description" :content="activeLocale === 'ar' ? 'تواصل مع فريق متجر وِصال لأي استفسار أو مقترح' : 'Get in touch with the Wisal Store team'" />
  </Head>

  <div class="min-h-screen bg-gray-50 dark:bg-[#1e1e1e]">

    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-br from-wisal-charcoal via-[#2a3a4a] to-wisal-aqua/80 py-24 overflow-hidden">
      <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-10 left-10 w-64 h-64 bg-wisal-aqua rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-20 w-96 h-96 bg-wisal-beige rounded-full blur-3xl"></div>
      </div>
      <div class="container mx-auto px-4 md:px-8 text-center relative z-10 space-y-4">
        <span class="inline-block bg-wisal-aqua/20 border border-wisal-aqua/40 text-wisal-aqua text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest">
          {{ activeLocale === 'ar' ? 'نحن هنا من أجلك' : "We're here for you" }}
        </span>
        <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">
          {{ activeLocale === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
        </h1>
        <p class="text-gray-300 text-sm md:text-base max-w-xl mx-auto font-medium">
          {{ activeLocale === 'ar' ? 'لديك استفسار أو مقترح؟ نحن سعيدون بمساعدتك في أي وقت' : "Have a question? We're happy to help you anytime" }}
        </p>
      </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 py-16 space-y-16">

      <!-- Info Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-wisal-charcoal/60 rounded-3xl p-8 text-center border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm hover:shadow-premium-lg transition-all duration-300 group hover:-translate-y-1">
          <div class="w-14 h-14 bg-wisal-aqua/10 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-wisal-aqua/20 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-wisal-aqua">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
            </svg>
          </div>
          <h3 class="font-extrabold text-wisal-charcoal dark:text-wisal-ivory mb-2">{{ activeLocale === 'ar' ? 'الهاتف' : 'Phone' }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-sans" dir="ltr">+970 59 000 0000</p>
        </div>

        <div class="bg-white dark:bg-wisal-charcoal/60 rounded-3xl p-8 text-center border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm hover:shadow-premium-lg transition-all duration-300 group hover:-translate-y-1">
          <div class="w-14 h-14 bg-wisal-aqua/10 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-wisal-aqua/20 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-wisal-aqua">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
          </div>
          <h3 class="font-extrabold text-wisal-charcoal dark:text-wisal-ivory mb-2">{{ activeLocale === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 font-sans" dir="ltr">info@wisal-store.com</p>
        </div>

        <div class="bg-white dark:bg-wisal-charcoal/60 rounded-3xl p-8 text-center border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm hover:shadow-premium-lg transition-all duration-300 group hover:-translate-y-1">
          <div class="w-14 h-14 bg-wisal-aqua/10 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-wisal-aqua/20 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-wisal-aqua">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
          </div>
          <h3 class="font-extrabold text-wisal-charcoal dark:text-wisal-ivory mb-2">{{ activeLocale === 'ar' ? 'الموقع' : 'Location' }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ activeLocale === 'ar' ? 'فلسطين' : 'Palestine' }}</p>
        </div>
      </div>

      <!-- Form + Sidebar -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

        <!-- Form -->
        <div class="lg:col-span-3 bg-white dark:bg-wisal-charcoal/60 rounded-[32px] p-8 md:p-10 border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm">

          <!-- Success -->
          <div v-if="submitted" class="text-center py-16 space-y-5">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-green-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-extrabold text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'تم الإرسال بنجاح! 🎉' : 'Sent Successfully! 🎉' }}</h3>
            <p class="text-gray-400 text-sm max-w-xs mx-auto">{{ activeLocale === 'ar' ? 'سنتواصل معك في أقرب وقت ممكن.' : "We'll get back to you soon." }}</p>
            <button @click="submitted = false" class="bg-wisal-aqua text-white px-8 py-3 rounded-2xl text-sm font-bold hover:bg-wisal-aqua/90 transition-colors">
              {{ activeLocale === 'ar' ? 'إرسال رسالة أخرى' : 'Send Another Message' }}
            </button>
          </div>

          <!-- Form fields -->
          <form v-else @submit.prevent="submitForm" class="space-y-6">
            <div class="space-y-1 mb-2">
              <h2 class="text-2xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ activeLocale === 'ar' ? 'أرسل لنا رسالة' : 'Send us a message' }}</h2>
              <p class="text-gray-400 text-sm">{{ activeLocale === 'ar' ? 'سنرد عليك خلال 24 ساعة' : "We'll reply within 24 hours" }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-extrabold text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}</label>
                <input id="contact-name" v-model="form.name" type="text" :placeholder="activeLocale === 'ar' ? 'أدخل اسمك' : 'Enter your name'"
                  class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-wisal-beige/25 dark:border-gray-700 rounded-2xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory transition-all"
                  :class="{ 'border-red-400 dark:border-red-500': errors.name }" />
                <span v-if="errors.name" class="text-xs text-red-500">{{ errors.name }}</span>
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-extrabold text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'البريد الإلكتروني *' : 'Email *' }}</label>
                <input id="contact-email" v-model="form.email" type="email" :placeholder="activeLocale === 'ar' ? 'بريدك الإلكتروني' : 'your@email.com'"
                  class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-wisal-beige/25 dark:border-gray-700 rounded-2xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory transition-all"
                  :class="{ 'border-red-400 dark:border-red-500': errors.email }" />
                <span v-if="errors.email" class="text-xs text-red-500">{{ errors.email }}</span>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-xs font-extrabold text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
                <input id="contact-phone" v-model="form.phone" type="tel" :placeholder="activeLocale === 'ar' ? '+970 59 ...' : '+1 555 ...'"
                  class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-wisal-beige/25 dark:border-gray-700 rounded-2xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory transition-all" dir="ltr" />
              </div>
              <div class="space-y-1.5">
                <label class="text-xs font-extrabold text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'الموضوع *' : 'Subject *' }}</label>
                <input id="contact-subject" v-model="form.subject" type="text" :placeholder="activeLocale === 'ar' ? 'موضوع رسالتك' : 'Message subject'"
                  class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-wisal-beige/25 dark:border-gray-700 rounded-2xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory transition-all"
                  :class="{ 'border-red-400 dark:border-red-500': errors.subject }" />
                <span v-if="errors.subject" class="text-xs text-red-500">{{ errors.subject }}</span>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-extrabold text-wisal-charcoal dark:text-wisal-ivory uppercase tracking-wider">{{ activeLocale === 'ar' ? 'الرسالة *' : 'Message *' }}</label>
              <textarea id="contact-message" v-model="form.message" rows="5" :placeholder="activeLocale === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...'"
                class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-wisal-beige/25 dark:border-gray-700 rounded-2xl py-3.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-wisal-aqua/30 dark:text-wisal-ivory transition-all resize-none"
                :class="{ 'border-red-400 dark:border-red-500': errors.message }"></textarea>
              <span v-if="errors.message" class="text-xs text-red-500">{{ errors.message }}</span>
            </div>

            <button id="contact-submit" type="submit" :disabled="sending"
              class="w-full bg-wisal-aqua text-white font-extrabold py-4 rounded-2xl text-sm hover:bg-wisal-aqua/90 transition-all hover:shadow-lg hover:shadow-wisal-aqua/20 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
              <svg v-if="sending" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
              </svg>
              {{ sending ? (activeLocale === 'ar' ? 'جارٍ الإرسال...' : 'Sending...') : (activeLocale === 'ar' ? 'إرسال الرسالة' : 'Send Message') }}
            </button>
          </form>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Social -->
          <div class="bg-white dark:bg-wisal-charcoal/60 rounded-[32px] p-7 border border-wisal-beige/20 dark:border-gray-800 shadow-premium-sm space-y-4">
            <h3 class="font-extrabold text-wisal-charcoal dark:text-wisal-ivory text-base">{{ activeLocale === 'ar' ? 'تابعنا على' : 'Follow us on' }}</h3>
            <div class="space-y-2">
              <a href="#" class="flex items-center gap-4 p-3.5 rounded-2xl hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors group">
                <div class="w-10 h-10 bg-gradient-to-br from-[#833AB4] via-[#E1306C] to-[#FCAF45] rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory group-hover:text-wisal-aqua transition-colors">Instagram</span>
              </a>
              <a href="#" class="flex items-center gap-4 p-3.5 rounded-2xl hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors group">
                <div class="w-10 h-10 bg-[#25D366] rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory group-hover:text-wisal-aqua transition-colors">WhatsApp</span>
              </a>
              <a href="#" class="flex items-center gap-4 p-3.5 rounded-2xl hover:bg-wisal-beige/10 dark:hover:bg-gray-800 transition-colors group">
                <div class="w-10 h-10 bg-[#1877F2] rounded-xl flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </div>
                <span class="text-sm font-bold text-wisal-charcoal dark:text-wisal-ivory group-hover:text-wisal-aqua transition-colors">Facebook</span>
              </a>
            </div>
          </div>

          <!-- Working Hours -->
          <div class="bg-gradient-to-br from-wisal-aqua to-wisal-aqua/80 rounded-[32px] p-7 space-y-4 shadow-lg shadow-wisal-aqua/20">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="font-extrabold text-white text-base">{{ activeLocale === 'ar' ? 'ساعات العمل' : 'Working Hours' }}</h3>
            </div>
            <div class="space-y-3">
              <div class="flex justify-between text-sm"><span class="text-white/80">{{ activeLocale === 'ar' ? 'الأحد - الخميس' : 'Sun - Thu' }}</span><span class="text-white font-extrabold">9:00 - 18:00</span></div>
              <div class="h-px bg-white/20"></div>
              <div class="flex justify-between text-sm"><span class="text-white/80">{{ activeLocale === 'ar' ? 'الجمعة - السبت' : 'Fri - Sat' }}</span><span class="text-white font-extrabold">{{ activeLocale === 'ar' ? 'مغلق' : 'Closed' }}</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
