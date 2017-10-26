<?php

namespace app\common\model;


use think\Db;

class Gallery extends BaseModel
{
	public function getGalleryAndCollege()
	{
		$builds = $this->identitySetModel();
		$len = count($builds);

		for ($i = 0; $i < $len; $i++) {
			$result[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room,gender AS sex,COUNT(DISTINCT college)
												AS college FROM `student_info` WHERE room LIKE '$builds[$i]%' AND college <> '' ORDER BY gender");
		}
		$result = $this->mergeArr($result,$builds,0);

		return $result;
	}

}