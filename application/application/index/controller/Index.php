<?php

namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
	public function index()
	{
		$result = $this->is_weChat();
		if ($result) {
			return $this->redirect('http://lysf.com/admin/index', 302);
		} else {
			return $this->fetch();
		}

	}

//  判断网站是否在微信中打开
	public function is_weChat()
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			return true;
		} else {
			return false;
		}

	}
}
