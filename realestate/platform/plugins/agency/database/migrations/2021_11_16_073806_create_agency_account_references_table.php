<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateAgencyAccountReferencesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('agency_account_references')) {
            Schema::create('agency_account_references', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('agency_id');
                $table->integer('account_id');
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
        Schema::drop('agency_account_references');
    }

}
