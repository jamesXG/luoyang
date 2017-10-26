<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/13
 * Time: 16:13
 */

namespace app\common\model;


class Button extends BaseModel
{
	protected $hidden = ['id'];
	public static function getHomeUrl()
	{
		$condition = [
			'name' => ['neq',''],
			'status' => ['eq','1']
		];
		$order = [
			'id' => 'ASC'
		];
		$result = self::where($condition)->order($order)->select();
		foreach ($result as $k=>$v){
			$result[$k]['url'] = config('wx.web_url').$result[$k]['url'];
			$result[$k]['img_url'] = config('wx.web_url').$result[$k]['img_url'];
		}
		$result = json_encode($result);
		return $result;
	}
}