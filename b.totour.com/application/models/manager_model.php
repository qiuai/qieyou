<?php

class manager_model extends MY_Model {

	// 获取昨天数据
	public function getYestodayData($user_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => '*',
				'where' => 'user_id = ' . $user_id . ' and TO_DAYS( NOW( ) ) - TO_DAYS(`create_time`) <= 1',
				'order_by' => 'create_time DESC'
		);
		return $this->get_one($cond);
	}
	
	// 获取七天数据
	public function getSevenData($user_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => 'sum(order_quantity),sum(order_cancel),sum(per_transaction),sum(total_sales),sum(self_sales),sum(proxy_sales),sum(amout),sum(self_amout),sum(proxy_amout)',
				'where' => 'user_id = ' . $user_id . ' and DATE_SUB(CURDATE(), INTERVAL 7 DAY)  <= date(`create_time`)',
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
	
	// 获取一月数据
	public function getMonthData($user_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => 'sum(order_quantity),sum(order_cancel),sum(per_transaction),sum(total_sales),sum(self_sales),sum(proxy_sales),sum(amout),sum(self_amout),sum(proxy_amout)',
				'where' => 'user_id = ' . $user_id . ' and DATE_SUB(CURDATE(), INTERVAL 30 DAY)  <= date(`create_time`)',
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
	
	// 获取所有数据
	public function getAllData($user_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => '*',
				'where' => 'user_id = ' . $user_id ,
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
}