<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // First update existing 'review' statuses to 'pending'
        DB::statement("UPDATE articles SET status = 'pending' WHERE status = 'review'");
        // Then change the enum (MySQL requires this workaround)
        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft','pending','published','rejected') NOT NULL DEFAULT 'draft'");
    }
    public function down(): void {
        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft','review','published','rejected') NOT NULL DEFAULT 'draft'");
    }
};
