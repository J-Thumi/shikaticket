<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketer_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_invitation_id')
                ->constrained('marketer_invitations')
                ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            // Immutable application-time snapshot of the optional agreement.
            $table->string('agreement_title')->nullable();
            $table->longText('agreement_snapshot')->nullable();
            $table->boolean('agreement_accepted')->default(false);
            $table->string('signature_name')->nullable();
            $table->timestamp('agreement_accepted_at')->nullable();
            $table->string('signer_ip', 45)->nullable();
            $table->text('signer_user_agent')->nullable();

            $table->foreignId('reviewed_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('organizer_note')->nullable();

            $table->foreignId('marketer_id')->nullable()
                ->constrained('marketers')->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['marketer_invitation_id', 'user_id'],
                'marketer_application_invitation_user_unique'
            );
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_applications');
    }
};
