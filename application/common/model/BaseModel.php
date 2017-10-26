<?php

namespace app\common\model;


use think\Cookie;
use think\Model;

class BaseModel extends Model
{
	/**
	 * @return 辨别用户的身份，区别是管理员、经理、学生
	 */
	protected static function userIdentity()
	{
		if (!Cookie::has('identity')) {
			return null;
		}

		$identity = base64_decode(cookie('identity'));
//		$identity = null;
		return $identity;
	}

	/**
	 * @return 在需要遍历楼座信息的模块上设置身份层,返回楼座数组
	 */
	protected static function identitySetModel()
	{
		$identity = self::userIdentity();
		if($identity == null){
			$builds = builds();
		}else{
			$builds = [$identity];
		}

		return $builds;
	}

	/**
	 * @param $arr  数组
	 * @param $builds 楼座
	 * @param int $status 状态
	 * @return array
	 */
	public static function mergeArr($arr, $builds, $status = 0)
	{
		if ($status == 0) {
			$arr = array_convert($arr);
			foreach ($builds as $k => $v) {
				$arr[$k]['room'] = $v;
			}
		} elseif ($status == 1) {
			foreach ($builds as $k => $v) {
				$arr[$k]['room'] = $v;
			}
		}

		return $arr;
	}

//	获取今日的月份+天数
	public static function getDate()
	{
		return date('md');
	}


	/**
	 * 修改一个二维数组的某一键值
	 * @param $arr
	 * @param $value 修改的数组值
	 * @param $start 字符串开始
	 * @param $len   字符串长度
	 * @return mixed
	 */
	protected static function changeArrValue($arr,$value,$start,$len)
	{
		foreach ($arr as $k=>$v){
			$arr[$k][$value] = mb_substr($arr[$k][$value],$start,$len);
		}

		return $arr;
	}

}