<?php

namespace Database\Factories\Pages;

use App\Models\Pages\CvPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class CvPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CvPage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'CV',
            'slug' => 'cv',
            'meta_title' => $this->faker->sentence(6),
            'meta_description' => $this->faker->sentence(15),
            'content' => [
                'cv_file' => null,
            ],
        ];
    }

    /**
     * Indicate that the CV page should have a CV file.
     */
    public function withCvFile(): Factory
    {
        return $this->state(function (array $attributes) {
            $content = $attributes['content'];
            $content['cv_file'] = 'documents/cv-'.$this->faker->uuid.'.pdf';

            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Indicate that the CV page should have a CV file.
     */
    public function withImage(): Factory
    {
        return $this->withCvFile();
    }
}
