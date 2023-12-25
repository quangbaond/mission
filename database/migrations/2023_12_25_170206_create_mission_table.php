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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('type')->default(0)->comment('0:url sort, 1: download app');
            $table->bigInteger('reward')->default(0);
            $table->bigInteger('exp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission');
    }
};
