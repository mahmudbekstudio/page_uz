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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('template_id')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('type_id')->default(0);
            $table->string('url');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('template_id');
            $table->index('status');
            $table->index('parent_id');
            $table->index('type_id');
            $table->index('url');
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
        Schema::dropIfExists('categories');
    }
};
