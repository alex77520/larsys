<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id');
            $table->string('author')->nullable();
            $table->string('comefrom')->nullable();
            $table->text('content');
            $table->string('title');
            $table->string('tag')->nullable();
            $table->text('digest')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('taxis')->default(0);
            $table->string('is_top')->default('F');
            $table->string('is_hot')->default('F');
            $table->integer('click_times')->default(0);
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
        Schema::dropIfExists('contents');
    }
}
