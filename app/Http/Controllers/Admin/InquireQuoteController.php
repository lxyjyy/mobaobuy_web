<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InquireQuoteService;
class InquireQuoteController extends Controller
{
    //列表
    public function index(Request $request)
    {
        $currpage = $request->input("currpage",1);
        $quote_currpage = $request->input("quote_currpage",1);
        $pageSize = $request->input('pageSize ',10);
        $inquire_id = $request->input('inquire_id',-1);
        $goods_name = $request->input('goods_name','');
        $condition['is_delete'] = 0;
        if($inquire_id!=-1){
            $condition['inquire_id'] = $inquire_id;
        }
        if(!empty($goods_name)){
            $condition['goods_name'] = '%'.$goods_name.'%';
        }
        $inquire_quotes = InquireQuoteService::getInquireQuoteList(['pageSize'=>$pageSize,'page'=>$currpage,'orderType'=>['add_time'=>'desc']],$condition);
        //dd($inquire_quotes);
        return $this->display('admin.inquire.quote_list',[
            'inquire_quotes'=>$inquire_quotes['list'],
            'total'=>$inquire_quotes['total'],
            'pageSize'=>$pageSize,
            'currpage'=>$currpage,
            'quote_currpage'=>$quote_currpage,
            'inquire_id'=>$inquire_id,
            'goods_name'=>$goods_name
        ]);
    }


    //删除
    public function delete(Request $request)
    {
        $id = $request->input('id');
        try{
            $flag = InquireQuoteService::modify(['id'=>$id,'is_delete'=>1]);
            if($flag){
                return $this->success('删除成功',url('/admin/inquireQuote/index'));
            }else{
                return $this->error('删除失败');
            }
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
