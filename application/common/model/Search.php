<?php

namespace app\common\model;


use think\Db;

class Search extends BaseModel
{

	public static function searchNum($data = '', $limit = 0)
	{
		$display = ['id', 'name', 'gender', 'major'];
		$order = [
			'id' => 'ASC'
		];

		if ($limit == 0) {
			$result = Db::table('student_info')->field($display)
				->where('stu_num', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->select();
		} else {
			$result = Db::table('student_info')->field($display)
				->where('stu_num', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->limit(0, $limit)
				->select();
		}


		return $result;
	}

	public static function searchName($data = '', $limit = 0)
	{
		$display = ['id', 'name', 'gender', 'major'];
		$order = [
			'id' => 'ASC'
		];

		if ($limit == 0) {
			$result = Db::table('student_info')->field($display)
				->where('name', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->select();
		} else {
			$result = Db::table('student_info')->field($display)
				->where('name', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->limit(0, $limit)
				->select();
		}

		return $result;
	}

	public static function searchRoom($data = '', $limit = 0)
	{
		$display = ['id', 'name', 'gender', 'major'];
		$order = [
			'id' => 'ASC'
		];
		if ($limit == 0) {
			$result = Db::table('student_info')->field($display)
				->where('room', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->select();
		} else {
			$result = Db::table('student_info')->field($display)
				->where('room', 'like', "$data%")
				->where('gender', 'neq', '')
				->order($order)
				->limit(0, $limit)
				->select();
		}


		return $result;
	}

	public static function searchTel($data = '', $limit = 0)
	{
		$display = ['id', 'name', 'gender', 'major', 'tel'];

		if ($limit == 0) {
			$result = Db::table('student_info')->field($display)
				->where('tel', $data)
				->select();
		}

		return $result;
	}

}