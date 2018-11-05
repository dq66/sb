<?php

namespace App\Api\v1\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Api\V1\Model\Article
 *
 * @property int $id
 * @property string|null $title 标题
 * @property string|null $slug 别名
 * @property int $metas_id 分类
 * @property int $user_id 作者
 * @property string|null $cover 封面
 * @property string|null $state 状态:{"1":"发布","0":"草稿"}
 * @property int $is_top 是否置顶该文章
 * @property string|null $summary 摘要
 * @property string|null $markdown Markdown
 * @property string|null $html Html
 * @property int $viewcount 浏览次数
 * @property int|null $favoritecount 点赞次数
 * @property int $order 排序
 * @property string $types types:{"1":"文章","2":"页面","3":"说说"}
 * @property string $criticism criticism:{"1":"允许评论","0":"不允许评论"}
 * @property string $template 模板
 * @property string $publicstate status:{"publish":"公开","hidden":"隐藏","password":"密码保护","private":"私有","waiting":"待审核"}
 * @property string|null $password 密码
 * @property string|null $quote 引用通告
 * @property int $criticismcount 评论数
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\User $Author
 * @property-read \App\Api\V1\Model\Metas $Metas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Api\V1\Model\Tags[] $Tags
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Article onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereCriticism($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereCriticismcount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereFavoritecount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereIsTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereMarkdown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereMetasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article wherePublicstate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereQuote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Api\V1\Model\Article whereViewcount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Article withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Api\V1\Model\Article withoutTrashed()
 * @mixin \Eloquent
 */
class Article extends Model
{
    use SoftDeletes;
    protected $table = 'articles';
    protected $guarded = [];

    /**
     * Function: Metas
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午1:39
     * Notes:
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Metas()
    {
        return $this->belongsTo(Metas::class);
    }

    /**
     * Function: Author
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午1:40
     * Notes:
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Author()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    /**
     * Function: Tags
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午1:40
     * Notes:
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Tags()
    {
        return $this->belongsToMany(Tags::class)->withTimestamps();
    }
}
