<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['article_id','tag_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->string('author_name');
            $table->string('author_email');
            $table->text('body');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('source')->default('website');
            $table->boolean('is_active')->default(true);
            $table->string('unsubscribe_token')->unique()->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('tags');
    }
};
