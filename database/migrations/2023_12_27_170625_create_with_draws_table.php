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
        Schema::create('with_draws', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->string('bank_name')->nullable();
            $table->string('bank_owner')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('method');
            $table->tinyInteger('status')->default('0')->comment('0:pending, 1:accepted, 2:rejected');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('with_draws');
    }
};
