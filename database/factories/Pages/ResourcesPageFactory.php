<?php

namespace Database\Factories\Pages;

use App\Models\Pages\ResourcesPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourcesPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResourcesPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Resources',
            'slug' => 'resources',
            'meta_title' => 'Resources | Kirsten Siebach',
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'intro' => $this->faker->paragraph,
                'show_in_menu' => false,
            ],
        ];
    }
}
