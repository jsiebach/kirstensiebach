<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Research;
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
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::whereSlug('research')->first()?->id ?? Page::factory()->create(['slug' => 'research'])->id,
            'sort_order' => $this->faker->numberBetween(1, 100),
            'project_name' => $this->faker->words(5, true),
            'description' => $this->faker->paragraphs(3, true),
            'image' => null,
        ];
    }

    /**
     * Indicate that the research project should have an image.
     */
    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'image' => 'images/research-'.$this->faker->uuid.'.jpg',
            ];
        });
    }

    /**
     * Indicate that the research project is featured.
     */
    public function featured(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sort_order' => $this->faker->numberBetween(1, 10),
                'image' => 'images/research-featured-'.$this->faker->uuid.'.jpg',
            ];
        });
    }
}
