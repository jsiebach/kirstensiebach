<?php

namespace Database\Factories\Pages;

use App\Models\Pages\PhotographyPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotographyPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhotographyPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Photography',
            'slug' => 'photography',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'flickr_album' => $this->faker->url,
            ],
        ];
    }
}
