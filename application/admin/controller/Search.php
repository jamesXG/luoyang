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
		$result = $this->classData($data);
		if(count($result) == 1){
//			页面重定向
			$url = 'http://'.$_SERVER['HTTP_HOST'].'/admin/search/studetail.html?id='.$result[0]["id"];
			$this->redirect($url,302);
			exit;
		}
		return $this->fetch('', [
			'result' => $result
		]);
	}

	private function classData($data, $limit = 0)
	{
		$result = [];
		if ($limit == 0) {
			if (preg_match_all('/^\d{9}$/', $data)) {
				$result = SearchModel::searchNum($data);
			} else if (preg_match_all('/^[\x{4e00}-\x{9fa5}]+$/u', $data)) {
				$result = SearchModel::searchName($data);
			} else if (strstr($data, BuildsEnum::DATAUNIT)) {
				$result = SearchModel::searchRoom($data);
			}else if(preg_match_all('/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/',$data)){
				$result = SearchModel::searchTel($data);
			}
		} else {
			if (preg_match_all('/^\d*$/', $data)) {
				$result = SearchModel::searchNum($data, $limit);
			} else if (preg_match_all('/^[\x{4e00}-\x{9fa5}]+$/u', $data)) {
				$result = SearchModel::searchName($data, $limit);
			} else if (strstr($data, BuildsEnum::DATAUNIT)) {
				$result = SearchModel::searchRoom($data, $limit);
			}
		}

		return $result;
	}

	public function stuDetail($id = 0)
	{
		$result = StudentModel::getStuInfo($id);

		$result = $this->arrReplace($result);
		$result['room'] = $this->strPlace($result['room'], BuildsEnum::DATAUNIT, BuildsEnum::COMPANY);

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function oneLists($room = '')
	{
		$room = $this->strPlace($room, BuildsEnum::COMPANY, BuildsEnum::DATAUNIT);
		$room = explode('-', $room);

		$data = model('StudentInfo')->getWholeRoomInfo($room[0]);

		return $this->fetch('', [
			'data' => $data,
			'room' => $room[0]
		]);
	}


//  接受来自首页的请求查询信息
	public function reAjaxSearch($data)
	{
		$result = $this->classData($data, 5);

		return $result;
	}
}