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
        Schema::create('efps', function (Blueprint $table) {
            $table->id(); // Equivalent to: $table->bigIncrements('id');
            $table->string('efp');
            $table->string('ville');
            $table->string('code')->unique();
            $table->foreignId('complexe_id')->constrained('complexes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efp');
    }
};
