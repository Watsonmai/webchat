<?php

use Swoft\Http\Server\HttpServer;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;

return [
    'logger'     => [
        'flushRequest' => true,
        'enable'       => false,
        'json'         => false,
    ],
    'httpServer' => [
        'class'    => HttpServer::class,
        'port'     => 8081,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        'on'       => [
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true
        ]
    ],
    'db'         => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=webchat;host=192.168.0.1',
        'username' => 'root',
        'password' => '12345',
        'config'   => [
            'collation' => 'utf8mb4_general_ci',
            'strict'    => false,
            'timezone'  => '+8:00',
            'modes'     => 'NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES',
            'fetchMode' => PDO::FETCH_ASSOC,
        ],
    ],
    'redis'      => [
        'class'    => RedisDb::class,
        'driver'   => 'phpredis',
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
        'retryInterval' => 20,
        'readTimeout'   => 0,
        'timeout'       => 2,
        'option'        => [
            'prefix'      => 'xxx',
            'serializer' => Redis::SERIALIZER_PHP
        ],
    ],
    'user'       => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'  => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
    'rpcServer'  => [
        'class' => ServiceServer::class,
    ],
    'wsServer'   => [
        'class'   => WebSocketServer::class,
        'port'     => 8081,
        'on'      => [
            // 加上如下一行，开启处理http请求
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug' => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
    'httpDispatcher'=>[
        'middlewares'=>[
            \App\Middlewares\RouteMiddlewares::class,
            \App\Middlewares\CorsMiddleware::class,
           // \Swoft\View\Middleware\ViewMiddleware::class,
        ]
    ]
];
