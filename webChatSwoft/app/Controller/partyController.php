<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/10
 * Time: 16:51
 */

namespace App\Controller;
use App\Lib\Response\MyResoponse;
use App\Services\partyService;
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
 * @Controller(prefix="webchat/party")
 */
class partyController
{

    /**
     * @Inject()
     * @var partyService
     */
    private $partyService;


    /**
     * @RequestMapping("getlist")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function getPartyList()
    {
        $userID = request()->getHeaderLine('Access-userID');
        $result = $this->partyService->getList($userID);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("add")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function addParty()
    {
        $userList = json_encode(request()->post('users'));
        $partyName = request()->post('name');
        var_dump($userList);
        $result = $this->partyService->addParty($userList, $partyName);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("setpartyofredis")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function setPartyOfRedis()
    {
        $userList = json_encode(request()->post('users'));
        $partyName = request()->post('name');
        var_dump($userList);
        $result = $this->partyService->addParty($userList, $partyName);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }
}