<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import HeroSlider from '@/Components/HeroSlider.vue'
import ProductCard from '@/Components/ProductCard.vue'
import { useToastStore } from '@/stores/toast'

interface Slide {
  id: number
  title: string
  subtitle?: string
  description?: string
  image: string
  button_text?: string
  button_link?: string
}

interface Banner {
  id: number
  title: string
  description?: string
  link?: string
  desktop_image: string
  mobile_image: string
}

interface Section {
  id: number
  type: string
  title?: string
  subtitle?: string
  description?: string
  items_count?: number
  settings?: any
  data: any[]
}

const props = defineProps<{
  sliders: Slide[]
  banners: Banner[]
  homepageSections: Section[]
  locale: string
}>()

const activeLocale = computed(() => props.locale || 'ar')

const activeSections = computed(() => {
  return props.homepageSections || []
})

const hasHeroSection = computed(() => activeSections.value.some(s => s.type === 'hero_section'))

const toastStore = useToastStore()
const newsletterEmail = ref('')

function subscribeNewsletter() {
  if (newsletterEmail.value) {
    toastStore.success(activeLocale.value === 'ar' ? 'تم الاشتراك بنجاح في النشرة البريدية!' : 'Subscribed to newsletter successfully!')
    newsletterEmail.value = ''
  }
}
</script>

<template>
  <Head>
    <title>{{ activeLocale === 'ar' ? 'الرئيسية - متجر وِصال' : 'Home - Wisal Store' }}</title>
  </Head>

  <div class="space-y-24 pb-28">
    <!-- Hero Slider -->
    <HeroSlider v-if="!hasHeroSection" :sliders="sliders" />

    <!-- Sections wrapper -->
    <div v-for="section in activeSections" :key="section.id" class="w-full">
      <!-- 1. Categories Section -->
      <section v-if="section.type === 'categories' && section.data.length > 0" class="container mx-auto px-4 md:px-8">
        <div class="text-center max-w-xl mx-auto mb-14 space-y-3">
          <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
          <h2 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
          <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm md:text-base leading-relaxed">{{ section.description }}</p>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
          <Link 
            v-for="cat in section.data" 
            :key="cat.id" 
            :href="`/products?category_id=${cat.id}`"
            class="group text-center flex flex-col items-center p-8 bg-white dark:bg-wisal-charcoal/30 rounded-3xl border border-wisal-beige/10 dark:border-gray-800/80 shadow-premium-sm hover:shadow-premium-lg transition-all duration-300 hover:-translate-y-1.5"
          >
            <div class="w-20 h-20 rounded-2xl bg-wisal-beige/10 dark:bg-gray-800/40 flex items-center justify-center text-wisal-aqua group-hover:bg-wisal-aqua group-hover:text-wisal-ivory overflow-hidden transition-all duration-300 shadow-inner">
              <img v-if="cat.image" :src="cat.image" :alt="cat.name" class="w-full h-full object-cover" />
              <span v-else class="text-3xl font-extrabold font-sans uppercase">{{ cat.name.charAt(0) }}</span>
            </div>
            <h3 class="font-extrabold text-sm md:text-base text-wisal-charcoal dark:text-wisal-ivory mt-5 group-hover:text-wisal-aqua transition-colors">
              {{ cat.name }}
            </h3>
            <span class="text-[10px] bg-wisal-beige/20 dark:bg-gray-800 text-gray-400 font-bold px-2 py-0.5 rounded-md mt-2 block w-fit">
              {{ cat.products_count ?? 0 }} {{ activeLocale === 'ar' ? 'منتج' : 'products' }}
            </span>
          </Link>
        </div>
      </section>

      <!-- 2. Featured / Latest Products -->
      <section 
        v-else-if="(section.type === 'featured_products' || section.type === 'latest_products') && section.data.length > 0" 
        class="container mx-auto px-4 md:px-8"
      >
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div class="space-y-2">
            <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
            <h2 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
            <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm max-w-xl leading-relaxed">{{ section.description }}</p>
          </div>
          <Link 
            href="/products" 
            class="inline-flex items-center gap-1.5 bg-wisal-beige text-wisal-charcoal hover:bg-wisal-aqua hover:text-wisal-ivory px-6 py-3 rounded-2xl text-xs font-black transition-all shadow-md shadow-wisal-beige/10 hover:shadow-wisal-aqua/10 hover:-translate-y-0.5"
          >
            <span>{{ activeLocale === 'ar' ? 'عرض الكل' : 'View All' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 rtl:rotate-180">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <ProductCard 
            v-for="product in section.data" 
            :key="product.id" 
            :product="product" 
          />
        </div>
      </section>

      <!-- 3. Offers (Promo Banners) -->
      <section v-else-if="section.type === 'offers' && banners.length > 0" class="container mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div 
            v-for="banner in banners.slice(0, 2)" 
            :key="banner.id" 
            class="group relative rounded-[32px] overflow-hidden bg-wisal-charcoal h-72 md:h-96 shadow-premium-md hover:shadow-premium-lg transition-all duration-300"
          >
            <!-- Image with zoom hover -->
            <img 
              :src="banner.desktop_image" 
              :alt="banner.title" 
              class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-[4000ms] ease-out opacity-85" 
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 p-8 md:p-12 flex flex-col justify-end text-white space-y-3">
              <h3 class="text-2xl md:text-3xl font-black leading-tight text-white max-w-md drop-shadow-md">{{ banner.title }}</h3>
              <p v-if="banner.description" class="text-xs md:text-sm text-gray-300 max-w-md line-clamp-2 leading-relaxed">
                {{ banner.description }}
              </p>
              <div v-if="banner.link" class="pt-3">
                <Link 
                  :href="banner.link" 
                  class="inline-flex items-center gap-2 bg-wisal-beige text-wisal-charcoal hover:bg-wisal-ivory hover:scale-103 px-6 py-3 rounded-2xl text-xs font-black shadow-lg transition-all duration-300"
                >
                  <span>{{ activeLocale === 'ar' ? 'اكتشف العرض' : 'Explore Offer' }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 rtl:rotate-180">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. Blog Articles -->
      <section v-else-if="section.type === 'articles' && section.data.length > 0" class="container mx-auto px-4 md:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div class="space-y-2">
            <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
            <h2 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
            <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm max-w-xl leading-relaxed">{{ section.description }}</p>
          </div>
          <Link 
            href="/blog" 
            class="inline-flex items-center gap-1.5 bg-wisal-beige text-wisal-charcoal hover:bg-wisal-aqua hover:text-wisal-ivory px-6 py-3 rounded-2xl text-xs font-black transition-all shadow-md shadow-wisal-beige/10 hover:shadow-wisal-aqua/10 hover:-translate-y-0.5"
          >
            <span>{{ activeLocale === 'ar' ? 'زيارة المدونة' : 'Visit Blog' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 rtl:rotate-180">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div 
            v-for="post in section.data" 
            :key="post.id"
            class="group bg-white dark:bg-wisal-charcoal/30 border border-wisal-beige/10 dark:border-gray-800/80 rounded-3xl overflow-hidden shadow-premium-sm hover:shadow-premium-lg transition-all duration-300 flex flex-col h-full hover:-translate-y-1.5"
          >
            <div class="relative aspect-[16/10] overflow-hidden bg-gray-50 dark:bg-gray-900/10">
              <Link :href="`/blog/${post.slug}`" class="block w-full h-full">
                <img 
                  :src="post.image" 
                  :alt="post.title" 
                  class="object-cover w-full h-full group-hover:scale-103 transition-transform duration-500 ease-out animate-fade-in" 
                />
              </Link>
              <div 
                v-if="post.category_name"
                class="absolute top-4 right-4 bg-wisal-beige/95 text-wisal-charcoal text-[10px] px-3 py-1 rounded-lg font-black shadow-md uppercase tracking-wider"
              >
                {{ post.category_name }}
              </div>
            </div>
            
            <div class="p-6 flex-grow flex flex-col justify-between">
              <div>
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider font-sans">{{ post.published_at }}</span>
                <h3 class="font-extrabold text-base md:text-lg text-wisal-charcoal dark:text-wisal-ivory mt-2 line-clamp-2 hover:text-wisal-aqua transition-colors duration-200">
                  <Link :href="`/blog/${post.slug}`">
                    {{ post.title }}
                  </Link>
                </h3>
                <p class="text-gray-400 dark:text-gray-500 text-xs md:text-sm mt-3 line-clamp-2 leading-relaxed">
                  {{ post.summary }}
                </p>
              </div>
              <div class="pt-5 mt-5 border-t border-wisal-beige/10 dark:border-gray-800">
                <Link 
                  :href="`/blog/${post.slug}`"
                  class="text-xs font-bold text-wisal-aqua hover:underline inline-flex items-center gap-1"
                >
                  <span>{{ activeLocale === 'ar' ? 'اقرأ المزيد' : 'Read More' }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 rtl:rotate-180">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 5. Testimonials -->
      <section v-else-if="section.type === 'testimonials'" class="bg-wisal-beige/5 dark:bg-gray-800/10 py-20 w-full relative">
        <div class="container mx-auto px-4 md:px-8">
          <div class="text-center max-w-xl mx-auto mb-14 space-y-3">
            <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
            <h2 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
            <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm leading-relaxed">{{ section.description }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div 
              v-for="i in 3" 
              :key="i"
              class="bg-white dark:bg-wisal-charcoal/20 p-8 md:p-10 rounded-3xl border border-wisal-beige/10 dark:border-gray-800/80 shadow-premium-sm relative"
            >
              <div class="text-6xl text-wisal-beige absolute top-6 right-6 select-none opacity-20 font-serif leading-none">“</div>
              <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-wisal-beige/15 flex items-center justify-center font-black text-wisal-aqua text-lg shadow-inner">
                  {{ i === 1 ? 'س' : (i === 2 ? 'أ' : 'م') }}
                </div>
                <div>
                  <h4 class="font-extrabold text-sm md:text-base text-wisal-charcoal dark:text-wisal-ivory">
                    {{ i === 1 ? 'سارة عبد الله' : (i === 2 ? 'أحمد الشمري' : 'منى العمري') }}
                  </h4>
                  <div class="flex text-yellow-500 text-xs mt-1">★★★★★</div>
                </div>
              </div>
              <p class="text-gray-400 dark:text-gray-500 text-sm leading-relaxed relative z-10 font-medium">
                {{ i === 1 ? 'المنتجات جودتها ممتازة جداً وتغليف الهدايا راقٍ ومميز، المصاحف والمنظمات ألوانها رائعة وتجعلها هدية مميزة للأحباب.' : (i === 2 ? 'خدمة سريعة وتوصيل ممتاز، والمنظمات الورقية تساعدني جداً في ترتيب مهامي اليومية.' : 'متجر متكامل وتصميماته تنبض بالأناقة والدفء، سأكرر التجربة بالتأكيد.') }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- 6. FAQ Section -->
      <section v-else-if="section.type === 'faq'" class="container mx-auto px-4 md:px-8">
        <div class="text-center max-w-xl mx-auto mb-14 space-y-3">
          <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
          <h2 class="text-3xl md:text-5xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
          <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm leading-relaxed">{{ section.description }}</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
          <div 
            v-for="i in 3" 
            :key="i"
            class="bg-white dark:bg-wisal-charcoal/30 border border-wisal-beige/10 dark:border-gray-800/80 rounded-2xl overflow-hidden shadow-premium-sm"
          >
            <details class="group p-6 cursor-pointer">
              <summary class="font-extrabold text-sm md:text-base flex items-center justify-between list-none text-wisal-charcoal dark:text-wisal-ivory">
                <span>
                  {{ i === 1 ? 'ما هي مدة توصيل الطلبات؟' : (i === 2 ? 'هل يمكنني إرفاق بطاقة إهداء مع الطلب؟' : 'ما هي طرق الدفع المتاحة؟') }}
                </span>
                <span class="transition-transform group-open:rotate-180 text-wisal-aqua bg-wisal-aqua/5 dark:bg-gray-800 p-2 rounded-xl">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </summary>
              <p class="text-gray-400 dark:text-gray-500 text-xs md:text-sm mt-4 pt-4 border-t border-wisal-beige/10 dark:border-gray-800 leading-relaxed font-semibold">
                {{ i === 1 ? 'يتم توصيل الطلبات داخل الفلسطين خلال 24-48 ساعة، وباقي مدن فلسطين خلال 3-5 أيام عمل.' : (i === 2 ? 'نعم بالتأكيد، يمكنك كتابة رسالة مخصصة وتحديد تغليف الهدية عند إتمام الطلب وسنقوم بإرفاق بطاقة مكتوبة بخط فاخر.' : 'نوفر طرق دفع متعددة تشمل بطاقة مدى، فيزا، ماستركارد، Apple Pay، والتحويل البنكي.') }}
              </p>
            </details>
          </div>
        </div>
      </section>

      <!-- 7. Hero Slider Section (In-loop placement) -->
      <section v-else-if="section.type === 'hero_section' && sliders.length > 0" class="w-full">
        <HeroSlider :sliders="sliders" />
      </section>

      <!-- 8. Newsletter Subscription Section -->
      <section v-else-if="section.type === 'newsletter'" class="bg-wisal-charcoal dark:bg-wisal-charcoal/40 text-white py-16 rounded-[40px] container mx-auto px-4 md:px-8 shadow-premium-lg border border-white/5 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,0.15),transparent)]"></div>
        <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
          <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/10 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
          <h2 class="text-3xl md:text-5xl font-black text-white">{{ section.title }}</h2>
          <p v-if="section.description" class="text-gray-300 text-sm md:text-base leading-relaxed max-w-lg mx-auto">{{ section.description }}</p>
          
          <form @submit.prevent="subscribeNewsletter" class="flex flex-col sm:flex-row items-center gap-4 max-w-md mx-auto pt-4">
            <input 
              type="email" 
              v-model="newsletterEmail" 
              required 
              :placeholder="activeLocale === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email address'" 
              class="w-full bg-white/10 border border-white/10 rounded-2xl px-5 py-4 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-wisal-aqua focus:ring-2 focus:ring-wisal-aqua/20 transition-all"
            />
            <button 
              type="submit" 
              class="w-full sm:w-auto bg-wisal-beige text-wisal-charcoal hover:bg-wisal-ivory px-8 py-4 rounded-2xl text-sm font-black transition-all shadow-lg shadow-wisal-beige/10 hover:shadow-wisal-ivory/10 hover:-translate-y-0.5 whitespace-nowrap"
            >
              {{ activeLocale === 'ar' ? 'اشترك الآن' : 'Subscribe' }}
            </button>
          </form>
        </div>
      </section>

      <!-- 9. Partners Section -->
      <section v-else-if="section.type === 'partners'" class="container mx-auto px-4 md:px-8 py-10">
        <div class="text-center max-w-xl mx-auto mb-12 space-y-3">
          <span v-if="section.subtitle" class="text-wisal-aqua font-black text-xs uppercase tracking-widest bg-wisal-aqua/5 px-3.5 py-1.5 rounded-full">{{ section.subtitle }}</span>
          <h2 class="text-2xl md:text-4xl font-black text-wisal-charcoal dark:text-wisal-ivory">{{ section.title }}</h2>
          <p v-if="section.description" class="text-gray-400 dark:text-gray-500 text-sm leading-relaxed">{{ section.description }}</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-8 items-center justify-items-center opacity-65 dark:opacity-45">
          <div v-for="i in 6" :key="i" class="h-12 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-300">
            <span class="text-lg font-black text-gray-400 tracking-wider">PARTNER {{ i }}</span>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
