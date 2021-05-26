<?php

namespace Database\Seeders;

use App\Models\Press;
use App\Models\Research;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Publication;
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
    }
}
