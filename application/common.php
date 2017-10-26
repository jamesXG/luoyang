<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param $a
 * @param $b
 * @return array
 * 合并两个二维数组，在使用时如果不生效，建议查看下键名是否重复
 */
function array_group($a, $b)
{
	$arr = [];
	foreach ($a as $k => $r) {
		foreach ($r as $k1 => $r1) {
			$arr[$k][$k1] = $r1;
		}
	}
	foreach ($b as $k => $r) {
		foreach ($r as $k1 => $r1) {
			$arr[$k][$k1] = $r1;
		}
	}
	return $arr;
}

// 三维数组转化为二维数组
function array_convert($arr)
{
	$newArr = array();
	foreach ($arr as $key => $val) {
		foreach ($val as $k => $v) {
			$newArr[] = $v;
		}
	}

	return $newArr;
}

// 微信获取token相关
function http_curl($url, &$data = null)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	if (!empty($data)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$file_contents = curl_exec($ch);
	$data = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $file_contents;
}

//  楼座全部信息
function builds()
{
	$male_building = ['安园17', '安园24', '安园25', '李园31', '李园32', '李园33', '枣园11', '枣园12', '枣园13', '枣园14', '枣园15', '枣园16'];
	//女
	$female_building = ['安园22', '安园16', '安园18', '安园23', '桂园11', '桂园12', '桂园13', '桂园21', '桂园22', '桂园23', '李园11', '李园12', '李园13', '李园14',
		'李园21', '李园22', '李园23', '李园24', '榴园11', '榴园12', '榴园13', '榴园14', '桃园11', '桃园12', '桃园13', '桃园14', '桃园21', '桃园22', '桃园23', '桃园24',
		'桃园25', '桃园31', '桃园32', '桃园33', '桃园34', '桃园35'];

	$builds = array_merge($female_building, $male_building);


	return $builds;
}

//民族数组
function nation()
{
	$data = ["汉族", "蒙古族", "回族", "藏族", "维吾尔族", "苗族", "彝族", "壮族", "布依族", "朝鲜族", "满族", "侗族", "瑶族", "白族", "土家族",
		"哈尼族", "哈萨克族", "傣族", "黎族", "傈僳族", "佤族", "畲族", "高山族", "拉祜族", "水族", "东乡族", "纳西族", "景颇族", "柯尔克孜族",
		"土族", "达斡尔族", "仫佬族", "羌族", "布朗族", "撒拉族", "毛南族", "仡佬族", "锡伯族", "阿昌族", "普米族", "塔吉克族", "怒族", "乌孜别克族",
		"俄罗斯族", "鄂温克族", "德昂族", "保安族", "裕固族", "京族", "塔塔尔族", "独龙族", "鄂伦春族", "赫哲族", "门巴族", "珞巴族", "基诺族"];

	return $data;
}

/**
 * @param $status 0|1 状态吗
 * @param string $message success|error 状态信息
 * @param array $data 获取的信息
 * @return array  用来将返回Ajax状态
 */
function show($status, $message = '', $data = [])
{
	return [
		'status' => intval($status),
		'message' => $message,
		'data' => $data
	];
}

function is_array_null($arr)
{
	for ($i = 0, $len = count($arr); $i < $len; $i++) {
		$result = [];
		$result_2 = [];
		foreach ($arr as $k => $v) {
			$result[] = $v['info'];
			$result_2[] = $v['lists'];
		}
	}
	foreach ($result as $k => $v) {
		$p = 0;
		if ($v == null) {
			$p = 1;
		} else {
			continue;
		}

	}
	foreach ($result_2 as $k => $v) {
		$d = 0;
		if ($v == null) {
			$d = 1;
		} else {
			continue;
		}

	}
	$sum = $p + $d;
	if ($sum == 2) {
		return true;
	} else {
		return false;
	}
}

//检测在调宿过程中是否房间为空
function is_room_null($data)
{
	foreach ($data as $k => $v) {
		if (empty($data)) {
			return true;
		}
		foreach ($v as $key => $value) {
			if ($value == null) {
				return true;
			}
		}

		return false;

	}
}

function array_conversion($arr)
{
	$data = [];
	foreach ($arr as $value) {
		$data = $value;
	}

	return $data;
}

//判断哪年为毕业年
function judgeGraduate()
{
	$outYear = intval(date('Y') . '08');
	$nowYear = intval(date('Ym'));
	$year = intval(date('Y'));
	if ($nowYear > $outYear) {
		return $year + 1;
	} else {
		return $year;
	}

}