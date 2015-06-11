<?php
/*
 * --------------------------------------------------------------------
 * 商家销售统计
 * --------------------------------------------------------------------
 *
 */
class Manage extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->check_token();
	}
	
	// 销售统计
	public function index(){
		// 昨天
		$data['yestoday'] = $this->getYestodayData();
		
		// 七天
		$data['sevenday'] = $this->getSevenData();
		
		// 30天
		$data['month'] = $this->getMonthData();
		
		response_data($data);
	}

	// 昨天数据统计
	public function getYestodayData(){
		response_data($this->model->getYestodayData($this->token['user_id']));
	}

	// 七天数据统计
	public function getSevenData(){
		response_data($this->model->getSevenData($this->token['user_id']));
	}

	// 30天数据统计
	public function getMonthData(){
		response_data($this->model->getMonthData($this->token['user_id']));
	}

	// 获取曲线数据
	public function getLine(){
		response_data($this->model->getAllData($this->token['user_id']));
	}

}
/* End of file Manage.php */