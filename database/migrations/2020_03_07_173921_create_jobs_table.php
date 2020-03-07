<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index()->comment('队列名称');
            $table->longText('payload')->comment('队列数据');
            $table->unsignedTinyInteger('attempts')->comment('尝试次数');
            $table->unsignedInteger('reserved_at')->nullable()->comment('保留时间');
            $table->unsignedInteger('available_at')->comment('可运行时间');
            $table->unsignedInteger('created_at')->comment('队列创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
