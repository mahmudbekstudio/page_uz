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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('folder_id')->default(0);
            $table->string('name');
            $table->string('extension', 10);
            $table->unsignedInteger('size');
            $table->boolean('is_local')->default(false);
            $table->timestamps();

            $table->index('website_id');
            $table->index('user_id');
            $table->index('folder_id');
            $table->index('name');
            $table->index('extension');
            $table->index('size');
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
        Schema::dropIfExists('files');
    }
};
