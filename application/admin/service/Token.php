<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/8
 * Time: 20:43
 */

namespace app\admin\service;


use app\lib\enum\BuildsEnum;
use app\lib\exception\CacheException;
use app\lib\exception\TokenException;
use think\Cache;

class Token
{
	protected $AppID;
	protected $Secret;
	protected $Ordinary_url;

//	构造函数 获取普通的Access Token，此处获取的token用于请求微信的拍照、相册、上传图片等JSSDK需求
	public function __construct($AppID = NULL, $Secret = NULL)
	{
		$this->AppID = config('wx.app_id');
		$this->Secret = config('wx.app_secret');
		$this->Ordinary_url = sprintf(config('wx.ordinary_url'), $this->AppID, $this->Secret);
	}

	public function getAccessToken()
	{
//		是否存在缓存文件
		if (!Cache::has(config('wx.cacheN'))) {
			return $this->grantToken();
		} else {
//			access_token是否过期
			$time = $_SERVER['REQUEST_TIME'];
			$expires_in = $this->getCacheByName(config('wx.cacheN'), 'time');
			if ($time <= $expires_in + BuildsEnum::TIME) {
				return $this->getCacheByName(config('wx.cacheN'), 'access_token');
			} else {
				return $this->grantToken();
			}
		}
	}

	protected function grantToken()
	{
		$result = http_curl($this->Ordinary_url);
		$wxResult = json_decode($result, true);
		if (empty($wxResult)) {
			throw new TokenException();
		} else {
//			准备数据，进行缓存
			$cachedValue = $this->prepareCacheValue($wxResult, config('wx.cacheN'));
			$token = $this->saveToCache($cachedValue, config('wx.cacheN'));

			return $this->getCacheByName($token, 'access_token');
		}

	}

	/**
	 * @param $wxResult 请求数据
	 * @param $config  缓存名
	 * @return mixed
	 * @return 整理缓存数据
	 */
	protected function prepareCacheValue($wxResult, $config)
	{
		$cachedValue = $wxResult;
		$cachedValue['name'] = $config;
		$cachedValue['time'] = $_SERVER['REQUEST_TIME'];

		return $cachedValue;
	}

	/**
	 * @param $cachedValue 缓存数据
	 * @param $config  缓存名，用于查询
	 * @return mixed
	 * @throws CacheException
	 * @return 缓存文件中24代表的是jssdk缓存，df代表的是access_token缓存
	 */
	protected function saveToCache($cachedValue, $config)
	{
		$key = $config;
		$value = json_encode($cachedValue);
		$expire_in = BuildsEnum::TIME;
//		写入缓存
		$result = cache($key, $value, $expire_in, $_SERVER['REQUEST_TIME']);
		if (!$result) {
			throw new CacheException();
		}
		return $key;
	}

	/**
	 * @param $name 缓存名
	 * @param string $key 缓存中的字段名，默认为空，则返回这个缓存数组
	 * @return mixed
	 */
	protected function getCacheByName($name, $key = '')
	{
		$res = cache($name);
		$result = json_decode($res, true);

		if ($key == NULL) {
			return $result;
		} else {
			return $result[$key];
		}
	}

}