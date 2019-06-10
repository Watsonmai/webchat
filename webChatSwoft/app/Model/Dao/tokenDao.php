<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/3
 * Time: 22:52
 */

namespace App\Model\Dao;

use App\Lib\Helper;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Db;
use Swoft\Db\Query;

/**
 * Class authService
 * @Bean()
 * @package App\Models\Dao
 */
class tokenDao {

    public function add($userID, $token){
        $expire_minute = env('AUTH_TOKEN_EXPIRE');
        $now = Helper::getCurrentTime();
        $expire = Helper::timeAdd($now, $expire_minute * 60);
        $data = array(
            'id' => Helper::create_guid(),
            'uid' => $userID,
            'token' => $token,
            'status' => 1,
            'createDate' => $now,
            'expireDate' => $expire
        );
        $result = DB::table('w_user_online')->insert($data);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function clean($userID){
        $result = DB::table('w_user_online')->where('uid','=',$userID)->delete();
        if (!is_int($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function checkToken($userID, $token){
        $result = DB::select('select * from w_user_online where uid = ? and token = ? and expireDate >= NOW()', [$userID,$token]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function refreshToken($userID){
        $expire_minute = env('AUTH_TOKEN_EXPIRE');
        $expire = Helper::timeAdd('', $expire_minute * 60);
        $result = DB::table('w_user_online')
                    ->where('uid','=' ,$userID)
                    ->update(['expireDate' => $expire]);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
}