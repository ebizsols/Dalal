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

               // $table->float('opening_price');
                $table->float('minimum_selling_price');
                $table->timestamp('start_date')->useCurrent()->getdate();
                $table->timestamp('end_date')->nullable();
                $table->enum('status', array('published', 'unpublished'))->default('published');
                $table->integer('is_featured')->default(0);
                $table->unsignedBigInteger('avatar_id')->nullable()->default(NULL);
                $table->unsignedBigInteger('property_id');
                $table->foreign('property_id')->references('id')->on('re_properties')->onDelete('cascade')->onUpdate('cascade');
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
