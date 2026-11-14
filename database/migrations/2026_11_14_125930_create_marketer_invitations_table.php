<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // One reusable invitation link; store only the hash, never the raw token.
            $table->string('token_hash', 64)->unique();

            $table->enum('commission_type', ['fixed', 'percent']);
            $table->decimal('fixed_commission', 12, 2)->nullable();
            $table->decimal('commission_percent', 5, 2)->nullable();

            $table->boolean('agreement_enabled')->default(false);
            $table->longText('agreement_text')->nullable();
            $table->string('agreement_title')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'is_active']);
            $table->index(['organizer_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_invitations');
    }
};
