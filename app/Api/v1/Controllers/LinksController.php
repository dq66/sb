<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Api\v1\Model\Links;

class LinksController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Links::all();
    }

    /**
     * 添加
     *
     * param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dump($request->post());
        $data = Links::create($request->post());
        return $data;
    }


    /**
     * 修改
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = links::where('id','=',$id)->update($request->post());
        return $data;
    }

    /**
     * 删除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data = Links::where('id','=',$id)->delete();
        if($data){
            return $this->response->array(["message" => "已删除该条数据!"])->setStatusCode(200);
        }else{
            return $this->response->error("删除该条数据失败!", 520);
        }
    }

    /**
     * 上传图片
     */
    public function upload(Request $request){
        $file = $request->file('file');
        dump($request->post());
        if ($file->isValid()) {
            $path = $file->store(date('ymd'), 'upload');
            return ['code'=>0, 'url'=>asset('/upload/' . $path)];
        }else{
            return ['code'=>1];
        }
    }
}
