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
        Schema::create('post_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->index('user_id');
            $table->unsignedBigInteger('website_id')->default(0)->index('website_id');
            $table->unsignedBigInteger('post_id')->default(0)->index('post_id');
            $table->string('meta_format', 100)->default(\App\Helpers\DataFormat::getDefault());
            $table->string('meta_key')->index('meta_key');
            $table->longText('meta_value')->nullable();
            $table->string('lang', 2)->default('')->index('lang');
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
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
        Schema::dropIfExists('post_metas');
    }
};
