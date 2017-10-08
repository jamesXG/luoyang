<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/15
 * Time: 11:03
 */

namespace app\admin\validate;


class GalleryMustBeRange extends BaseValidate
{
	protected $rule = [
		'room' => 'require|galleryRange'
	];

	protected $message = [
		'room.require' => '没有传递参数',
		'room.galleryRange' => '楼座不在范围内'
	];
}