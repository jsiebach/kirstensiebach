<?php

namespace Database\Factories;

use App\Models\ResourceSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceSection>
 */
class ResourceSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(2, true)),
            'description' => $this->faker->sentence(10),
        ];
    }
}
