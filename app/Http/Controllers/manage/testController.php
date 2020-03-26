<?php


namespace App\Http\Controllers\manage;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class testController extends Controller
{
    function add(Request $request){
        if($request->isMethod('get')){
            return $this->success('获取信息成功！这是get方法',$request->isMethod('get'));
        }
        if($request->isMethod('post')){
            return $this->success('获取信息成功！这是post方法',$request->isMethod('get'));
        }
        if($request->isMethod('delete')){
            return $this->success('获取信息成功！这是delete方法',$request->isMethod('get'));
        }
        if($request->isMethod('put')){
            return $this->success('获取信息成功！这是put方法',$request->isMethod('get'));
        }
        return 0;
    }
}