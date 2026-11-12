
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Allow transaction_reference to be NULL
            $table->string('transaction_reference')
                ->nullable()
                ->change();

            // Change status from ENUM to VARCHAR
            $table->string('status')
                ->default('initiated')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Restore transaction_reference to NOT NULL
            $table->string('transaction_reference')
                ->nullable(false)
                ->change();

            // Restore status to ENUM
            $table->enum('status', [
                'initiated',
                'successful',
                'failed'
            ])->default('initiated')->change();
        });
    }
};
