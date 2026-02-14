<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('press', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('social_links', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('research', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });

        Schema::table('science_abstracts', function (Blueprint $table) {
            $table->dropColumn('page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('press', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });

        Schema::table('social_links', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });

        Schema::table('research', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });

        Schema::table('science_abstracts', function (Blueprint $table) {
            $table->foreignId('page_id')->nullable()->constrained('pages');
        });
    }
};
