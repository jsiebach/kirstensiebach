<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Press;
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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::whereSlug('publications')->first()->id,
            'title' => $this->faker->sentence,
            'authors' => $this->faker->paragraph,
            'publication_name' => $this->faker->word,
            'published' => $this->faker->boolean(80),
            'date_published' => $this->faker->date,
            'abstract' => $this->faker->paragraph,
            'doi' => $this->faker->numberBetween(10000, 1000000),
            'link' => $this->faker->url,
        ];
    }
}
