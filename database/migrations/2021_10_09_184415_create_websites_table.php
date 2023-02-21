<?php

use App\Models\Website;
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
        Schema::create('websites', function (Blueprint $table) {
            $table->id('id');
            $table->tinyInteger('status')->default(Website::STATUS_NOT_CONFIRMED)->index('status');
            $table->string('domain')->unique();
            $table->tinyInteger('type')->default(Website::TYPE_MAIN)->index('type');
            $table->unsignedBigInteger('domain_id')->nullable()->index('domain_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
};
