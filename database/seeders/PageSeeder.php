<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PageSeeder extends Seeder
{
    private $pages = [
        [
            'title' => 'Home',
            'slug' => 'home',
            'meta_title' => 'Kirsten Siebach | Martian Geologist',
            'meta_description' => 'Kirsten Siebach is an Assistant Professor in the Rice University Department of Earth, Environmental, and Planetary Sciences. Her work focuses on understanding the history of water interacting with sediments on Mars and early Earth through analysis of sedimentary rock textures and chemistry. She is currently a member of the Science and Operations Teams for the Mars Exploration Rovers and the Mars Science Laboratory.'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->pages)->each(function ($page) {
            $path = "page-stubs/{$page['slug']}.json";
            $page['content'] = Storage::exists($path)
                ? json_decode(Storage::get($path), true)
                : [];
            Page::create($page);
        });
    }
}
