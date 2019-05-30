<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('项目名称');
            $table->string('description')->nullable()->comment('项目描述');
            $table->string('pid')->unique()->comment('项目唯一标识');
            $table->string('path')->comment('项目域');
            $table->json('loginfield')->nullable()->comment('登录字段');
            $table->string('logintitle')->nullable()->comment('登录显示标题');
            $table->json('regfield')->nullable()->comment('注册字段');
            $table->string('regtitle')->nullable()->comment('注册显示标题');
            $table->integer('number')->nullable()->comment('统计人数');
            $table->string('user')->comment('操作人');
            $table->string('logo')->comment('项目logo地址');
            $table->mediumText('protocol')->comment('项目协议');
            $table->timestamp('regstart')->nullable()->comment('注册开始时间');
            $table->timestamp('regend')->nullable()->comment('注册截止时间');
            $table->timestamp('loginstart')->nullable()->comment('登录开始时间');
            $table->timestamp('loginend')->nullable()->comment('登录截止时间');
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
        Schema::dropIfExists('projects');
    }
}
