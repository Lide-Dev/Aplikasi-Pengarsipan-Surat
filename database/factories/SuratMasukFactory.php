<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratMasukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuratMasuk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_sifat'=>$this->faker->numberBetween(1,4),
            'tanggal_surat'=> $this->faker->dateTimeBetween('-5 months', 'now'),
            'no_surat'=> $this->faker->numerify('###/###/###'),
            'asal_surat'=> $this->faker->company,
            'perihal'=> $this->faker->sentences(1, true),
            'tembusan'=> $this->faker->sentences(1, true),
            'isi_ringkas' => $this->faker->sentences(3, true),
        ];
    }
}
