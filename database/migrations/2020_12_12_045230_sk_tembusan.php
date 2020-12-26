<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SkTembusan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tembusan_sk', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_tembusan');
            $table->unsignedBigInteger('id_suratkeluar');
            $table->foreign('id_tembusan')->references('id')->on('tembusan')->restrictOnDelete();
            $table->foreign('id_suratkeluar')->references('id')->on('suratkeluar')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tembusan_sk');
    }
}
