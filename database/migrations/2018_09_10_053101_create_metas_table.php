<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('parent')->default(0);
            $table->string('label')->unique()->comment('类型名');//->unique()
            $table->string('slug')->nullable()->comment('别名');
            $table->integer("postcount")->default(0)->comment('该分类下文章总数');
            $table->string('description')->nullable()->comment('描述');
            $table->integer('typescount')->nullable()->default(0)->comment('子分类数量');
            $table->integer('order')->nullable()->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('metas_closure', function (Blueprint $table) {
            $table->unsignedInteger('ancestor');
            $table->unsignedInteger('descendant');
            $table->unsignedTinyInteger('distance');
            $table->primary(['ancestor', 'descendant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
}
