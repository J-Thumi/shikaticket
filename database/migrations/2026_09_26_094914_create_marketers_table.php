<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            /*
             * Example:
             * JOHN7X2K
             */
            $table->string('referral_code', 32)->unique();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index([
                'organizer_id',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketers');
    }
};