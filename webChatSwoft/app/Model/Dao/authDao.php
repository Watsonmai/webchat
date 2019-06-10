<?php


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
class authDao
{

    public function getUserByName($username){
        $result = DB::select('select * from w_user where `userName` = ?', [$username]);
        if($result){
            return $result[0];
        }else{
            return false;
        }

    }

    public function add($username,$password){
        $values = [
            'id' => Helper::create_guid(),
            'userName' => $username,
            'userNo' => mt_rand(100000000,999999999),
            'password' => $password,
            'nickName' => mt_rand(100000000,999999999),
            'status' => 1,
            'createDate' => Helper::getCurrentTime()
        ];
        //$result = DB::insert('insert into w_user (`id`, `name`) values (?, ?)', [1, 'sumi']);
        $result = DB::table('w_user')->insert($values);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}