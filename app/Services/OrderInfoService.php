<?php
namespace App\Services;
use App\Repositories\OrderInfoRepo;
use App\Repositories\OrderGoodsRepo;
use App\Repositories\ShopGoodsQuoteRepo;
use App\Repositories\GoodsRepo;
use App\Repositories\UserInvoicesRepo;
class OrderInfoService
{
    use CommonService;

    //列表（分页）
    public static function getOrderInfoList($pager, $condition)
    {
        return OrderInfoRepo::getListBySearch($pager, $condition);
    }

    //查询一条数据
    public static function getOrderInfoById($id)
    {
        return OrderInfoRepo::getInfo($id);
    }


    public static function modify($data)
    {
        return OrderInfoRepo::modify($data['id'], $data);
    }

    //获取订单商品信息
    public static function getOrderGoodsByOrderid($order_id)
    {
        $order_goods = OrderGoodsRepo::getList([], ['order_id' => $order_id]);
        foreach ($order_goods as $k => $vo) {
            $shop_goods_quote = ShopGoodsQuoteRepo::getInfo($vo['shop_goods_quote_id']);
            $good = GoodsRepo::getInfo($vo['goods_id']);
            $order_goods[$k]['shop_name'] = $shop_goods_quote['shop_name'];
            $order_goods[$k]['brand_name'] = $good['brand_name'];
        }
        return $order_goods;
    }

    //获取收货地址信息
    public static function getConsigneeInfo($id)
    {
        return OrderInfoRepo::getList([], ['id' => $id], ['consignee', 'country', 'province', 'city', 'district', 'street', 'address', 'zipcode', 'mobile_phone'])[0];
    }

    //获取发票信息
    public static function getInvoiceInfo($id)
    {
        return UserInvoicesRepo::getInfo($id);
    }

    //修改商品信息
    public static function modifyOrderGoods($data)
    {
        return OrderGoodsRepo::modify($data['id'], $data);
    }

    //更新订单的商品总金额
    public static function modifyGoodsAmount($id)
    {
        $orderInfo = OrderInfoRepo::getInfo($id);
        //查询该所有的商品
        $orderGoods = OrderGoodsRepo::getList([], ['order_id' => $orderInfo['id']]);
        $sum = 0;
        foreach ($orderGoods as $k => $v) {
            $sum += $v['goods_price'] * $v['goods_number'];
        }
        return OrderInfoRepo::modify($id, ['goods_amount' => $sum]);
    }


    /**
     * 根据条件查询
     * @param $where
     * @return array
     */
    public static function getOrderInfoByWhere($where)
    {
        return OrderInfoRepo::getInfoByFields($where);
    }

}
