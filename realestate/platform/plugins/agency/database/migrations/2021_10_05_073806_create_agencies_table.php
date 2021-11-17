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
                $table->longText('description')->nullable()->default(NULL);
                $table->string('email')->nullable()->default(NULL);
                $table->unsignedInteger('avatar_id')->nullable()->default(NULL);
                $table->bigInteger('phone')->nullable()->default(NULL);
                $table->bigInteger('fax')->nullable()->default(NULL);
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
