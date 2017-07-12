<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id');
            $table->string('name');
            $table->text('content')->nullable();
            $table->string('tag')->nullable();
            $table->text('digest')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('taxis')->default(0);
            $table->string('is_top')->default('F');
            $table->string('is_hot')->default('F');
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
        Schema::dropIfExists('goods');
    }
}
