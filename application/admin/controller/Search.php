<?php

namespace app\admin\controller;


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
			$this->error('请求错误');
		}

		$data = input('post.');
//		搜索信息校验
		$validate = validate('Search');
		if (!$validate->scene('search')->check($data)) {
			$this->error($validate->getError());
//			throw new PostException();
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
		$result = StudentModel::getStuInfo($id);

		$result = $this->arrReplace($result);
		$result['room'] = str_replace(BuildsEnum::DATAUNIT, BuildsEnum::COMPANY, $result['room']);

		return $this->fetch('', [
			'result' => $result
		]);
	}

	public function oneLists($room = '')
	{
		$room = str_replace(BuildsEnum::COMPANY, '#', $room);
		$room = explode('-', $room);

		$data = model('StudentInfo')->getWholeRoomInfo($room[0]);

		return $this->fetch('', [
			'data' => $data,
			'room' => $room[0]
		]);
	}

	/**
	 * 调宿流程
	 * @param $room
	 * @param $id
	 * @return
	 * 1: 选择楼层，男生只能选择男生的楼座，女生只能选择女生的楼层，否则提示并阻止,ok
	 * 2：选完楼座后，输入房间号和床位（204-4），格式不能出错，否则提示并阻止
	 * 3：输入符合格式的房间+床位后，Ajax检测此床位是否为空，否则提示，入住人数不能大于房间的出床位数
	 * 4：确定房间+床位为空后，点击提交，更新数据库，并更新room数据库的空床位数
	 */
	public function reviseRoom($room, $id, $gender)
	{
		$data = $this->getGalleryAttribute($gender);
		$room = str_replace(BuildsEnum::COMPANY, BuildsEnum::DATAUNIT, $room);
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

//	更新宿舍信息
	public function save()
	{
		if (!request()->isPost()) {
			throw new PostException();
		}

		$data = input('post.');
		$room = $data['gallery'] . BuildsEnum::DATAUNIT  . $data['room'];

		$result = model('StudentInfo')->save([
			'room' => $room
		], ['id' => $data['id']]);

		if ($result) {
			echo '调宿成功';
		} else {
			echo '调宿失败,请联系客服！';
		}
	}

//  根据性别显示不同的楼座
	public function getGalleryAttribute($sex)
	{
		if ($sex == BuildsEnum::FEMALE) {
			$data = array_slice(builds(), 0, BuildsEnum::END);
		} else if ($sex == BuildsEnum::MALE) {
			$data = array_slice(builds(), BuildsEnum::END);
		} else {
			$data = builds();
		}

		return $data;
	}
}