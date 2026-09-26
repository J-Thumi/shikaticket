<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->nullable()->default(0.00)->after('referral_code'); // Percentage e.g. 10.00%
            $table->decimal('fixed_commission', 10, 2)->nullable()->default(0.00)->after('commission_percent'); // Flat amount per order e.g. 150.00
            $table->enum('commission_type', ['fixed', 'percent'])->default('fixed');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('commission_amount', 10, 2)->default(0.00)->after('marketer_id');
            $table->boolean('is_commission_paid')->default(false)->after('commission_amount');
            $table->timestamp('commission_paid_at')->nullable()->after('is_commission_paid');
        });
    }

    public function down(): void
    {
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'fixed_commission','commission_type']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['commission_amount', 'is_commission_paid', 'commission_paid_at']);
        });
    }
};