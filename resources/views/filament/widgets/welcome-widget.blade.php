<div class="fi-wi-welcome-banner hover:shadow-xl transition-all duration-300" dir="rtl">
    {{-- Top Row: greeting + date --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-white/70">{{ $date }}</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                {{ $greeting }}، {{ $userName }} 👋
            </h1>
            <p class="mt-1 text-white/80 text-sm">مرحباً بك في لوحة تحكم متجر وِصال</p>
        </div>

        <div class="flex items-center gap-2 bg-white/10 rounded-xl px-4 py-2 border border-white/20 backdrop-blur-md">
            <x-heroicon-o-sparkles class="w-5 h-5 text-yellow-300 animate-pulse" />
            <span class="text-sm font-semibold text-white">متجر وِصال</span>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Today Revenue --}}
        <div class="kpi-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-white/80">إيرادات اليوم</span>
                <x-heroicon-o-banknotes class="w-5 h-5 text-white/90" />
            </div>
            <p class="text-xl sm:text-2xl font-black text-white">{{ $todayRevenue }} <span class="text-sm font-normal text-white/80">ر.س</span></p>
        </div>

        {{-- Today's Orders --}}
        <div class="kpi-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-white/80">طلبات اليوم</span>
                <x-heroicon-o-shopping-cart class="w-5 h-5 text-white/90" />
            </div>
            <p class="text-xl sm:text-2xl font-black text-white">{{ $todayOrders }} <span class="text-sm font-normal text-white/80">طلب</span></p>
        </div>

        {{-- Pending Orders --}}
        <div class="kpi-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-white/80">طلبات معلّقة</span>
                <x-heroicon-o-clock class="w-5 h-5 text-white/90" />
            </div>
            <p class="text-xl sm:text-2xl font-black text-white">
                {{ $pendingOrders }}
                @if($pendingOrders > 0)
                <span class="mr-2 inline-flex items-center rounded-full bg-yellow-400/30 px-2 py-0.5 text-xs font-bold text-yellow-250 animate-pulse">مراجعة</span>
                @endif
            </p>
        </div>
    </div>
</div>