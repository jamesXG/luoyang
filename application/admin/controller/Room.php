<?php

namespace app\admin\controller;


class Room extends BaseController
{
	//初始化控制器
	private $obj;

	public function _initialize()
	{
		$this->obj = model("RoomInfo");
	}

	public function index()
	{
		$data = $this->getRoomInfo();
//		print_r($data);exit();
		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function getRoomInfo()
	{

//		循环信息
		$data['roomInfo'] = $this->obj->getRoomInfo();
//		总房间数
		$data['roomTotal'] = $this->obj->getAllRoom();
//		总空房间数
		$data['freeTotal'] = $this->obj->getTotalFreeRoom()[0]['room'];
//		总不可用
		$data['UnMadeTotal'] = $this->obj->getUnmadeRoom()[0]['room'];
//		女生总房间数
		$data['femaleTotal'] = $this->obj->getFemaleRoom()[0]['room'];
//		男生总房间数
		$data['maleTotal'] = $this->obj->getMaleRoom()[0]['room'];
//		女生总空房间数
		$data['femaleFreeTotal'] = $this->obj->getFemaleFreeRoom()[0]['room'];
//		男生总空房间数
		$data['maleFreeTotal'] = $this->obj->getMaleFreeRoom()[0]['room'];
//		女生总不可用房间
		$data['femaleUnMadeTotal'] = $this->obj->getFemaleUnMadeRoom()[0]['room'];
//		男生总不可用房间
		$data['maleUnMadeTotal'] = $this->obj->getMaleUnMadeRoom()[0]['room'];

		return $data;
	}

//  楼座的详细数据
	public function details($room = '')
	{

		$data = $this->obj->getCorrGalleryInfo($room);
//      将字段中的#替换成-
		$data = $this->arrReplace($data);
//		print_r($data);exit();

		return $this->fetch('', [
			'data' => $data
		]);
	}

//	不可用房间详细信息列表
	public function unavailableRoom($room = '')
	{
		$data = $this->obj->getUnavailableLists($room);

		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function lists($room = '', $roomCount = 0)
	{
		$room = str_replace('-', '#', $room);

		$data = $this->obj->getGalleryInfoLists($room);
//		print_r($data);exit();
		$length = count($data);
//		echo $length;exit();
//      表头部分相关数据
		$freeRoom = $this->obj->getGalleryInfoCount($room);
//		入住人数
		$count = 0;
		foreach ($freeRoom as $items) {
			$count += $items['freeRoom'];
		}
		return $this->fetch('', [
			'data' => $data,
			'room' => $room,
			'roomCount' => $roomCount,
			'count' => $count,
			'length'=>$length
		]);
	}

//	空房间的统计分类
	public function emptyRoom($room = '')
	{
		$data = $this->obj->getEmptyRoomInfo($room);

		return $this->fetch('',[
			'data' => $data
		]);
	}

	public function contentAjax()
	{
		$result = input('post.');
//		print_r($result);

		$data = $this->obj->getGalleryInfoLists($result['room'],$result['start']);
		if(!$data){
			return show(0,'error');
		}
		return show(1,'success',$data);

	}

}