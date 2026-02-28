<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('twitter')->nullable()->after('github');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('youtube')->nullable()->after('instagram');
            $table->string('tiktok')->nullable()->after('youtube');
            $table->string('dribbble')->nullable()->after('tiktok');
            $table->string('behance')->nullable()->after('dribbble');
            $table->string('medium')->nullable()->after('behance');
            $table->string('availability_status')->nullable()->after('medium');
            $table->string('scheduling_url')->nullable()->after('availability_status');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'twitter', 'instagram', 'youtube', 'tiktok',
                'dribbble', 'behance', 'medium',
                'availability_status', 'scheduling_url',
            ]);
        });
    }
};
