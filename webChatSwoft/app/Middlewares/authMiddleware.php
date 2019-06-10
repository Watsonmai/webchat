<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Middlewares;

use App\Lib\Helper;
use App\Lib\Response\MyResoponse;

use App\Services\authService;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Context\Context;
/**
 * Class AdminAuthMiddleware - Custom middleware
 * @Bean()
 * @package App\Middlewares
 */
class authMiddleware implements MiddlewareInterface
{
    /**
     * @Inject()
     * @var authService
     */
    private $authService;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // before request handle
        $token = $request->getHeaderLine('Access-Token');
        $userID = $request->getHeaderLine('Access-userID');
//        Helper::debug($token, $adminID);
        $result = $this->authService->checkAuth($userID, $token);
        if (!$result) {
            $data = MyResoponse::getExceptionResult(NOAUTH_CODE, NOAUTH_MSG, '', __FILE__, __LINE__);
            return response()->withData($data);
        } else {
            $response = $handler->handle($request);
            // after request handle
            return $response;
        }
    }
}
