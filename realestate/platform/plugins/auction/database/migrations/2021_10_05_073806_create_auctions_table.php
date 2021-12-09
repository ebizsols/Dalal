<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('auctions')) {
            Schema::create('auctions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->longText('description')->nullable();
                $table->float('price');
                $table->string('image')->nullable()->default(NULL);
                $table->unsignedBigInteger('property_id');
                $table->foreign('property_id')->references('id')->on('re_properties');
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
        Schema::drop('auctions');
    }

}
