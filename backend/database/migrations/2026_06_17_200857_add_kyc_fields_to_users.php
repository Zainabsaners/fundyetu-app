<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('address')->nullable()->after('birth_year');
            $table->string('bank_name')->nullable()->after('address');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            $table->string('mpesa_phone')->nullable()->after('bank_account_name');
            $table->string('withdrawal_method')->nullable()->after('mpesa_phone');
            $table->string('id_front_path')->nullable()->after('withdrawal_method');
            $table->string('id_back_path')->nullable()->after('id_front_path');
            $table->string('address_proof_path')->nullable()->after('id_back_path');
            $table->string('profile_photo_path')->nullable()->after('address_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address', 'bank_name', 'bank_account_number', 'bank_account_name',
                'mpesa_phone', 'withdrawal_method', 'id_front_path', 'id_back_path',
                'address_proof_path', 'profile_photo_path',
            ]);
        });
    }
};
