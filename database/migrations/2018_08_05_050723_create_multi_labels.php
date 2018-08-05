<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_todo', function (Blueprint $table) {
            $table->integer('todo_id')->unsigned()->index();
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->integer('label_id')->unsigned()->index();
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
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
        Schema::dropIfExists('label_todo');
    }
}
