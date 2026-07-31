<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فاتورة مبيعات #{{ $order->order_number }} - متجر وِصال</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 28px 36px;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Cairo', 'dejavu sans', sans-serif;
            background-color: #FFFFFF;
            color: #2C3E50;
            margin: 0;
            padding: 10px;
            direction: ltr;
            font-size: 11px;
            line-height: 1.6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Print Media Styles */
        @media print {
            body {
                padding: 0;
                background-color: #FFFFFF;
            }
            .no-print {
                display: none !important;
            }
        }

        /* ====== HEADER: Unified Panel Design ====== */
        .header-panel {
            width: 100%;
            background-color: #F3F7FA;
            border-radius: 10px;
            border: 1px solid #D8E8EF;
            border-top: 4px solid #467389;
            margin-bottom: 24px;
            border-spacing: 0;
        }
        .hdr-brand-cell {
            width: 38%;
            background-color: #467389;
            border-radius: 0 9px 9px 0;
            text-align: center;
            vertical-align: middle;
            padding: 14px 16px;
        }
        .hdr-info-cell {
            width: 62%;
            vertical-align: middle;
            padding: 14px 18px;
            text-align: left;
        }
        .brand-logo-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 6px auto;
        }
        .brand-fallback-title {
            color: #FFFFFF;
            font-size: 22px;
            font-weight: 900;
            margin: 0 0 4px 0;
            text-align: center;
        }
        .brand-slogan {
            color: rgba(255,255,255,0.75);
            font-size: 9px;
            font-weight: 500;
            text-align: center;
            display: block;
        }
        .hdr-label {
            color: #467389;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .hdr-invoice-num {
            color: #1A2E3B;
            font-size: 17px;
            font-weight: 900;
            margin: 0 0 10px 0;
            line-height: 1;
        }
        /* Meta chips row */
        .hdr-chips-table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .hdr-chip {
            background-color: #FFFFFF;
            border: 1px solid #D8E8EF;
            border-radius: 6px;
            padding: 4px 10px;
            margin: 0 6px 0 0;
        }
        .hdr-chip-label {
            font-size: 7px;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            display: block;
            margin-bottom: 2px;
        }
        .hdr-chip-value {
            font-size: 10px;
            font-weight: 800;
            color: #2C3E50;
        }
        .hdr-chip-gap {
            width: 8px;
        }

        /* Section Titles */
        .section-label {
            color: #467389;
            font-size: 12.5px;
            font-weight: 800;
            margin-bottom: 10px;
            border-bottom: 1px solid #E2E8F0;
            padding-bottom: 4px;
            text-align: right;
        }

        /* 2-Column Grid Cards */
        .cards-table {
            width: 100%;
            margin-bottom: 26px;
            border-spacing: 0;
        }
        .card-box {
            background-color: #FAFBFD;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .card-title {
            color: #467389;
            font-size: 12px;
            font-weight: 800;
            border-bottom: 1px solid #E2E8F0;
            padding-bottom: 6px;
            margin-bottom: 8px;
            text-align: right;
        }
        .kv-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kv-table td {
            padding: 6px 0;
            font-size: 10.5px;
            vertical-align: middle;
            height: 24px;
        }
        .kv-label {
            text-align: right;
            color: #64748B;
            font-weight: 600;
            width: 40%;
        }
        .kv-value {
            text-align: left;
            color: #2C3E50;
            font-weight: 700;
            width: 60%;
        }

        /* Sleek Inline Status Badges */
        .status-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 700;
            line-height: 1.3;
        }
        .status-paid {
            background-color: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #C8E6C9;
        }
        .status-pending {
            background-color: #FFF8E1;
            color: #D97706;
            border: 1px solid #FFE082;
        }

        /* Products Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 26px;
        }
        .items-table th {
            background-color: #F4F6F8;
            color: #467389;
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 800;
            border-bottom: 2px solid #467389;
            border-top: 1px solid #E2E8F0;
        }
        .items-table td {
            border-bottom: 1px solid #E2E8F0;
            padding: 11px 12px;
            font-size: 11px;
            color: #2C3E50;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) td {
            background-color: #FAFBFD;
        }
        .item-name {
            font-weight: 800;
            font-size: 11px;
            color: #2C3E50;
        }
        .item-spec {
            font-size: 9.5px;
            color: #64748B;
            margin-top: 2px;
            font-weight: 600;
        }

        /* Financial Summary Container */
        .summary-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-card {
            width: 46%;
            float: left;
            background-color: #FAFBFD;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            overflow: hidden;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 7px 12px;
            font-size: 11px;
            vertical-align: middle;
        }
        .summary-label {
            text-align: right;
            color: #64748B;
            font-weight: 600;
        }
        .summary-value {
            text-align: left;
            font-weight: 800;
            color: #2C3E50;
        }
        .summary-row-discount .summary-label,
        .summary-row-discount .summary-value {
            color: #DC2626;
        }
        .summary-row-grand td {
            background-color: #EBF4F7;
            border-top: 2px solid #467389;
            padding: 9px 12px;
        }
        .summary-row-grand .summary-label {
            font-size: 12.5px;
            font-weight: 900;
            color: #467389;
            text-align: right;
        }
        .summary-row-grand .summary-value {
            font-size: 15.5px;
            font-weight: 900;
            color: #467389;
            text-align: left;
        }

        /* Footer Container */
        .footer-container {
            clear: both;
            margin-top: 40px;
            text-align: center;
            font-size: 10.5px;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
            padding-top: 16px;
            line-height: 1.6;
        }
        .footer-thankyou {
            font-weight: 800;
            color: #467389;
            font-size: 11.5px;
            margin-bottom: 3px;
        }
        .footer-contacts {
            color: #64748B;
            font-weight: 600;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- ======= HEADER: 3-Column (Number | Logo | Date) ======= -->
    <table class="header-panel" style="border-spacing:0;">
        <tr>
            <!-- LEFT: Invoice type label + order number -->
            <td style="width:33%; vertical-align:middle; padding:16px 20px; text-align:left;">
                <div class="hdr-label" style="margin-bottom:6px;">{{ $ar('فاتورة مبيعات') }}</div>
                <div class="hdr-invoice-num">#{{ $order->order_number }}</div>
            </td>

            <!-- CENTER: Logo -->
            <td style="width:34%; vertical-align:middle; padding:16px 10px; text-align:center;">
                @if(!empty($brand['logo_data']))
                    <img src="{{ $brand['logo_data'] }}" class="brand-logo-img" alt="وِصال" />
                @else
                    <div style="color:#467389; font-size:22px; font-weight:900; text-align:center;">✦ {{ $ar('وِصال') }} ✦</div>
                @endif
                <div style="color:#94A3B8; font-size:9px; font-weight:500; text-align:center; margin-top:4px;">{{ $ar($brand['slogan']) }}</div>
            </td>

            <!-- RIGHT: Issue date -->
            <td style="width:33%; vertical-align:middle; padding:16px 20px; text-align:right;">
                <div style="font-size:8px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:5px;">{{ $ar('تاريخ الإصدار') }}</div>
                <div style="font-size:13px; font-weight:800; color:#2C3E50;">{{ $order->created_at->format('Y-m-d') }}</div>
            </td>
        </tr>
    </table>

    <!-- Customer & Payment Grid Cards -->
    <table class="cards-table">
        <tr>
            <!-- Left Card: Payment Info -->
            <td style="width: 48%; vertical-align: top;">
                <div class="card-box">
                    <div class="card-title">{{ $ar('تفاصيل الدفع والطلب') }}</div>
                    <table class="kv-table">
                        <tr>
                            <td class="kv-value">
                                @if(in_array(strtolower($order->payment_method), ['cash_on_delivery', 'cod']))
                                    {{ $ar('الدفع عند الاستلام') }}
                                @elseif(strtolower($order->payment_method) === 'credit_card')
                                    {{ $ar('بطاقة ائتمانية') }}
                                @elseif(strtolower($order->payment_method) === 'mada')
                                    {{ $ar('بطاقة مدى') }}
                                @elseif(strtolower($order->payment_method) === 'paypal')
                                    {{ $ar('بايبال') }}
                                @elseif(strtolower($order->payment_method) === 'palpay')
                                    {{ $ar('بال باي') }}
                                @else
                                    {{ strtoupper($order->payment_method) }}
                                @endif
                            </td>
                            <td class="kv-label">{{ $ar('طريقة الدفع:') }}</td>
                        </tr>
                        <tr>
                            <td class="kv-value">
                                @if($order->payment_status === 'paid')
                                    <span class="status-pill status-paid">{{ $ar('تم الدفع ✔') }}</span>
                                @elseif(in_array(strtolower($order->payment_method), ['cash_on_delivery', 'cod']))
                                    <span class="status-pill status-pending">{{ $ar('بانتظار التحصيل') }}</span>
                                @else
                                    <span class="status-pill status-pending">{{ $ar('بانتظار الدفع') }}</span>
                                @endif
                            </td>
                            <td class="kv-label">{{ $ar('حالة الدفع:') }}</td>
                        </tr>
                    </table>
                </div>
            </td>

            <td style="width: 4%;"></td>

            <!-- Right Card: Customer Info -->
            <td style="width: 48%; vertical-align: top;">
                <div class="card-box">
                    <div class="card-title">{{ $ar('معلومات العميل والتوصيل') }}</div>
                    <table class="kv-table">
                        <tr>
                            <td class="kv-value">{{ $ar($order->shipping_name) }}</td>
                            <td class="kv-label">{{ $ar('اسم العميل:') }}</td>
                        </tr>
                        <tr>
                            <td class="kv-value">{{ $order->shipping_phone }}</td>
                            <td class="kv-label">{{ $ar('رقم الهاتف:') }}</td>
                        </tr>
                        <tr>
                            <td class="kv-value">{{ $ar($order->shipping_city) }} - {{ $ar($order->shipping_address) }}</td>
                            <td class="kv-label">{{ $ar('العنوان والتوصيل:') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Products Section Title -->
    <div class="section-label">{{ $ar('تفاصيل الطلب والمنتجات') }}</div>

    <!-- Products Table (DOMPDF LTR Column Mapping for Seamless Visual RTL Order) -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 20%; text-align: left;">{{ $ar('الإجمالي') }}</th>
                <th style="width: 20%; text-align: left;">{{ $ar('سعر الوحدة') }}</th>
                <th style="width: 10%; text-align: center;">{{ $ar('الكمية') }}</th>
                <th style="width: 50%; text-align: right;">{{ $ar('المنتج والمواصفات') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="width: 20%; text-align: left; font-weight: 800; color: #467389;">
                    {{ number_format((($item->price_cents * $item->quantity) / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}
                </td>
                <td style="width: 20%; text-align: left;">
                    {{ number_format(($item->price_cents / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}
                </td>
                <td style="width: 10%; text-align: center; font-weight: 800;">
                    {{ $item->quantity }}
                </td>
                <td style="width: 50%; text-align: right; vertical-align: middle;">
                    <table style="width:100%; border-spacing:0; border-collapse:collapse;">
                        <tr>
                            <td style="vertical-align:middle; text-align:right; padding:0;">
                                <div class="item-name">{{ $item->product ? $ar($item->product->name) : '' }}</div>
                                @if($item->color || $item->size)
                                    <div class="item-spec">
                                        {{ $item->color ? $ar($item->color->name) : '' }} {{ $item->size ? '- ' . $ar($item->size->name) : '' }}
                                    </div>
                                @endif
                            </td>
                            @if($item->product && !empty($item->product->image_base64))
                            <td style="width:40px; vertical-align:middle; text-align:center; padding: 0 0 0 8px;">
                                <img src="{{ $item->product->image_base64 }}"
                                     style="width:36px; height:36px; border-radius:6px; object-fit:cover; border:1px solid #E2E8F0; display:block;"
                                     alt="" />
                            </td>
                            @endif
                        </tr>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Financial Summary Section -->
    <div class="summary-container">
        <div class="summary-card">
            <table class="summary-table">
                <tr>
                    <td class="summary-value">{{ number_format(($order->subtotal_cents / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}</td>
                    <td class="summary-label">{{ $ar('المجموع الفرعي:') }}</td>
                </tr>

                @if($order->discount_cents > 0)
                <tr class="summary-row-discount">
                    <td class="summary-value">- {{ number_format(($order->discount_cents / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}</td>
                    <td class="summary-label">{{ $ar('الخصم:') }}</td>
                </tr>
                @endif

                <tr>
                    <td class="summary-value">
                        @if($order->shipping_cents > 0)
                            {{ number_format(($order->shipping_cents / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}
                        @else
                            {{ $ar('مجاني') }}
                        @endif
                    </td>
                    <td class="summary-label">{{ $ar('رسوم الشحن:') }}</td>
                </tr>

                <tr class="summary-row-grand">
                    <td class="summary-value">{{ number_format(($order->total_cents / 100) * $order->exchange_rate, 2) }} {{ $order->currency_code }}</td>
                    <td class="summary-label">{{ $ar('الإجمالي الكلي:') }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Connected Footer -->
    <div class="footer-container">
        <div class="footer-thankyou">{{ $ar('شكراً لتسوقكم من متجر وِصال — يسعدنا خدمتكم دائماً!') }}</div>
        <div class="footer-contacts">support@wisal.store | www.wisal.store</div>
    </div>

</body>
</html>
