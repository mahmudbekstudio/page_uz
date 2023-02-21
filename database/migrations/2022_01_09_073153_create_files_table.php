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
            $table->unsignedBigInteger('website_id')->index('website_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->unsignedBigInteger('folder_id')->default(0)->index('folder_id');
            $table->string('name')->index('name');
            $table->string('extension', 10)->index('extension');
            $table->unsignedInteger('size')->index('size');
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
