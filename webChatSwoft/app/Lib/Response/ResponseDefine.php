<?php
/**
 * Created by PhpStorm.
 * User: Mars
 * Date: 2018/10/4
 * Time: 7:14 PM
 */

namespace App\Lib\Response;

// 执行请求成功时
!defined('SUCCESS_CODE') && define('SUCCESS_CODE', 1);
!defined('SUCCESS_MSG') && define('SUCCESS_MSG', 'success');

// 执行请求失败时
!defined('FAILED_CODE') && define('FAILED_CODE', 0);
//!defined('FAILED_MSG') && define('FAILED_MSG', 'failed');

// 参数校验异常时
!defined('VALIDATOR_CODE') && define('VALIDATOR_CODE', 400);
//!defined('VALIDATOR_MSG') && define('VALIDATOR_MSG', '参数校验失败');

// 登录后检查token异常时
!defined('NOAUTH_CODE') && define('NOAUTH_CODE', 401);
!defined('NOAUTH_MSG') && define('NOAUTH_MSG', '检查授权未通过');

// 登录后检查访问权限异常时
!defined('NOACCESS_CODE') && define('NOACCESS_CODE', 403);
!defined('NOACCESS_MSG') && define('NOACCESS_MSG', '拒绝访问');

// 没有控制器处理时
!defined('NOTFOUND_CODE') && define('NOTFOUND_CODE', 404);
//!defined('NOTFOUND_MSG') && define('NOTFOUND_MSG', '没有处理该请求的方法');

// 路由过滤处理时
!defined('FILTER_CODE') && define('FILTER_CODE', 405);
!defined('FILTER_MSG') && define('FILTER_MSG', '对本次请求不提供服务');

// 没有控制器处理时
!defined('INTERNAL_CODE') && define('INTERNAL_CODE', 500);
!defined('INTERNAL_MSG') && define('INTERNAL_MSG', '服务器暂时开小差了，请稍后再试');
