<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/13
 * Time: 23:41
 */

namespace app\lib\exception;


class CacheException extends BaseException
{
	public $code = 510;
	public $msg = '缓存写入错误';
	public $errorCode = 10003;
}