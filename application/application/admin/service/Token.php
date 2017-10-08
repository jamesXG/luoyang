<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/8
 * Time: 20:43
 */

namespace app\admin\service\token;


class Token
{
	protected $wxAppID;
	protected $wxSecret;
	protected $redirectUrl;
	protected $codeUrl;
	protected $scope;

	function __construct()
	{
		$this->wxAppID = config('wx.app_id');
		$this->wxSecret = config('wx.app_secret');
		$this->redirectUrl = config('wx.REDIRECT_URI');      //回调地址
		$this->scope = config('wx.scope');
		$this->codeUrl = sprintf(config('wx.Code_url'),$this->wxAppID,urlencode($this->redirectUrl),$this->scope);
	}

	public function getCode()
	{
		$code = input('get.');
		if(empty($code)){
			$this->redirect($this->codeUrl,'302');
		}
//		通过code换取access_token
		$obj = $this->getAccess($code);
//      拉取两个必须的参数
		$access_token = $obj->access_token;
		$openid = $obj->openid;

//		拉去头像信息
		$userInfo = $this->getUserInfo($access_token,$openid);
	}

	public function getAccess($code)
	{
		$accessUrl = sprintf(config('wx.Access_url'),$this->wxAppID,$this->wxSecret,$code);
		$resultJson = http_curl($accessUrl);
		$obj = json_decode($resultJson);

		return $obj;
	}

	public function getUserInfo($access,$openid)
	{
		$getInfoUrl = sprintf(config('wx.Info_url'),$access,$openid);
		$data = http_curl($getInfoUrl);
		$data = json_decode($data,true);

		return $data;
	}
}