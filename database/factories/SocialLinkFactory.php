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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id'    => Page::whereSlug('home')->first()->id,
            'sort_order' => $this->faker->numberBetween(1, 8),
            'title'      => $this->faker->name,
            'link'       => $this->faker->url,
            'icon'       => $this->faker->randomElement(['fas fa-address-book', 'fab fa-angular']),
        ];
    }
}
