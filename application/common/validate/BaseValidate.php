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
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

}