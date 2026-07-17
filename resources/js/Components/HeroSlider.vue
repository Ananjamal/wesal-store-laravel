<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useLinkFormatter } from '../composables/useLinkFormatter'

const { formatLink, isExternal } = useLinkFormatter()

interface Slide {
  id: number
  title: string
  subtitle?: string
  description?: string
  image: string
  button_text?: string
  button_link?: string
}

const props = defineProps<{
  sliders: Slide[]
}>()

const activeIndex = ref(0)
let timer: any = null

function startTimer() {
  if (props.sliders.length > 1) {
    timer = setInterval(nextSlide, 6000)
  }
}

function stopTimer() {
  if (timer) clearInterval(timer)
}

function nextSlide() {
  activeIndex.value = (activeIndex.value + 1) % props.sliders.length
}

function prevSlide() {
  activeIndex.value = (activeIndex.value - 1 + props.sliders.length) % props.sliders.length
}

function goToSlide(index: number) {
  activeIndex.value = index
  stopTimer()
  startTimer()
}

onMounted(() => {
  startTimer()
})

onUnmounted(() => {
  stopTimer()
})
</script>

<template>
  <div 
    v-if="sliders && sliders.length > 0" 
    class="relative w-full h-[450px] md:h-[600px] bg-wisal-charcoal overflow-hidden group"
    @mouseenter="stopTimer"
    @mouseleave="startTimer"
  >
    <!-- Slides -->
    <div class="relative w-full h-full">
      <div 
        v-for="(slide, index) in sliders" 
        :key="slide.id"
        v-show="index === activeIndex"
        class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
      >
        <!-- Background Image with Overlay Gradient -->
        <div class="absolute inset-0 z-0">
          <img 
            :src="slide.image" 
            :alt="slide.title" 
            class="w-full h-full object-cover transform scale-105 group-hover:scale-100 transition-transform duration-[6000ms] ease-out" 
          />
          <div class="absolute inset-0 bg-gradient-to-t from-wisal-charcoal via-wisal-charcoal/50 to-transparent"></div>
          <div class="absolute inset-0 bg-gradient-to-r from-wisal-charcoal/40 via-transparent to-wisal-charcoal/40"></div>
        </div>

        <!-- Content Overlay -->
        <div class="absolute inset-0 z-10 flex items-center">
          <div class="container mx-auto px-4 md:px-8 text-wisal-ivory max-w-4xl space-y-6">
            <span 
              v-if="slide.subtitle"
              class="inline-block bg-wisal-aqua/90 text-wisal-ivory text-xs md:text-sm font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg transform translate-y-4 animate-fade-in"
            >
              {{ slide.subtitle }}
            </span>
            <h1 
              class="text-3xl md:text-6xl font-extrabold leading-tight tracking-wide drop-shadow-md text-white"
            >
              {{ slide.title }}
            </h1>
            <p 
              v-if="slide.description"
              class="text-sm md:text-lg text-gray-200 font-light max-w-2xl line-clamp-3 leading-relaxed"
            >
              {{ slide.description }}
            </p>
            <div v-if="slide.button_text && slide.button_link" class="pt-4">
              <a 
                v-if="isExternal(slide.button_link)"
                :href="formatLink(slide.button_link)"
                target="_blank"
                class="inline-flex items-center gap-2 bg-wisal-beige text-wisal-charcoal hover:bg-wisal-ivory px-6 py-3 rounded-xl font-bold shadow-2xl hover:shadow-wisal-beige/30 transition-all duration-300 hover:-translate-y-1"
              >
                <span>{{ slide.button_text }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </a>
              <Link 
                v-else
                :href="formatLink(slide.button_link)" 
                class="inline-flex items-center gap-2 bg-wisal-beige text-wisal-charcoal hover:bg-wisal-ivory px-6 py-3 rounded-xl font-bold shadow-2xl hover:shadow-wisal-beige/30 transition-all duration-300 hover:-translate-y-1"
              >
                <span>{{ slide.button_text }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Arrows -->
    <button 
      v-if="sliders.length > 1"
      @click="prevSlide"
      class="absolute top-1/2 left-4 z-20 -translate-y-1/2 bg-white/20 hover:bg-white/40 dark:bg-black/20 dark:hover:bg-black/40 text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-md"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 rotate-180 rtl:rotate-0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
      </svg>
    </button>
    <button 
      v-if="sliders.length > 1"
      @click="nextSlide"
      class="absolute top-1/2 right-4 z-20 -translate-y-1/2 bg-white/20 hover:bg-white/40 dark:bg-black/20 dark:hover:bg-black/40 text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-md"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
      </svg>
    </button>

    <!-- Slide Indicators -->
    <div 
      v-if="sliders.length > 1"
      class="absolute bottom-6 left-1/2 z-20 -translate-x-1/2 flex gap-2.5"
    >
      <button 
        v-for="(_, index) in sliders" 
        :key="index"
        @click="goToSlide(index)"
        :class="[
          'h-2.5 rounded-full transition-all duration-300',
          index === activeIndex ? 'w-8 bg-wisal-beige' : 'w-2.5 bg-white/40 hover:bg-white/75'
        ]"
      ></button>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in {
  animation: fadeInUp 0.8s forwards;
}
</style>
