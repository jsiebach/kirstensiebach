<?php

namespace Database\Factories\Pages;

use App\Models\Pages\OutreachPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutreachPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OutreachPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Outreach',
            'slug' => 'outreach',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [],
        ];
    }
}
