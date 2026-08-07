<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('parent_id') ->default(0);
            $table->string('name');
            $table->string('path');
            $table->boolean('is_local')->default(false);
            $table->timestamps();

            $table->index('website_id');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('name');
            $table->index('path');
            $table->index('is_local');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders');
    }
};
