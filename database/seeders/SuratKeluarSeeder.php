<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuratKeluarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SuratKeluar::factory(100)->create();

    }
}
