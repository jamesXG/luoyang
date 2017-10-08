<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/24
 * Time: 21:57
 */

namespace app\common\validate;


class Room extends BaseValidate
{
	protected $rule = [
		'room'=>'require|isTrueRoom'
	];

	protected $message = [
		'room' => '房间输入不符合规则'
	];

}