<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use App\Repositories\FirmUserRepo;
use App\Repositories\UserRealRepo;
use App\Repositories\UserRepo;
use App\Services\FirmUserService;
use App\Services\UserService;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class WebClosed extends Controller
{
    public function handle($request, Closure $next, $guard = null)
    {
        if(getConfig('shop_closed') == '1'){
            print_r(getConfig('close_comment'));
            die();
        }

        if(!empty(session('_web_user_id'))){
            //缓存用户的基本信息
            if(session()->has('_web_user')) {
                if (session('_web_user.is_real') == 2) {
                    $user_real_count = UserRealRepo::getTotalCount(['user_id' => session('_web_user_id'), 'review_status' => 1]);
                    if ($user_real_count > 0) {
                        session()->forget('_web_user');
                        session()->forget('_curr_deputy_user');
                    }
                }
                if (session()->has('_curr_deputy_user') && session('_curr_deputy_user.is_self') != 1) {
                    $user_info = UserService::getInfo(session('_web_user_id'));
                    //用户不切换生效权限,is_logout存的是企业的id
                    if ($user_info['is_logout'] > 0 && $user_info['is_logout'] == session('_curr_deputy_user.firm_id')) {
                        $firm = FirmUserRepo::getInfoByFields(['firm_id'=>session('_curr_deputy_user.firm_id'),'user_id'=>session('_web_user_id')]);
                        $firm_info = UserRepo::getInfo($firm['firm_id']);
                        //修改代表信息
                        $firm['is_self'] = 0;
                        $firm['is_firm'] = 1;
                        $firm['name'] = $firm_info['nick_name'];
                        $firm['address_id'] = $firm_info['address_id'];
                        session()->put('_curr_deputy_user', $firm);
                        UserService::modify(session('_web_user_id'), ['is_logout' => 0]);
                    }
                }
            }
            if(!session()->has('_web_user')){
                $user_info = UserService::getInfo(session('_web_user_id'));
                #判断是否实名
                $user_real_count = UserRealRepo::getTotalCount(['user_id'=>session('_web_user_id'),'review_status'=>1]);
                if($user_real_count > 0){
                    $user_info['is_real'] = 1;//已实名
                }else{
                    $user_info['is_real'] = 2;//未实名
                }

                if(!$user_info['is_firm']){
                    $user_info['firms'] = UserService::getUserFirms(session('_web_user_id'));
                }

                session()->put('_web_user', $user_info);
            }

            if(!session()->has('_curr_deputy_user')){
                $info = [
                    'is_self' => 1,
                    'is_firm' => session('_web_user')['is_firm'],
                    'firm_id'=> session('_web_user_id'),
                    'name' => session('_web_user')['nick_name'],
                    'address_id' => session('_web_user')['address_id']
                ];
                session()->put('_curr_deputy_user', $info);
            }
            //缓存模板信息
            if(!session()->has('web_theme')){
                session()->put('web_theme', getConfig('template','default'));
            }
        }

        if($request->session()->exists("lang")){

            App::setLocale(session("lang"));
        if(App::getLocale() == 'en'){
            \Carbon\Carbon::setLocale('en');
        }else{
            \Carbon\Carbon::setLocale('zh');
        }
        }

        return $next($request);
    }
}
