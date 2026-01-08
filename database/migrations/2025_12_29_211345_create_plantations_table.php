<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plantations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('champ_id')
                ->constrained('champs')
                ->cascadeOnDelete();

            $table->foreignUuid('culture_id')
                ->constrained('cultures')
                ->cascadeOnDelete();

            $table->date('date_debut');      
            $table->date('date_fin')->nullable(); 

            $table->timestamps();

            $table->index(['champ_id', 'culture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantations');
    }
};
