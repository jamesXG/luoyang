<?php
/**
 * Created by PhpStorm.
 * User: james
 * Date: 2017/9/6
 * Time: 17:47
 */

namespace app\admin\controller;


class Degree extends BaseController
{
	public function index()
	{
		$data = model('StudentInfo')->getDegreeAndGallery();

		$info = model('StudentInfo')->getDiffDegreeCount();

		return $this->fetch('', [
			'data' => $data,
			'info' => $info
		]);
	}

	public function details($gallery)
	{
		$data = model('StudentInfo')->getGalleryDegreeDetails($gallery);


		return $this->fetch('',[
			'data' => $data,
			'gallery' => $gallery
		]);
	}
}