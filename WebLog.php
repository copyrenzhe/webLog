<?php

namespace weblib\log;

/**
 * 日志类
 * Class log
 * @package weblib\log\WebLog
 */
class WebLog
{
    // 日志存放路径
    protected static $logpath =TSF_TAF_LOG_PATH;

    /**
     * 设置日志路径
     * @param $path
     * @return bool
     */
    public static function setLogpath($path = '')
    {
        if (!empty($path) && file_exists($path)) {
            self::$logpath = $path;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 信息日志(用于请求出入的参数)
     * @param $msg
     * @param $r
     * @param $method
     */
    public static function info($msg, $r, $method, $df = null, $df2 = null, $otherMsgArr = [])
    {
        if (isset(\ENVConst::$log_path) && !empty(\ENVConst::$log_path)) {
            self::setLogPath(\ENVConst::$log_path);
        }
        $logLevel = \ENVConst::LOG_LEVEL;
        if ($logLevel >= 4){
            self::logJson($msg, "info", $otherMsgArr, $r, $method, $df, $df2);
        }
    }

    /**
     * 错误日志(所有失败的地方都必须记录)
     * @param $msg
     * @param $r
     * @param $method
     */
    public static function error($msg, $r, $method, $df = null, $df2 = null, $otherMsgArr = [])
    {
        if (isset(\ENVConst::$log_path) && !empty(\ENVConst::$log_path)) {
            self::setLogPath(\ENVConst::$log_path);
        }
        self::logJson($msg, "error", $otherMsgArr, $r, $method, $df, $df2);
    }

    /**
     * 调试用的信息日志
     * @param $msg
     * @param $r
     * @param $method
     */
    public static function debug($msg, $r, $method, $df = null, $df2 = null, $otherMsgArr = [])
    {
        if (isset(\ENVConst::$log_path) && !empty(\ENVConst::$log_path)) {
            self::setLogPath(\ENVConst::$log_path);
        }
        $logLevel = \ENVConst::LOG_LEVEL;
        if ($logLevel >= 5)
            self::logJson($msg, "debug", $otherMsgArr, $r, $method, $df, $df2);
    }

    public static function logJson($msg, $level = 'info', $otherMsgArr = [], $r = 0, $class = 0, $df = null, $df2 = null)
    {
        $list = explode('::', $class);
        $class = $list[0];
        $class = str_replace('\\', '_', $class);
        $method = $list[1];

        $logArray = $otherMsgArr;
        $logArray['msg'] = $msg;
        $logArray['time'] = date('m-d H:i:s');
        $logArray['method'] = $method;
        $logArray['class'] = $class;
        if (isset($df)) {
            $logArray['ywGuid'] = $df;
        }
        if (isset($df2)) {
            $logArray['bookId'] = $df2;
        }
        $logArray['r'] = $r;
        $logArray['level'] = $level;

        $msg = json_encode($logArray, JSON_UNESCAPED_UNICODE) . "\n\r";

        error_log($msg, 3, self::$logpath . $class . '.' . date('Y-m-d') . '.log');

        return $msg;
    }
}
