<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activite_agricoles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('plantation_id')
                ->constrained('plantations')
                ->cascadeOnDelete();

            $table->string('type'); // 'Semis' | 'Recolte' | 'Traitement'
            $table->dateTime('done_at')->nullable(); // date/heure activité

            $table->timestamps();

            $table->index(['plantation_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activite_agricoles');
    }
};
