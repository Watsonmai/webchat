<?php
/**
 * Created by PhpStorm.
 * User: Mars
 * Date: 2018/10/4
 * Time: 6:13 PM
 */

namespace App\Lib\Response;

use App\Lib\Helper;

class MyResoponse {

    /**
     * 普通返回结果
     * @return array
     */
    public static function getNormalResult($code, $msg, $data): array
    {
        return array(
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data
        );
    }

    /**
     * 异常返回
     * @return array
     */
    public static function getExceptionResult($code, $msg, $data, $file, $line): array
    {
        $debug = env('APP_DEBUG', false);
        if ($debug) {
            return array(
                'code'  => $code,
                'msg'   => $msg,
                'data'  => $data,
                'file'  => $file,
                'line'  => $line,
            );
        } else {
            return array(
                'code'  => $code,
                'msg'   => $msg,
                'data'  => $data
            );
        }
    }
}