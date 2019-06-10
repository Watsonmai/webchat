<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 18:15
 */
namespace App\Services;

use App\Model\Dao\userDao;
use App\WebSocket\Service\chatwsService;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;


/**
 * Class authService
 * @Bean()
 * @package App\Services
 */
class userService{

    /**
     * @Inject()
     * @var userDao
     */
    private $userDao;


    /**
     * @Inject()
     * @var chatwsService
     */
    private $chatwsService;

    public function getUserInfoByID($userID){
       $result = $this->userDao->getUserInfoByID($userID);
       if($result){
           return array(
               'code' => 1,
               'error' => '',
               'data' => $result
           );
       }else{
           return array(
               'code' => 0,
               'error' => '获取信息异常',
               'data' => ''
           );
       }
    }

    public function edit($userID,$nickName){
        $result = $this->userDao->edit($userID,$nickName);
        if($result){
            return array(
                'code' => 1,
                'msg' => '',
                'data' => $result
            );
        }else{
            return array(
                'code' => 0,
                'msg' => '修改用户信息异常',
                'data' => ''
            );
        }
    }

    public function finUser($userNo){
        $result = $this->userDao->findUser($userNo);
        if($result){
            return array(
                'code' => 1,
                'msg' => '',
                'data' => $result
            );
        }else{
            return array(
                'code' => 0,
                'msg' => '查找用户信息异常',
                'data' => ''
            );
        }
    }

    public function sendApply($userID,$applyNo){
        $result = $this->userDao->sendApply($userID,$applyNo);
        if($result){
            $this->chatwsService->sendApply($userID,$result);
            return array(
                'code' => 1,
                'msg' => '',
                'data' => $result
            );
        }else{
            return array(
                'code' => 0,
                'msg' => '添加好友异常',
                'data' => ''
            );
        }
    }

    public function checkApply($userID){
        $result = $this->userDao->checkApply($userID);
        return array(
            'code' => 1,
            'msg' => '',
            'data' => $result
        );
    }

    public function handleApply($userID,$applyID){
        $result = $this->userDao->handleApply($userID,$applyID);
        if($result){
            return array(
                'code' => 1,
                'msg' => '',
                'data' => $result
            );
        }else{
            return array(
                'code' => 0,
                'msg' => '添加好友异常',
                'data' => ''
            );
        }
    }
}