<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Api\v1\Model\Comment;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::with('article')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        dump($request->all());
        // $data = [
        //     'article_id' => 2,
        //     'parent' => 0,
        //     'is_boke' => 0,
        //     'username' => '22',
        //     'email' => '22@qq.com',
        //     'url' => 'baidu.com',
        //     'content' => '测试测试测试'
        // ];
        // return Comment::create($data);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dump($id);
        // dump($request->post());
        return Comment::where('id','=',$id)->update($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
