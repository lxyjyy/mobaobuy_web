<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-09-19
 * Time: 14:42
 */
namespace App\Services;

use App\Repositories\ShopRepo;
use App\Repositories\ShopUserRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ShopLoginService
{
    use CommonService;

    /**
     * 商户入驻
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function Register($data)
    {
        try {
            if (self::checkShopNameExists($data['shop_name'])){
                self::throwBizError('该店铺已注册');
            }
            if (self::checkCompanyExists($data['company_name'])){
                self::throwBizError('该企业已注册');
            }
            if (self::checkUserExists($data['user_name'])){
                self::throwBizError('该用户已注册');
            }

            $data['password'] = bcrypt($data['password']);
            $shop_data = [
                'user_id' => $data['user_id'],
                'company_name' => $data['company_name'],
                'shop_name' => $data['shop_name'],
                'attorney_letter_fileImg' => $data['attorney_letter_fileImg'],
                'license_fileImg' => $data['license_fileImg'],
                'reg_time' => Carbon::now(),
                'is_validated' => 0
            ];
            self::beginTransaction();
            $shop_info = ShopRepo::create($shop_data);
            if (!empty($shop_info)){
                $user_data = [
                    'shop_id' => $shop_info['id'],
                    'user_name' => $data['user_name'],
                    'password' =>$data['password'],
                    'add_time' =>Carbon::now(),
                    'is_super' => 1
                ];
                $user_res = ShopUserRepo::create($user_data);
                if (!empty($user_res)){
                    self::commit();
                    return true;
                } else {
                    self::rollBack();
                    return false;
                }
            }
        } catch (\Exception $e) {
            self::rollBack();
            self::throwBizError($e);
        }
    }

    public static function CheckLogin($data)
    {
        if (empty($data)){
            return ;
        }
        $user_info = ShopUserRepo::getInfoByFields(['user_name'=>$data['user_name']]);
        if (empty($user_info)){
            self::throwBizError('用户不存在');
        }
        if (!Hash::check($data['password'],$user_info['password'])){
            self::throwBizError('密码不正确');
        }
        $shop_info = ShopRepo::getInfoByFields(['id'=>$user_info['shop_id']]);
        if ($shop_info['is_validated'] == 0){
            self::throwBizError('该店铺未通过审核，暂不能登录，请耐心等待');
        }
        if ($shop_info['is_freeze'] == 1){
            self::throwBizError('该店铺已经冻结，暂不能登录');
        }
        // 验证成功后，创建事件
        createEvent('sellerUserLogin',['shop_id'=>$shop_info['id'],'user_id'=>$user_info['id'],'ip'=>$data['ip']]);
        return ['shop_id'=>$user_info['shop_id'],'user_id'=>$user_info['id']];
    }
    /**
     * 发送短信
     * @param $mobile
     * @return mixed|\stdClass
     * @throws \Exception
     */
    public static function sendSMSCode($type,$mobile,$code)
    {
        $params = [
            'code'=>$code
        ];
        return SmsService::sendSms($mobile, $type, $params);
    }

    public static function getMsgSession()
    {
        return Session::get('register_code');
    }

    /**
     * 获取商户管理者基本信息
     * @param $id
     * @return array
     */
    public static function getInfo($id)
    {
        $user_info = ShopUserRepo::getInfo($id);
        unset($user_info['password']);
        return $user_info;
    }

    /**
     * 检查店铺名是否存在
     * @param $ShopName
     * @return bool
     */
    public static function checkShopNameExists($ShopName)
    {
       if (ShopRepo::getTotalCount(['shop_name'=>$ShopName])){
           return true;
       }
       return false;
    }

    /**
     * 检查企业是否已经注册
     * @param $company_name
     * @return bool
     */
    public static function checkCompanyExists($company_name)
    {
        if (ShopRepo::getTotalCount(['company_name'=>$company_name])){
            return true;
        }
        return false;
    }

    /**
     * 检查用户是否已经存在
     * @param $user_name
     * @return bool
     */
    public static function checkUserExists($user_name)
    {
        if (ShopUserRepo::getTotalCount(['user_name'=>$user_name])){
            return true;
        }
        return false;
    }

}
