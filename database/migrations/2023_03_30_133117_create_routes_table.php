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
            $table->unsignedBigInteger('website_id')->default(0);
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('type_id')->default(0);
            $table->timestamps();

            $table->index('website_id');
            $table->index('name');
            $table->index('parent_id');
            $table->index('type_id');
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
        Schema::dropIfExists('routes');
    }
};
