<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/24
 * Time: 11:49
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
	public $code = 202;
	public $msg = '参数错误';
	public $errorCode = 10001;
}