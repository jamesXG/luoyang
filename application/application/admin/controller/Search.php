<?php

namespace app\admin\controller;


use app\common\model\RoomInfoModel;
use app\common\model\Search as SearchModel;
use app\common\model\StudentInfo as StudentModel;
use app\lib\exception\PostException;
use think\Request;
use app\lib\enum\BuildsEnum;

class Search extends BaseController
{
	public function index()
	{
		if (!Request::instance()->isPost()) {
			throw new PostException();
		}

		$data = input('post.');
//		搜索信息校验
		$validate = validate('Search');
		if (!$validate->scene('search')->check($data)) {
			$this->error($validate->getError());
		}
//      进行数据查询
		$data = $data['data'];

		if (preg_match_all('/\b[0-9]+\b/', $data)) {
			$result = SearchModel::searchNum($data);
		} else if (preg_match_all('/^[\x7f-\xff]+$/', $data)) {
			$result = SearchModel::searchName($data);
		}

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function stuDetail($id = 0)
	{
		$result = StudentModel::getStuInfo($id);

		$result = $this->arrReplace($result);
//		print_r($result);exit;
		$result['room']  = $this->strPlace($result['room'] ,BuildsEnum::DATAUNIT,BuildsEnum::COMPANY);

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function oneLists($room = '')
	{
		$room = $this->strPlace($room,BuildsEnum::COMPANY,BuildsEnum::DATAUNIT);
		$room = explode('-', $room);

		$data = model('StudentInfo')->getWholeRoomInfo($room[0]);

		return $this->fetch('', [
			'data' => $data,
			'room' => $room[0]
		]);
	}

}