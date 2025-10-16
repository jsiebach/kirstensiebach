<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Press;
use Illuminate\Database\Eloquent\Factories\Factory;

class PressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Press::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::whereSlug('home')->first()?->id ?? Page::factory()->create(['slug' => 'home'])->id,
            'title' => $this->faker->sentence(6),
            'link' => $this->faker->url,
            'date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }

    /**
     * Indicate that the press item is recent (within the last month).
     */
    public function recent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the press item is featured.
     */
    public function featured(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            ];
        });
    }
}
