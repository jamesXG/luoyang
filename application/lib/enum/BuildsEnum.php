<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/20
 * Time: 12:47
 */

namespace app\lib\enum;


class BuildsEnum
{
	const END = 36; //此常量代表楼座数组中的女生和男生的分割，36之前是女生宿舍楼，之后是男生宿舍楼
	const COMPANY = '栋';  //get参数传递时代替#
	const DATAUNIT = '#';  //最初的楼座单位
	const MALE = '男';
	const FEMALE = '女';
	const GALATE = '-';
	const GRAMONTH = '07';
	const TIME = 7000;
	const DATANUM = 20;
}