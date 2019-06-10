<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/3
 * Time: 14:29
 */

namespace App\Services;

use App\Lib\Helper;
use App\Model\Dao\authDao;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class authService
 * @Bean()
 * @package App\Services
 */
class authService{

    /**
     * @Inject()
     * @var authDao
     */
    private $authDao;

    /**
     * @Inject()
     * @var tokenService
     */
    private $tokenService;



    public function checkIn($username,$password){
        $result = $this->authDao->getUserByName($username);
        if (!$result) {
            return array(
                'code' => 0,
                'error' => '登录账户或密码错误',
                'data' => ''
            );
        } else if ($result['status'] != 1) {
            return array(
                'code' => 0,
                'error' => '账户状态异常',
                'data' => ''
            );
        }else {
            $salt = env('AUTH_SALT');
            $saltPwd = sha1($password . $salt);
            if ($result['password'] == $saltPwd) {
                $token = $this->tokenService->setToken($result['id']);
                if ($token['code'] == 1) {
                    return array(
                        'code' => 1,
                        'error' => '',
                        'data' => array(
                            'userID' => $result['id'],
                            'nickName' => $result['nickName'],
                            'token' => $token['data']
                        )
                    );
                } else {
                    return array(
                        'code' => 0,
                        'error' => '',
                        'data' => $result['msg']
                    );
                }

            } else {
                return array(
                    'code' => 0,
                    'error' => '登录账户或密码错误',
                    'data' => ''
                );
            }
        }
    }

    public function add($username,$password){
        $result = $this->authDao->getUserByName($username);
        if($result){
            return array(
                'code' => 0,
                'error' => '注册的账号已存在！',
                'data' => ''
            );
        }else{
            $salt = env('AUTH_SALT');
            $password = sha1($password . $salt);
            $result = $this->authDao->add($username,$password);
            if ($result) {
                return array(
                    'code' => 1,
                    'error' => '',
                    'data' => $result
                );
            } else {
                return array(
                    'code' => 0,
                    'error' => '注册异常',
                    'data' => ''
                );
            }
        }
    }

    public function checkAuth($userID,$token){
        $resilt = $this->tokenService->checkToken($userID, $token);
        if ($resilt) {
            // 刷新token
            $this->tokenService->refreshToken($userID);
            return true;
        } else {
            return false;
        }
    }
}