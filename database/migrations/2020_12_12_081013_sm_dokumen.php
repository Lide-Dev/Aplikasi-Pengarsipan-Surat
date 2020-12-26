<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmDokumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_sm', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('alias', 255);
            $table->string('tipe', 15);
            $table->unsignedInteger('ukuran');
            $table->timestamps();
            $table->unsignedBigInteger('id_suratmasuk');
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
        Schema::dropIfExists('dokumen_sm');
        //
    }
}
