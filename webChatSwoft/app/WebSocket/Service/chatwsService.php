<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/5
 * Time: 21:33
 */
namespace App\WebSocket\Service;

use App\Model\Dao\partyDao;
use App\Model\Dao\recordDao;
use App\Model\Dao\userDao;
use Redis;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Redis\Pool;

/**
 * Class authService
 * @Bean()
 * @package App\WebSocket\Service
 */
class chatwsService{

    /**
     *
     * 返回给前端的数据类型：
     * type = 1:聊天信息接收
     * type = 2:好友申请
     * type = 3:发送聊天信息的返回
     */

    /**
     * @Inject()
     * @var userDao
     */
    private $userDao;

    /**
     * @Inject()
     * @var recordDao
     */
    private $recordDao;

    public function initUser($fid,$userID){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        if( $redis->get($userID)){
            $redis->del($userID);
        }
        $redis->set($userID,  json_encode(["userID"=>$userID,'fid'=>$fid]));
        //返回建立连接的信息
        $reData = array(
            'type'=>0,
            'text'=>'建立连接'
        );
        server()->push($fid, json_encode($reData));
        //搜索是否有离线留言
        $record  = $this->recordDao->getRecord($userID);
        if($record){
            $length = count($record);
            for($i = 0;$i < $length;$i++){
                $senderInfo = $this->userDao->findUserByUserID($record[$i]['userID']);
                $reTargeterData = array(
                    'type'=>1,
                    'userType'=>0,
                    'nickName'=>$senderInfo['nickName'],
                    'sendID'=>$record[$i]['userID'],
                    'text'=>$record[$i]['text']
                );
                server()->push($fid,json_encode($reTargeterData));
                $this->recordDao->delRecord($record[$i]['id']);
            }
        }

        //检查是否有离线的好友申请
        $apply = $this->userDao->checkApply($userID);
        //var_dump($apply);
        if($apply){
            $length = count($record);
            for($i = 0;$i < $length;$i++){
                $reData = array(
                    'type'=>2,
                    'applyID'=>$apply[$i]['id']
                );
                server()->push($fid,json_encode($reData));
            }
        }
    }

    /**
     * @param $data
     */
    public function chat($data){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $user = json_decode($redis->get($data['targetID']),true);
        $sendUser =json_decode($redis->get($data['fromID']),true);
        $TargeterInfo = $this->userDao->findUserByUserID($data['targetID']);
        $senderInfo = $this->userDao->findUserByUserID($data['fromID']);
        //var_dump($sendUserInfo);
        if($user){
            $reTargeterData = array(
                'type'=>1,
                'userType'=>0,
                'chatType'=>1,
                'nickName'=>$senderInfo['nickName'],
                'sendID'=>$sendUser['userID'],
                'text'=>$data['text']
            );
            $reSenderData = array(
                'type'=>3,
                'userType'=>1,
                'nickName'=>$TargeterInfo['nickName'],
                'sendID'=>$sendUser['userID']
            );
            server()->push($user['fid'],json_encode($reTargeterData));
            server()->push($sendUser['fid'],json_encode($reSenderData));
        }else{
            $result = $this->recordDao->setRecord($data['fromID'],$data['targetID'],$data['text']);
        }
    }

    public function sendApply($applyID,$sendID){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $user = json_decode($redis->get($sendID),true);
        if($user){
            $reData = array(
                'type'=>2,
                'applyID'=>$applyID
            );
            server()->push($user['fid'],json_encode($reData));
        }

    }

    public function partyChat($partyID,$text,$fid){
        var_dump($partyID);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $partyInfo = json_decode($redis->get($partyID),true);
        $userList = $partyInfo['userList'];
        $length = count($userList);
        $sendArray = [];
        $recordArray = [];
        for($i =0 ;$i <$length;$i++){
            $userInfo = json_decode($redis->get($userList[$i]),true);
            if($userInfo){
                array_push($sendArray,$userInfo['fid']);
            }else{
                array_push($recordArray,$userList[$i]);
            }
        }
        var_dump($partyInfo);
        $redata = array(
            'type'=>1,
            'userType'=>0,
            'chatType'=>2,
            'nickName'=>$partyInfo['partyName'],
            'sendID'=>$partyID,
            'text'=>$text
        );
        server()->sendToSome(json_encode($redata), $sendArray,[$fid]);
    }

    public function close($userID){
        var_dump('close: '.$userID);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->del($userID);
    }
}