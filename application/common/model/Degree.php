<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/6
 * Time: 11:46
 */

namespace app\common\model;


use think\Db;

class Degree extends BaseModel
{
	public static function getDegreeNum()
	{
		$result = self::cache(true,7200)->count();

		return $result;
	}

	public function degreeLists()
	{
		$result = Db::view('degree','degree')->select();
//		$result = array_convert($result);

		return $result;
	}
}