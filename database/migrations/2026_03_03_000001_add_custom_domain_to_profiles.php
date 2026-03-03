<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->string('custom_domain')->nullable()->unique()->after('scheduling_url');
            $table->string('domain_verification_token')->nullable()->after('custom_domain');
            $table->timestamp('domain_verified_at')->nullable()->after('domain_verification_token');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn(['custom_domain', 'domain_verification_token', 'domain_verified_at']);
        });
    }
};
