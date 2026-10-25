<?php

namespace Database\Factories;

use App\Enums\ResourceType;
use App\Models\ResourceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceItem>
 */
class ResourceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(12),
            'type' => ResourceType::Link,
            'url' => $this->faker->url,
        ];
    }

    public function document(string $filePath = 'resources/lesson-plan.pdf'): static
    {
        return $this->state([
            'type' => ResourceType::Document,
            'file_path' => $filePath,
            'file_name' => basename($filePath),
            'url' => null,
        ]);
    }

    public function image(string $filePath = 'resources/crater.jpg'): static
    {
        return $this->state([
            'type' => ResourceType::Image,
            'file_path' => $filePath,
            'file_name' => basename($filePath),
            'url' => null,
        ]);
    }

    public function video(string $youtubeId = 'iMt7azVQsCs'): static
    {
        return $this->state([
            'type' => ResourceType::Video,
            'url' => "https://www.youtube.com/watch?v={$youtubeId}",
        ]);
    }
}
