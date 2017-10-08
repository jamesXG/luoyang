<?php
namespace app\admin\controller;


class User extends BaseController
{
	public function user()
	{
		return $this->fetch();
	}
}