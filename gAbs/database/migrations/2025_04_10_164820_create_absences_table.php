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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();

            // Foreign key to users.id
            $table->foreignId('stagiaire_id')->constrained('users')->onDelete('cascade');
            $table->string('stagiaire_user_id_number');

            $table->date('date');
            $table->enum('seance_number', ['1', '2', '3', '4']);

            $table->foreignId('inserted_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('inserted_by_user_id_number');

            $table->timestamps();

            // Foreign keys
            

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
