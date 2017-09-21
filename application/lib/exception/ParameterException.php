<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/15
 * Time: 10:58
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
	public $code = 400;
	public $msg = 'parameter error';
	public $errorCode = 10000;

}