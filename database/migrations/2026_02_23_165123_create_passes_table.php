<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passes', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('roles')->onDelete('cascade');
            $table->text('document_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passes');
    }
};