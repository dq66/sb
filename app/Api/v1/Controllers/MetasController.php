<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Model\Article;
use App\Api\v1\Model\Metas;
use Illuminate\Http\Request;

class MetasController extends ApiController
{
    /**
     * Function: index
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午2:04
     * Notes: 获取全部分类
     * @return Metas[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function index()
    {
        $metas = Metas::withTrashed()
            ->get();
        return $metas;
    }

    /**
     * Function: create
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/10 / 下午3:58
     * Notes: 添加分类
     * @param Request $request
     * @return Metas|\Illuminate\Database\Eloquent\Model|mixed
     * @throws \Jiaxincui\ClosureTable\Exceptions\ClosureTableException
     */
    public function create(Request $request)
    {
        if ($request->post("parent") != 0) {
            $input = $request->except(['parent']);
            $parent = Metas::where("id", "=", $request->post("parent"))->first();
            $child = $parent->createChild($input);
            //更新子分类
            Metas::where("id", "=", $request->post('parent'))
                ->update([
                    "typescount" => Metas::where("parent", "=", $request->post('parent'))
                        ->count()
                ]);

            $request->post('slug') ?? Metas::where("id", "=", $child->id)
                ->update(["slug" => $request->post('label')]);

            return $child;
        } else {
            $Metas = Metas::create($request->post());
            // 验证下别名是否为空,不为空不管 为空更新别名
            $request->post('slug') ?? Metas::where("id", "=", $Metas->id)
                ->update(["slug" => $request->post('label')]);

            return $Metas;
        }
    }

    /**
     * Function: destroy
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/11 / 上午9:02
     * Notes: 软删除
     * @param $id
     */
    public function destroy($id)
    {
        // 如果存在子分类 则抛出错误 免得误删
        if (Metas::where("parent", "=", $id)->count()) {
            return $this->response->error("请先删除该分类下存在的子分类", 520);
        } else {
            if (Article::where("metas_id", "=", $id)->count() > 0) {
                return $this->response->error("请先删除该分类下的文章", 520);
            } else {
                if (Metas::destroy($id)) {
                    return $this->response->array(["message" => "以将该条数据放入回收站,你可以点击恢复来恢复该条数据!"])->setStatusCode(200);
                } else {
                    return $this->response->error("放入回收站失败!", 520);
                }
            }
        }
    }

    /**
     * Function: delete
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/11 / 上午9:06
     * Notes:彻底删除数据
     * @param $id
     */
    public function delete($id)
    {
        $delete = Metas::where("id", "=", $id)->forceDelete();
        // 修复树关联
        // Metas::find($id)->perfectTree();
        // 清理冗余的关联信息
        if ($delete) {
            Metas::deleteRedundancies();
            return $this->response->array(["message" => "删除数据成功!"])->setStatusCode(200);
        } else {
            return $this->response->error("删除失败!", 520);
        }
    }

    /**
     * Function: restore
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/11 / 上午9:08
     * Notes: 恢复软删除
     * @param $id
     */
    public function restore($id)
    {
        $metas = Metas::withTrashed()->find($id);
        $me = $metas->restore();
        // 修复树关联
        Metas::find($id)->perfectTree();
        // 清理冗余的关联信息
        Metas::deleteRedundancies();
        if ($me) {
            return $this->response->array(["message" => "删除恢复成功!"])->setStatusCode(200);
        } else {
            return $this->response->error("恢复数据失败!", 520);
        }
    }

    /**
     * Function: update
     * Author: Teeoo <oo.ee.ooe.teeoo@gmail.com>
     * Time: 2018/9/11 / 上午10:16
     * Notes:
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $me = Metas::find($id);
        $parent = $me->parent;
        $metas = Metas::where("id", "=", $id)->update($request->post());
        if ($metas) {
            // 更新之前子分类的数量
            Metas::where("id", "=", $parent)
                ->update([
                    "typescount" => Metas::where("parent", "=", $parent)
                        ->count()
                ]);
            // 更新现在子分类的数量
            Metas::where("id", "=", $request->post('parent'))
                ->update([
                    "typescount" => Metas::where("parent", "=", $request->post('parent'))
                        ->count()
                ]);
            // 修复树关联
            Metas::find($id)->perfectTree();
            // 清理冗余的关联信息
            Metas::deleteRedundancies();
            return $this->response->array(["message" => "修改分类成功!"])->setStatusCode(200);
        } else {
            return $this->response->error("数据修改失败了!", 520);
        }

    }
}
