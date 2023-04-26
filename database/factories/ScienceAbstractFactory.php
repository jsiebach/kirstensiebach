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
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::whereSlug('publications')->first()->id,
            'title' => $this->faker->sentence,
            'link' => $this->faker->randomElement([$this->faker->url, null]),
            'authors' => $this->faker->sentence,
            'location' => $this->faker->name,
            'city_state' => $this->faker->city.', '.$this->faker->state,
            'date' => $this->faker->date,
            'details' => $this->faker->paragraph,
        ];
    }
}
