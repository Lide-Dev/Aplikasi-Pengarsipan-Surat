<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Profil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profil', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('id_jabatan');
            $table->string('nama', 255);
            $table->string('nip', 18)->nullable();
            $table->string('no_telpon')->nullable();
            $table->timestamps();

            $table->foreign('id_jabatan')->references('id')->on('jabatan')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profil');
    }
}
