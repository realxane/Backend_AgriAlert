<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommandations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('titre');
            $table->text('contenu');

            $table->string('saison');

            $table->foreignUuid('region_id')
                ->constrained('regions')
                ->cascadeOnUpdate()
                ->restrictOnDelete(); 

            $table->foreignUuid('culture_id')
                ->constrained('cultures')
                ->cascadeOnUpdate()
                ->restrictOnDelete(); 

            $table->timestamps();

            $table->index(['region_id', 'culture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommandations');
    }
};