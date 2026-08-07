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
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('category_id')->default(0);
            $table->unsignedBigInteger('template_id')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('type_id')->default(0);
            $table->string('url');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('category_id');
            $table->index('template_id');
            $table->index('status');
            $table->index('parent_id');
            $table->index('type_id');
            $table->index('url');
            $table->foreign('website_id')->references('id')->on('websites');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
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
