<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/24
 * Time: 15:46
 */

namespace app\lib\exception;


class PostException extends BaseException
{
	public $code = 400;
	public $msg = '参数传递错误';
	public $errorCode = 10001;
}