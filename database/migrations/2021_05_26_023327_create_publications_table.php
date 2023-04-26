<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id');
            $table->string('title', 1024);
            $table->string('publication_name', 1024);
            $table->text('authors');
            $table->boolean('published')->default(true);
            $table->date('date_published');
            $table->text('abstract')->nullable();
            $table->string('link')->nullable();
            $table->string('doi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
