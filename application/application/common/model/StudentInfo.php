<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/28
 * Time: 16:09
 */

namespace app\common\model;


use think\Db;
use think\Exception;

class StudentInfo extends BaseModel
{
//	总床位数
	public function getAllBed()
	{

		$result = $this->where('room', 'neq', '')->cache(true, 7200)->count();
		return $result;
	}


//	床位模块信息
	public function getBedInfo()
	{
		$builds = builds();
		$len = count($builds);
//		楼座名+床位数+空床位
		for ($i = 0; $i < $len; $i++) {
			$bed[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room, gender AS sex,COUNT(room) AS rooms FROM `student_info` 
																WHERE room LIKE '$builds[$i]%'");
			$freeBed[] = Db::query("SELECT COUNT(room) AS freerooms FROM `student_info` 
																WHERE room LIKE '$builds[$i]%' AND stu_num = '' AND name = ''");
		}
		$bed = array_convert($bed);
		$freeBed = array_convert($freeBed);
//      合并数组
		$result = array_group($bed, $freeBed);

//      合并键值对
		foreach ($builds as $k => $v) {
			$result[$k]['room'] = $v;
		}

		return $result;
	}


//	总空床位
	public function getTotalFreeBed()
	{
		$data = [
			'stu_num' => '',
			'name' => ''
		];
		$result = $this->where($data)->where('room', 'neq', '')->cache(true, 7200)->count();

		return $result;
	}

//	男生总床位
	public function getMaleTotalBed()
	{
		$data = [
			'gender' => '男'
		];
		$result = $this->where($data)->where('room', 'neq', '')->cache(true, 7200)->count();

		return $result;
	}

//	男生总空床位
	public function getMaleFreeBed()
	{
		$data = [
			'gender' => '男',
			'name' => '',
			'stu_num' => ''
		];
		$result = $this->where($data)->where('room', 'neq', '')->cache(true, 7200)->count();

		return $result;

	}

//	女生总床位
	public function getFemaleTotalBed()
	{
		$data = [
			'gender' => '女'
		];
		$result = $this->where($data)->where('room', 'neq', '')->cache(true, 7200)->count();

		return $result;
	}

//	男生总空床位
	public function getFemaleFreeBed()
	{
		$data = [
			'gender' => '女',
			'name' => '',
			'stu_num' => ''
		];
		$result = $this->where($data)->where('room', 'neq', '')->cache(true, 7200)->count();

		return $result;

	}


//  查询学生详细信息
	public static function getStuInfo($id = '')
	{
		$display = ['id', 'name', 'room', 'stu_num', 'class', 'major', 'college', 'grade', 'gender', 'degree', 'tel', 'nation', 'len_school'];
		$data = [
			'id' => $id
		];

		$result = self::field($display)->where($data)->find();

		return $result;
	}

//	学生详细信息楼座下的信息
	public function getWholeRoomInfo($room)
	{
		$result = Db::query("SELECT DISTINCT(MID(room,-1,length(room))) AS num,id,name,major FROM `student_info` WHERE room LIKE '$room%'");

		return $result;
	}


//	每个楼座的床位相关信息
	public function getCorrBedInfo($room = '')
	{
		$result = Db::query("SELECT DISTINCT(MID(room,1,length(room)-6)) AS room FROM `room_info` WHERE room LIKE '$room%'");

		$arr = array_column($result, 'room');
		$totalBed = [];
		$freeBed = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$totalBed[] = Db::query("SELECT COUNT(room) AS totalbed FROM `student_info` WHERE room LIKE '$arr[$i]%'");
			$freeBed[] = Db::query("SELECT COUNT(room) AS freebed FROM `room_info` 
																		WHERE room LIKE '$arr[$i]%' AND bed_num = empty_bed_num");
			$partFreeBed[] = Db::query("SELECT COUNT(room) AS partFreeBed FROM `room_info` 
																		WHERE room LIKE '$arr[$i]%' AND bed_num != empty_bed_num AND empty_bed_num <> 0");
		}

		$totalBed = array_convert($totalBed);
		$freeBed = array_convert($freeBed);
		$partFreeBed = array_convert($partFreeBed);

		$data = array_group($result, $totalBed);
		$data = array_group($data, $freeBed);
		$data = array_group($data, $partFreeBed);

		return $data;

	}

//	统计每个学院所占有的楼座
	public function getCollegeGallery()
	{
		$college = model('College')->getCollegeName();
		$arr = array_column($college, 'college');
		$data = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data[] = Db::query("SELECT college,COUNT(DISTINCT(MID(room,1,length(room)-10))) AS room FROM `student_info`
															WHERE college='$arr[$i]' AND room <> ''");
		}
		$data = array_convert($data);
		//print_r($data);
		return $data;
	}

//  学院楼座下的详细信息
	public function getCollegeDetails($college = '')
	{
		$gallery = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room FROM `student_info`
															WHERE college='$college' AND room <> ''");
		$arr = array_column($gallery, 'room');

		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room,COUNT(name) AS stuNumber, gender FROM `student_info`
															WHERE college='$college' AND room LIKE '$arr[$i]%' AND name <> ''");
		}

		$data = array_convert($data);

		return $data;
	}

//	楼座下的学院数详情
	public function getGalleryDetails($gallery = '')
	{
//		SELECT DISTINCT(college) FROM `student_info` WHERE room LIKE '安园22%'
		$college = Db::query("SELECT DISTINCT(college) AS college FROM `student_info` WHERE room LIKE '$gallery%' AND college <> ''");

		$arr = array_column($college, 'college');
		$data = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data[] = Db::query("SELECT college, COUNT(name) AS stuNumber, gender FROM `student_info`
															WHERE college = '$arr[$i]' AND room LIKE '$gallery%' AND name <> ''");
		}

		$data = array_convert($data);

		return $data;
	}

//   楼座与学历之间的统计
	public function getDegreeAndGallery()
	{
		$builds = builds();
		$len = count($builds);

		for ($i = 0; $i < $len; $i++) {
			$data[] = Db::query("SELECT DISTINCT(MID(room,1,length(room)-10)) AS room,gender,COUNT(DISTINCT(degree)) AS degreeCount FROM `student_info` 
											WHERE room LIKE '$builds[$i]%' AND degree <> ''");
		}

		$data = array_convert($data);

		foreach ($builds as $k => $v) {
			$data[$k]['room'] = $v;
		}
		return $data;
	}

//	楼座下的学历详情
	public function getGalleryDegreeDetails($gallery)
	{
		$degree = Db::query("SELECT DISTINCT(degree) AS degree FROM `student_info` WHERE room LIKE '$gallery%' AND name <> ''");
		$arr = array_column($degree, 'degree');
		$data = [];
		for ($i = 0, $len = count($arr); $i < $len; $i++) {
			$data [] = Db::query("SELECT DISTINCT(degree),COUNT(name) AS stuNum FROM `student_info` 
								WHERE room LIKE '$gallery%' AND name <> '' AND degree = '$arr[$i]'");
		}

		$data = array_convert($data);

		return $data;
	}

//	获取指定学院名下的专业
	public function getNormalMajor($college)
	{
		$data = Db::query("SELECT DISTINCT(major) FROM `student_info` WHERE college = '$college' AND room <> ''");

		return $data;
	}

//	不同学历分布的楼座数量
	public function getDiffDegreeCount()
	{
		$degree = model('Degree')->degreeLists();
		$degree = array_convert($degree);
		$data = [];
		for ($i = 0, $len = count($degree); $i < $len; $i++) {

			$data[] = Db::query("SELECT COUNT(DISTINCT(MID(room,1,length(room)-10))) AS room,degree FROM `student_info` WHERE degree = '$degree[$i]'");
		}

		$data = array_convert($data);

		return $data;
	}

	/**
	 * @param $room 调整后的宿舍
	 * @param $beRoom 调整前的宿舍
	 * @param id定位
	 */
	public function adjustmentRoom($room, $id)
	{

		$result = Db::query("select * from student_info where id = ?", [$id]);
		$data = array_conversion($result);
//			给$room的字段里插入学生数据
		$roomRes = Db::execute("UPDATE student_info SET stu_num = :stu_num,name = :name,nation=:nation,class=:class,major=:major
			,college=:college,grade=:grade,degree=:degree,len_school=:len_school,birthday=:birthday,tel=:tel,openid=:openid,
			header_img=:header_img WHERE room = :room", ['stu_num' => $data['stu_num'], 'name' => $data['name'], 'nation' => $data['nation'],
			'class' => $data['class'], 'major' => $data['major'], 'college' => $data['college'], 'grade' => $data['grade'], 'degree' => $data['degree'],
			'len_school' => $data['len_school'], 'birthday' => $data['birthday'], 'tel' => $data['tel'], 'openid' => $data['openid'],
			'header_img' => $data['header_img'], 'room' => $room]);
//			将$bedRoom里的数据清空
		$bedRoomRes = Db::execute("UPDATE student_info SET stu_num = '',name = '',nation='',class='',major=''
			,college='',grade='',degree='',len_school='',birthday='',tel='',openid='',
			header_img='' WHERE id = :id", ['id' => $id]);

		return $roomRes && $bedRoomRes ? true : false;

	}

	/**
	 * 退宿
	 * @param $room 宿舍号 安园23栋301-2
	 * @param $id 房间id  2904
	 * @param $reason 退宿原因
	 */
	public function upCheckOutRoom($room, $id, $reason, $stu_num, $beRoom)
	{
		Db::startTrans();   //事务开启
		try {
			Db::execute("UPDATE student_info SET room = '' WHERE id = :id", ['id' => $id]);

			Db::execute("UPDATE room_info SET empty_bed_num = empty_bed_num + 1 WHERE room = :room", ['room' => $beRoom]);

			Db::execute("INSERT INTO student_info (room) VALUES ('$room')");

			Db::execute("INSERT INTO check_room (room,stu_num,reason) VALUES ('$room','$stu_num','$reason')");

			Db::commit();  //提交事务
			return true;
		} catch (Exception $e) {
			Db::rollback();  //事务回滚
		}

	}

//	入住更新操作
	public function getIntoRoom($room, $id)
	{

		Db::startTrans();
		try {
			Db::execute("UPDATE student_info SET room = :room WHERE id = :id", ['room' => $room, 'id' => $id]);
			Db::execute("DELETE FROM student_info WHERE room = :room AND stu_num = '' AND name = ''", ['room' => $room]);

			Db::commit();
			return true;
		} catch (Exception $e) {
			Db::rollback();
		}
	}

//  通过学号获取学生部分信息
	public function getByStuNum($stu_num)
	{
		$result = Db::query("SELECT id,room,name,stu_num,major,gender FROM student_info WHERE stu_num = '$stu_num'");

		return array_conversion($result);
	}

//  通过ID获取房间名
	public function getRoomByID($id)
	{
		$result = Db::query("SELECT room FROM student_info WHERE id = '$id'");

		return array_conversion($result);
	}

}