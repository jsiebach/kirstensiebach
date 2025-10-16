<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SocialLink::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $icons = [
            'fas fa-envelope',
            'fab fa-twitter',
            'fab fa-github',
            'fab fa-linkedin',
            'fas fa-address-book',
            'fab fa-angular',
        ];

        return [
            'page_id' => Page::whereSlug('home')->first()?->id ?? Page::factory()->create(['slug' => 'home'])->id,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'title' => $this->faker->words(2, true),
            'link' => $this->faker->url,
            'icon' => $this->faker->randomElement($icons),
        ];
    }

    /**
     * Indicate that the social link is featured with high priority.
     */
    public function featured(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sort_order' => $this->faker->numberBetween(1, 10),
            ];
        });
    }
}
