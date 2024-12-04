<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_intervals', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('worker_id');
            $table->integer('task');
            $table->string('status')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_intervals');
    }
}
