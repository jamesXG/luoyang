<?php

namespace app\common\model;


use think\Db;

class Gallery extends BaseModel
{
	public function getGalleryAndCollege()
	{
		$builds = builds();
		$len = count($builds);

		for ($i = 0; $i < $len; $i++) {
			$result[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room,gender AS sex,COUNT(DISTINCT college)
												AS college FROM `student_info` WHERE room LIKE '$builds[$i]%' AND college <> '' ORDER BY gender");
		}
		$result = array_convert($result);
//      合并键值对
		foreach ($builds as $k => $v) {
			$result[$k]['room'] = $v;
		}

		return $result;
	}

}