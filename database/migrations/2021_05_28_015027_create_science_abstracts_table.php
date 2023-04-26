<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScienceAbstractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('science_abstracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id');
            $table->string('title', 1024);
            $table->string('link', 1024)->nullable();
            $table->string('authors', 1024);
            $table->string('location', 1024);
            $table->string('city_state');
            $table->date('date');
            $table->text('details');
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
        Schema::dropIfExists('science_abstracts');
    }
}
