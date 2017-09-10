<?php

namespace app\admin\controller;

use app\common\model\Degree as DegreeModel;

class Index extends BaseController
{
	public function index()
	{
		$data = $this->getModelData();

//      输出变量
		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function getModelData()
	{
//		查询模板
		$collegeRes = model('College')->getAllCollege();
		$roomRes = model('RoomInfo')->getAllRoom();
		$bedRes = model('StudentInfo')->getAllBed();
		$galleryRes = count(builds());
		$degree = DegreeModel::getDegreeNum();
//      计算个数
		$data['college'] = $collegeRes;
		$data['room'] = $roomRes;
		$data['bed'] = $bedRes;
		$data['gallery'] = $galleryRes;
		$data['degree'] = $degree;

		return $data;
	}

}
