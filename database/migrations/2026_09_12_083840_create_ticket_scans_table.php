<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained();
            $table->foreignId('scanned_by_user_id')->constrained('users'); // Staff performing verification
            $table->timestamp('scanned_at')->useCurrent();
            $table->string('device_info')->nullable();
            $table->enum('scan_result', ['granted', 'already_used', 'invalid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_scans');
    }
};