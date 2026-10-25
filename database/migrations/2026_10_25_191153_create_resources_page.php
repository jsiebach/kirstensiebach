<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Pages are single rows the admin edits, so the new Resources page needs one to exist.
 * It starts hidden from the menu and sitemap.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('pages')->where('slug', 'resources')->exists()) {
            return;
        }

        DB::table('pages')->insert([
            'title' => 'Resources',
            'slug' => 'resources',
            'meta_title' => 'Resources | Kirsten Siebach',
            'meta_description' => 'Curriculum materials, activities, videos and links from Kirsten Siebach for teachers, students and outreach groups.',
            'content' => json_encode(['intro' => null, 'show_in_menu' => false]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->where('slug', 'resources')->delete();
    }
};
