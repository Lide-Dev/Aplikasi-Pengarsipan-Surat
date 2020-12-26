<?php

namespace Database\Factories;

use App\Models\Tembusan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TembusanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tembusan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->company
        ];
    }
}
