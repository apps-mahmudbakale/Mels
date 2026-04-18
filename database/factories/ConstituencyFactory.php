<?php

namespace Database\Factories;

use App\Models\Constituency;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConstituencyFactory extends Factory
{
    protected $model = Constituency::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city() . ' Constituency',
            'type' => $this->faker->randomElement(['federal', 'state', 'senatorial', 'state_house', 'lga']),
            'state_id' => null,
        ];
    }
}
