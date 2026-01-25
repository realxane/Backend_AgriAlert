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
    // On nettoie l'ancien essai raté
    Schema::dropIfExists('notifications');

    Schema::create('notifications', function (Blueprint $table) {
        $table->id(); // ID de la notification (auto-increment classique)
        
        // --- LA CORRECTION EST ICI ---
        // On utilise foreignUuid au lieu de foreignId
        $table->foreignUuid('user_id') 
              ->constrained('users') 
              ->onDelete('cascade');
        
        $table->string('title');
        $table->text('message');
        $table->string('icon')->default('assets/images/notification_default.png');
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
