<script setup lang="ts">
// Page Title
useHead({
  title: 'وِصال - الصفحة الرئيسية',
})
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="relative bg-wisal-beige/20 py-20 overflow-hidden">
      <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h1 class="text-4xl md:text-6xl font-bold text-wisal-charcoal leading-tight mb-6">
            {{ $t('home.hero_title') }}
          </h1>
          <p class="text-lg text-gray-600 mb-8 max-w-lg">
            {{ $t('home.hero_subtitle') }}
          </p>
          <div class="flex gap-4">
            <WButton size="lg">{{ $t('home.shop_now') }}</WButton>
            <WButton variant="secondary" size="lg">{{ $t('home.explore_offers') }}</WButton>
          </div>
        </div>
        <div class="relative">
          <div class="aspect-square bg-wisal-beige rounded-full absolute -inset-4 opacity-50 blur-3xl"></div>
          <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hero" class="relative rounded-2xl shadow-2xl object-cover h-[500px] w-full" />
        </div>
      </div>
    </section>

    <!-- Featured Products Placeholder -->
    <section class="py-20 container mx-auto px-4">
      <div class="flex justify-between items-end mb-10">
        <div>
          <h2 class="text-3xl font-bold text-wisal-charcoal">{{ $t('home.featured_products') }}</h2>
          <p class="text-gray-500 mt-2">{{ $t('home.featured_desc') }}</p>
        </div>
        <WButton variant="ghost">{{ $t('home.view_all') }}</WButton>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <WCard v-for="i in 4" :key="i" padding="none" class="group cursor-pointer">
          <div class="aspect-square bg-gray-100 relative overflow-hidden">
            <img :src="`https://picsum.photos/seed/${i + 10}/400/400`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300" />
            <div class="absolute top-2 right-2">
              <WBadge variant="success">جديد</WBadge>
            </div>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-lg mb-1">منتج تجريبي {{ i }}</h3>
            <p class="text-gray-500 text-sm mb-3">وصف قصير للمنتج يظهر هنا</p>
            <div class="flex justify-between items-center">
              <span class="font-bold text-wisal-aqua">150.00 ر.س</span>
              <WButton size="sm" variant="secondary">إضافة</WButton>
            </div>
          </div>
        </WCard>
      </div>
    </section>
  </div>
</template>
