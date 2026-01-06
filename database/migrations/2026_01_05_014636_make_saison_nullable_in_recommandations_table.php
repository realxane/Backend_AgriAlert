<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('recommandations', function (Blueprint $table) {
            $table->string('saison', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('recommandations', function (Blueprint $table) {
            $table->string('saison', 50)->nullable(false)->change();
        });
    }
};
