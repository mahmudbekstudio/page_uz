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
        Schema::create('category_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('category_id')->default(0);
            $table->string('meta_format', 100)->default(\App\Helpers\DataFormat::getDefault());
            $table->string('meta_key');
            $table->longText('meta_value');
            //$table->string('lang', 2)->default('');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('category_id');
            $table->index('meta_key');
            //$table->index('lang');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('category_metas');
    }
};
