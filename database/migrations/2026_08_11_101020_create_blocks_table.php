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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('type_id')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('status');
            $table->index('type_id');
            $table->foreign('website_id')->references('id')->on('websites');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
