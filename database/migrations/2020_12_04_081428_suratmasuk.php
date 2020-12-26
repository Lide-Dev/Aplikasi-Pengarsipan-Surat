<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Suratmasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suratmasuk', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('id_sifat');
            $table->date('tanggal_surat');
            $table->string('no_surat', 150);
            $table->string('asal_surat', 255);
            $table->string('perihal', 255);
            // $table->string('tembusan', 500)->nullable();
            $table->string('isi_ringkas', 500)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_sifat')->references('id')->on('sifatsurat')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suratmasuk');
    }
}
