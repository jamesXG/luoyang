<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/15
 * Time: 11:43
 */

namespace app\admin\validate;


class ListsRoom extends BaseValidate
{
	protected $rule = [
		'room' => 'require|chsDash',
		'roomCount' => 'isPositiveInteger'
	];

	protected $message = [
		'room.require' => '没有传递参数',
		'room.chsDash' => '参数1不符合规则',
		'roomCount' => '参数2不符合规则'
	];
}