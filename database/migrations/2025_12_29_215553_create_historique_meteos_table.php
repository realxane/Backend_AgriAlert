<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historique_meteos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('region_id');
            $table->dateTime('date');

            $table->double('temperature');
            $table->double('precipitation');
            $table->double('humidite');

            $table->string('source');

            $table->timestamps();

            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->cascadeOnDelete();

            $table->index(['region_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_meteos');
    }
};