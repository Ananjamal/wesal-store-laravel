<x-filament-widgets::widget>
    <div class="fi-wi-welcome p-6 rounded-2xl bg-gradient-to-l from-[#467389] to-[#2f5468] text-white shadow-lg" dir="rtl">
        {{-- Top Row: greeting + date --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-white/70">{{ $date }}</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-bold tracking-tight">
                    {{ $greeting }}، {{ $userName }} 👋
                </h1>
                <p class="mt-1 text-white/80 text-sm">مرحباً بك في لوحة تحكم متجر وِصال</p>
            </div>

            <div class="flex items-center gap-2 bg-white/15 rounded-xl px-4 py-2 backdrop-blur-sm">
                <x-heroicon-o-sparkles class="w-5 h-5 text-yellow-300" />
                <span class="text-sm font-semibold">متجر وِصال</span>
            </div>
        </div>

        {{-- KPI cards --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Today Revenue --}}
            <div class="rounded-xl bg-white/10 border border-white/20 p-4 backdrop-blur-sm hover:bg-white/20 transition-colors duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-white/70">إيرادات اليوم</span>
                    <x-heroicon-o-banknotes class="w-5 h-5 text-green-300" />
                </div>
                <p class="text-xl font-bold">{{ $todayRevenue }} <span class="text-sm font-normal text-white/70">ر.س</span></p>
            </div>

            {{-- Today's Orders --}}
            <div class="rounded-xl bg-white/10 border border-white/20 p-4 backdrop-blur-sm hover:bg-white/20 transition-colors duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-white/70">طلبات اليوم</span>
                    <x-heroicon-o-shopping-cart class="w-5 h-5 text-blue-300" />
                </div>
                <p class="text-xl font-bold">{{ $todayOrders }} <span class="text-sm font-normal text-white/70">طلب</span></p>
            </div>

            {{-- Pending Orders --}}
            <div class="rounded-xl bg-white/10 border border-white/20 p-4 backdrop-blur-sm hover:bg-white/20 transition-colors duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-white/70">طلبات معلّقة</span>
                    <x-heroicon-o-clock class="w-5 h-5 text-yellow-300" />
                </div>
                <p class="text-xl font-bold">
                    {{ $pendingOrders }}
                    @if($pendingOrders > 0)
                    <span class="mr-1 inline-flex items-center rounded-full bg-yellow-400/30 px-2 py-0.5 text-xs font-semibold text-yellow-200">تحتاج مراجعة</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>