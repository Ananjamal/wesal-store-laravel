<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_name')->nullable()->after('coupon_id');
            $table->string('shipping_phone')->nullable()->after('shipping_name');
            $table->string('shipping_alt_phone')->nullable()->after('shipping_phone');
            $table->string('shipping_city')->nullable()->after('shipping_alt_phone');
            $table->string('shipping_area')->nullable()->after('shipping_city');
            $table->string('shipping_address')->nullable()->after('shipping_area');
            $table->string('shipping_landmark')->nullable()->after('shipping_address');
            $table->string('payment_method')->nullable()->after('shipping_landmark');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->string('currency_code', 3)->nullable()->after('payment_status');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('currency_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_phone',
                'shipping_alt_phone',
                'shipping_city',
                'shipping_area',
                'shipping_address',
                'shipping_landmark',
                'payment_method',
                'payment_status',
                'currency_code',
                'exchange_rate',
            ]);
        });
    }
};
