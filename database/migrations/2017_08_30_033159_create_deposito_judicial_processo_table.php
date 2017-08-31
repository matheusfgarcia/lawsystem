<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositoJudicialProcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('deposito_judicial_processo', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('deposito_judicial_id')->unsigned()->nullable();
            $table->integer('processo_id')->unsigned()->nullable();
            $table->double('deposito_valor',15,2)->default(0)->nullable();
            $table->timestamps();

        });


        Schema::table('deposito_judicial_processo', function($table) {

            $table->foreign('deposito_judicial_id')->references('id')->on('deposito_judicial');

            $table->foreign('processo_id')->references('id')->on('processos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}