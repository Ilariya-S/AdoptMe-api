<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('breed_visual')->nullable();
            $table->string('name');
            $table->string('sex');
            $table->text('description');
            $table->integer('age_months');
            $table->string('size');
            $table->float('weight_kg')->nullable();
            $table->string('color')->nullable();
            $table->boolean('sterilized')->default(false);
            $table->json('temperament_tags')->nullable();
            $table->string('health_status')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->json('ideal_owner_tags')->nullable();
            $table->string('photo_url')->nullable();
            $table->integer('monthly_cost')->nullable();
            $table->enum('status', ['available', 'trial', 'adopted'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
