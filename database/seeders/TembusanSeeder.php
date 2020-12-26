<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TembusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Tembusan::factory(10)->create();
    }
}
