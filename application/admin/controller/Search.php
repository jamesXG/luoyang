<?php

namespace app\admin\controller;


use app\common\model\Search as SearchModel;
use app\common\model\StudentInfo as StudentModel;
use think\Request;

class Search extends BaseController
{
	public function index()
	{
		if (!Request::instance()->isPost()) {
			$this->error('请求错误');
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
		} else {
			$result = SearchModel::searchName($data);
		}

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function stuDetail($id = 0)
	{
//		echo $id;exit;
		$result = StudentModel::getStuInfo($id);

		$result = $this->arrReplace($result);
		$result['room'] = str_replace('#', '楼', $result['room']);

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function oneLists($room = '')
	{
		$room = str_replace('楼', '#', $room);
		$room = explode('-', $room);

		$data = model('StudentInfo')->getWholeRoomInfo($room[0]);

		return $this->fetch('', [
			'data' => $data,
			'room' => $room[0]
		]);
	}

	public function reviseRoom($room, $id)
	{
		$data = builds();
		$room = str_replace('楼', '#', $room);
		$gallery = explode('#', $room);
		$k = $gallery[0];
		$v = $gallery[1];

		return $this->fetch('', [
			'data' => $data,
			'k' => $k,
			'v' => $v,
			'id' => $id
		]);
	}

//	更新宿舍信息
	public function save()
	{
		if (!request()->isPost()) {
			$this->error('请求失败');
		}

		$data = input('post.');
		$room = $data['gallery'] . '#' . $data['room'];

		$result = model('StudentInfo')->save([
			'room' => $room
		], ['id' => $data['id']]);

		if ($result) {
			echo '调宿成功';
		} else {
			echo '调宿失败,请联系客服！';
		}
	}
}