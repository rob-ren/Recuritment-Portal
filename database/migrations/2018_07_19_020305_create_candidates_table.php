<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->longText('cv_file_path')->nullable();
            $table->longText('comments')->nullable();
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('recruiter_id')->unsigned();
            $table->foreign('recruiter_id')->references('id')->on('recruiters');
            $table->longText('notice_period')->nullable();
            $table->longText('visa_status')->nullable();
            $table->longText('number_years_experience')->nullable();
            $table->longText('reason_of_leaving')->nullable();
            $table->longText('communication_skills')->nullable();
            $table->timestamps();
            // declair the index
            $table->index(['id', 'rate']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
