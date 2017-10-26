<?php

namespace app\common\validate;


use think\Validate;

class BaseValidate extends Validate
{
	protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
	{
		return is_numeric($value) && is_int($value + 0) && ($value + 0) > 0 ? true : false;
	}

//    验证手机号格式
	public function isMobile($value)
	{
		$rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
		$result = preg_match($rule, $value);

		return $result ? true : false;
	}

//	只允许汉字和数字和#
	protected function isChNum($value, $rule = '', $data = '', $field = '')
	{
		$rule = '/^[\x{4e00}-\x{9fa5}0-9\#\-]+$/u';
		$result = preg_match($rule, $value);

		return $result ? true : false;
	}

}