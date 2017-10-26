<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/9
 * Time: 22:57
 */

namespace app\admin\controller;

use app\admin\validate\GalleryMustBeRange;
use app\common\model\StudentInfo as StudentInfoModel;
use app\lib\enum\BuildsEnum;

class Graduate extends BaseController
{
	public function index()
	{
		$data = StudentInfoModel::getFloorGraduateNum();

		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function details($room)
	{
		(new GalleryMustBeRange())->goCheck();

		$data = StudentInfoModel::getGraduateStuInfo($room);

		return $this->fetch('', [
			'data' => $data,
			'room' => $room
		]);

	}

	public function graduateAjax()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}

		$result = input('post.');
		$data = StudentInfoModel::getGraduateStuInfo($result['room'],($result['start']-1)*BuildsEnum::DATANUM);
		if(!$data){
			return show(0,'error',null);
		}
		return show(1,'success',$data);
	}
}