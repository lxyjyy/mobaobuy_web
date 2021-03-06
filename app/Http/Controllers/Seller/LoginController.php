<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-09-18
 * Time: 16:32
 */
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\GsxxService;
use App\Services\ShopLoginService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')){
            if (!empty(session('_seller_id'))){
                return $this->redirect('/seller');
            }
            return $this->display('seller.login');
        } else {
            $type = $request->input('type');
            $user_name = $request->input('user_name','');
            $password = base64_decode($request->input('password',''));
            $mobile = $request->input('mobile','');
            $mobileCode = $request->input('mobileCode','');
            if ($type=='pwdLogin'){
                if (empty($user_name)){
                    return $this->error('用户名不能为空');
                }
                if (empty($password)){
                    return $this->error('密码不能为空');
                }
            } else {
                return $this->error('网络错误');
            }

            $request->setTrustedProxies(['116.226.54.5'],0);
            $params = [
                'user_name'=>$user_name,
                'password'=>$password,
                'ip'=>$request->getClientIp()
            ];
            try{
                $seller_id = ShopLoginService::CheckLogin($params);
                if ($seller_id){
                    session()->put('_seller_id',$seller_id);
                    return $this->success('登录成功,正在进入系统');
                } else {
                    return $this->error('登录失败');
                }
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 验证是否存在店铺名
     * @param Request $request
     * @return LoginController
     */
    public function checkShopName(Request $request)
    {
        $checkShopName = $request->input('shop_name','');
        if (!$checkShopName){
            return $this->error('参数为空');
        }
        $re = ShopLoginService::checkShopExists($checkShopName);
        if ($re['status']==4){
            return $this->error($re['msg']);
        }
        return $this->success('');
    }

    /**
     * 验证企业(企查查等)
     * @param Request $request
     * @return LoginController
     */
    public function checkCompany(Request $request)
    {
        $CompanyName = $request->input('company_name','');
        $re = GsxxService::GsSearch($CompanyName);
        if ($re){
            return $this->result([],'200','验证通过');
        } else {
            return $this->error('验证失败');
        }
    }

    /**
     * 注册商户
     * @param Request $request
     * @return LoginController|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function register(Request $request)
    {
        if ($request->isMethod('get')){
            return $this->display('seller.register');
        } else {
            $user_id = $request->input('user_id','0');
            $company_name = $request->input('companyName','');
            $attorney_letter_fileImg = $request->input('attorney_letter_fileImg','');
            $license_fileImg = $request->input('license_fileImg','');
            $password = base64_decode($request->input('password', ''));
            $mobile= $request->input('mobile','');
            $mobile_code = $request->input('mobile_code','');

            $type = 'sms_signup';
            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.$mobile) != $mobile_code){
                return $this->error('手机验证码不正确');
            }

            if (empty($company_name)){
                return $this->error('企业名称不能为空');
            }

            if (empty($attorney_letter_fileImg)){
                return $this->error('授权委托书电子版不能为空');
            }

            if (empty($license_fileImg)){
                return $this->error('营业执照副本电子版不能为空');
            }

            if (empty($password)){
                return $this->error('密码不能为空');
            }
            if (empty($mobile)){
                return $this->error('手机号不能为空');
            }
            $data = [
                'user_id' =>$user_id,
                'shop_name' =>$company_name,
                'company_name' => $company_name,
                'attorney_letter_fileImg' => $attorney_letter_fileImg,
                'license_fileImg' => $license_fileImg,
                'user_name' => $mobile,
                'password' => $password
            ];
            try{
               $re = ShopLoginService::Register($data);
               if ($re){
                   return $this->success('注册申请提交成功','seller_login');
               }
                return $this->success($re);
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 获取手机验证码 -- 商户注册
     * @param Request $request
     * @return LoginController|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function getSmsCode(Request $request){
        $mobile = $request->input('mobile','');
        if (!$mobile){
            return $this->error('手机号错误');
        }
        $type = 'sms_signup';
        //生成的随机数
        $code = SmsService::getRandom(6);
        Cache::add(session()->getId().$type.$mobile, $code, 5);
        $re = ShopLoginService::sendSMSCode($type,$mobile,$code);
        if ($re == 0){
            return $this->success('发送成功');
        } else {
            return $this->error('发送失败,请稍后重试');
        }

    }

    /**
     * 获取手机验证码 -- 商户登录
     * @param Request $request
     * @return LoginController|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function SmsCodeLogin(Request $request){
        $mobile = $request->input('mobile','');
        if (!$mobile){
            return $this->error('手机号错误');
        }
        $type = 'sms_seller_signin';
        //生成的随机数
        $code = SmsService::getRandom(6);
        Cache::add(session()->getId().$type.$mobile, $code, 5);
        $re = ShopLoginService::sendSMSCode($type,$mobile,$code);
        if ($re == 0){
            return $this->success('发送成功');
        } else {
            return $this->error('发送失败,请稍后重试');
        }

    }
    /**
     * 登出
     * @return LoginController|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        session()->forget('_seller_id');
        session()->forget('_seller');

        return $this->success('退出成功','seller/login.html','',0);
    }

    /**
     * 前端获取session
     * @return LoginController
     */
    public function checkSession()
    {
        return $this->result(session('_seller_id'),'200','');
    }

    public function waitForExamine()
    {
        return $this->display('seller.waitForExamine');
    }
}
