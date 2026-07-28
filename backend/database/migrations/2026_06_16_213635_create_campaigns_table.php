<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('story')->nullable();
            $table->decimal('target_amount', 12, 2);
            $table->decimal('raised_amount', 12, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('expiry_date')->nullable();
            $table->string('video_url')->nullable();
            $table->decimal('platform_fee_percent', 5, 2)->default(4.25);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
