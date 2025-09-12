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
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->string('groupe'); // Nom du groupe
            $table->string('code_groupe'); // Code du groupe
            $table->integer('num_groupe'); // Numéro du groupe
            $table->integer('annee_formation'); // Année de formation
            $table->foreignId('filiere_id')->constrained('filieres')->onDelete('cascade'); // Relation avec la table filieres
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};
