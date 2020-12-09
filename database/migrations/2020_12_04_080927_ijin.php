<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ijin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ijin', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->boolean('d_surat');
            $table->boolean('d_miliksurat');
            $table->boolean('dp_surat');
            $table->boolean('w_disposisi');
            $table->boolean('w_surat');
            $table->boolean('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ijin');
    }
}
