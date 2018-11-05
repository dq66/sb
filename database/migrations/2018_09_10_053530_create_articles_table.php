<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->default('默认标题')->comment('标题');
            $table->string('slug')->nullable()->comment('别名');
            $table->integer('metas_id')->unsigned()->comment('分类');
            $table->foreign('metas_id')->references('id')->on('metas');
            $table->integer('user_id')->unsigned()->comment('作者');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('cover')->nullable()->comment('封面');
            $table->string('state')->nullable(1)->comment('状态:{"1":"发布","0":"草稿"}');
            $table->boolean('is_top')->default(0)->comment('是否置顶该文章');
            $table->string('summary')->nullable()->comment('摘要');
            $table->text('markdown')->nullable()->comment('Markdown');
            $table->text('html')->nullable()->comment('Html');
            $table->integer('viewcount')->default(0)->unsigned()->comment('浏览次数');
            $table->integer('favoritecount')->default(0)->nullable()->unsigned()->comment('点赞次数');
            $table->integer('order')->default(0)->comment('排序');
            $table->string('types')->default('1')->comment('types:{"1":"文章","2":"页面","3":"说说"}');
            $table->string('criticism')->default('1')->comment('criticism:{"1":"允许评论","0":"不允许评论"}');
            $table->string('template')->default('')->comment('模板');
            $table->string('publicstate')->default('publish')->comment('status:{"publish":"公开","hidden":"隐藏","password":"密码保护","private":"私有","waiting":"待审核"}');
            $table->string('password')->nullable()->comment('密码');
            $table->string('quote')->nullable()->comment('引用通告');
            $table->integer('criticismcount')->default('0')->comment('评论数');
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
        Schema::dropIfExists('articles');
    }
}
