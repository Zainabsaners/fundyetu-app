<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'platform_fee_percent', 'value' => '4.25', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sms_cost_per_credit', 'value' => '5', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'withdrawal_fee', 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'airtel_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'card_enabled', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'paypal_enabled', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_consumer_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_consumer_secret', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_passkey', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_shortcode', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
