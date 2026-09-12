<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('gateway'); // e.g. mpesa, stripe, paypal
            $table->string('transaction_reference')->unique()->index(); // M-Pesa Receipt Number / Stripe Charge ID
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['initiated', 'successful', 'failed'])->default('initiated');
            $table->json('payload')->nullable(); // Full webhook audit logs
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};