<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/10/14
 * Time: 21:41
 */

namespace app\admin\service;


use app\lib\enum\BuildsEnum;
use app\lib\exception\TokenException;
use think\Cache;

class JdkToken extends Token
{
	/**
	 * @return array
	 * @return 生成调用微信接口的API凭据
	 */
	public function getSignPackage()
	{
		$jsApiTicket = $this->getJsApiTicket();

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$timestamp = $_SERVER['REQUEST_TIME'];
		$nonceStr = $this->createNonceStr();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsApiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);

		$signPackage = array(
			"appId" => $this->AppID,
			"nonceStr" => $nonceStr,
			"timestamp" => $timestamp,
			"url" => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}

	/**
	 * @param int $length 字符串的长度
	 * @return string
	 * @return 生成随机字符串
	 */
	private function createNonceStr($length = 16)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	/**
	 * @return mixed缓存、生成所需的凭据
	 */
	private function getJsApiTicket()
	{
		$data = $this->getCacheByName(config('wx.ticketN'));

		if ($data['time'] + BuildsEnum::TIME < $_SERVER['REQUEST_TIME'] || !Cache::has(config('wx.ticketN'))) {
			$accessToken = $this->getAccessToken();
			$url = sprintf(config('wx.sdk_url'), $accessToken);
			$res = json_decode(http_curl($url), true);
			if (empty($res)) {
				throw new TokenException();
			}
			$cacheValue = $this->prepareCacheValue($res, config('wx.ticketN'));
			$ticket = $this->saveToCache($cacheValue, config('wx.ticketN'));
		} else {
			$ticket = $data['ticket'];
		}

		return $ticket;
	}
}