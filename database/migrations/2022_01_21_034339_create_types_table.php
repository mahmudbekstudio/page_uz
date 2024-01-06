<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedTinyInteger('status')->default(1);// 0 or 1 (not active or active)
            $table->string('title');
            $table->string('name')->nullable();//unique in website and by type
            $table->enum('type', Type::types())->default(Type::defaultType());
            $table->unsignedTinyInteger('has_parent')->default(0);// 0 or 1, if post or category has parent set 1
            $table->unsignedInteger('child_of')->default(0);//only for post type, put category id for post, if post type has category
            $table->json('structure');// structure of fields in every tabs
            $table->json('fields');// all added fields with params
            $table->timestamps();

            $table->index('website_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('name');
            $table->index('type');
            $table->index('child_of');
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
        Schema::dropIfExists('types');
    }
};
