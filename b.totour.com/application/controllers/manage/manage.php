<?php
/*
 * --------------------------------------------------------------------
 * 商家销售统计
 * --------------------------------------------------------------------
 *
 */
class Manage extends WEBbase {
	
	private $inn_id;
	public $managerMemcache;
	
	public function __construct() {
		parent::__construct();
		$this->_LoadModel('manager');
		$this->inn_id = $this->get_inn_id(TRUE);
		
		$this->load_manager_memcache();
	}
	
	public function load_manager_memcache(){
		if($this->managerMemcache)
			return ;
		$this->managerMemcache = new Memcache;
		$this->managerMemcache->connect($this->config->item('B_tokenMemcache_ip'),$this->config->item('B_tokenMemcache_port'));
	}
	
	// 管理页面
	public function index(){
		// 更新订单数据
		$this->updateToday();
	}

	// 管理页面
	public function product(){
		
	}
	
	// 销售统计
	public function getManagerData(){
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
		response_data($this->manager_model->getYestodayData($this->inn_id));
	}

	// 七天数据统计
	public function getSevenData(){
		response_data($this->manager_model->getSevenData($this->inn_id));
	}

	// 30天数据统计
	public function getMonthData(){
		response_data($this->manager_model->getMonthData($this->inn_id));
	}

	// 获取曲线数据
	public function getLine(){
		response_data($this->manager_model->getAllData($this->inn_id));
	}
	
	// 更新今天数据
	public function updateToday(){
		// 判断订单是否更新
		if($this->manager_model->isUpdateToday($this->inn_id,$this->managerMemcache->get('B_manager_today_count_'.$this->inn_id))){
			$this->managerMemcache->set('B_manager_today_count_'.$this->inn_id, $this->manager_model->updateToday($this->inn_id));
		}
		response_data($this->managerMemcache->get('B_manager_today_count'.$this->inn_id));
	}
	
	// 定时转移数据至历史统计表
	public function updateHistory(){
		response_data($this->manager_model->updateHistory());
	}

}
/* End of file Manage.php */