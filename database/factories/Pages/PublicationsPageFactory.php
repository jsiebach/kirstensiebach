<?php

namespace Database\Factories\Pages;

use App\Models\Pages\PublicationsPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationsPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PublicationsPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Publications',
            'slug' => 'publications',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [],
        ];
    }
}
