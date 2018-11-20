<?php
namespace App\Services;

use App\Repositories\ActivityWholesaleRepo;

use App\Repositories\GoodsRepo;
use App\Repositories\OrderGoodsRepo;
use App\Repositories\OrderInfoRepo;
use App\Repositories\UserCollectGoodsRepo;

use Carbon\Carbon;

class ActivityWholesaleService
{
    use CommonService;


    /**
     * 根据条件查询列表 —— 分页
     * @param $pager
     * @param $where
     * @return mixed
     */
    public static function getListBySearch($pager,$where)
    {
        return ActivityWholesaleRepo::getListBySearch($pager,$where);
    }

    /**
     * 根据id获取详情
     * @param $id
     * @return array
     */
    public static function getInfoById($id)
    {
        $res = ActivityWholesaleRepo::getInfo($id);
        $goods_info = GoodsService::getGoodInfo($res['goods_id']);
        $cat_info = GoodsCategoryService::getInfo($goods_info['cat_id']);
        $res['cat_id'] = $goods_info['cat_id'];
        $res['cat_name'] = $cat_info['cat_name'];
        return $res;
    }

    /**
     * 创建
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        $data['add_time'] = Carbon::now();
        return ActivityWholesaleRepo::create($data);
    }

    /**
     * 编辑
     * @param $id
     * @param $data
     * @return bool
     */
    public static function updateById($id,$data)
    {
        return ActivityWholesaleRepo::modify($id,$data);
    }

    /**
     * delete
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        return ActivityWholesaleRepo::delete($id);
    }


    public static function getList($params=[], $page = 1 ,$pageSize=10){
        $condition = [];
        if(isset($params['status'])){
            $condition['review_status'] = $params['status'];
        }
        if(isset($params['end_time'])){
            $condition['end_time|>'] = Carbon::now();
        }
        if(!empty($params['goods_name'])){
            $condition['goods_name'] = '%'.$params['goods_name'].'%';
        }

        $info_list = ActivityWholesaleRepo::getListBySearch(['pageSize'=>$pageSize, 'page'=>$page, 'orderType'=>['end_time'=>'desc']],$condition);
        foreach ($info_list['list'] as &$item){
            if(Carbon::now()->gt($item['end_time'])){
                $item['is_over'] = true;
            }else{
                $item['is_over'] = false;
            }

            if(Carbon::now()->lt($item['begin_time'])){
                $item['is_soon'] = true;
            }else{
                $item['is_soon'] = false;
            }
        }
        unset($item);
        return $info_list;
    }


    //web
    //限时抢购
    public static function buyLimit($condition){
        $info_list = ActivityWholesaleRepo::getList([],$condition);
        foreach ($info_list as &$item){
            if(Carbon::now()->gt($item['end_time'])){
                $item['is_over'] = true;
            }else{
                $item['is_over'] = false;
            }

            if(Carbon::now()->lt($item['begin_time'])){
                $item['is_soon'] = true;
            }else{
                $item['is_soon'] = false;
            }
        }
        unset($item);
        //未结束
        $buyLimitArr = [];
        //已结束
        $buyLimitArrOver = [];
        foreach($info_list as $k=>$v){
            if($v['is_over'] == false){
                $buyLimitArr[] = $v;
            }else{
                $buyLimitArrOver[] = $v;
            }
        }
        foreach ($buyLimitArrOver as $kk=>$vv){
            $keyLen = count($buyLimitArr) + 1;
            $buyLimitArr[$keyLen] = $vv;
        }
        return $buyLimitArr;
    }

    //限时抢购详情
    public static function buyLimitDetails($id,$userId){
        $id = decrypt($id);
        $ActivityInfo =  ActivityWholesaleRepo::getInfo($id);
        if(empty($ActivityInfo)){
            self::throwBizError('促销商品不存在');
        }
        $goodsInfo = GoodsRepo::getInfo($ActivityInfo['goods_id']);
        if(empty($goodsInfo)){
            self::throwBizError('产品不存在');
        }

        $goodsInfo['activity_price'] = $ActivityInfo['price'];
        $goodsInfo['activity_num'] = $ActivityInfo['num'];
        $goodsInfo['available_quantity'] = $ActivityInfo['available_quantity'];
        $goodsInfo['activity_id'] = $ActivityInfo['id'];
        $goodsInfo['min_limit'] = $ActivityInfo['min_limit'];
        $goodsInfo['goods_name'] = $ActivityInfo['goods_name'];
        //活动有效期总秒数
        $goodsInfo['seconds'] = strtotime($ActivityInfo['end_time']) - time();
        //产品市场价
        $goodsList = GoodsRepo::getList([],['id'=>$ActivityInfo['goods_id']]);
        $goodsInfo['goodsList'] = $goodsList;

        //产品是否已收藏
        $collectGoods= UserCollectGoodsRepo::getInfoByFields(['user_id'=>$userId,'goods_id'=>$ActivityInfo['goods_id']]);
        if(empty($collectGoods)){
            $goodsInfo['collectGoods'] = 0;
        }else{
            $goodsInfo['collectGoods'] = 1;
        }
        return $goodsInfo;
    }

    //限时抢购 立即下单
    public static function buyLimitToBalance($goodsId,$activityId,$goodsNum,$userId){
        $goodsInfo = GoodsRepo::getInfo($goodsId);
        $activityInfo = ActivityWholesaleRepo::getInfo($activityId);

        //先判断活动有效期
        if(strtotime($activityInfo['end_time']) < time()){
            self::throwBizError('该活动已结束！');
        }
        //规格判断处理
        if($goodsNum > $activityInfo['available_quantity']){
            self::throwBizError('超出当前可售数量');
        }
        if($goodsNum < $activityInfo['min_limit']){
            self::throwBizError('不能低于起售数量');
        }

        if($goodsNum % $goodsInfo['packing_spec'] == 0){
            $goodsNumber = $goodsNum;
        }else{
            if($goodsNum > $goodsInfo['packing_spec']){
                $yuNumber = $goodsNum % $goodsInfo['packing_spec'];
                $dNumber = $goodsInfo['packing_spec'] - $yuNumber;
                $goodsNumber = $goodsNum + $dNumber;
            }else{
                $goodsNumber = $goodsInfo['packing_spec'];
            }
        }

        //商品信息
        $activityInfo['goods_number'] = $goodsNumber;
        $activityInfo['account_money'] = $goodsNumber * $activityInfo['price'];
        $activityInfo['goods_price'] = $activityInfo['price'];
        $activityArr = [];
        $activityArr[] = $activityInfo;
        return $activityArr;
    }

    //通过id查抢购表数据
    public static function getActivityWholesaleById($id){
        $id = decrypt($id);
        $activityPromoteInfo = ActivityWholesaleRepo::getInfo($id);
        if(empty($activityPromoteInfo)){
            self::throwBizError('不存在的商品信息');
        }
    }

    //增加限时抢购的点击量
    public static function addClickCount($id)
    {
        $id = decrypt($id);
        return ActivityWholesaleRepo::addClickCount($id);
    }

    public static function buyLimitMaxLimit($userId,$id,$goodsNumber){
        $id = decrypt($id);
        $activityInfo = ActivityWholesaleRepo::getInfo($id);
        if(empty($activityInfo)){
            self::throwBizError('商品信息有误');
        }
        if($activityInfo['max_limit'] != 0){
            $orderList = OrderInfoRepo::getList([],['firm_id'=>$userId,'extension_id'=>$id]);
            $goodsCount = 0;
            foreach($orderList as $v){
                $goodsCount += OrderGoodsRepo::getInfoByFields(['order_id'=>$v['id']])['goods_number'];
            }
            $goodsCount += $goodsNumber;
            if($goodsCount > $activityInfo['max_limit']){
                self::throwBizError('超出最大限量');
            }
            $data['max_limit'] = $activityInfo['max_limit'];
            $data['can_buy_num'] = $activityInfo['max_limit'] - $goodsCount;
            return $data;
        }



    }

}