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
        Schema::create('data', function (Blueprint $table) {
            $table->id(); // Not necessary, but it's a good practice to have an ID
            $table->integer('Year');
            $table->integer('Age');
            $table->integer('Ethnic');
            $table->integer('Sex');
            $table->integer('Area');
            $table->integer('count');
            $table->timestamps(); // Not necessary, but Laravel offers using models with created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
