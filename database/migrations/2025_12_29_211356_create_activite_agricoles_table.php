<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activite_agricoles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('champ_id')
                ->constrained('champs')
                ->cascadeOnDelete();

            $table->string('type'); // 'Semis' | 'Recolte' | 'Traitement'
            $table->timestamps();

            $table->index(['champ_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activite_agricoles');
    }
};