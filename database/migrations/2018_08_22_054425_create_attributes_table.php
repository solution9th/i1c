<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('字段名称');
            $table->string('display')->comment('显示名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('type')->comment('类型');
            $table->integer('min')->nullable()->comment('小于');
            $table->integer('max')->nullable()->comment('大于');
            $table->string('size')->nullable()->comment('限定类型min:less,max:greater,区间:between');
            $table->tinyInteger('must')->default(2)->comment('是否必填');
            $table->json('confine')->nullable()->comment('字段限制');
            $table->tinyInteger('default')->default(2)->comment('是否默认');
            $table->string('formtype')->comment('表单类型 register,login');
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
        Schema::dropIfExists('attributes');
    }
}
