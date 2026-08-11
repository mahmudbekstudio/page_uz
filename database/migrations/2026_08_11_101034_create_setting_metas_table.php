<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setting_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('setting_id')->default(0);
            $table->string('meta_format', 100)->default(\App\Helpers\DataFormat::getDefault());
            $table->string('meta_key');
            $table->longText('meta_value')->nullable();
            //$table->string('lang', 2)->default('');
            $table->timestamps();

            $table->index('user_id');
            $table->index('website_id');
            $table->index('setting_id');
            $table->index('meta_key');
            //$table->index('lang');
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
            $table->foreign('website_id')->references('id')->on('websites');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_metas');
    }
};
