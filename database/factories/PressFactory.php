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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::whereSlug('home')->first()->id,
            'title' => $this->faker->sentence,
            'link' => $this->faker->url,
            'date' => $this->faker->date,
        ];
    }
}
