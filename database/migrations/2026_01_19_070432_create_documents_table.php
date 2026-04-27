<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('id_number', 50)->nullable()->index();
            $table->string('surname', 100)->nullable();
            $table->string('given_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('year_level', 50)->nullable();
            $table->string('program', 100)->nullable();
            $table->string('document_type', 120)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('tracking_code', 32)->unique()->nullable();
            $table->enum('status', [
                'Submitted',
                'Collected and Processing',
                'Ready for claiming',
                'Claimed'
            ])->default('Submitted and processing');
            $table->text('remarks')->nullable();
            $table->dateTime('date_claimed')->nullable();
            $table->string('batch_id', 50)->nullable();
            $table->dateTime('email_sent_at')->nullable();
            $table->string('email_message_id', 255)->nullable();
            $table->dateTime('retrieved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
