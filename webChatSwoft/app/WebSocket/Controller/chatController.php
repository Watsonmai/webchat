<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/5
 * Time: 10:53
 */
namespace App\WebSocket\Controller;

use App\Lib\Response\MyResoponse;
use App\Services\tokenService;
use App\WebSocket\Service\chatwsService;
use Redis;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\OnHandshake;
use Swoft\WebSocket\Server\Annotation\Mapping\OnMessage;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class EchoModule
 *
 * @WsModule("/chatws")
 */
class chatController
{

    /**
     * @Inject()
     * @var tokenService
     */
    private $tokenService;

    /**
     * @Inject()
     * @var chatwsService
     */
    private $chatwsService;

    /**
     * 在这里你可以验证握手的请求信息
     * @OnHandshake()
     * @param Request $request
     * @param Response $response
     * @return array [bool, $response]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        $token = $request->query('token');
        $userID = $request->query('userID');
        $result = $this->tokenService->checkToken($userID,$token);
        if($result){
            //$this->redis->set($userID, ["userID"=>$userID,'fid'=>]);
            return [true, $response];
        }else{
            return [false,$response];
        }

    }

    /**
     * On connection has open
     *
     * @OnOpen()
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        $userID = $request->query('userID');
        $this->chatwsService->initUser($fd,$userID);
    }

    /**
     * 前端信息接收，type类型标注信息类型
     * type = 0:心跳检测，服务端与客户端确认对方存在。
     * type = 1:用户发送信息。
     * type = -1:页面关闭，关闭连接。
     * @OnMessage()
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $data = json_decode($frame->data,true);
        var_dump($data);
        if($data['type'] != 0){
            if($data['type'] == -1){
                $this->chatwsService->close($data['userid']);
            }elseif($data['type'] == 1){
                $this->chatwsService->chat($data);
            }elseif($data['type'] == 2){
                $this->chatwsService->partyChat($data['targetID'],$data['text'],$frame->fd);
            }
            //$server->push($frame->fd, 'I have received message: ' . $frame->data);
        }else{
            $reData = array(
                'type'=>0,
                'text'=>'serve check'
            );
            $server->push($frame->fd, json_encode($reData));
        }

    }

    /**
     * On connection closed
     * - you can do something. eg. record log
     *
     * @OnClose()
     * @param Server $server
     * @param int $fd
     */
    public function onClose(Server $server, int $fd): void
    {
        //$this->redis->del('userFid:'.$fd);
        // you can do something. eg. record log, unbind user...
    }
}