<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->nullable(); 
            $table->string('surname');
            $table->string('given_name');
            $table->string('middle_name')->nullable();
            $table->string('year_level')->nullable();
            $table->string('program')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('document_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
