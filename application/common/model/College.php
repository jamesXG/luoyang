<?php

namespace app\common\model;


use app\lib\enum\BuildsEnum;
use think\Db;

class College extends BaseModel
{
//	获取学院总数
	public function getAllCollege()
	{
		$identity = self::userIdentity();
		if($identity == null){
			$result = $this->cache(true, BuildsEnum::TIME)->count();
		}else{
			$condition = [
				'college' => ['neq',''],
				'room' => ['like',$identity.'%']
			];
			$result = self::table('student_info')->where($condition)->group('college')->cache(true,BuildsEnum::TIME)->count();
		}


		return $result;
	}

//	获取学院名称
	public function getCollegeName()
	{
		$identity = self::userIdentity();
		if($identity == null){
			$result = Db::view('college', 'college')->field('college')->cache(true,BuildsEnum::TIME)->group('college')->select();
		}else{
			$condition = [
				'college' => ['neq',''],
				'room' => ['like',$identity.'%']
			];
			$result = Db::table('student_info')->where($condition)->group('college')->cache(true,BuildsEnum::TIME)->select();
		}

		return $result;
	}


}