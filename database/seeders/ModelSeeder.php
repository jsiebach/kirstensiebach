<?php

namespace Database\Seeders;

use App\Models\Press;
use App\Models\Publication;
use App\Models\Research;
use App\Models\ResourceItem;
use App\Models\ResourceSection;
use App\Models\ScienceAbstract;
use App\Models\SocialLink;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Press::factory()->count(6)->create();
        SocialLink::factory()->count(6)->create();
        TeamMember::factory()->count(6)->create();
        Research::factory()->count(6)->create();
        Publication::factory()->count(20)->create();
        ScienceAbstract::factory()->count(8)->create();
        ResourceItem::factory()->create();
        ResourceSection::factory()->count(2)->create()->each(function (ResourceSection $section): void {
            ResourceItem::factory()->for($section, 'section')->count(2)->create();
            ResourceItem::factory()->for($section, 'section')->video()->create();
        });
    }
}
