<?php

namespace app\common\model;


use think\Db;

class Search extends BaseModel
{

	public static function searchNum($data = '')
	{
		$display = ['id', 'name', 'gender', 'major'];
		$order = [
			'id' => 'ASC'
		];
		$result = Db::table('student_info')->field($display)->where('stu_num', 'like', "$data%")->where('gender', 'neq', '')->order($order)->select();

		return $result;
	}

	public static function searchName($data = '')
	{
		$display = ['id', 'name', 'gender', 'major'];
		$order = [
			'id' => 'ASC'
		];
		$result = Db::table('student_info')->field($display)->where('name', 'like', "$data%")->where('gender', 'neq', '')->order($order)->select();

		return $result;
	}


}