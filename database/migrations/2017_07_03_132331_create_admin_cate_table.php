<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminCateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('model')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->string('icon')->nullable();
            $table->string('pic')->nullable();
            $table->text('digest')->nullable();
            $table->text('content')->nullable();
            $table->string('self_temp')->nullable();
            $table->string('article_temp')->nullable();
            $table->integer('pid')->default(0);
            $table->tinyInteger('level')->default(0);
            $table->integer('taxis')->default(0);
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
        Schema::dropIfExists('cate');
    }
}
