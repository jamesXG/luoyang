<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/14
 * Time: 21:49
 */

namespace app\admin\controller;


class Notice extends BaseController
{
	public function index()
	{
		return $this->fetch();
	}
}