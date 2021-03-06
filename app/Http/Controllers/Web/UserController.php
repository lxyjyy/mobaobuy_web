<?php

namespace App\Http\Controllers\Web;


use App\Repositories\AppUsersRepo;
use App\Repositories\RegionRepo;
use App\Services\CartService;
use App\Services\OrderInfoService;
use App\Services\UserAddressService;

use App\Repositories\UserRepo;

use App\Services\UserInvoicesService;
use App\Services\UserLoginService;
use App\Services\UserService;
use App\Services\UserRealService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use App\Services\UserAccountLogService;
use Monolog\Handler\IFTTTHandler;
use League\Flysystem\Exception;

class UserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function  index(){
        $userId = session('_web_user_id');
        //企业
        if(session('_curr_deputy_user')['is_self'] == 1 && session('_curr_deputy_user')['is_firm'] == 1){
//            $userId = session('_curr_deputy_user')['firm_id'];
            $userId = '';
            $firmId = session('_curr_deputy_user')['firm_id'];
        }elseif(session('_curr_deputy_user')['is_firm'] == 0 && session('_curr_deputy_user')['is_self'] == 1){
            //个人
            $userId = session('_curr_deputy_user')['firm_id'];
            $firmId = 0;
        }elseif(session('_curr_deputy_user')['is_self'] == 0 && session('_curr_deputy_user')['is_firm'] == 1){
            //代理企业
            $userId = '';
            $firmId = session('_curr_deputy_user')['firm_id'];
        }

        $memberInfo = UserService::userMember($userId,$firmId);
        return $this->display('web.user.index',compact('memberInfo'));
    }

    //检查账号用户是否存在
    public function checkNameExists(Request $request){
        $accountName = $request->input('accountName');
        $rs = UserService::checkNameExists($accountName);
        if($rs){
            return $this->success($rs);
        }else{
            return $this->error($rs);
        }

    }
    //检查账号用户是否绑定过微信
    public function checkNameIsBindWx(Request $request){
        $accountName = $request->input('accountName');
        $rs = checkNameIsBindWx($accountName);
        if($rs){
            return $this->success($rs);
        }else{
            return $this->error($rs);
        }

    }
    //检查账号用户是否已实名 根据手机号码
    public function checkRealNameBool(Request $request)
    {
        $mobile = $request->input('mobile');
        if(!$mobile){
            return $this->error(trans('error.param_error'));
        }
        $userInfo = UserService::getUserInfoByUserName($mobile);
        if(empty($userInfo)){
            return $this->error(trans('error.user_not_exist'));
        }
        if($userInfo['is_firm'] == 1){
            return $this->error(trans('error.enterprise_cannot_add'));
        }
        $res = getRealNameBool($userInfo['id']);
        if($res){
            return $this->success(trans('error.verification_success'));
        }else{
            return $this->error(trans('error.user_not_real_name'));
        }
    }

    //检查公司是否允许注册
    public function checkCompanyNameCanAdd(Request $request){
        $companyName = $request->input('companyName');
        $rs = UserService::checkCompanyNameCanAdd($companyName);

        return $this->success($rs);
    }
    /**
     * 注册获取手机验证码**************************************************
     * sendRegisterSms
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function sendRegisterSms(Request $request){
        $accountName = $request->input('accountName');

        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error(trans('error.graphic_verify_error'));
        }
        if(UserService::checkNameExists($accountName)){
            return $this->error(trans('error.mobile_registered'));
        }

        $type = 'sms_signup';
        if (Cache::has(session()->getId().$type.$accountName)) {
            //
            Cache::forget(session()->getId().$type.$accountName);
        }
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::add(session()->getId().$type.$accountName, $mobile_code, 5);
        createEvent('sendSms', ['phoneNumbers'=>$accountName, 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }

    //个人用户注册
    public function userRegister(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.register.user');
        }else{
//            dd(getConfig('remind_mobile'));
            $accountName = $request->input('accountName', '');
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $type = 'sms_signup';

            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.$accountName) != $messCode){
                return $this->error(trans('error.mobile_verification_error'));
            }

            $data=[
                'user_name' => $accountName,
                'password' => $password,
                'is_firm' => 0
            ];

            try{
                UserService::userRegister($data);
                $this->sms_listen_register($accountName);
                if(getConfig('individual_reg_check')) {
                    return $this->success(trans('error.sub_success'), url('/verifyReg'));
                }else{
                    return $this->success(trans('error.register_success'), route('login'));
                }
            } catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    //短信通知
    public function sms_listen_register($accountName){
        if(!empty(getConfig('remind_mobile')) && getConfig('open_user_register')){
            createEvent('sendSms', ['phoneNumbers'=>getConfig('remind_mobile'), 'type'=>'sms_listen_register', 'tempParams'=>['code'=>$accountName]]);
        }
    }

    //企业用户注册
    public function firmRegister(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.register.firm');
        }else{
            $companyName = $request->input('companyName', '');
            $accountName = $request->input('accountName', '');
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $attorneyLetterFileImg = $request->input('attorneyLetterFileImg', '');
            $licenseFileImg = $request->input('licenseFileImg', '');
            $type = 'sms_signup';

            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.$accountName) != $messCode){
                return $this->error(trans('error.mobile_verification_error'));
            }

            $data=[
                'company_name' => $companyName,
                'user_name' => $accountName,
                'password' => $password,
                'attorney_letter_fileImg' => $attorneyLetterFileImg,
                'license_fileImg' => $licenseFileImg,
                'is_firm' => 1
            ];

            try{
                UserService::userRegister($data);

                if(getConfig('firm_reg_check')) {
                    return $this->success(trans('error.sub_success'), url('/verifyReg'));
                }else{
                    return $this->success(trans('error.register_success'), route('login'));
                }
            } catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }

    }

    //注册审核页面
    public function verifyReg(){
        return $this->display('web.user.register.verifyReg');
    }

    //登出
    public function logout()
    {
//        session()->forget('_web_user_id');
//        session()->forget('_web_user');
//        session()->forget('_curr_deputy_user');
        session()->flush();
        return $this->success(trans('error.logout_success'),  route('login'), '', 0);
    }

    //获取用户购物车商品数
    public function getCartNum()
    {

        if(session()->has('_web_user_id')){
            //登录用户
            if(session('_curr_deputy_user')['is_self'] == 0){
                $user_id = session('_curr_deputy_user')['firm_id'];
            }else{
                $user_id = session('_web_user_id');
            }
            $num = CartService::getUserCartNum($user_id);
        }else{
            $num = 0;

        }
        return $this->success('','',['cart_num'=>$num]);
    }

    //显示用户收货地列表
    public function shopAddressList(){
        $user_info = session('_web_user');
        $condition = [];
        $condition['user_id'] = $user_info['id'];
        $addressList = UserService::shopAddressList($condition);
        foreach ($addressList as $k=>$v){
            $addressList[$k] = UserAddressService::getAddressInfo($v['id']);
            if ($v['id'] == $user_info['address_id']){
                $addressList[$k]['is_default'] =1;
                $first_one[$k] = $addressList[$k];
            } else {
                $addressList[$k]['is_default'] ='';
            };
        }
        if(!empty($first_one)) {
            foreach ($first_one as $k1 => $v1){
                unset($addressList[$k1]);
                array_unshift($addressList, $first_one[$k1]);
            }
        }
        return $this->display('web.user.userAddress',compact('addressList'));
    }

    /**
     * 新增 编辑 收获地址
     * @param Request $request
     * @return UserController|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function addShopAddress(Request $request){
            $id =$request->input('id','');
            $user_id = session('_web_user_id');
            $str_address = $request->input('str_address','');
            $address = $request->input('address','');
            $zipcode = $request->input('zipcode','');
            $consignee = $request->input('consignee','');
            $mobile_phone = $request->input('mobile','');
            $default = $request->input('default_address','');
            if (empty($str_address)){
                return $this->error(trans('error.choose_address'));
            }
            if (empty($address)){
                return $this->error(trans('error.enter_address_detail'));
            }
//            if (empty($zipcode)){
//                return $this->error('请输入邮政编码');
//            }
            if (empty($consignee)){
                return $this->error(trans('error.enter_consignee'));
            }
            if (empty($mobile_phone)){
                return $this->error(trans('error.enter_mobile'));
            }
            $address_ids = explode('|',$str_address);
            $data = [
                'user_id' => $user_id,
                'consignee' => $consignee,
                'country' => $address_ids[0],
                'province' => $address_ids[1],
                'city' => $address_ids[2],
                'district' => $address_ids[3],
                'address' => $address,
                'zipcode' => $zipcode,
                'mobile_phone' => $mobile_phone,
            ];
            try{

                if ($id){
                    $res = UserService::updateShopAdderss($id,$data);
                    if(!empty($default) && $default == 'Y'){//设为默认地址
                        $data = [
                            'id'=>$user_id,
                            'address_id' =>$id
                        ];
                        session()->forget('_web_user');
                        UserService::updateDefaultAddress($data);
                    }
                    return $this->success(trans('error.edit_success'));
                } else{
                    $re =  UserService::addShopAddress($data);
                    if(!empty($default) && $default == 'Y'){//设为默认地址
                        $data = [
                            'id'=>$user_id,
                            'address_id' =>$re['id']
                        ];
                        session()->forget('_web_user');
                        UserService::updateDefaultAddress($data);
                    }
                    return $this->success(trans('error.add_address_success'));
                }
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
    }

    //通过省获取市区
    public function getCity(Request $request){
        $regionId = $request->input('region_id');
        try{
            $cityInfo = UserService::getCity($regionId);
            return json_encode(array('status'=>1,'info'=>$cityInfo));
        }
        catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //通过市区获取县级
    public function getCounty(Request $request){
        $cityId = $request->input('cityId');
//        dd($cityId);
        try{
            $countyInfo = UserService::getCounty($cityId);
            return json_encode(array('status'=>1,'info'=>$countyInfo));
        }
        catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }


    /**
     * 编辑收获地址获取数据
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function updateShopAddress(Request $request){
        $id = $request->input('id','');
        $is_default = $request->input('is_default','');
        if ($id){
            $address_info = UserAddressService::getAddressInfo($id,1);
        } else {
            $address_info = [];
        }
        return $this->display('web.user.editAddress',['data'=>$address_info,'is_default'=>$is_default]);

    }

    /**
     * delete address
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function deleteAddress(Request $request)
    {
        $id = $request->input('id','');
        if (empty($id)){
            return $this->error(trans('error.param_error'));
        }
        try{
            $re = UserAddressService::delete($id);

            if ($re){
                return $this->success(trans('error.delete_success'));
            } else {
                return $this->error(trans('error.delete_failed'));
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

    }

    /**
     * 发票新增
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function createInvoices(Request $request){
        $current_user = session('_curr_deputy_user');
        $id = $request->input('id','');
        $address_ids = $request->input('address_ids','');
        $consignee_address = $request->input('consignee_address','');
        $company_address = $request->input('company_address','');
        $company_name = $request->input('company_name','');
        $tax_id = $request->input('tax_id','');
        $bank_of_deposit = $request->input('bank_of_deposit','');
        $bank_account = $request->input('bank_account','');
        $company_telephone = $request->input('company_telephone','');
        $consignee_name = $request->input('consignee_name','');
        $consignee_mobile_phone = $request->input('consignee_mobile_phone','');

        if ($current_user['is_firm']==1){
            if (empty($company_name)){
                return $this->error(trans('error.fill_title'));
            }
            if (empty($tax_id)){
                return $this->error(trans('error.fill_tax_number'));
            }
            if (empty($bank_of_deposit)){
                return $this->error(trans('error.fill_open_bank'));
            }
            if (empty($bank_account)){
                return $this->error(trans('error.fill_bank_account'));
            }
        }

        $address_ids = explode('|',$address_ids);
        $data = [
            "consignee_address" => $consignee_address,
            "company_address" => $company_address,
            "company_name" => $company_name,
            "tax_id" => $tax_id,
            "bank_of_deposit" => $bank_of_deposit,
            "bank_account" => $bank_account,
            "company_telephone" => $company_telephone,
            "consignee_name" => $consignee_name,
            "consignee_mobile_phone" => $consignee_mobile_phone,
            'country' => $address_ids[0],
            'province' => $address_ids[1],
            'city' => $address_ids[2],
            'district' => $address_ids[3]
        ];

        try{
            if (!empty($id)){
               $re =  UserInvoicesService::editInvoices($id,$data);
            } else {
                $data['user_id'] = session('_web_user_id');
                $re =  UserInvoicesService::create($data);
            }
            if ($re){
                return $this->success(trans('error.success'));
            } else {
                return $this->error(trans('error.fail'));
            }

        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //编辑用户发票信息
    public function editInvoices(Request $request){
        $userInfo = session('_curr_deputy_user');
        $id = $request->input('id','');
        if ($id){
            $invoice_info = UserInvoicesService::getInvoice($id);
            $data = $invoice_info;
        } else {
            $data = [];
            if ($userInfo['is_firm']){
                $data['company_name'] = $userInfo['name'];
            }
        }
        return $this->display('web.user.editInvoice',['data'=>$data]);

    }

    public function deleteInvoices(Request $request)
    {
        $id = $request->input('id','');
        if (empty($id)){
            return $this->error(trans('error.param_error'));
        }
        $re = UserInvoicesService::delete($id);

        if ($re){
            return $this->success(trans('error.delete_success'));
        } else {
            return $this->error(trans('error.delete_failed'));
        }
    }

    //用户发票信息
    public function invoicesList(){
        $user_info = session('_web_user');
        $condition = [];
        $condition['user_id'] = $user_info['id'];
        $invoicesInfo = UserInvoicesService::invoicesById($condition);
        foreach ($invoicesInfo as $k=>$v){
            $invoicesInfo[$k] = UserInvoicesService::getInvoice($v['id']);
            if ($v['id'] == $user_info['invoice_id']){
                $invoicesInfo[$k]['is_default'] =1;
                $first_one[$k] = $invoicesInfo[$k];
            } else {
                $invoicesInfo[$k]['is_default'] ='';
            };
        }
        if(!empty($first_one)) {
            foreach ($first_one as $k1 => $v1) {
                unset($invoicesInfo[$k1]);
                array_unshift($invoicesInfo, $first_one[$k1]);
            }
        }
        return $this->display('web.user.userInvoices',compact('invoicesInfo'));
    }

    //完善用户信息
    public function userUpdate(Request $request){
       if($request->isMethod('post')){
            $rule = [
                'nick_name'=>'nullable',
                'email'=>'nullable|email|unique:user',
                'real_name'=>'required',
                'sex'=>'nullable|numeric|max:1',
//                'birthday'=>'nullable|numeric',
                'avatar'=>'required|file',
                'front_of_id_card'=>'required|file',
                'reverse_of_id_card'=>'required|file'
            ];
           $data = $this->validate($request,$rule);
           $data['avatar'] = $request->file('avatar');
           $data['front_of_id_card'] = $request->file('front_of_id_card');
           $data['reverse_of_id_card'] = $request->file('reverse_of_id_card');
            try{
                UserService::updateUserInfo(session('_web_user_id'),$data);
                return $this->success(trans('error.add_real_name_success'),'/');
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }

       }else{
//            $userInfo = UserLoginService::getInfo(session('_web_info')['id']);
            return $this->display('web.user.userUpdate');
       }
    }

    //修改密码获取手机验证码
    public function sendUpdatePwdSms(Request $request){
        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error(trans('error.graphic_verify_error'));
        }
        if(!UserService::checkNameExists(session('_web_user.user_name'))){
            return $this->error(trans('error.mobile_not_register'));
        }


        $type = 'sms_update_pwd';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        if (Cache::has(session()->getId().$type.session('_web_user.user_name'))) {
            //
            Cache::forget(session()->getId().$type.session('_web_user.user_name'));
        }

        Cache::add(session()->getId().$type.session('_web_user.user_name'), $mobile_code, 5);
        createEvent('sendSms', ['phoneNumbers'=>session('_web_user.user_name'), 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }

    //修改密码
    public function userUpdatePwd(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.updatePwd');
        }else{
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $type = 'sms_update_pwd';

            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.session('_web_user.user_name')) != $messCode){
                return $this->error(trans('error.mobile_verification_error'));
            }

            $id = session('_web_user.id');

            try{
                UserService::userUpdatePwd($id, ['newPassword' => $password]);
                return $this->success(trans('error.edit_success'),'/');
            }catch(\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    //忘记密码获取验证码
    public function sendFindPwdSms(Request $request){
        $accountName = $request->input('accountName');
        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error(trans('error.graphic_verify_error'));
        }
        if(!UserService::checkNameExists($accountName)){
            return $this->error(trans('error.mobile_not_register'));
        }

        $type = 'sms_find_signin';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put(session()->getId().$type.$accountName, $mobile_code, 5);
        createEvent('sendSms', ['phoneNumbers'=>$accountName, 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }

    //忘记密码
    public function userFindPwd(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.findPwd');
        }else{
            $accountName = $request->input('accountName', '');
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $type = 'sms_find_signin';

            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.$accountName) != $messCode){
                return $this->error(trans('error.mobile_verification_error'));
            }

            try{
                UserService::userFindPwd($accountName, $password);
                return $this->success(trans('error.edit_success'),'/');
            }catch(\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }


    //手机验证码登陆获取手机验证码
    public function sendMessLoginSms(Request $request){
        $accountName = $request->input('user_name');
        if(!UserService::checkNameExists($accountName)){
            return $this->error(trans('error.user_not_exist'));
        }
        $type = 'sms_signin';
        if (Cache::has(session()->getId().$type.$accountName)) {
            //
            Cache::forget(session()->getId().$type.$accountName);
        }
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::add(session()->getId().$type.$accountName, $mobile_code, 5);

        createEvent('sendSms', ['phoneNumbers'=>$accountName, 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }



    public function empList(Request $request){
        return $this->display('web.user.emp.list');
    }

    //用户收藏商品列表
    public function userCollectGoodsList(Request $request){
        $firm_id = session('_web_user_id');
        if($request->isMethod('get')){

            return $this->display('web.user.userCellectGoodsList');
        }else{
            $page = $request->input('start', 0) / $request->input('length', 10) + 1;
            $page_size = $request->input('length', 10);
            $rs_list =UserService::userCollectGoodsList($firm_id,$page,$page_size);

            $data = [
                'draw' => $request->input('draw'), //浏览器cache的编号，递增不可重复
                'recordsTotal' => $rs_list['total'], //数据总行数
                'recordsFiltered' => $rs_list['total'], //数据总行数
                'data' => $rs_list['list']
            ];
            return $this->success('', '', $data);
        }


    }

    //收藏商品
    public function addCollectGoods(Request $request){
        $id = $request->input('id');
        $userId = session('_web_user_id');
        try{
            UserService::addCollectGoods($id,$userId);
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    //删除收藏夹商品
    public function delCollectGoods(Request $request){
        $id = $request->input('id');
        try{
             UserService::delCollectGoods($id);
             return $this->success();
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return $this
     * 检测用户是否实名通过
     */
    public function isReal(Request $request){
        $userId = $request->input('userId');
        try{
            UserService::isReal($userId);
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //用户信息
    public function userInfo(Request $request)
    {
        $userInfo = session()->get("_web_user");
        try{
            $userRealName = UserService::getUserRealbyId($userInfo['id']);
            $userInfo['real_name'] = $userRealName;
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        return $this->display("web.user.account.accountInfo",[
            'userInfo'=>$userInfo
        ]);
    }

    //保存
    public function saveUser(Request $request)
    {
        $params = $request->all();
        $params['nick_name'] = trim($request->input('nick_name'));
        try{
            $data = [];
            $data['email'] = $params['email'];
            $data['nick_name'] = htmlspecialchars($params['nick_name']);
            $userId = session('_web_user_id');
            $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
            if(!empty($data['email'])){
                if(!preg_match($pattern,$data['email'])){
                    return $this->error(trans('error.mail_format_error'));
                }
            }

            if(session('_web_user.is_firm')){
                //企业
                $data['need_approval'] = $params['need_approval'];
                //1是  0否
                if(!$data['need_approval']){
                    $approvalStatus =  OrderInfoService::checkApprovalByOrderCount(session('_web_user_id'));
                    if($approvalStatus){
                        return $this->error(trans('error.process_unapproved_order'));
                    }
                }

            }


            $flag = UserService::modify($userId,$data);

            if(!$flag['is_firm']){
                if(isset(session('_web_user')['firms'])){
                    $firms = session('_web_user')['firms'];
                    $flag['firms'] = $firms;
                    session()->put('_web_user', $flag);
                }else{
                    session()->put('_web_user', $flag);
                }
                return $this->success(trans('error.success'), '', $flag);
            }else{
                session()->put('_web_user', $flag);
//                dd(session('_web_user'));
                return $this->success(trans('error.success'), '', $flag);
            }
        }catch(\Exception $e){
            return $this->success(trans('error.fail'), '', $flag);
        }

    }

    //查看积分
    public function viewPoints(Request $request)
    {
        $user_id = session()->get("_web_user")['id'];
        $condition['user_id']=$user_id;
        $pageSize = 10;
        $currpage = $request->input("currpage",1);
        //积分列表
        $user_account_logs = UserAccountLogService::getInfoByUserId(['pageSize'=>$pageSize,'page'=>$currpage,'orderType'=>['change_time'=>'desc']],$condition);
        return $this->display("web.user.account.accountLog",[
            'user_account_logs'=>$user_account_logs['list'],
            'total'=>$user_account_logs['total'],
            'currpage'=>$currpage,
            'pageSize'=>$pageSize,
            'totalPoints'=>session()->get("_web_user")['points']
        ]);
    }

    //实名信息
    public function userRealInfo(Request $request)
    {
        $user_id = session()->get("_web_user_id");
        $user_name = session()->get("_web_user")['user_name'];
        $is_firm = session()->get("_web_user")['is_firm'];
        $user_real = UserRealService::getInfoByUserId($user_id);
        if(empty($user_real) || $user_real['review_status'] == 2){
            return $this->display("web.user.account.realName",[
                'user_name'=>$user_name,
                'is_firm'=>$is_firm,
                'user_real'=>$user_real,
                'user_id'=>$user_id
            ]);
        }
        if($user_real['review_status'] == 1 || $user_real['review_status'] == 0){
            return $this->display("web.user.account.realNamePass",[
                'user_name'=>$user_name,
                'is_firm'=>$is_firm,
                'user_real'=>$user_real,
                'user_id'=>$user_id
            ]);
        }

    }

    //保存实名
    public function saveUserReal(Request $request)
    {
        $user_id = session('_web_user_id');
        $dataArr = $request->all();

        //is_self 1是个人提交  2是企业
        $is_self = $request->input('is_self');
        $errorMsg = [];

       if($is_self == 1){
           if(empty($dataArr['real_name'])){
               $errorMsg[] = trans('error.enter_real_name');
               return $this->result("",0,implode("|",$errorMsg));
           }
           if(empty($dataArr['front_of_id_card'])){
               $errorMsg[] = trans('error.enter_card_front');
               return $this->result("",0,implode("|",$errorMsg));
           }
           if(empty($dataArr['reverse_of_id_card'])){
               $errorMsg[] = trans('error.enter_card_reverse');
               return $this->result("",0,implode("|",$errorMsg));
           }
           if(!empty($errorMsg)){
               return $this->result("",0,implode("|",$errorMsg));
           }
       }elseif($is_self == 2){
           if(empty($dataArr['real_name_firm'])){
               $errorMsg[] = trans('error.enter_company_name');
               return $this->result("",0,implode("|",$errorMsg));
           }

//           if(empty($dataArr['tax_id'])){
//               $errorMsg[] = "税号";
//               return $this->result("",0,implode("|",$errorMsg));
//           }

           if(empty($dataArr['attorney_letter_fileImg'])){
               $errorMsg[] = trans('error.upload_electronic_attorney');
               return $this->result("",0,implode("|",$errorMsg));
           }

           if(empty($dataArr['invoice_fileImg'])){
               $errorMsg[] = trans('error.upload_electronic_invoice');
               return $this->result("",0,implode("|",$errorMsg));
           }

           if(empty($dataArr['license_fileImg'])){
               $errorMsg[] = trans('error.upload_electronic_license');
               return $this->result("",0,implode("|",$errorMsg));
           }

//           if(empty($dataArr['company_name'])){
//               $errorMsg[] = "公司抬头";
//               return $this->result("",0,implode("|",$errorMsg));
//           }
//
//           if(empty($dataArr['bank_of_deposit'])){
//               $errorMsg[] = "开户银行";
//               return $this->result("",0,implode("|",$errorMsg));
//           }
//
//           if(empty($dataArr['bank_account'])){
//               $errorMsg[] = "银行账号";
//               return $this->result("",0,implode("|",$errorMsg));
//           }
//
//           if(empty($dataArr['company_address'])){
//               $errorMsg[] = "开票地址";
//               return $this->result("",0,implode("|",$errorMsg));
//           }
//
//           if(empty($dataArr['company_telephone'])){
//               $errorMsg[] = "开票电话";
//               return $this->result("",0,implode("|",$errorMsg));
//           }

           if(!empty($errorMsg)){
               return $this->result("",0,implode("|",$errorMsg));
           }
       }else{
           return $this->result("",0,trans('error.illegal_operation'));
       }

        try{
                $flag = UserRealService::saveUserReal($dataArr,$is_self,$user_id);
                if($flag){
                    $this->sms_listen_real(session('_web_user')['user_name'],$is_self);
                    return $this->result("",1,trans('error.success'));
                }
            return $this->result('',0,trans('error.fail'));
        }catch(\Exception $e){
            return $this->result('',0,$e->getMessage());
        }
    }

    //短信通知
    public function sms_listen_real($user_name,$is_self){
        if($is_self == 1){
            $type = '个人会员';
        }else{
            $type = '企业会员';
        }
        if(!empty(getConfig('remind_mobile')) && getConfig('open_user_real')){
            createEvent('sendSms', ['phoneNumbers'=>getConfig('remind_mobile'), 'type'=>'sms_listen_real', 'tempParams'=>['phone'=>$user_name,'type'=>$type]]);
        }
    }

    //发送支付密码手机验证码
    public function sendPayPwdSms(Request $request){
        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error(trans('error.graphic_verify_error'));
        }
        if(!UserService::checkNameExists(session('_web_user.user_name'))){
            return $this->error(trans('error.mobile_not_register'));
        }

        $type = 'sms_pay_pwd';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::add(session()->getId().$type.session('_web_user.user_name'), $mobile_code, 5);
        createEvent('sendSms', ['phoneNumbers'=>session('_web_user.user_name'), 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }

    //修改支付密码
    public function editPayPassword(Request $request)
    {
        if($request->isMethod('post')){
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $type = 'sms_pay_pwd';
            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.session('_web_user.user_name')) != $messCode){
                return $this->error(trans('error.mobile_verification_error'));
            }

            $id = session('_web_user.id');

            try{
                UserService::modifyPayPwd($id,$password);
                return $this->success(trans('error.set_pay_password_success'),'/');
            }catch(\Exception $e){
                return $this->error($e->getMessage());
            }
        }
        return $this->display("web.user.account.payPassword");
    }

    /**
     * 修改默认发票
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function updateDefaultInvoice(Request $request)
    {
        $invoice_id = $request->input('invoice_id','');
        if (empty($invoice_id)){
            return $this->error(trans('error.param_error'));
        }
        $userInfo  = session('_web_user');
        $data = [
            'id'=>$userInfo['id'],
            'invoice_id' =>$invoice_id
        ];

        $re = UserService::modify($data);
        if ($re){
            session()->forget('_web_user');
            return $this->success(trans('error.edit_success'));
        } else {
            return $this->error(trans('error.fail'));
        }
    }

    /**
     * 更新默认地址
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function updateDefaultAddress(Request $request)
    {
        $address_id = $request->input('address_id','');
        if (empty($address_id)){
            return $this->error(trans('error.param_error'));
        }
        $userInfo  = session('_web_user');
        $data = [
            'id'=>$userInfo['id'],
            'address_id' =>$address_id
        ];

        $re = UserService::updateDefaultAddress($data);
        if ($re){
            session()->forget('_web_user');
            return $this->success(trans('error.edit_success'));
        } else {
            return $this->error(trans('error.fail'));
        }
    }


    //会员卖货
    public function sale(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.account.userSale');
        }else{
            $userInfo = session('_web_user');
            $saleData = $request->all();
            $saleData['user_id'] = $userInfo['id'];
            $saleData['user_name'] = $userInfo['user_name'];
            $saleData['add_time'] = Carbon::now();
            try{
                UserService::sale($saleData);
                return $this->success();
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }

    }

    //注销账号
    public function accountLogout()
    {
        $userInfo = session('_web_user');
        //检测用户是否已经企业认证
        $realInfo = UserRealService::getInfoByUserId($userInfo['id']);
        //已经实名认证的企业用户不能注销账号
        if($realInfo && $realInfo['review_status'] == 1 && $realInfo['is_firm'] == 1){
            return $this->error(trans('error.passed_real_name_cannot_cancel'));
        }
        $user_data = [
            'user_name'=>$userInfo['user_name'].'_'.time().'_logout',
            'is_freeze'=>1
        ];
        $res = UserService::modify($userInfo['id'],$user_data);
        if($res){
            session()->flush();
            return $this->success(trans('error.user_logout_success'));
        }else{
            return $this->error(trans('error.user_logout_fail'));
        }
    }

    /**
     * 解绑微信
     */
    public function untying()
    {
        $userInfo = session('_web_user');
        #先检测是否绑定了微信
        if(checkNameIsBindWx($userInfo['user_name'])){
            $res = AppUsersRepo::deleteByFields(['user_id'=>$userInfo['id']]);
            if($res){
                return $this->success(trans('error.success'));
            }
        }
        return $this->error(trans('error.fail'));

    }
}
