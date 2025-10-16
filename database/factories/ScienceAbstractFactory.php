<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\ScienceAbstract;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScienceAbstractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScienceAbstract::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::whereSlug('publications')->first()?->id ?? Page::factory()->create(['slug' => 'publications'])->id,
            'title' => $this->faker->sentence(8),
            'link' => $this->faker->url,
            'authors' => implode(', ', $this->faker->words(4, false)),
            'location' => $this->faker->company.' Conference',
            'city_state' => $this->faker->city.', '.$this->faker->stateAbbr,
            'date' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'details' => $this->faker->paragraphs(2, true),
        ];
    }

    /**
     * Indicate that the science abstract does not have a link.
     */
    public function withoutLink(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'link' => null,
            ];
        });
    }

    /**
     * Indicate that the science abstract is recent (within the last year).
     */
    public function recent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the science abstract is featured.
     */
    public function featured(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            ];
        });
    }
}
