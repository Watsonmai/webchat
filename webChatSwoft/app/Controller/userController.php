<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/4
 * Time: 18:08
 */

namespace App\Controller;

use App\Lib\Response\MyResoponse;
use App\Services\userService;
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
 * @Controller(prefix="webchat/user")
 */
class userController{

    /**
     * @Inject()
     * @var userService
     */
    private $userService;

    /**
     * @RequestMapping("getuserinfo")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function getUserInfo() {
        $userID = request()->getHeaderLine('Access-userID');
        $result = $this->userService->getUserInfoByID($userID);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("edit")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function edit() {
        $userID = request()->getHeaderLine('Access-userID');
        $nickName = request()->post('nickName');
        $result = $this->userService->edit($userID,$nickName);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("finduser")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function findUser() {
        $userNo = request()->post('id');
        $result = $this->userService->finUser($userNo);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("sendapply")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function sendApply() {
        $userID = request()->getHeaderLine('Access-userID');
        $applyNo = request()->post('applyNo');
        $result = $this->userService->sendApply($userID,$applyNo);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("checkapply")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function checkApply(){
        $userID = request()->getHeaderLine('Access-userID');
        $result = $this->userService->checkApply($userID);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("handleapply")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function handleApply(){
        $userID = request()->getHeaderLine('Access-userID');
        $applyID = request()->post('applyID');
        $result = $this->userService->handleApply($userID,$applyID);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

}