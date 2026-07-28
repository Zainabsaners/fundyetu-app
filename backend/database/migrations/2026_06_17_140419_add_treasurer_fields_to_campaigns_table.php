<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->boolean('is_treasurer_controlled')->default(false)->after('platform_fee_percent');
            $table->string('treasurer_name')->nullable()->after('is_treasurer_controlled');
            $table->string('treasurer_phone')->nullable()->after('treasurer_name');
            $table->string('treasurer_id_number')->nullable()->after('treasurer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['is_treasurer_controlled', 'treasurer_name', 'treasurer_phone', 'treasurer_id_number']);
        });
    }
};
