<?php

namespace App\Api\v1\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

/**
 * App\Api\V1\Model\Metas
 *
 * @property int $id
 * @property int $parent
 * @property string $label 类型名
 * @property string|null $slug 别名
 * @property int $postcount 该分类下文章总数
 * @property string|null $description 描述
 * @property int|null $typescount 子分类数量
 * @property int|null $order 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Api\V1\Model\Article[] $Article
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas isolated()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas onlyRoot()
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Metas onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas wherePostcount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereTypescount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Metas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Metas withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Metas withoutTrashed()
 * @mixin \Eloquent
 */
class Metas extends Model
{
    use SoftDeletes;
    use ClosureTable;
    protected $table = 'metas';
    protected $guarded = [];

    /**
     * Function: Article
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午1:40
     * Notes:
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Article()
    {
        return $this->hasMany(Article::class);
    }
}
