<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Publication::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::whereSlug('publications')->first()?->id ?? Page::factory()->create(['slug' => 'publications'])->id,
            'title' => $this->faker->sentence(8),
            'authors' => implode(', ', $this->faker->words(4, false)),
            'publication_name' => $this->faker->words(3, true),
            'published' => $this->faker->boolean(80),
            'date_published' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'abstract' => $this->faker->paragraphs(3, true),
            'doi' => '10.'.$this->faker->numberBetween(1000, 9999).'/'.$this->faker->lexify('??????'),
            'link' => $this->faker->url,
        ];
    }

    /**
     * Indicate that the publication is published.
     */
    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
                'date_published' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the publication is a draft (unpublished).
     */
    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => false,
                'date_published' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the publication is recent (within the last year).
     */
    public function recent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
                'date_published' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ];
        });
    }
}
