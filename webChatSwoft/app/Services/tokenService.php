<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/3
 * Time: 22:49
 */
namespace App\Services;

use App\Lib\Helper;
use App\Model\Dao\tokenDao;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Class authService
 * @Bean()
 * @package App\Services
 */
class tokenService{

    /**
     * @Inject()
     * @var tokenDao
     */
    private $tokenDao;

    public function setToken($userID){
        DB::beginTransaction();
        // 清理
        $result = $this->tokenDao->clean($userID);
        if (!$result) {
            Db::rollback();
            return array(
                'code' => 0,
                'error' => '清理token异常',
                'data' => ''
            );
        }
        $token = Helper::create_guid();
        // 插入
        $result = $this->tokenDao->add($userID, $token);
        if (!$result) {
            Db::rollback();
            return array(
                'code' => 0,
                'error' => '设置token异常',
                'data' => ''
            );
        }
        Db::commit();
        return array(
            'code' => 1,
            'error' => '',
            'data' => $token
        );
    }

    /**
     * 清理token
     * @param $adminID
     * @return bool
     */
    public function clean($adminID) {
        $this->tokenDao->clean($adminID);
        return true;
    }

    public function checkToken($userID, $token){
        $result = $this->tokenDao->checkToken($userID, $token);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function refreshToken($userID){
        $result = $this->tokenDao->refreshToken($userID);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
}