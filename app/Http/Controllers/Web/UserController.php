<?php

namespace App\Http\Controllers\Web;


use App\Services\UserAddressService;

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
            $consignee=$request->input('consignee','');
            $mobile_phone=$request->input('mobile_phone','');
            if (empty($str_address)){
                return $this->error('请选择地址');
            }
            if (empty($consignee)){
                return $this->error('请填写收货人');
            }
            if (empty($mobile_phone)){
                return $this->error('请填写收货电话');
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
                'mobile_phone' => $mobile_phone
            ];
            try{
                if ($id){
                    $res = UserService::updateShopAdderss($id,$data);
                    if ($res){
                        return $this->success('修改成功');
                    }
                } else{
                   $re =  UserService::addShopAddress($data);
                    return $this->success('添加收获地址成功');
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
        if ($id){
            $address_info = UserAddressService::getAddressInfo($id);
        } else {
            $address_info = [];
        }
        return $this->display('web.user.editAddress',['data'=>$address_info]);

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
            return $this->error('参数错误');
        }
        $re = UserAddressService::delete($id);

        if ($re){
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }

    /**
     * 发票新增
     * @param Request $request
     * @return UserController|\Illuminate\Http\RedirectResponse
     */
    public function createInvoices(Request $request){
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
                return $this->success('成功');
            } else {
                return $this->error('失败');
            }

        }catch (\Exception $e){
            dd($e->getMessage());
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
            return $this->error('参数错误');
        }
        $re = UserInvoicesService::delete($id);

        if ($re){
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
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
        $params = $request->all();
        try{
            $data = [];
            $data['email'] = $params['email'];
            $data['id'] = session('_web_user_id');
            if(session('_web_user.is_firm')){
                //企业
                $data['need_approval'] = $params['need_approval'];
            }else{
                //个人，可以修改昵称
                $data['nick_name'] = $params['nick_name'];
            }
            $flag = UserService::modify($data);
            if($flag){
                session()->put('_web_user', $flag);
                return $this->success('保存成功', '', $flag);
            }
            return $this->success('保存失败', '', $flag);
        }catch(\Exception $e){
            //return $this->result('',0,$e->getLine().$e->getMessage());
            return $this->success('保存失败', '', $flag);
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

    //发送支付密码手机验证码
    public function sendPayPwdSms(Request $request){
        $t = $request->input('t');
        $code = $request->input('verifyCode');
        $s_code = Cache::get(session()->getId().'captcha'.$t, '');
        if($s_code != $code){
            return $this->error('图形验证有误');
        }
        if(!UserService::checkNameExists(session('_web_user.user_name'))){
            return $this->error('手机号未注册');
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
                return $this->error('手机验证码不正确');
            }

            $id = session('_web_user.id');

            try{
                UserService::modifyPayPwd($id,$password);
                return $this->success('设置支付密码成功','/');
            }catch(\Exception $e){
                return $this->error($e->getMessage());
            }
        }
        return $this->display("web.user.account.payPassword");
    }


}
