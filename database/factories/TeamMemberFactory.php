<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\SocialLink;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::whereSlug('lab')->first()->id,
            'sort_order' => $this->faker->numberBetween(1, 8),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'bio' => $this->faker->paragraph,
            'profile_picture' => $this->faker->imageUrl,
        ];
    }
}
