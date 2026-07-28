<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treasurer_id')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['withdrawal_id', 'treasurer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_approvals');
    }
};
