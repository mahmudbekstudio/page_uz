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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id')->default(0)->index('website_id');
            $table->string('name')->index('name');
            $table->unsignedBigInteger('parent_id')->default(0)->index('parent_id');
            $table->unsignedBigInteger('type_id')->default(0)->index('type_id');
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites');
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
        Schema::dropIfExists('routes');
    }
};
