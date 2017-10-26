<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/26
 * Time: 21:56
 */

namespace app\admin\validate;


class GradeValidate extends BaseValidate
{
	protected $rule = [
		'grade' => 'require|number'
	];

	protected $message = [
		'grade.number' => '参数不合法',
		'grade.require' => '缺少参数'
	];
}