<?php

namespace App\Services\Admin;

use App\Services\BaseService;
use App\Repositories\SeoRepo;
class SeoService extends BaseService
{
    //获取所有的配置信息
    public static function getList()
    {
        return SeoRepo::getList();
    }

    //根据id获取一条信息
    public static function getInfo($id)
    {
        return SeoRepo::getInfo($id);
    }

    public static function modify($data)
    {
       return SeoRepo::modify($data['id'],$data);
    }


}