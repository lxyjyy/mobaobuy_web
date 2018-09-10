<?php

namespace App\Services\Admin;

<<<<<<< HEAD:app/Services/FirmBlacklistService.php
use App\Services\BaseService;
use App\Repositories\FirmBlacklistRepository;
class FirmBlacklistService
=======
use App\Services\CommonService;
use App\Repositories\FirmBlacklistRepo;
class FirmBlacklistService extends CommonService
>>>>>>> 039764dbb692d11bb288c6921e8081269efa3aaf:app/Services/Admin/FirmBlacklistService.php
{
    use BaseService;

    //获取黑名单列表(导出excel表)
    public static function getBlacklists($fields)
    {
        $info = FirmBlacklistRepo::getBlacklists($fields);
        return $info;
    }

    //获取黑名单列表（分页）
    public static function getList($pageSize,$firm_name)
    {
        $info = FirmBlacklistRepo::search($pageSize,$firm_name);
        return $info;
    }

    //添加
    public static function create($data)
    {
        $flag = FirmBlacklistRepo::create($data);
        return $flag;
    }



    //获取黑名单总条数
    public static function getCount($firm_name)
    {
        return FirmBlacklistRepo::getCount($firm_name);
    }

    //检测企业名称是否已经存在
    public static function validateUnique($firm_name)
    {
        return FirmBlacklistRepo::getInfoByFields(['firm_name'=>$firm_name]);
    }

    //删除
    public static function delete($id)
    {
        return FirmBlacklistRepo::delete($id);
    }



}