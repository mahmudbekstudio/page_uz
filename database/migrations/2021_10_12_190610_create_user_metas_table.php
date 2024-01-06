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
        Schema::create('user_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->unsignedBigInteger('user_id');
            $table->string('meta_key');
            $table->longText('meta_value');
            $table->string('meta_format', 100)->default(\App\Helpers\DataFormat::getDefault());
            //$table->string('lang', 2)->default(config('app.locale'));
            $table->timestamps();

            $table->index('website_id');
            $table->index('user_id');
            $table->index('meta_key');
            //$table->index('lang');
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
        Schema::dropIfExists('user_metas');
    }
};
