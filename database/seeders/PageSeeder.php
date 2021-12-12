<?php

namespace Database\Seeders;

use App\Models\Page;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);

        $pages = [
            [
                'title'            => 'Home',
                'slug'             => 'home',
                'meta_title'       => 'Kirsten Siebach | Martian Geologist',
                'meta_description' => 'Kirsten Siebach is an Assistant Professor in the Rice University Department of Earth, Environmental, and Planetary Sciences. Her work focuses on understanding the history of water interacting with sediments on Mars and early Earth through analysis of sedimentary rock textures and chemistry. She is currently a member of the Science and Operations Teams for the Mars Exploration Rovers and the Mars Science Laboratory.',
                'content'          => [
                    'use_callout'         => true,
                    'callout'             => $faker->paragraph,
                    'callout_action'      => $faker->url,
                    'callout_action_text' => $faker->sentence(2),
                    'tagline'             => 'Martian Geologist',
                    'banner'              => $faker->imageUrl,
                    'profile_picture'     => $faker->imageUrl,
                    'profile_summary'     => $faker->paragraph,
                    'bio'                 => $faker->paragraph,
                ],
            ],
            [
                'title'            => 'Lab',
                'slug'             => 'lab',
                'meta_title'       => 'Siebach Lab | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [
                    'use_callout'         => false,
                    'callout'             => $faker->paragraph,
                    'callout_action'      => $faker->url,
                    'callout_action_text' => $faker->sentence(2),
                    'banner'              => $faker->imageUrl,
                    'intro'               => $faker->paragraph,
                    'lower_content'       => $faker->paragraph,
                ],
            ],
            [
                'title'            => 'Research',
                'slug'             => 'research',
                'meta_title'       => 'Research | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [],
            ],
            [
                'title'            => 'Publications',
                'slug'             => 'publications',
                'meta_title'       => 'Publications | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [],
            ],
            [
                'title'            => 'CV',
                'slug'             => 'cv',
                'meta_title'       => 'Curriculum Vitae | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [],
            ],
            [
                'title'            => 'Speaking & Outreach',
                'slug'             => 'outreach',
                'meta_title'       => 'Speaking & Outreach | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [],
            ],
            [
                'title'            => 'Photography',
                'slug'             => 'photography',
                'meta_title'       => 'Photography | Kirsten Siebach',
                'meta_description' => '',
                'content'          => [],
            ],
        ];

        collect($pages)->each(function ($page) {
//            $path = "page-stubs/{$page['slug']}.json";
//            $page['content'] = Storage::exists($path)
//                ? json_decode(Storage::get($path), true)
//                : new \stdClass;
            Page::create($page);
        });
    }
}
