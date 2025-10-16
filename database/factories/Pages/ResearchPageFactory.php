<?php

namespace Database\Factories\Pages;

use App\Models\Pages\ResearchPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResearchPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Research',
            'slug' => 'research',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'banner' => null,
                'intro' => $this->faker->paragraphs(2, true),
            ],
        ];
    }

    /**
     * Indicate that the research page should have a banner image.
     */
    public function withBanner(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['banner'] = 'images/research-banner-'.$this->faker->uuid.'.jpg';

            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Indicate that the research page should have a banner image.
     */
    public function withImage(): Factory
    {
        return $this->withBanner();
    }
}
