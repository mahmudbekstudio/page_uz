<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Template;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->index('user_id');
            $table->unsignedBigInteger('website_id')->default(0)->index('website_id');
            $table->string('name');
            $table->enum('type', Template::types())->default(Template::defaultType())->index('type');
            $table->unsignedBigInteger('type_id')->default(0)->index('type_id');
            $table->unsignedBigInteger('layout_id')->default(0)->index('layout_id');
            $table->json('content');
            $table->json('params');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('templates');
    }
};
