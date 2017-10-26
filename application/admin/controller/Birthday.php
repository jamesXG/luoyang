<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/25
 * Time: 21:16
 */

namespace app\admin\controller;

use app\common\model\StudentInfo as StudentInfoModel;

class Birthday extends BaseController
{
	public function index()
	{
		$data = StudentInfoModel::getBirthdayInfo();

		return $this->fetch('',[
			'data' => $data
		]);
	}
}