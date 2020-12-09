<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Disposisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengirim')->nullable();
            $table->unsignedBigInteger('id_penerima')->nullable();
            $table->unsignedBigInteger('id_suratmasuk');
            $table->string('isi',500)->nullable();
            $table->date('read_at')->nullable();
            $table->timestamps();

            $table->foreign('id_pengirim')->references('id')->on('profil')->onDelete('set null');
            $table->foreign('id_penerima')->references('id')->on('profil')->onDelete('set null');
            $table->foreign('id_suratmasuk')->references('id')->on('suratmasuk')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposisi');

    }
}
