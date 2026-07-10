@php
    $logo = \App\Models\StoreSetting::getValue('store_logo');
    $storeName = \App\Models\StoreSetting::getValue('store_name', 'وِصال');
@endphp
<div class="flex items-center gap-3 py-1">
    <div class="relative flex h-11 w-11 shrink-0 overflow-hidden rounded-xl border border-[#D1CBB4] bg-white shadow-sm transition hover:scale-105">
        <img src="{{ $logo ? asset('storage/' . $logo) : asset('images/logo.jpg') }}" alt="Logo" class="h-full w-full object-cover">
    </div>
    <div class="flex flex-col">
        <span class="text-base font-bold tracking-wide text-gray-900 dark:text-white">{{ $storeName }}</span>
        <span class="text-[10px] uppercase tracking-wider text-[#467389] font-medium leading-none">لوحة التحكم</span>
    </div>
</div>