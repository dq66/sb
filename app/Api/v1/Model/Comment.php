<?php

namespace App\Api\v1\Model;

use Illuminate\Database\Eloquent\Model;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

class Comment extends Model
{
    use ClosureTable;
    protected $table = "comments";
    // 关联表名，默认'Model类名+_closure',如'menu_closure'
    protected $closureTable = 'comments_closure';
    // parent列名,默认'parent',此列是计算生成,不在数据库存储
    protected $parentColumn = 'parent';
    protected $guarded = [];

    public function Article(){
        return $this->belongsTo(Article::class,"article_id","id");
    }
}
