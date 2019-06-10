<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 20:34
 */
namespace App\Services;

use App\Model\Dao\chatDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class authService
 * @Bean()
 * @package App\Services
 */
class chatService{

    /**
     * @Inject()
     * @var chatDao
     */
    private $chatDao;


    public function getFriendList($userID){
      $result = $this->chatDao->getFriendList($userID);
        return array(
            'code' => 1,
            'error' => '',
            'data' => $result
        );
    }
}