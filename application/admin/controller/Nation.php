<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/25
 * Time: 21:35
 */

namespace app\admin\controller;


class Nation extends BaseController
{
	public function index()
	{
		return $this->fetch();
	}
}