<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmTembusan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tembusan_sm', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_tembusan');
            $table->unsignedBigInteger('id_suratmasuk');
            $table->foreign('id_tembusan')->references('id')->on('tembusan')->restrictOnDelete();
            $table->foreign('id_suratmasuk')->references('id')->on('suratmasuk')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tembusan_sm');
    }
}
