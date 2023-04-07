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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->index('user_id');
            $table->unsignedBigInteger('website_id')->default(0)->index('website_id');
            $table->unsignedBigInteger('category_id')->default(0)->index('category_id');
            $table->unsignedBigInteger('template_id')->default(0)->index('template_id');
            $table->unsignedTinyInteger('status')->default(0)->index('status');
            $table->unsignedBigInteger('parent_id')->default(0)->index('parent_id');
            $table->unsignedBigInteger('type_id')->default(0)->index('type_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
