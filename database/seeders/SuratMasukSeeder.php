<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class SuratMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SuratMasuk::factory(100)->create();
    }
}
