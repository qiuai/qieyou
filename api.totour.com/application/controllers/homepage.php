<?php

class Homepage extends MY_Controller {

   /**
	* 首页
	*/
	public function index() 
	{
		$data = array();
		$home = $this->model->home_config();
		$citys = array(
			array(
				'name' => '丽江',
				'city' => '530700'
			),
			array(
				'name' => '大理',
				'city' => '532900'
			)
		);
		$data = array(
			'groups' => isset($home['groups'])?$home['groups']:(object)array(),
			'products' => isset($home['products'])?$home['products']:(object)array(),
			'jianren' => isset($home['jianren'])?$home['jianren']:(object)array(),
			'citys' => $citys	
		);
		response_json('1',$data);
    }
}