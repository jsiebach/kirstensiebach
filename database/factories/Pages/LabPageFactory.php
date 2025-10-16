<?php

namespace Database\Factories\Pages;

use App\Models\Pages\LabPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LabPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Lab',
            'slug' => 'lab',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'banner' => null,
                'intro' => $this->faker->paragraphs(2, true),
                'lower_content' => $this->faker->paragraphs(3, true),
            ],
        ];
    }

    /**
     * Indicate that the lab page should have a banner image.
     */
    public function withBanner(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['banner'] = 'images/lab-banner-'.$this->faker->uuid.'.jpg';

            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Indicate that the lab page should have a banner image.
     */
    public function withImage(): Factory
    {
        return $this->withBanner();
    }
}
