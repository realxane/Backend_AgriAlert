<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cultures', function (Blueprint $table) {
            $table->unsignedSmallInteger('temp_ideale_min')->nullable()->after('cycle_date');
            $table->unsignedSmallInteger('temp_ideale_max')->nullable()->after('temp_ideale_min');
            $table->string('ensoleillement')->nullable()->after('temp_ideale_max');
            $table->string('eau')->nullable()->after('ensoleillement');
            $table->string('image_url')->nullable()->after('eau'); 
            $table->text('description')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('cultures', function (Blueprint $table) {
            $table->dropColumn(['temp_ideale_min','temp_ideale_max','ensoleillement','eau','image_url','description']);
        });
    }
};