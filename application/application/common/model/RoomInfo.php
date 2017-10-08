<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/30
 * Time: 14:52
 */

namespace app\common\model;

use think\Db;

class RoomInfo extends BaseModel
{
	public function getAllRoom()
	{
		$result = $this->cache(true, 7200)->count();

		return $result;
	}

	//  房间模块信息
	public function getRoomInfo()
	{
		$builds = builds();
		$len = count($builds);
//      查询楼座名+对应楼座的房间数+空房间+不可用房间数
		for ($i = 0; $i < $len; $i++) {
			//总数
			$room[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-8)) AS room,gender,COUNT(room) AS totalroom FROM `room_info` 
													WHERE room LIKE '$builds[$i]%'");
			//空房间
			$emptyRoom[] = Db::query("SELECT COUNT(room) AS freeroom FROM `room_info` 
													WHERE bed_num = empty_bed_num AND room LIKE '$builds[$i]%'");
			//不可用房间
			$unUseRoom[] = Db::query("SELECT COUNT(room) AS unavaroom FROM `room_info` WHERE status = 0 AND room LIKE '$builds[$i]%'");
		}
		$room = array_convert($room);
		$emptyRoom = array_convert($emptyRoom);
		$unUseRoom = array_convert($unUseRoom);

		$result = array_group($room, $emptyRoom);
		$result = array_group($result, $unUseRoom);
//      合并键值对
		foreach ($builds as $k => $v) {
			$result[$k]['room'] = $v;
		}

		return $result;
	}

//  总空房间数
	public function getTotalFreeRoom()
	{
		$result = Db::query("SELECT COUNT(room) AS room FROM `room_info` WHERE bed_num = empty_bed_num");

		return $result;
	}

//	女生总房间数
	public function getFemaleRoom()
	{
		$result = Db::query("SELECT COUNT(room) AS room FROM `room_info` 
												WHERE gender = '女' ");
		return $result;
	}

//	男生总房间数
	public function getMaleRoom()
	{
		$result = Db::query("SELECT COUNT(room) AS room FROM `room_info` 
												WHERE gender = '男' ");
		return $result;
	}

//	女生总空房间数
	public function getFemaleFreeRoom()
	{
		$result = Db::query("SELECT COUNT(room) AS room FROM `room_info` 
												WHERE gender = '女' AND bed_num = empty_bed_num");
		return $result;
	}

//	男生总空房间数
	public function getMaleFreeRoom()
	{
		$result = Db::query("SELECT COUNT(room) AS room FROM `room_info` 
												WHERE gender = '男' AND bed_num = empty_bed_num");
		return $result;
	}

//	总不可用房间
	public function getUnmadeRoom()
	{
		$result = Db::query("SELECT COUNT(*) AS room FROM `room_info` WHERE status = 0");

		return $result;
	}

//	男生不可用房间数
	public function getMaleUnMadeRoom()
	{
		$result = Db::query("SELECT COUNT(*) AS room FROM `room_info` WHERE gender = '男' AND status = 0");

		return $result;
	}

//	女生不可用房间数
	public function getFemaleUnMadeRoom()
	{
		$result = Db::query("SELECT COUNT(*) AS room FROM `room_info` WHERE gender = '女' AND status = 0");

		return $result;
	}

//	每个楼座的房间相关信息
	public function getCorrGalleryInfo($room = '')
	{

		$result = Db::query("SELECT DISTINCT(MID(room,1,length(room)-6)) AS room FROM `room_info` WHERE room LIKE '$room%'");

		$arr = array_column($result, 'room');
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$totalRoom[] = Db::query("SELECT COUNT(room) AS totalrooms FROM `room_info`
																WHERE room LIKE '$arr[$i]%'");
			$freeRoom[] = Db::query("SELECT COUNT(room) AS freerooms FROM `room_info`
																WHERE room LIKE '$arr[$i]%' AND bed_num = empty_bed_num ");
		}

		$totalRoom = array_convert($totalRoom);
		$freeRoom = array_convert($freeRoom);

		$data = array_group($result, $totalRoom);
		$data = array_group($data, $freeRoom);

		return $data;
	}

//  每个楼层下的详情信息
	public function getGalleryInfoLists($room, $start = 0)
	{
		$room = Db::table('room_info')->where('room', 'like', $room . '%')->page($start, 8)->select();

		$arr = array_column($room, 'room');

		$college = [];
		$data = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$college[]['info'] = Db::query("SELECT DISTINCT(MID(room,6,length(room)-11)) AS room,college FROM `student_info` WHERE room LIKE '$arr[$i]%'");
			$data[]['lists'] = Db::query("SELECT DISTINCT(MID(room,10,length(room)-1)) AS room,id,name,major,grade,college,openid FROM `student_info` WHERE room LIKE '$arr[$i]%'");
		}

		$data = array_group($college, $data);

		return $data;
	}

//  获取楼层下的相关统计
	public function getGalleryInfoCount($room)
	{
		$room = Db::query("SELECT room  FROM `room_info` WHERE room LIKE '$room%'");
//		$room = Db::table('room_info')->where('room','like',$room.'%')->page('1,10')->select();

		$arr = array_column($room, 'room');
		$data = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data[] = Db::query("SELECT COUNT(name) AS freeRoom 
												FROM `student_info` WHERE room LIKE '$arr[$i]%' AND stu_num <> '' AND name <> ''");
		}
		$data = array_convert($data);

		return $data;
	}

//	每个楼座下的不可用房间列表
	public function getUnavailableLists($room)
	{
		$room = Db::query("SELECT room  FROM `room_info` WHERE room LIKE '$room%'");

		$arr = array_column($room, 'room');
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data[] = Db::query("SELECT room,reason 
												FROM `room_info` WHERE room LIKE '$arr[$i]%' AND status = 0");
		}
		$data = array_convert($data);

		return $data;
	}

//	空床位房间信息
	public static function getFreeBedRoom($room)
	{
		$data = Db::query("SELECT room,gender,bed_num FROM `room_info` WHERE room LIKE '$room%' AND bed_num = empty_bed_num");

		return $data;
	}

//	空房间信息
	public function getEmptyRoomInfo($room)
	{
		$data = Db::query("SELECT room,bed_num FROM `room_info` WHERE room LIKE '$room%' AND bed_num = empty_bed_num AND status = 1");

		return $data;
	}

	public function isEmptyRoom($room)
	{
//		SELECT stu_num,name FROM student_info WHERE room = '安园16#114-1'
		$data = Db::query("SELECT stu_num,name FROM student_info WHERE room = '$room'");

		return $data;
	}

//	获取房间的床位数
	public function getRoomBedNum($room)
	{

		$data = Db::query("SELECT room,bed_num FROM room_info WHERE room LIKE '$room'");

		$data = array_convert($data);

		return $data;
	}

	/**
	 * 更新空床位数安园23栋301-4
	 * @param $room 调整后的宿舍
	 * @param $beRoom 调整前的宿舍
	 */
	public function updateEmptyBed($room, $beRoom)
	{
//		调整后的宿舍空床位数减一
		$resultR = Db::execute("UPDATE room_info SET empty_bed_num = empty_bed_num - 1 WHERE room=:room AND status = 1", ['room' => $room]);
//		调整前的宿舍空床位数加一
		$resultB = Db::execute("UPDATE room_info SET empty_bed_num = empty_bed_num + 1 WHERE room=:room AND status = 1", ['room' => $beRoom]);

		return $resultR && $resultB ? true : false;
	}

//	入住成功后更新空床位数
	public function intoSuccess($room)
	{
		$result = Db::execute("UPDATE room_info SET empty_bed_num = empty_bed_num - 1 WHERE room=:room AND status = 1", ['room' => $room]);
		return $result ? true : false;
	}
}