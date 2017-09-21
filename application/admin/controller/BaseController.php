<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/31
 * Time: 14:25
 */

namespace app\admin\controller;


use think\Controller;

class BaseController extends Controller
{

//  替换数组中字段的特殊字符
	public function arrReplace($data)
	{
		foreach ($data as $key => $value) {
			$data[$key]['room'] = str_replace("#", "-", $value['room']);
		}

		return $data;
	}
}