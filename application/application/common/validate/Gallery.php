<?php
namespace app\common\validate;


class Gallery extends BaseValidate
{
	protected $rule = [
		'id' => 'require|isPositiveInteger'
	];
}