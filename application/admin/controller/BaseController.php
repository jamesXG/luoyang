<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/31
 * Time: 14:25
 */

namespace app\admin\controller;


use app\lib\enum\BuildsEnum;
use app\lib\exception\PostException;
use think\Controller;

class BaseController extends Controller
{

//  替换数组中字段的特殊字符
	public function arrReplace($data)
	{
		foreach ($data as $key => $value) {
			$data[$key]['room'] = str_replace(BuildsEnum::DATAUNIT, BuildsEnum::GALATE, $value['room']);
		}

		return $data;
	}

	/**
	 * @param $str需要替换的字符串
	 * @param $k 需要替换的字符
	 * @param $v 替换后的字符
	 * @return mixed
	 */
	public function strPlace($str, $k, $v)
	{
		if (is_string($str)) {
			return str_replace($k, $v, $str);
		} elseif (is_array($str)) {
			foreach ($str as $key => $value) {
				if (!strpos($value, $k)) {
					continue;
				} else {
					$str[$key] = str_replace($k, $v, $value);
				}
			}
			return $str;

		}

	}

//  根据性别显示不同的楼座
	public function getGalleryAttribute($sex)
	{
		if ($sex == BuildsEnum::FEMALE) {
			$data = array_slice(builds(), 0, BuildsEnum::END);
		} else if ($sex == BuildsEnum::MALE) {
			$data = array_slice(builds(), BuildsEnum::END);
		} else {
			$data = builds();
		}

		return $data;
	}
}