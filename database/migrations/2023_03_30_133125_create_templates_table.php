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
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('theme_id')->default(0);
            $table->string('name');
            $table->enum('type', Template::types())->default(Template::defaultType());
            $table->unsignedBigInteger('type_id')->default(0);
            $table->unsignedBigInteger('layout_id')->default(0);
            $table->json('content');
            $table->json('params');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('theme_id');
            $table->index('type');
            $table->index('type_id');
            $table->index('layout_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
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
