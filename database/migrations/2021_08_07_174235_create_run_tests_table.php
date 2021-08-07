<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('run_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained();
            $table->foreignId('test_id')->constrained();
            $table->enum('result', ['pass', 'fail', 'skip'])->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('run_tests');
    }
}
