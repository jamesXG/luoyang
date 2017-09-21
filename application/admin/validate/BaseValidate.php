<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/14
 * Time: 12:44
 */

namespace app\admin\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
	public function goCheck()
	{
//      获取传入的参数
		$request = Request::instance();
		$params = $request->param();

		$result = $this->batch()->check($params);
		if (!$result) {
			$e = new ParameterException([

				'msg' => $this->error,

			]);

			throw $e;

		} else {
			return true;
		}
	}

//  检查楼座是否在范围内
	public function galleryRange($value, $rule = '', $data = '', $field = '')
	{
		return in_array($value, builds()) ? true : false;
	}

//  检测参数是否为正整数
	protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
	{
		return is_numeric($value) && is_int($value + 0) && ($value + 0) > 0 ? true : false;
	}
}
