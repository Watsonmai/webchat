<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/10
 * Time: 16:52
 */

namespace App\Services;
use App\Model\Dao\partyDao;
use Redis;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;


/**
 * Class authService
 * @Bean()
 * @package App\WebSocket\Service
 */
class partyService{

    /**
     * @Inject()
     * @var partyDao
     */
    private $partyDao;

    public function getList($userID){
        $result = $this->partyDao->getPart($userID);
        return array(
            'code' => 1,
            'error' => '',
            'data' => $result
        );
    }

    public function addParty($userID,$partyName){
        $result = $this->partyDao->addParty($userID,$partyName);
        if($result){
            $this->setPartyOfRedis($result);
            return array(
                'code' => 1,
                'error' => '',
                'data' => $result
            );
        }else{
            return array(
                'code' => 0,
                'error' => '获取群聊异常',
                'data' => ''
            );
        }
    }

    public function setPartyOfRedis($partyID){
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $party = $redis->get($partyID);
        if($party){
            return array(
                'code' => 1,
                'error' => '',
                'data' => 'success'
            );
        }else{
            $partyInfo = $this->partyDao->getPartyById($partyID);
            var_dump($partyInfo);
            $userList = $partyInfo[0]['userList'];
            $redis->set($partyID,  json_encode(["userList"=>$userList,"partyName"=>$partyInfo[0]['partyName']]));
            return array(
                'code' => 1,
                'error' => '',
                'data' => 'success'
            );
        }
    }
}