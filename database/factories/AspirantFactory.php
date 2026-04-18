<?php

namespace Database\Factories;

use App\Models\Aspirant;
use App\Models\Party;
use App\Models\Office;
use App\Models\Constituency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aspirant>
 */
class AspirantFactory extends Factory
{
    protected $model = Aspirant::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'slug' => Str::slug($firstName . ' ' . $lastName . '-' . $this->faker->unique()->numberBetween(1, 9999)),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'party_id' => Party::inRandomOrder()->first()?->id ?? Party::factory()->create()->id,
            'office_id' => Office::inRandomOrder()->first()?->id ?? Office::factory()->create()->id,
            'constituency_id' => Constituency::inRandomOrder()->first()?->id ?? Constituency::factory()->create()->id,
            'state_id' => null,
            'bio' => $this->faker->paragraphs(2, true),
            'photo_path' => null,
            'website' => $this->faker->optional()->url(),
            'facebook' => $this->faker->optional()->userName(),
            'twitter' => $this->faker->optional()->userName(),
            'instagram' => $this->faker->optional()->userName(),
            'is_incumbent' => $this->faker->boolean(20),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
