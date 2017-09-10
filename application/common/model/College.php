<?php

namespace app\common\model;


use think\Db;

class College extends BaseModel
{
//	获取学院总数
	public function getAllCollege()
	{

		$result = $this->cache(true, 7200)->count();

		return $result;
	}

//	获取学院名称
	public function getCollegeName()
	{
		$result = Db::view('college', 'college')->field('college')->cache(true,7200)->group('college')->select();

		return $result;
	}


}