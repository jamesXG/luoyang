<?php

namespace app\admin\controller;

use app\common\model\RoomInfo as RoomInfoModel;
use app\lib\enum\BuildsEnum;

class Bed extends BaseController
{
	//初始化控制器
	private $obj;

	public function _initialize()
	{
		$this->obj = model("StudentInfo");
	}

	public function index()
	{
		$data = $this->obj->getBedInfo();

		$result = $this->getBedInfo();
//		print_r($data);exit();
		return $this->fetch('', [
			'data' => $data,
			'result' => $result
		]);
	}

	public function getBedInfo()
	{
		$result['totalBed'] = $this->obj->getAllBed();
		$result['totalFreeBed'] = $this->obj->getTotalFreeBed();
		$result['totalMaleBed'] = $this->obj->getMaleTotalBed();
		$result['totalFreeMaleBed'] = $this->obj->getMaleFreeBed();
		$result['totalFemaleBed'] = $this->obj->getFemaleTotalBed();
		$result['totalFreeFemaleBed'] = $this->obj->getFemaleFreeBed();

		return $result;
	}

	public function details($room = '')
	{
		$data = $this->obj->getCorrBedInfo($room);
		$data = $this->arrReplace($data);
//		print_r($data);exit();


		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function freeRoom($room = '')
	{
		$room = str_replace('-', BuildsEnum::DATAUNIT, $room);

		$data = RoomInfoModel::getFreeBedRoom($room);

		return $this->fetch('',[
			'data' => $data
		]);
	}
}