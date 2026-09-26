<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('KES');
            $table->string('payment_method')->default('lightning'); // lightning, manual
            $table->text('destination')->nullable(); // Lightning Address or Invoice
            $table->string('blink_status')->nullable(); // SUCCESS, PENDING, FAILED
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Add optional default lightning address to marketers table
        Schema::table('marketers', function (Blueprint $table) {
            $table->string('lightning_address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_payouts');
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropColumn('lightning_address');
        });
    }
};