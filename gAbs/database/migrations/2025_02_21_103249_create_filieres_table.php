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
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('code_secteur');
            $table->string('secteur');
            $table->string('niv');
            $table->string('code_filiere');
            $table->string('filiere');
            $table->integer('annee_formation');
            $table->unsignedBigInteger('efp_id'); // Ensure it's unsigned
            $table->foreign('efp_id')->references('id')->on('efps')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
