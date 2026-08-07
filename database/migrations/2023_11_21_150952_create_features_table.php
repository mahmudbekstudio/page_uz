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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->string('name');
            $table->string('feature_type');
            $table->unsignedBigInteger('type_id')->nullable()->default(0);
            $table->json('content');
            $table->json('params');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('feature_type');
            $table->index('type_id');
            $table->foreign('website_id')->references('id')->on('websites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
};
