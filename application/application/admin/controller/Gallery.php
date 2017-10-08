<?php

namespace app\admin\controller;


class Gallery extends BaseController
{
	public function index()
	{
		$count = model('Gallery')->getGalleryAndCollege();

//		print_r($count);exit();
		return $this->fetch('', [
			'count' => $count
		]);
	}

	public function details($gallery = '')
	{
		$data = model('StudentInfo')->getGalleryDetails($gallery);

//		计算总人数
		$stuNum = 0;
		foreach ($data as $item) {
			$stuNum += $item['stuNumber'];
		}

		return $this->fetch('', [
			'data' => $data,
			'gallery' => $gallery,
			'stuNum' => $stuNum
		]);
	}
}