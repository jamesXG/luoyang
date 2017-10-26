<?php

namespace app\index\controller;

use app\lib\enum\BuildsEnum;
use think\Controller;
use think\Cookie;

class Index extends Controller
{
	/**
	 * @return 系统入口重定向
	 */
	public function index()
	{
		$result = $this->is_weChat();
		$identity = $this->userCookie();
		if ($result && $identity != null) {
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

//	设置用户的身份cookie
	private function userCookie()
	{
		$identity = base64_encode('');   //对cookie进行加密
		if(!Cookie::has('identity')){
			Cookie::set('identity',$identity,BuildsEnum::TIME);
		}

		$identity = cookie('identity');

		return $identity;
	}
}
