<?php
/**
 * Created by PhpStorm.
 * User: Mars
 * Date: 2018/10/4
 * Time: 8:18 PM
 */

namespace App\Lib;

use Psr\Http\Message\ResponseInterface;

class Helper {
    /**
     * 生成随机字符串
     * @param $length
     * @return null|string
     */
    public static function getRandChar($length = 40) {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            //rand($min,$max)生成介于min和max两个数之间的一个随机整数
            $str.=$strPol[rand(0,$max)];
        }

        return $str;
    }

    /**
     * @param int $length
     * @return null|string
     */
    public static function getRandNum($length = 4) {
        $str = null;
        $strPol = "0123456789";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            //rand($min,$max)生成介于min和max两个数之间的一个随机整数
            $str.=$strPol[rand(0,$max)];
        }

        return $str;
    }

    /**
     * 获取请求的来源ip地址
     * @return string
     */
    public static function getremoteip(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return($ip);
    }

    /**
     * debug输入函数
     */
    public static function debug()
    {
        $args = func_get_args();
        if (PHP_SAPI !== 'cli') echo '<pre>';
        echo "\n------------------------debug info------------------------------\n";
        foreach ($args as $v) {
            if (!$v)
                echo var_dump($v), "\n";
            else print_r($v);
            echo "\n";
        }
        echo "\n", "----------------------------------------------------------------\n";
        $trace = current(debug_backtrace());
        echo "FILE: {$trace['file']}, Line: {$trace['line']}\n";
        if (PHP_SAPI !== 'cli') echo '</pre>';
    }

    public static function makeSign($arr, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);
        $string = "";
        foreach ($arr as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $string .= $k . "=" . $v . "&";
            }
        }

        $string = trim($string, "&");
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $key;
        //签名步骤三：MD5加密
        $string = sha1($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 生成加密串
     * @param $salt
     * @param $str
     * @return string
     */
    public static function encryptStr($salt, $str) {
        $tmpArr = array($salt, $str);
        sort($tmpArr, SORT_STRING);

        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        return $tmpStr;
    }

    /**
     * 生成guid
     * @return string
     */
    public static function create_guid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }

    /**
     * 获取当前日期
     * @return false|string
     */
    public static function getCurrentDate() {
        return date("Y-m-d");
    }

    /**
     * 获取当前时间
     * @return false|string
     */
    public static function getCurrentTime() {
        return date("Y-m-d H:i:s");
    }

    /**
     * 获取月
     * @param $date
     * @return false|string
     */
    public static function getMonth($date) {
        $data = date("m", strtotime($date));

        if ($data >= 10) {
            return $data;
        } else {
            return substr($data, 1, 1);
        }
    }

    /**
     * 获取周几
     * @param $date
     * @return false|string
     */
    public static function getWeek($date) {
        $tmp = date("w", strtotime($date));
        if ($tmp == 0) {
            return 7;
        } else {
            return $tmp;
        }
    }

    /**
     * 获取日期
     * @param $date
     * @return false|string
     */
    public static function getDate($date) {
        return date("d", strtotime($date));
    }

    /**
     * 日期加
     * @param $date
     * @param $day
     * @return false|string
     */
    public static function DateAdd($date, $day) {
        return date("Y-m-d", strtotime("$date +$day day"));
    }

    /**
     * 时间差
     * @param $fromTime
     * @param string $toTIme
     * @return float
     */
    public static function getDiffTime($fromTime, $toTIme = '') {
        if (empty($toTIme)) {
            $toTIme = date('Y-m-d H:i:s');
        }

        return floor((strtotime($toTIme)-strtotime($fromTime))%86400/60);;
    }

    /**
     * 获取在指定时间上增加指定秒数后的时间
     * @param $time
     * @param $add  秒数
     * @return false|string
     */
    public static function timeAdd($time, $add) {
        if (empty($time)) {
            $time = date('Y-m-d H:i:s');
        }

        return date('Y-m-d H:i:s', strtotime($time . "+". $add ."second"));
    }

    /**
     * 截取两位小数
     * @param $num
     * @return float|int
     */
    public static function getTwoTruncation($num) {
        $tmp = floor($num * 100);
        return $tmp / 100;
    }

    /**
     * 判断是否是数字类型
     * @param $value
     * @return bool
     */
    public static function isNumber($value) {
        if (is_numeric($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断参数是否是字符串类型
     * @param $value
     * @return bool
     */
    public static function isString($value) {
        if (is_string($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否正确的电话号码
     * @param $mobile
     * @return bool
     */
    public static function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }

        $result = preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[\d]{9}$#', $mobile) ? true : false;
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * 判断是否日期
     * @param $date
     * @return bool
     */
    public static function isDate($date){
        $unixTime = strtotime($date);
        if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
            return false;
        } else {
            return true;
        }
    }

    /**
     * 对象转数组
     * @param $object
     * @return mixed
     */
    public static function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        }
        else {
            $array = $object;
        }
        return $array;
    }

    /**
     * 跨域处理
     * @param ResponseInterface $response
     * @return mixed
     */
    public static function configCrosResponse(ResponseInterface $response)
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Access-Token, Access-AdminID')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('content-type', 'text/json')
            ->withHeader('charset', 'utf-8');
    }
}