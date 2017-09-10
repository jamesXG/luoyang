<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/8
 * Time: 0:05
 */

namespace app\admin\controller;

use app\common\model\StudentInfo as StudentInfoModel;

class Student extends BaseController
{
	public function edit($id = 0)
	{
		$data = StudentInfoModel::getStuInfo($id);
		$degree = model('Degree')->degreeLists();
		$college = model('College')->getCollegeName();

		$nation = nation();

		return $this->fetch('',[
			'data' => $data,
			'degree' => $degree,
			'college' => $college,
			'nation' => $nation
		]);
	}

	public function save()
	{
		if (!request()->isPost()) {
			$this->error('请求失败');
		}

		$data = input('post.');

		$res = model('StudentInfo')->save([
			'class'=>$data['class'],
			'tel' => $data['tel'],
			'college' => $data['college'],
			'major' => $data['major'],
			'degree' => $data['degree'],
			'nation' => $data['nation']
		],['id' => $data['id']]);

		if($res){
			$this->success('编辑成功','search/studetail?id='.$data['id']);
		}else{
			$this->error('编辑失败');
		}
	}
}