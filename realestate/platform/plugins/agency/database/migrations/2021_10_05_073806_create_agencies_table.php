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
                $table->longText('description')->default(NULL);
                $table->string('email')->default(NULL);
                $table->unsignedInteger('avatar_id')->default(NULL);
                $table->bigInteger('phone')->default(NULL);
                $table->bigInteger('fax')->default(NULL);
                $table->integer('is_featured')->default(0);
                $table->enum('status', array('published', 'unpublished'))->default('published');
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
