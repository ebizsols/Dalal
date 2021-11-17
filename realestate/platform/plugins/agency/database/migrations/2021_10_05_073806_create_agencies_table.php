<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenciesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('agencies')) {
            Schema::create('agencies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->longText('description');
                $table->string('email');
                $table->integer('avatar_id');
                $table->bigInteger('phone');
                $table->bigInteger('fax');
                $table->integer('is_featured');
                $table->enum('status', array('published', 'unpublished'));
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('agencies');
    }

}
