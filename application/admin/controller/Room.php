<?php

namespace app\admin\controller;


use app\admin\validate\GalleryMustBeRange;
use app\admin\validate\ListsRoom;
use app\lib\enum\BuildsEnum;
use app\lib\exception\PostException;

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

		return $this->fetch('', [
			'data' => $data
		]);
	}

	protected function getRoomInfo()
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
		(new GalleryMustBeRange())->goCheck();  //验证参数

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
		(new GalleryMustBeRange())->goCheck();

		$data = $this->obj->getUnavailableLists($room);

		return $this->fetch('', [
			'data' => $data
		]);
	}

//  信息列表
	public function lists($room, $roomCount = 0)
	{
		(new ListsRoom())->goCheck();

		$room = $this->strPlace($room, BuildsEnum::GALATE, BuildsEnum::DATAUNIT);
		$data = $this->obj->getGalleryInfoLists($room);
//		print_r($data);exit;
		$length = count($data);
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
			'length' => $length
		]);
	}

//	空房间的统计分类
	public function emptyRoom($room = '')
	{
		(new GalleryMustBeRange())->goCheck();
		$data = $this->obj->getEmptyRoomInfo($room);

		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function contentAjax()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}

		$result = input('post.');

		$data = $this->obj->getGalleryInfoLists($result['room'], $result['start']);
		if (!$data) {
			return show(0, 'error',null);
		}
		return show(1, 'success', $data);

	}

//  检测此房间是否为空
	public function isRoomEmpty()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}
		$room = input('post.')['room'];
		$data = $this->obj->isEmptyRoom($room);
		if (!$data) {
			return show(0, 'error', $data);
		}
		$bedNum = $this->isBedRange($room);

//		数组为空则说明此房间床位无人入住
		if (is_room_null($data) && $bedNum) {
			return show(1, 'success', $data);
		}

		return show(0, 'error', $data);
	}

//  检测是否在床位合理范围内
	protected function isBedRange($room)
	{
		$roomNumber = strstr($room, BuildsEnum::GALATE, true);
		$num = substr($room, -1);

		$data = $this->obj->getRoomBedNum($roomNumber);
		$bedNum = $data[1];

		return $num > $bedNum ? false : true;
	}


	/**
	 * 调宿、入住流程
	 * @param $room
	 * @param $id
	 * @return
	 * 1: 选择楼层，男生只能选择男生的楼座，女生只能选择女生的楼层，否则提示并阻止,ok
	 * 2：选完楼座后，输入房间号和床位（204-4），格式不能出错，否则提示并阻止
	 * 3：输入符合格式的房间+床位后，Ajax检测此床位是否为空，否则提示，同时入住人数不能大于房间的床位数
	 * 4：确定房间+床位为空后，点击提交，更新数据库，并更新room数据库的空床位数
	 */
	public function reviseRoom($room, $id, $gender, $stu_num = '')
	{
		$data = $this->getGalleryAttribute($gender);
		$room = $this->strPlace($room, BuildsEnum::COMPANY, BuildsEnum::DATAUNIT);
		if ($room == null) {
			$k = 1; $v = 0;
			return $this->fetch('', [
				'data' => $data,
				'k' => $k,
				'v' => $v,
				'stu_num' => $stu_num,
				'id' => $id
			]);
		} else {
			$gallery = explode(BuildsEnum::DATAUNIT, $room);

			$k = $gallery[0];
			$v = $gallery[1];

			return $this->fetch('', [
				'data' => $data,
				'k' => $k,
				'v' => $v,
				'id' => $id
			]);
		}
	}

//	更新宿舍信息
	public function save()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}

		$data = input('post.');

		$room = $data['gallery'] . BuildsEnum::DATAUNIT . $data['room'];
		$id = $data['id'];

		$result = $this->inspectKey($data, $room, $id);
		if ($result) {
			echo '操作成功';
			echo "<script>top.location.reload();</script>";
		} else {
			echo '操作失败,请联系客服！';
			echo "<script>top.location.reload();</script>";
		}
	}

	private function inspectKey($arr, $room, $id)
	{
		if (array_key_exists('stu_num', $arr)) {
//			Array ( [gallery] => 安园16 [room] => 114-1 [id] => 2908 [stu_num] => 161014037 )
			$resultS = model('StudentInfo')->getIntoRoom($room, $id);
//			截取#之前的字符串
			$resultR = $this->obj->intoSuccess(strstr($room, BuildsEnum::GALATE, true));
		} else {
//		调整前的宿舍
			$beRoom = $arr['gallery'] . BuildsEnum::DATAUNIT . $arr['beRoom'];
			$resultS = model('StudentInfo')->adjustmentRoom($room, $id);
			$resultR = $this->obj->updateEmptyBed(strstr($room, BuildsEnum::GALATE, true), strstr($beRoom, BuildsEnum::GALATE, true));
		}

		return $resultR && $resultS ? true : false;
	}

//	退宿

	/**
	 * 退宿流程
	 * 1: 点击退宿，输入退宿原因
	 * 2：点击确定后弹出再次确定的窗口，点击确认后，进行更新插入操作。
	 * 3：同时再将room_info里的指定空床位进行更新
	 * @throws PostException
	 */
	public function checkOutRoom()
	{
		if (!request()->isGet()) {
			throw new PostException();
		}
		$data = input('get.');
		$room = $data['room'];
		$id = $data['id'];
		$stu_num = $data['stu_num'];
		$beRoom = strstr($room, BuildsEnum::GALATE, true);

		return $this->fetch('', [
			'room' => $room,
			'beRoom' => $beRoom,
			'id' => $id,
			'stu_num' => $stu_num
		]);
	}

//  退宿
	public function checkOutSave()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}

		$data = $this->strPlace(input('post.'), BuildsEnum::COMPANY, BuildsEnum::DATAUNIT);
//		Array ( [room] => 安园16栋202-1 [reason] => 毕业了 [id] => 29 [stu_num] => 162144131 [beRoom] => 安园16栋202 )
		$result = model('StudentInfo')->upCheckOutRoom($data['room'], $data['id'], $data['reason'], $data['stu_num'], $data['beRoom']);

		if ($result) {
			echo '退宿成功';
			echo "<script>top.location.reload();</script>";
		} else {
			echo '退宿失败';
			echo "<script>top.location.reload();</script>";
		}
	}

	public function getIntoRoom()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}
		$data = input('post.');
		$result = model('StudentInfo')->getByStuNum($data['stu_num']);

		if (empty($result)) {
			return show(0, 'error', null);    //查无此人
		} elseif ($result['room'] != Null) {
			return show(0, 'error', $result);   //已入住，不可再次入住
		}

		$room = model('StudentInfo')->getRoomByID($data['id']);
		$result['room'] = $room['room'];
//		执行入住操作
		$res = $this->inspectKey($result,$room['room'],$result['id']);
		if($res){
			return show(1, 'success', $result);
		}else{
			return show(0,'error','入住失败');
		}

	}

}