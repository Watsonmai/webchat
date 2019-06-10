<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/10
 * Time: 11:39
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
class recordDao{

    public function setRecord($userID,$toUserID,$text){
        $values = [
            'id' => Helper::create_guid(),
            'userID' => $userID,
            'toUserID' => $toUserID,
            'text' => $text,
            'status' => 1,
            'createDate' => Helper::getCurrentTime()
        ];
        //$result = DB::insert('insert into w_user (`id`, `name`) values (?, ?)', [1, 'sumi']);
        $result = DB::table('w_user_record')->insert($values);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function getRecord($toUserID){
        $result = DB::select('select * from w_user_record where `toUserID` = ?', [$toUserID]);
        if (!$result) {
            return false;
        } else {
            return $result;
        }
    }

    public function delRecord($id){
        $result = DB::select('delete from w_user_record where id = ?', [$id]);
        if (!$result) {
            return false;
        } else {
            return $result;
        }
    }
}