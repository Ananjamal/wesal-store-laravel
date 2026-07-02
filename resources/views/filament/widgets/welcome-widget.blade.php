<div class="fi-wi-welcome-banner" dir="rtl">

    {{-- Background decorative element --}}
    <div style="position:absolute;top:-60px;right:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,0.06);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:5%;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>

    {{-- Header Row --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;position:relative;z-index:1;">
        <div>
            <p style="font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.65);letter-spacing:0.05em;margin-bottom:0.25rem;">
                {{ $date }}
            </p>
            <h1 style="font-size:1.875rem;font-weight:900;color:#ffffff;margin:0;line-height:1.2;">
                {{ $greeting }}، {{ $userName }} 👋
            </h1>
            <p style="font-size:0.875rem;color:rgba(255,255,255,0.75);margin-top:0.375rem;">
                مرحباً بك في لوحة تحكم متجر وِصال
            </p>
        </div>

        {{-- Brand badge --}}
        <div style="display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:0.875rem;padding:0.625rem 1rem;backdrop-filter:blur(8px);">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:#fde68a;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z" />
            </svg>
            <span style="font-size:0.875rem;font-weight:700;color:#ffffff;">متجر وِصال</span>
        </div>
    </div>

    {{-- KPI Grid --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.5rem;position:relative;z-index:1;">

        {{-- Revenue --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                <span style="font-size:0.75rem;font-weight:500;color:rgba(255,255,255,0.75);">إيرادات اليوم</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p style="font-size:1.5rem;font-weight:900;color:#ffffff;margin:0;">
                {{ $todayRevenue }}
                <span style="font-size:0.875rem;font-weight:400;color:rgba(255,255,255,0.75);"> ر.س</span>
            </p>
        </div>

        {{-- Today Orders --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                <span style="font-size:0.75rem;font-weight:500;color:rgba(255,255,255,0.75);">طلبات اليوم</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p style="font-size:1.5rem;font-weight:900;color:#ffffff;margin:0;">
                {{ $todayOrders }}
                <span style="font-size:0.875rem;font-weight:400;color:rgba(255,255,255,0.75);"> طلب</span>
            </p>
        </div>

        {{-- Pending --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
                <span style="font-size:0.75rem;font-weight:500;color:rgba(255,255,255,0.75);">طلبات معلّقة</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.8);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="display:flex;align-items:center;gap:0.625rem;">
                <p style="font-size:1.5rem;font-weight:900;color:#ffffff;margin:0;">{{ $pendingOrders }}</p>
                @if($pendingOrders > 0)
                <span style="font-size:0.7rem;font-weight:700;background:rgba(253,230,138,0.25);color:#fde68a;border:1px solid rgba(253,230,138,0.35);border-radius:9999px;padding:0.125rem 0.625rem;">تحتاج مراجعة</span>
                @endif
            </div>
        </div>

    </div>
</div>