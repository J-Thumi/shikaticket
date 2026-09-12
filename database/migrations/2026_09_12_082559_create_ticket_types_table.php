<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. Early Bird, VIP
            $table->decimal('price', 10, 2)->default(0.00);
            $table->unsignedInteger('total_quantity');
            $table->unsignedInteger('reserved_quantity')->default(0);
            $table->unsignedInteger('sold_quantity')->default(0);
            $table->unsignedInteger('min_per_order')->default(1);
            $table->unsignedInteger('max_per_order')->default(10);
            $table->dateTime('sales_start_date')->nullable();
            $table->dateTime('sales_end_date')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // Database-level safety net against overselling
        DB::statement('ALTER TABLE ticket_types ADD CONSTRAINT chk_ticket_quantity CHECK ((sold_quantity + reserved_quantity) <= total_quantity)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};