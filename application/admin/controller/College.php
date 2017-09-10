<?php

namespace app\admin\controller;


class College extends BaseController
{
	//初始化控制器
	private $obj;

	public function _initialize()
	{
		$this->obj = model("StudentInfo");
	}

	public function index()
	{
		$data = $this->obj->getCollegeGallery();
//		print_r($data);
		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function details($college = '')
	{
		$data = $this->obj->getCollegeDetails($college);
//		计算总人数
		$stuNum = 0;
		foreach ($data as $item) {
			$stuNum += $item['stuNumber'];
		}
		return $this->fetch('', [
			'data' => $data,
			'college' => $college,
			'stuNum' => $stuNum
		]);

	}
//  获取学院名下的专业
	public function getCollege()
	{
		$college = input('post.');

		if(!$college){
			$this->error('参数不合法');
		}

		$major = $this->obj->getNormalMajor($college['college']);

		if(!$major){
			return show(0,'error');
		}
		return show(1,'success',$major);

	}
}