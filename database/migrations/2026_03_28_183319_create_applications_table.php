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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->enum('type', ['trial_day', 'adoption']);
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('has_children')->default(false);
            $table->boolean('has_other_pets')->default(false);
            $table->date('booking_date')->nullable();
            $table->time('booking_time')->nullable();
            $table->boolean('agreed_to_costs')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
