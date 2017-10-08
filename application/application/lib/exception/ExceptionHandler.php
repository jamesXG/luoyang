<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/19
 * Time: 16:41
 */

namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandler extends Handle
{
	private $msg;
	private $code;
	private $errorCode;

//  自定义异常处理类
	public function render(Exception $e)
	{
		if ($e instanceof BaseException) {
			$this->code = $e->code;
			$this->msg = $e->msg;
			$this->errorCode = $e->errorCode;
		} else {
			if (config('app_debug')) {
				return parent::render($e); //返回TP5自带异常处理
			} else {
				$this->code = 500;
				$this->msg = 'server internal error';
				$this->errorCode = 999;
			}
		}

		$request = Request::instance();

		$result = [
			'msg' => $this->msg,
			'errorCode' => $this->errorCode,
			'requestUrl' => $request->baseUrl()
		];

		return json($result, $this->code);
	}
}