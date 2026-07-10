@php
    $record = $getRecord();
    $record->loadMissing(['user', 'address', 'shippingMethod', 'items.product.images']);
    $currencyService = app(\App\Services\CurrencyService::class);
@endphp

<div class="space-y-6 text-right" dir="rtl">
    <!-- Invoice Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-50 dark:bg-gray-800/40 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400">
                    طلب رقم
                </span>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white select-all">
                    {{ $record->order_number }}
                </h2>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                تاريخ الطلب: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $record->created_at->format('Y-m-d H:i') }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Status Badge -->
            @php
                $statusColor = match ($record->status->value) {
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                    'processing' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                    'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                    'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                    default => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-500/10 dark:text-gray-400 dark:border-gray-500/20',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                {{ $record->status->getLabel() ?? $record->status->value }}
            </span>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Info Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">العميل</h3>
            <div class="space-y-1">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $record->user?->name ?? 'عميل غير معروف' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $record->user?->email }}</p>
                @if($record->address?->phone)
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $record->address?->phone }}</p>
                @endif
            </div>
        </div>

        <!-- Shipping Info Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">الشحن والتوصيل</h3>
            <div class="space-y-1">
                @if($record->shippingMethod)
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $record->shippingMethod->name }} ({{ $record->shippingMethod->carrier }})
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        وقت التوصيل المقدر: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $record->shippingMethod->estimated_delivery_days }}</span>
                    </p>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">لم يتم اختيار طريقة الشحن</p>
                @endif
            </div>
        </div>

        <!-- Address Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm space-y-3">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">عنوان التوصيل</h3>
            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                @if($record->address)
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $record->address->first_name }} {{ $record->address->last_name }}
                    </p>
                    <p>{{ $record->address->address_line1 }}</p>
                    @if($record->address->address_line2)
                        <p>{{ $record->address->address_line2 }}</p>
                    @endif
                    <p>{{ $record->address->city }}، {{ $record->address->state }}، {{ $record->address->country }}</p>
                    @if($record->address->postal_code)
                        <p class="font-mono">الرمز البريدي: {{ $record->address->postal_code }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">لا يوجد عنوان شحن مسجل</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table Container -->
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">المنتجات المطلوبة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-right">
                <thead class="bg-gray-50/50 dark:bg-gray-800/30">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">الصورة</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">المنتج</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">الكمية</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">سعر الوحدة</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($record->items as $item)
                        @php
                            $image = $item->product?->images->first()?->image_path;
                            $imageUrl = $image ? asset('storage/' . $image) : asset('images/logo.jpg');
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <img src="{{ $imageUrl }}" class="h-12 w-12 rounded-lg object-cover border border-gray-100 dark:border-gray-800 bg-white shadow-sm" />
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-950 dark:text-white">
                                {{ $item->product?->name ?? 'منتج غير معروف' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-white">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-600 dark:text-gray-300 font-mono">
                                {{ $currencyService->format($item->price_cents) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-gray-950 dark:text-white font-mono">
                                {{ $currencyService->format($item->price_cents * $item->quantity) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/50 dark:bg-gray-800/20 divide-y divide-gray-100 dark:divide-gray-800/80">
                    <!-- Subtotal -->
                    <tr>
                        <td colspan="3"></td>
                        <td class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">المجموع الفرعي:</td>
                        <td class="px-6 py-2.5 text-left text-sm font-bold text-gray-900 dark:text-white font-mono">
                            {{ $currencyService->format($record->subtotal_cents) }}
                        </td>
                    </tr>
                    <!-- Shipping -->
                    @if($record->shipping_cents > 0)
                        <tr>
                            <td colspan="3"></td>
                            <td class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">تكلفة الشحن:</td>
                            <td class="px-6 py-2.5 text-left text-sm font-bold text-gray-900 dark:text-white font-mono">
                                {{ $currencyService->format($record->shipping_cents) }}
                            </td>
                        </tr>
                    @endif
                    <!-- Tax -->
                    @if($record->tax_cents > 0)
                        <tr>
                            <td colspan="3"></td>
                            <td class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">الضريبة:</td>
                            <td class="px-6 py-2.5 text-left text-sm font-bold text-gray-900 dark:text-white font-mono">
                                {{ $currencyService->format($record->tax_cents) }}
                            </td>
                        </tr>
                    @endif
                    <!-- Discount -->
                    @if($record->discount_cents > 0)
                        <tr>
                            <td colspan="3"></td>
                            <td class="px-6 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">الخصم:</td>
                            <td class="px-6 py-2.5 text-left text-sm font-bold text-danger-600 dark:text-danger-400 font-mono">
                                - {{ $currencyService->format($record->discount_cents) }}
                            </td>
                        </tr>
                    @endif
                    <!-- Total -->
                    <tr class="bg-gray-100/40 dark:bg-gray-800/60">
                        <td colspan="3"></td>
                        <td class="px-6 py-3.5 text-left text-sm font-bold text-gray-900 dark:text-white border-t border-gray-200 dark:border-gray-700">الإجمالي الكلي:</td>
                        <td class="px-6 py-3.5 text-left text-base font-black text-emerald-600 dark:text-emerald-400 font-mono border-t border-gray-200 dark:border-gray-700">
                            {{ $currencyService->format($record->total_cents) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Notes Card -->
    @if($record->notes)
        <div class="bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10 p-5 rounded-2xl shadow-sm space-y-2">
            <h3 class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase tracking-wider">ملاحظات على الطلب</h3>
            <p class="text-sm text-amber-900 dark:text-amber-300 leading-relaxed">{{ $record->notes }}</p>
        </div>
    @endif
</div>
