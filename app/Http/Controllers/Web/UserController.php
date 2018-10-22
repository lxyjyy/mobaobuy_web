<?php

namespace App\Http\Controllers\Web;

use App\Repositories\UserRepo;
use App\Services\UserInvoicesService;
use App\Services\UserLoginService;
use App\Services\UserService;
use App\Services\UserRealService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use App\Services\UserAccountLogService;

class UserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function  index(){
        return $this->display('web.user.index');
    }

    //检查账号用户是否存在
    public function checkNameExists(Request $request){
        $accountName = $request->input('accountName');
        $rs = UserService::checkNameExists($accountName);

        return $this->success($rs);
    }

    //检查公司是否允许注册
    public function checkCompanyNameCanAdd(Request $request){
        $companyName = $request->input('companyName');
        $rs = UserService::checkCompanyNameCanAdd($companyName);

        return $this->success($rs);
    }

    //注册获取手机验证码
    public function sendRegisterSms(Request $request){
        $accountName = $request->input('accountName');

        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error('图形验证有误');
        }
        if(UserService::checkNameExists($accountName)){
            return $this->error('手机号已经注册');
        }

        $type = 'sms_signup';
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
            $accountName = $request->input('accountName', '');
            $password = base64_decode($request->input('password', ''));
            $messCode = $request->input('messCode', '');
            $type = 'sms_signup';

            //手机验证码是否正确
            if(Cache::get(session()->getId().$type.$accountName) != $messCode){
                return $this->error('手机验证码不正确');
            }

            $data=[
                'user_name' => $accountName,
                'password' => $password,
                'is_firm' => 0
            ];

            try{
                UserService::userRegister($data);
                if(getConfig('individual_reg_check')) {
                    return $this->success('提交成功，请等待审核！', '/');
                }else{
                    return $this->success('注册成功！', '/');
                }
                return $this->success('注册成功','/');
            } catch (\Exception $e){
                return $this->error($e->getMessage());
            }
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
                return $this->error('手机验证码不正确');
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
                      return redirect('/verifyReg');
//                    return $this->success('提交成功，请等待审核！', '/');
                }else{
                    return $this->success('注册成功！', '/');
                }
            } catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }

    }

    //用户登录页面
    public function showLoginForm()
    {
        if (!empty(session('_web_user_id'))) {
            return redirect('/');
        }
        return $this->display('web.login');
    }

    //用户登录提交
    public function login(Request $request)
    {
        $username = $request->input('user_name');
        $password = base64_decode($request->input('password'));

        if(empty($username)){
            return $this->error('用户名不能为空');
        }
        if(empty($password)){
            return $this->error('密码不能为空');
        }

        $other_params = [
            'ip'  => $request->getClientIp()
        ];
        try{
            $user_id = UserService::loginValidate($username, $password, $other_params);
            session()->put('_web_user_id', $user_id);
            return $this->success('登录成功，正在进入系统...');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //注册审核页面
    public function verifyReg(){
        return $this->display('web.user.register.verifyReg');
    }


    //登出
    public function logout()
    {
        session()->forget('_web_user_id');
        session()->forget('_web_user');
        session()->forget('_curr_deputy_user');
        return $this->success('退出登录成功！',  route('login'), '', 0);
    }


    //显示用户收获地
    public function shopAddressList(){
        $userId = session('_web_user_id');
        $condition = [];
        $condition['user_id'] = $userId;
        $addressInfo = UserService::shopAddressList($condition);
        return $this->display('web.user.userAddress',compact('addressInfo'));
    }

    //新增收获地址
    public function addShopAddress(Request $request){
        if($request->isMethod('post')){
            $user_id = session('_web_user_id');
            $message = [
                'required' => ':attribute 不能为空'
            ];
            $attributes = [
                'address_name'=>'地址别名',
                'consignee'=>'收货人',
                'country'=>'国家',
                'province'=>'省',
                'city'=>'市',
                'district'=>'县',
                'street'=>'街道',
                'address'=>'详细地址',
                'zipcode'=>'邮编',
                'mobile_phone'=>'手机'
            ];
            $rule = [
//                'address_name'=>'required',
                'consignee'=>'required',
//                'country'=>'required',
                'province'=>'required',
                'city'=>'required',
                'district'=>'required',
//                'street'=>'required',
                'address'=>'required',
                'zipcode'=>'required',
                'mobile_phone'=>'required',
            ];
            $data = $this->validate($request,$rule,$message,$attributes);
            $data['user_id'] = $user_id;
            $data['country'] = 1;
            try{
                UserService::addShopAddress($data);
                return $this->success('添加收获地址成功');
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }else{
            $region_type = 1;
            $addressInfo = UserService::provinceInfo($region_type);
            return $this->display('web.user.createAddress',compact('addressInfo'));
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


    //编辑收获地址
    public function updateShopAddress(Request $request,$id){
        if($request->isMethod('post')){
            $rule = [
                'address_name'=>'required',
                'user_id'=>'required|numeric',
                'consignee'=>'required',
                'country'=>'required',
                'province'=>'required',
                'city'=>'required',
                'district'=>'required',
                'street'=>'required',
                'address'=>'required',
                'zipcode'=>'required',
                'mobile_phone'=>'required',
            ];
            $data = $this->validate($request,$rule);
            try{
                UserService::updateShopAdderss($id,$data);
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }else{
            return $this->display('web.xx');
        }

    }

    //会员发票信息新增
    public function createInvoices(Request $request){
        if($request->isMethod('get')){
            try{
                $region_type = 1;
                $addressInfo = UserService::provinceInfo($region_type);
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }

            return $this->display('web.user.createInvoices',compact('addressInfo'));
        }else{
            $rule = [
                'company_name' => 'required',
                'tax_id' => 'required',
                'bank_of_deposit' =>'required',
                'bank_account' => 'required',
                'company_address' => 'required',
                'company_telephone' => 'required',
                'consignee_name' => 'required',
                'consignee_mobile_phone' =>'required',
//                'country' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
//                'street' => 'required',
                'consignee_address' => 'required',
            ];
            $data = $this->validate($request,$rule);
            $data['user_id'] = session('_web_user_id');
            $data['country'] = 1;
            try{
                UserInvoicesService::create($data);
                return $this->success();
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }


    //编辑用户发票信息
    public function editInvoices(Request $request,$id){
        if($request->isMethod('get')){
            return $this->display('');
        }else{
            $rule = [
                'company_name' => 'required',
                'tax_id' => 'required',
                'bank_of_deposit' =>'required',
                'bank_account' => 'required',
                'company_address' => 'required',
                'company_telephone' => 'required',
                'consignee_name' => 'required',
                'consignee_mobile_phone' =>'required',
                'country' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
                'street' => 'required',
                'consignee_address' => 'required',
            ];
            $data = $this->validate($request,$rule);
            $data['user_id'] = session('_web_user_id');
            try{
                UserInvoicesService::editInvoices($id,$data);
                return $this->success('保存成功');
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    //用户发票信息
    public function invoicesList(){
        $userId = session('_web_user_id');
        $condition = [];
        $condition['user_id'] = $userId;
        $invoicesInfo = UserInvoicesService::invoicesById($condition);
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
                return $this->success('实名信息添加成功，等待审核...','/');
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
            return $this->error('图形验证有误');
        }
        if(!UserService::checkNameExists(session('_web_user.user_name'))){
            return $this->error('手机号未注册');
        }

        $type = 'sms_update_pwd';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

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
                return $this->error('手机验证码不正确');
            }

            $id = session('_web_user.id');

            try{
                UserService::userUpdatePwd($id, ['newPassword' => $password]);
                return $this->success('修改密码成功','/');
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
            return $this->error('图形验证有误');
        }
        if(!UserService::checkNameExists($accountName)){
            return $this->error('手机号未注册');
        }

        $type = 'sms_find_signin';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::add(session()->getId().$type.$accountName, $mobile_code, 5);
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
                return $this->error('手机验证码不正确');
            }

            try{
                UserService::userFindPwd($accountName, $password);
                return $this->success('修改密码成功','/');
            }catch(\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    //发送支付密码验证码
    public function sendCodeByPay(Request $request){
        $mobile = session('_web_user')['user_name'];
        $type = $request->input('type');
        $mobile_code = rand(1000, 9999);
        session()->put('pay_send_code', $mobile_code);
        try{
            UserLoginService::sendCode($mobile,$type,$mobile_code);
            return json_encode(array('code'=>1,'msg'=>'succ'));
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //设置支付密码
    public function setPayPwd(Request $request){
        if($request->isMethod('get')){
            return $this->display('web.user.forgotPwd');
        }else{

            $payInfo = [];
            $payInfo['password'] = $request->input('password');
            $payInfo['passwords'] = $request->input('passwords');
            $payInfo['code'] = $request->input('code');
            $payInfo['user_id'] = session('_web_user_id');
            if($payInfo['code'] != session('pay_send_code')){
                return $this->error('验证码错误');exit;
            }
            try{
                UserService::setPayPwd($payInfo);
                return $this->success('支付密码设置成功','/');
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
    }

    public function empList(Request $request){
        return $this->display('web.user.emp.list');
    }

    //用户收藏商品列表
    public function userCollectGoodsList(){
        $id = session('_web_user_id');
        $collectGoods = UserService::userCollectGoodsList($id);
        return $this->display('web.user.userCellectGoodsList',compact('collectGoods'));
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
        }catch(\Exection $e){
            return $this->error($e->getMessage());
        }
    }


    /**
     * 以下为账号管理相关代码
     *   "id" => 26
     */
    public function userInfo(Request $request)
    {
        $userInfo = session()->get("_web_user");
        return $this->display("web.user.account.accountInfo",[
            'userInfo'=>$userInfo,
        ]);
    }

    //保存
    public function saveUser(Request $request)
    {
        $data = $request->all();
        try{
            $flag = UserRepo::modify($data['id'],$data);
            session()->put("_web_user",$flag);
            if(!empty($flag)){
                return $this->result($flag['id'],1,'保存成功');
            }
            return $this->result('',0,'保存失败');
        }catch(\Exception $e){
            return $this->result('',0,"保存失败");
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
        $user_id = session()->get("_web_user")['id'];
        $user_name = session()->get("_web_user")['user_name'];
        $is_firm = session()->get("_web_user")['is_firm'];
        $user_real = UserRealService::getInfoByUserId($user_id);
        //dd($user_real);
        return $this->display("web.user.account.realName",[
            'user_name'=>$user_name,
            'is_firm'=>$is_firm,
            'user_real'=>$user_real,
            'user_id'=>$user_id
        ]);
    }

    //保存实名
    public function saveUserReal(Request $request)
    {
        $data = $request->all();
        $errorMsg = [];
        if(empty($data['real_name'])){
            $errorMsg[] = "请输入真实姓名";
        }
        if(!empty($errorMsg)){
            return $this->result("",0,implode("|",$errorMsg));
        }
        try{
            if($data['id']==""){
                unset($data['id']);
                $flag = UserRealService::create($data);
                if(!empty($flag)){
                    return $this->result("",1,"保存成功");
                }
            }else{

                $flag = UserRealService::modify($data);
                if(!empty($flag)){
                    return $this->result("",1,"保存成功");
                }
            }
            return $this->result('',0,"保存失败");
        }catch(\Exception $e){
            return $this->result('',0,$e->getMessage());
        }
    }

    //修改密码
    public function editPassword(Request $request)
    {
        $userInfo = session()->get("_web_user");
        if($request->isMethod('post')){
            $data = $request->all();
            try{
                $info = UserService::getUserInfo($userInfo['id']);
                if(empty($data['password'])){
                    return $this->result("",0,'旧密码不能为空');
                }
                if(!Hash::check($data['password'], $info['password'])){
                    return $this->result("",0,'旧密码输入有误');
                }
                if(empty($data['newpassword'])){
                    return $this->result("",0,'新密码不能为空');
                }
                if($data['newpassword']!=$data['renewpassword']){
                    return $this->result("",0,'两次输入的密码不一致');
                }
                UserService::modify(['id'=>$info['id'],'password'=>Hash::make($data['newpassword'])]);
                return $this->result("",1,'修改密码成功，请重新登录');
            }catch(\Exception $e){
                return $this->result("",0,$e->getMessage());
            }
        }
        return $this->display("web.user.account.password",[
            'userInfo'=>$userInfo,
        ]);
    }

    //发送验证码
    public function sendSms(Request $request)
    {
        $accountName = $request->input('accountName');

        $type = 'sms_signup';
        //生成的随机数
        $mobile_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::add(session()->getId().$type.$accountName, $mobile_code, 5);
        createEvent('sendSms', ['phoneNumbers'=>$accountName, 'type'=>$type, 'tempParams'=>['code'=>$mobile_code]]);

        return $this->success();
    }


    //修改支付密码
    public function editPayPassword(Request $request)
    {
        $userInfo = session()->get("_web_user");
        $paywadInfo = UserService::getPayPwdInfo($userInfo['id']);
        if($request->isMethod('post')){
            $data = $request->all();
            try{
                if(key_exists('id',$data)){
                    //{user_id: "29", id: "1", pay_password: "12312312", newpay_password: "111111"}
                    if(empty($data['pay_password'])){
                        return $this->result("",0,'旧密码不能为空');
                    }

                    $paywadInfo = UserService::getPayPwdInfo($data['user_id']);
                    //return $this->result($paywadInfo,0,"");
                    if(!Hash::check($data['pay_password'], $paywadInfo['pay_password'])){
                        return $this->result("",0,'旧密码输入有误');
                    }
                    if(empty($data['newpay_password'])){
                        return $this->result("",0,'新密码不能为空');
                    }
                    if($data['renewpay_password']!=$data['newpay_password']){
                        return $this->result("",0,'两次输入的密码不一致');
                    }

                    UserService::modifyPayWad(['id'=>$data['id'],'pay_password'=>Hash::make($data['newpay_password'])]);
                    return $this->result("",1,'修改支付密码成功');
                }else{
                    //return $this->result($data,1,"");
                    $data['pay_password'] = Hash::make($data['pay_password']);
                    $flag = UserService::createPayWad($data);
                    if(!empty($flag)){
                        return $this->result("",1,'修改支付密码成功');
                    }
                    return $this->result("",0,'修改支付密码失败');
                }
            }catch(\exception $e){
                return $this->result("",0,$e->getMessage());
            }

        }
        return $this->display("web.user.account.payPassword",[
            'userInfo'=>$userInfo,
            'paywadInfo'=>$paywadInfo
        ]);
    }


}
