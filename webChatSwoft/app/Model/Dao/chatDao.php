<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 20:35
 */

namespace App\Model\Dao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Class authService
 * @Bean()
 * @package App\Models\Dao
 */
class chatDao{

    public function getFriendList($userID){
        $sql = "SELECT 
                u.id,
                u.nickName,
                u.id as 'key',
                u.nickName as 'label'
                FROM
                w_user_friend as uf 
                LEFT JOIN w_user as u on uf.fid = u.id
                where uf.uid = ? and uf.status = 1";
        $result = DB::select($sql, [$userID]);
        if($result){
            return $result;
        }else{
            return false;
        }
    }
}