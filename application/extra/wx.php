<?php

return [
	'app_id'=>'wx5046c5dfcdd0a425',
	'app_secret' => '77816249d579f3281a94b017ed20138b',
	'REDIRECT_URI' => '',
	'scope' => 'snsapi_base',
	'Code_url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s
					&response_type=code&scope=%s&state=123#wechat_redirect',
	'Access_url' => 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',
	'Info_url' => 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN'
];