<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('recommandations', function (Blueprint $table) {
            $table->string('activite_type')->nullable()->after('culture_id');
            $table->unsignedInteger('min_jour')->nullable()->after('activite_type');
            $table->unsignedInteger('max_jour')->nullable()->after('min_jour');
            $table->json('weather_rules')->nullable()->after('max_jour');
            $table->unsignedInteger('priorite')->default(100)->after('weather_rules');
            $table->boolean('is_active')->default(true)->after('priorite');

            $table->index(['culture_id', 'region_id', 'saison', 'activite_type']);
        });
    }

    public function down(): void
    {
        Schema::table('recommandations', function (Blueprint $table) {
            $table->dropIndex(['culture_id', 'region_id', 'saison', 'activite_type']);
            $table->dropColumn(['activite_type','min_jour','max_jour','weather_rules','priorite','is_active']);
        });
    }
};