<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card 1: Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('reports.total_revenue') }}</span>
                    <h3 class="text-3xl font-bold mt-2 text-[#467389]">{{ $totalRevenue }} <span class="text-sm font-normal">{{ $currencySymbol }}</span></h3>
                </div>
                <div class="p-3 bg-[#467389]/10 rounded-xl text-[#467389]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-[#467389]"></div>
        </div>

        <!-- Card 2: Total Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition duration-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('reports.total_orders') }}</span>
                    <h3 class="text-3xl font-bold mt-2 text-gray-800 dark:text-white">{{ $totalOrdersCount }} <span class="text-sm font-normal">{{ __('reports.order_unit') }}</span></h3>
                </div>
                <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-500 dark:text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gray-400 dark:bg-gray-600"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Orders by Status Widget -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-1">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">{{ __('reports.status_heading') }}</h4>
            <div class="space-y-4">
                @foreach ($statusCounts as $label => $count)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</span>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:bg-gray-200">
                        {{ $count }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top 5 Selling Products -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 lg:col-span-2">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">{{ __('reports.top_selling_products') }}</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                            <th class="pb-3 pt-1 font-medium">{{ __('reports.product') }}</th>
                            <th class="pb-3 pt-1 font-medium">{{ __('reports.price') }}</th>
                            <th class="pb-3 pt-1 font-medium text-left">{{ __('reports.quantity_sold') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($topProducts as $item)
                        <tr class="text-sm">
                            <td class="py-3.5 font-medium text-gray-800 dark:text-gray-200">
                                {{ $item->product?->name ?? __('reports.unknown_product') }}
                            </td>
                            <td class="py-3.5 text-gray-600 dark:text-gray-400">
                                @if($item->product)
                                {{ $item->product->formatted_price }}
                                @else
                                -
                                @endif
                            </td>
                            <td class="py-3.5 text-left font-bold text-[#467389]">
                                {{ $item->total_quantity }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-400">{{ __('reports.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 mt-6">
        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">{{ __('reports.recent_orders') }}</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                        <th class="pb-3 pt-1 font-medium">{{ __('reports.order_number') }}</th>
                        <th class="pb-3 pt-1 font-medium">{{ __('reports.customer') }}</th>
                        <th class="pb-3 pt-1 font-medium">{{ __('reports.total') }}</th>
                        <th class="pb-3 pt-1 font-medium">{{ __('reports.status') }}</th>
                        <th class="pb-3 pt-1 font-medium text-left">{{ __('reports.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($recentOrders as $order)
                    <tr class="text-sm">
                        <td class="py-3.5 font-bold text-gray-800 dark:text-gray-200">
                            {{ $order->order_number ?? '#' . $order->id }}
                        </td>
                        <td class="py-3.5 text-gray-600 dark:text-gray-300">
                            {{ $order->user?->name ?? __('reports.unknown') }}
                        </td>
                        <td class="py-3.5 text-gray-600 dark:text-gray-400">
                            {{ $order->formatted_total }}
                        </td>
                        <td class="py-3.5">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full">
                                {{ $order->status instanceof \App\Enums\OrderStatus ? $order->status->getLabel() : $order->status }}
                            </span>
                        </td>
                        <td class="py-3.5 text-left text-gray-400">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-400">{{ __('reports.no_orders') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>