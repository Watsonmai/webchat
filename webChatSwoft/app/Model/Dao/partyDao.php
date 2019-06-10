<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/10
 * Time: 15:31
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
class partyDao{

    public function addParty($userList,$partName){
        $id = Helper::create_guid();
        $values = [
            'id' => $id,
            'userList' => $userList,
            'partyName' => $partName,
            'status' => 1,
            'createDate' => Helper::getCurrentTime()
        ];
        //$result = DB::insert('insert into w_user (`id`, `name`) values (?, ?)', [1, 'sumi']);
        $result = DB::table('w_party')->insert($values);
        if($result){
            return $id;
        }else{
            return false;
        }
    }

    public function getPart($userID){
        $sql = "select * from w_party where userList like '%$userID%'";
        $result = DB::select($sql);
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function getPartyById($partyID){
        $sql = "select * from w_party where id = ?";
        $result = DB::select($sql,[$partyID]);
        if($result){
            return $result;
        }else{
            return false;
        }
    }
}