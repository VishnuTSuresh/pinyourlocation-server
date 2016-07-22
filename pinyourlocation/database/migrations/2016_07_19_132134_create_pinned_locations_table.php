<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePinnedLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinned_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->enum('location', ['home', 'office', 'leave']);
            $table->text('description');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->unique(array('date', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pinned_locations');
    }
}
