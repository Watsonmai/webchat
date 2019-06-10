<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/3
 * Time: 12:36
 */

namespace App\Controller;

use App\Lib\Response\MyResoponse;
use App\Services\authService;
use Redis;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use App\Middlewares\authMiddleware;
use Swoft\Redis\Pool;

/**
 * Class ViewController
 *
 * @since 2.0
 *
 * @Controller(prefix="webchat/auth")
 */
class authController{

    /**
     * @Inject()
     * @var authService
     */
    private $authService;

    /**
     * @RequestMapping("in")
     * @return array
     */
    public function in() {
        $username = request()->post('userName');
        $password = request()->post('password');
        $result = $this->authService->checkIn($username,$password);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping("add")
     * @return array
     */
    public function add() {
        $username = request()->post('userName');
        $password = request()->post('password');
        $result = $this->authService->add($username,$password);
        if ($result['code'] == 1) {
            return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, $result['data']);
        } else {
            return MyResoponse::getNormalResult(FAILED_CODE, $result['error'], '');
        }
    }

    /**
     * @RequestMapping(route="out")
     * @Middleware(authMiddleware::class)
     * @return array
     */
    public function out(): array
    {
        $userID = request()->getHeaderLine('Access-userID');
        var_dump($userID);
        return MyResoponse::getNormalResult(SUCCESS_CODE, SUCCESS_MSG, '');
    }

    /**
     * eg 2
     *
     * @Inject()
     *
     * @var Pool
     */
    private $redis;

    /**
     * @RequestMapping(route="wstest")
     * @return array
     */
    public function test(): array
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $test = request()->query('test');
        //server()->push(1, '2,发送测试'.$test);
        var_dump($redis->get('90C6F280-85D3-5465-5743-0D5A64D14932'));
        //$this->redis->close();
        return array(

        );
    }
}