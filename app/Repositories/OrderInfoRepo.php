<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2017/4/18
 * Time: 14:56
 */

namespace App\Repositories;

class OrderInfoRepo
{
    use CommonRepo;
    public static function orderList($userId){
        $clazz = self::getBaseModel();
        $query = $clazz::query();
        return $query->where('user_id',$userId)->paginate(10);
    }
}