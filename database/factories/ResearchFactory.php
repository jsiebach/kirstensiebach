<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Research;
use App\Models\SocialLink;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Research::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::whereSlug('research')->first()->id,
            'sort_order' => $this->faker->numberBetween(1, 8),
            'project_name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->randomElement([$this->faker->imageUrl, null]),
        ];
    }
}
