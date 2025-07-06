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
        Schema::create('digital_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('account_type', ['individual', 'organization']);
            $table->string('display_name');
            $table->string('job_title')->nullable();
            $table->text('short_bio')->nullable();

            $table->string('email')->nullable(); // change to required if needed
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('location')->nullable();

            $table->string('profile_image')->nullable();
            $table->string('template')->default('default');
            $table->boolean('is_public')->default(true);

            $table->string('qr_code_url')->nullable();
            $table->string('slug')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_profiles');
    }
};
