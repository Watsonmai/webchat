<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 20:20
 */
namespace App\Controller;
use App\Lib\Response\MyResoponse;
use App\Services\chatService;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use App\Middlewares\authMiddleware;
/**
 * Class ViewController
 *
 * @since 2.0
 *
 * @Controller(prefix="webchat/chat")
 */
class chatController{

    /**
     * @Inject()
     * @var chatService
     */
    private $chatService;

    /**
     * @RequestMapping("getfriendlist")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function getFriendList(){
        $userID = request()->getHeaderLine('Access-userID');
        $result = $this->chatService->getFriendList($userID);
        return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
    }

}