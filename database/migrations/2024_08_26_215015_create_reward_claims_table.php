<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reward_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reward_id');
            $table->foreign('reward_id')->references('id')->on('rewards');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->double('price');
            $table->string('code');
            $table->enum('status', ['waiting_confirmation', 'on_progress', 'ready_to_pickup', 'done', 'rejected'])->default('waiting_confirmation');
            $table->string('reason_rejection')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_claims');
    }
};
