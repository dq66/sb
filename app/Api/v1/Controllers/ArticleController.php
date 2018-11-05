<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Model\Article;
use App\Api\v1\Model\Metas;
use App\Api\v1\Model\Tags;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends ApiController
{
    /**
     * Function: index
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/11 / 上午10:42
     * Notes: 所有文章
     * @return Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function index()
    {
        $article = Article::withTrashed()->with("Metas")->with("tags")->with("Author")->get();
        return $article;
    }

    /**
     * Function: create
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/12 / 下午1:51
     * Notes:
     * @param Request $request
     */
    public function create(Request $request)
    {
        // timing 定时时间
        $input = $request->except(['tags', 'timing']);
        // 取出文章的第一张图片作为头图 $out[1][0]
        $pattern = "/src=\\\\?[\\\"']?([\\w\\.\\/-]*)\\\\?[\\\"']?\s/";
        preg_match_all($pattern,
            $request->post('html'),
            $out);
        // 如果有就用文章里图片,如果没有就给张默认的
        $out = $out[1][0] ?? "uploads/2018-09-05-7.png";
        $data = array_merge($input, [
            "cover" => $out,
            "summary" => '',
            "user_id" => \auth()->id()
        ]);
        $article = Article::create($data);
        // 添加tags
        foreach ($request->post("tags") as $item) {
            $res = Tags::firstOrCreate($item);
            $ids[] = $res->id;
        }
        // 添加关联表信息
        $article->Tags()->attach($ids);
        // 更新分类下文章的数量
        Metas::where("id", "=", $request->post("metas_id"))
            ->update(
                [
                    "postcount" => Article::where("metas_id", "=", $request->post("metas_id"))
                        ->count()
                ]
            );
        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dump($request->post());
        //查询原来的数据
        $yarticle = Article::find($id);

        // timing 定时时间
        $input = $request->except(['tags', 'timing']);
        // 取出文章的第一张图片作为头图 $out[1][0]
        $pattern = "/src=\\\\?[\\\"']?([\\w\\.\\/-]*)\\\\?[\\\"']?\s/";
        preg_match_all($pattern,
            $request->post('html'),
            $out);
        // 如果有就用文章里图片,如果没有就给张默认的
        $out = $out[1][0] ?? "uploads/2018-09-05-7.png";
        $data = array_merge($input, [
            "cover" => $out,
            "summary" => '',
            "user_id" => \auth()->id()
        ]);

        $article = Article::where('id','=',$id)->update($data);

        if(!is_null($request->post("tags"))){
            // 添加tags
            foreach ($request->post("tags") as $item) {
                $res = Tags::firstOrCreate(['text'=>$item['text']]);
                $ids[] = $res->id;
            }
            //使用集合取出所有的id
            $yids = collect(Article::find($id)->tags()->get())->map(function ($k) {
                return $k['id'];
            });
            //判断原来的tags是否多余现在提交的tags
            //如果原来的比现在的多就删除原来多余的关系
            //如果原来的比现在的少那么那么清除之前所有的添加现在的tags
            if (count($yids->toArray()) > count($ids)) {
                Article::find($id)->tags()->detach(array_diff($yids->toArray(), $ids));
            } else {
                Article::find($id)->tags()->detach();
                Article::find($id)->tags()->attach($ids);
            }
        }

        //判断现在的分类跟之前的分类是否相等
        //不相等　就更新之前分类下文章的数量　再更新现在的分类下文章的数量
        //相等　不做任何操作
        if($yarticle->metas_id != $request->post("metas_id")){
            //　更新之前分类下文章的数量
            Metas::where("id", "=", $yarticle->metas_id)
            ->update(
                [
                    "postcount" => Article::where("metas_id", "=", $yarticle->metas_id)
                        ->count()
                ]
            );
            // 更新现在分类下文章的数量
            Metas::where("id", "=", $request->post("metas_id"))
            ->update(
                [
                    "postcount" => Article::where("metas_id", "=", $request->post("metas_id"))
                        ->count()
                ]
            );
        }

        return $article;
    }

    /**
     * 软删除
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Article::where('criticismcount','>',0)->where('id','=',$id)->get();
        if(!$data->isEmpty()){
            return $this->response->error("请先删除该文章下的评论", 520);
        }else{
            if(Article::destroy($id)){
                return $this->response->array(["message" => "以将该条数据放入回收站,你可以点击恢复来恢复该条数据!"])->setStatusCode(200);
            }else{
                return $this->response->error("放入回收站失败!", 520);
            }
        }
    }

    /**
     * 恢复软删除
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $article = Article::withTrashed()->find($id);

        if ($article->restore()) {
            return $this->response->array(["message" => "删除恢复成功!"])->setStatusCode(200);
        } else {
            return $this->response->error("恢复数据失败!", 520);
        }
    }

    /**
     * 彻底删除数据
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //先把原来的数据拿出来
        $article = Article::withTrashed()->find($id);
        //执行彻底删除文章
        $delete = Article::where("id", "=", $id)->forceDelete();

        if ($delete) {
            // 更新当前文章分类下文章的数量
            Metas::where("id", "=", $article['metas_id'])
            ->update(
                [
                    "postcount" => Article::where("metas_id", "=", $article['metas_id'])
                        ->count()
                ]
            );
            return $this->response->array(["message" => "删除数据成功!"])->setStatusCode(200);
        } else {
            return $this->response->error("删除失败!", 520);
        }
    }

}
