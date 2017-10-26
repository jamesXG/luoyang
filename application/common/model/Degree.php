<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/6
 * Time: 11:46
 */

namespace app\common\model;


use app\lib\enum\BuildsEnum;
use think\Db;

class Degree extends BaseModel
{
	public static function getDegreeNum()
	{
		$identity = self::userIdentity();
		if ($identity == null) {
			$result = self::cache(true, BuildsEnum::TIME)->count();
		} else {
			$condition = [
				'degree' => ['neq',''],
				'room' => ['like',$identity.'%']
			];
			$result = self::table('student_info')->where($condition)->group('degree')->cache(true, BuildsEnum::TIME)->count();
		}

		return $result;
	}

	public function degreeLists()
	{
		$identity = self::userIdentity();
		if ($identity == null) {
			$result = Db::view('degree', 'degree')->select();
		} else {
			$display = ['degree'];
			$condition = [
				'degree' => ['neq', ''],
				'room' => ['like', $identity . '%']
			];
			$result = Db::table('student_info')->where($condition)->group('degree')->field($display)->cache(true, BuildsEnum::TIME)->select();
		}

		return $result;
	}
}