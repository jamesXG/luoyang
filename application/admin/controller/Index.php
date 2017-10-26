<?php

namespace app\admin\controller;

use app\common\model\Button as ButtonModel;
use app\common\model\Degree as DegreeModel;
use app\common\model\StudentInfo as StudentInfoModel;
use app\lib\exception\PostException;
use think\Request;

class Index extends BaseController
{
	public function index()
	{
		$data = $this->getModelData();
		$url = json_decode(ButtonModel::getHomeUrl(),true);
		$data = array_group($url,$data);

		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function getModelData()
	{
//		查询模板
		$collegeRes = model('College')->getAllCollege();        //学院
		$roomRes = model('RoomInfo')->getAllRoom();         //房间
		$bedRes = StudentInfoModel::getAllBed();                //床位
		$checkIn = StudentInfoModel::getCheckInStuNum();        //入住人数
//		$galleryRes = StudentInfoModel::getGalleryNum();        //楼座
		$degree = DegreeModel::getDegreeNum();                  //学历
		$graduates = StudentInfoModel::getGraduateStuNum();     //毕业生
		$birthdays = StudentInfoModel::birthdayNumberToday();   //今日生日
		$nations = StudentInfoModel::nationSortNumber();        //民族
//      计算个数
		$data[0]['num'] = $roomRes;
		$data[1]['num'] = $bedRes;
		$data[3]['num'] = $collegeRes;
		$data[2]['num'] = $checkIn;
		$data[4]['num'] = $degree;
		$data[5]['num'] = $graduates;
		$data[6]['num'] = $birthdays;
		$data[7]['num'] = $nations;

		return $data;
	}

	public function contentAjaxSearch()
	{
		if(!request()->isPost()){
			throw new PostException();
		}
		$data = Request::instance()->post();
		$data = $data['str'];

		$result = controller('Search')->reAjaxSearch($data);

		if(!$result){
			return show(0,'error',null);
		} else {
			return show(1,'success',$result);
		}
	}
}
