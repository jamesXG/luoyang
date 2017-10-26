<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/13
 * Time: 22:44
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
	public $code = 500;
	public $msg = '微信服务器错误';
	public $errorCode = 10002;
}