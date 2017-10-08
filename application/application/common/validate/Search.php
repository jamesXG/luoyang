<?php
namespace app\common\validate;


class Search extends BaseValidate
{
	protected $rule = [
		'data' => 'require|chsAlphaNum'
	];
	protected $message = [
		'data' => '输入内容不符合规则'
	];

	protected $scene = [
		'search' => ['data']
	];
}