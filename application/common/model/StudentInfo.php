<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/8/28
 * Time: 16:09
 */

namespace app\common\model;


use think\Db;

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
		$display = ['id', 'name', 'room', 'stu_num', 'class', 'major', 'college', 'grade', 'gender', 'degree', 'tel', 'nation'];
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


}