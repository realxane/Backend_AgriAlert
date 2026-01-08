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
        Schema::table('historique_meteos', function (Blueprint $table) {
            // On ajoute la colonne physique
            $table->uuid('champ_id')->nullable()->after('region_id');

            // On définit la relation
            $table->foreign('champ_id')
                  ->references('id')->on('champs')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historique_meteos', function (Blueprint $table) {
            $table->dropForeign(['champ_id']);
            $table->dropColumn('champ_id');
        });
    }
};