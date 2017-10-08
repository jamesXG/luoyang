<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/8
 * Time: 14:01
 */

namespace app\common\validate;


class Student extends BaseValidate
{
	protected $rule = [
		['tel','isMobile','手机格式不符合规则']
	];
}