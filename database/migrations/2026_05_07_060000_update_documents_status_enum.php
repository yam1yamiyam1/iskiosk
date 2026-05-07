<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM column to add 'Pending Print' and 'Printer Error'
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('Pending Print', 'Submitted', 'Collected and Processing', 'Ready for claiming', 'Claimed', 'Printer Error') DEFAULT 'Submitted'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ENUM column
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('Submitted', 'Collected and Processing', 'Ready for claiming', 'Claimed') DEFAULT 'Submitted'");
    }
};
