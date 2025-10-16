<?php

namespace Database\Factories\Pages;

use App\Models\Pages\HomePage;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomePageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HomePage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'Home',
            'slug' => 'home',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'add_call_to_action_banner' => $this->faker->boolean(50),
                'call_to_action' => $this->faker->sentence(10),
                'action_link' => $this->faker->url,
                'action_text' => $this->faker->words(3, true),
                'banner' => null,
                'tagline' => $this->faker->sentence(8),
                'profile_picture' => null,
                'profile_summary' => $this->faker->paragraph(3),
                'bio' => $this->faker->paragraphs(4, true),
            ],
        ];
    }

    /**
     * Indicate that the home page should have a banner image.
     */
    public function withBanner(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['banner'] = 'images/banner-'.$this->faker->uuid.'.jpg';

            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Indicate that the home page should have a profile picture.
     */
    public function withProfilePicture(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['profile_picture'] = 'images/profile-'.$this->faker->uuid.'.jpg';

            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Indicate that the home page should have both banner and profile picture.
     */
    public function withImages(): Factory
    {
        return $this->withBanner()->withProfilePicture();
    }

    /**
     * Indicate that the home page should have a call to action banner.
     */
    public function withCallToAction(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['add_call_to_action_banner'] = true;
            $content['call_to_action'] = $this->faker->sentence(10);
            $content['action_link'] = $this->faker->url;
            $content['action_text'] = $this->faker->words(3, true);

            return [
                'content' => $content,
            ];
        });
    }
}
