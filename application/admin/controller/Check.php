<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/26
 * Time: 12:22
 */

namespace app\admin\controller;

use app\common\model\StudentInfo as StudentInfoModel;
use app\admin\validate\GradeValidate;

class Check extends BaseController
{
	public function index()
	{
		$data = StudentInfoModel::checkInfo();

		return $this->fetch('', [
			'data' => $data
		]);
	}

	public function details($grade='')
	{
		(new GradeValidate())->goCheck();
		$data = StudentInfoModel::infoByGrade($grade);

		return $this->fetch('',[
			'data' => $data
		]);
	}
}