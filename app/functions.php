<?php
/**
 * Created by PhpStorm.
 * User: kery
 * Date: 2018/7/17
 * Time: 10:40
 */
if(!function_exists('themePath')){
<<<<<<< HEAD
    function themePath($joint = ''){
        $theme = session('theme');
        //dd($theme);
=======
    function themePath($joint = '',$pro = '', $default = 'default'){
        $theme_name = empty($pro)?'theme':$pro.'_theme';
        $theme = session($theme_name);
>>>>>>> 4639a564bad61f79a5689e4cefe58e25a48f73f7
        if(empty($theme)){
            return $default;
        }
        return $theme.$joint;
    }
}

if(!function_exists('getValue')){
    function getValue($obj, $key, $default=''){
        if(empty($obj)){
            return $default;
        }
        return $obj[$key] ?? $default;
    }
}

