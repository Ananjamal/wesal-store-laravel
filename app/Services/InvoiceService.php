<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic;

class InvoiceService
{
    /**
     * Generate PDF invoice stream/download for an order.
     */
    public function generate(Order $order)
    {
        $order->load(['user', 'items.product.media', 'items.color', 'items.size', 'coupon']);

        if (!class_exists(\ArPHP\I18N\Arabic::class) && file_exists(base_path('vendor/khaled.alshamaa/ar-php/src/Arabic.php'))) {
            require_once base_path('vendor/khaled.alshamaa/ar-php/src/Arabic.php');
        }

        $arabic = new Arabic();
        $ar = function (?string $text) use ($arabic): string {
            if (empty($text)) return '';
            if (preg_match('/\p{Arabic}/u', $text)) {
                return $arabic->utf8Glyphs($text);
            }
            return $text;
        };

        $logoPath = public_path('images/logo.png');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Base64-encode each product's first image for embedding in PDF
        foreach ($order->items as $item) {
            if ($item->product) {
                $media = $item->product->getFirstMedia('product-images')
                      ?? $item->product->getFirstMedia('product-cover');

                if ($media) {
                    $path = $media->getPath();
                    if (file_exists($path)) {
                        $mime = $media->mime_type ?? 'image/jpeg';
                        $item->product->image_base64 = "data:{$mime};base64," . base64_encode(file_get_contents($path));
                    }
                }

                if (empty($item->product->image_base64)) {
                    $item->product->image_base64 = null;
                }
            }
        }

        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'ar' => $ar,
            'brand' => [
                'name' => 'وِصال - WISAL STORE',
                'slogan' => 'بين كل هدية وذكرى — وِصال',
                'logo_data' => $logoData,
            ]
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        return $pdf;
    }
}
