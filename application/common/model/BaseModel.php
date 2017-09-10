<?php

namespace app\common\model;


use think\Model;

class BaseModel extends Model
{
//	获取分页的相关参数
	public function getNum($num = 8)
	{
//		$pageSize = 5;
		$now = $num;

		return $now;
	}
}