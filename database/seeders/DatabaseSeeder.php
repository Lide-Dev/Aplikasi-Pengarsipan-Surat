<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pengguna')->insert([
            'username' => 'collins.dion',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        \App\Models\User::factory(4)->create();
        $this->call([
            SifatSuratSeeder::class,
            SuratKeluarSeeder::class,
            SuratMasukSeeder::class,
            TembusanSeeder::class,
        ]);
    }
}
