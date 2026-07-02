{{-- The x-filament-widgets::widget wrapper MUST be the root element for Filament to apply the grid col-span --}}
<x-filament-widgets::widget class="fi-wi-custom-welcome" dir="rtl">

    <div style="
    width:100%;
    box-sizing:border-box;
    padding:2rem 2.5rem;
    border-radius:1.25rem;
    background:linear-gradient(135deg,#467389 0%,#2a4e62 60%,#1d3a4a 100%);
    color:#fff;
    box-shadow:0 8px 30px rgba(70,115,137,0.3);
    position:relative;
    overflow:hidden;
">
        {{-- Decorative circles --}}
        <div style="position:absolute;top:-80px;right:-80px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-100px;left:5%;width:350px;height:350px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>

        {{-- ─── Header Row ─── --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;position:relative;z-index:1;">

            <div>
                <p style="font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.6);letter-spacing:0.05em;margin:0 0 0.25rem;">
                    {{ $date }}
                </p>
                <h1 style="font-size:1.875rem;font-weight:900;color:#fff;margin:0;line-height:1.1;">
                    {{ $greeting }}، {{ $userName }} 👋
                </h1>
                <p style="font-size:0.9rem;color:rgba(255,255,255,0.7);margin:0.375rem 0 0;">
                    مرحباً بك في لوحة تحكم متجر وِصال
                </p>
            </div>

            {{-- Brand badge --}}
            <div style="display:flex;align-items:center;gap:0.75rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:1rem;padding:0.75rem 1.25rem;backdrop-filter:blur(8px);">
                <div style="width:40px;height:40px;border-radius:10px;overflow:hidden;border:1.5px solid rgba(209,203,180,0.5);flex-shrink:0;">
                    <img src="{{ asset('images/logo.jpg') }}" alt="وِصال" style="width:100%;height:100%;object-fit:cover;" />
                </div>
                <div>
                    <p style="font-size:0.875rem;font-weight:800;color:#fff;margin:0;line-height:1;">متجر وِصال</p>
                    <p style="font-size:0.7rem;color:rgba(209,203,180,0.75);margin:0;letter-spacing:0.08em;">WISAL STORE</p>
                </div>
            </div>
        </div>

        {{-- ─── KPI Grid ─── --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.75rem;position:relative;z-index:1;">

            {{-- Revenue --}}
            <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);border-radius:1rem;padding:1.25rem;transition:background 0.2s,transform 0.15s;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.625rem;">
                    <span style="font-size:0.8rem;font-weight:600;color:rgba(255,255,255,0.7);">إيرادات اليوم</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.75);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p style="font-size:1.625rem;font-weight:900;color:#fff;margin:0;line-height:1;">
                    {{ $todayRevenue }}<span style="font-size:0.8rem;font-weight:500;color:rgba(255,255,255,0.65);margin-right:0.25rem;">ر.س</span>
                </p>
            </div>

            {{-- Today Orders --}}
            <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);border-radius:1rem;padding:1.25rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.625rem;">
                    <span style="font-size:0.8rem;font-weight:600;color:rgba(255,255,255,0.7);">طلبات اليوم</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.75);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p style="font-size:1.625rem;font-weight:900;color:#fff;margin:0;line-height:1;">
                    {{ $todayOrders }}<span style="font-size:0.8rem;font-weight:500;color:rgba(255,255,255,0.65);margin-right:0.25rem;">طلب</span>
                </p>
            </div>

            {{-- Pending --}}
            <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);border-radius:1rem;padding:1.25rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.625rem;">
                    <span style="font-size:0.8rem;font-weight:600;color:rgba(255,255,255,0.7);">طلبات معلّقة</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:rgba(255,255,255,0.75);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div style="display:flex;align-items:center;gap:0.625rem;">
                    <p style="font-size:1.625rem;font-weight:900;color:#fff;margin:0;line-height:1;">{{ $pendingOrders }}</p>
                    @if($pendingOrders > 0)
                    <span style="font-size:0.68rem;font-weight:700;background:rgba(253,230,138,0.2);color:#fde68a;border:1px solid rgba(253,230,138,0.35);border-radius:9999px;padding:0.15rem 0.6rem;white-space:nowrap;">تحتاج مراجعة</span>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-filament-widgets::widget>