<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SkDokumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_sk', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->string('alias',255);
            $table->string('tipe',15);
            $table->unsignedInteger('ukuran');
            $table->timestamps();
            $table->unsignedBigInteger('id_suratkeluar');
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
        Schema::dropIfExists('dokumen_sk');
        //
    }
}
