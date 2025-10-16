<?php

namespace Database\Factories;

use App\Models\Page;
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
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::whereSlug('lab')->first()?->id ?? Page::factory()->create(['slug' => 'lab'])->id,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'alumni' => $this->faker->boolean(30),
            'bio' => $this->faker->paragraphs(2, true),
            'profile_picture' => 'images/team-default-'.$this->faker->uuid.'.jpg',
        ];
    }

    /**
     * Indicate that the team member has a profile picture.
     */
    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'profile_picture' => 'images/team-'.$this->faker->uuid.'.jpg',
            ];
        });
    }

    /**
     * Indicate that the team member is an alumni.
     */
    public function alumni(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'alumni' => true,
            ];
        });
    }

    /**
     * Indicate that the team member is active (not alumni).
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'alumni' => false,
            ];
        });
    }

    /**
     * Indicate that the team member is featured with high priority.
     */
    public function featured(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sort_order' => $this->faker->numberBetween(1, 10),
                'alumni' => false,
                'profile_picture' => 'images/team-featured-'.$this->faker->uuid.'.jpg',
            ];
        });
    }
}
