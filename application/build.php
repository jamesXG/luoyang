<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 其他更多的模块定义
	'admin' => [
		'__dir__' => ['controller','view'],
		'controller' => ['user'],
	],
	'student' => [
		'__dir__' => ['controller','view'],
		'controller' => ['user'],
	],
	'maintain' => [
	'__dir__' => ['controller','view'],
	'controller' => ['user'],
	],
	'common' => [
		'__dir__' => ['controller','model','validate'],
	],
];
