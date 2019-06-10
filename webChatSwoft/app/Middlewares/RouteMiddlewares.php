<?php
/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 2019/6/2
 * Time: 17:18
 */
namespace App\Middlewares;
use App\Lib\Response\MyResoponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Context\Context;
/**
 * @Bean()
 */
class RouteMiddlewares implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 获取路由配置信息
        $filterPrefix = env('Filter_Prefix');
        $filterController = env('Filter_Controller');

        // 获取请求信息
        $uri = $request->getUri()->getPath();
        $tmpArr = explode('/', $uri);
        $prefix = isset($tmpArr[1]) ? strtolower($tmpArr[1]) : '';
        $controller = isset($tmpArr[2]) ? strtolower($tmpArr[2]) : '';

        if ($filterPrefix != '*' && strstr($filterPrefix, $prefix) === false) {
            $data = MyResoponse::getExceptionResult(FILTER_CODE, FILTER_MSG, '', __FILE__, __LINE__);
            return response()->withData($data);
        } else if ($filterController != '*' && strstr($filterController, $controller) === false) {
            $data = MyResoponse::getExceptionResult(FILTER_CODE, FILTER_MSG, '', __FILE__, __LINE__);
            return response()->withData($data);
        } else {
            $response = $handler->handle($request);
            return $response;
        }
    }
}