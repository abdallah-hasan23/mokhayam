<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        // Seed defaults
        $defaults = [
            'site_name'        => 'مخيّم',
            'site_tagline'     => 'رواية الإنسان في زمن الحرب',
            'site_email'       => 'editor@mukhayyam.ps',
            'articles_per_page'=> '8',
            'telegram'         => '',
            'twitter'          => '',
            'instagram'        => '',
            'tiktok'           => '',
            'logo_path'        => '',
        ];
        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert(['key' => $key, 'value' => $value, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
