<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 18:22
 */

namespace App\Model\Dao;
use App\Lib\Helper;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Class authService
 * @Bean()
 * @package App\Models\Dao
 */
class userDao{

    public function getUserInfoByID($userID){
        $result = DB::select('select userNo,nickName from w_user where `id` = ?', [$userID]);
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }

    public function edit($userID,$nickName){
        $result = DB::table('w_user')
            ->where('id','=' ,$userID)
            ->update(['nickName' => $nickName]);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public function findUser($userNo){
        $result = DB::select('select userNo,nickName from w_user where `userNo` = ?', [$userNo]);
        if (!$result) {
            return false;
        } else {
            return $result[0];
        }
    }

    public function findUserByUserID($userId){
        $result = DB::select('select userNo,nickName from w_user where `id` = ?', [$userId]);
        if (!$result) {
            return false;
        } else {
            return $result[0];
        }
    }

    public function sendApply($userID,$applyNo){

        $applyInfo = DB::select('select id from w_user where `userNo` = ?', [$applyNo]);
        $values = [
            'id' => Helper::create_guid(),
            'uid' => $userID,
            'fid' => $applyInfo[0]['id'],
            'status' => 0,
            'createDate' => Helper::getCurrentTime()
        ];
        //$result = DB::insert('insert into w_user (`id`, `name`) values (?, ?)', [1, 'sumi']);
        $result = DB::table('w_user_friend')->insert($values);
        if($result){
            return $applyInfo[0]['id'];
        }else{
            return false;
        }
    }

    public function checkApply($userID){
        $sql = "SELECT
                    uu.id,
                    uu.nickName,
                    uu.userNo 
                FROM
                    w_user_friend AS uf
                    LEFT JOIN
                    w_user as u
                    on uf.fid = u.id 
                    LEFT JOIN 
                    w_user as uu on uf.uid = uu.id
                    where u.id = ? and uf.status = 0
                    ";
        $result = DB::select($sql, [$userID]);
        return $result;
    }

    public function handleApply($userID,$applyID){
        Db::beginTransaction();
        $result = DB::table('w_user_friend')
            ->where([
                ['uid','=' ,$applyID],
                ['fid','=' ,$userID],
                ['status','=' ,0]
            ])
            ->update(['status' => 1]);
        if (!$result) {
            Db::rollBack();
        } else {
            $values = [
                'id' => Helper::create_guid(),
                'uid' => $userID,
                'fid' => $applyID,
                'status' => 1,
                'createDate' => Helper::getCurrentTime()
            ];
            //$result = DB::insert('insert into w_user (`id`, `name`) values (?, ?)', [1, 'sumi']);
            $result = DB::table('w_user_friend')->insert($values);
            if($result){
                Db::commit();
                return true;
            }else{
                return false;
            }
        }

    }
}