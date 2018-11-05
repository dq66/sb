<?php

namespace App\Api\v1\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Api\V1\Model\Tags
 *
 * @property int $id
 * @property string $text 标签名称
 * @property string $color 标签颜色
 * @property string|null $hot 标签热度
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Api\V1\Model\Article[] $Article
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Tags whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tags extends Model
{
    //    use SoftDeletes;
    protected $table = 'tags';
    protected $guarded = [];

    /**
     * Function: Article
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午1:41
     * Notes:
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Article()
    {
        return $this->belongsToMany(Article::class);
    }
}
