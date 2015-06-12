<?php

class manager_model extends MY_Model {

	// 获取昨天数据
	public function getYestodayData($inn_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => '*',
				'where' => 'inn_id = ' . $inn_id . ' and TO_DAYS( NOW( ) ) - TO_DAYS(`create_time`) <= 1',
				'order_by' => 'create_time DESC'
		);
		return $this->get_one($cond);
	}
	
	// 获取七天数据
	public function getSevenData($inn_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => 'sum(order_quantity),sum(order_cancel),sum(per_transaction),sum(total_sales),sum(self_sales),sum(proxy_sales),sum(amout),sum(self_amout),sum(proxy_amout)',
				'where' => 'inn_id = ' . $inn_id . ' and DATE_SUB(CURDATE(), INTERVAL 7 DAY)  <= date(`create_time`)',
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
	
	// 获取一月数据
	public function getMonthData($inn_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => 'sum(order_quantity),sum(order_cancel),sum(per_transaction),sum(total_sales),sum(self_sales),sum(proxy_sales),sum(amout),sum(self_amout),sum(proxy_amout)',
				'where' => 'inn_id = ' . $inn_id . ' and DATE_SUB(CURDATE(), INTERVAL 30 DAY)  <= date(`create_time`)',
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
	
	// 获取所有数据
	public function getAllData($inn_id){
		$cond = array(
				'table' => 'manager_history',
				'fileds' => '*',
				'where' => 'inn_id = ' . $inn_id ,
				'order_by' => 'create_time DESC'
		);
		return $this->get_all($cond);
	}
	
	// 判断今天数据是否更新
	public function isUpdateToday($inn_id,$num){
		//$num 上次数量
		$yestday = strtotime(date("Y-m-d",strtotime("-1 day")));
		$tomorrow= strtotime(date("Y-m-d",strtotime("+1 day")));
		$sql = "select *from orders where inn_id = $inn_id and create_time >= '$yestday' and create_time < '$tomorrow'";
		$count = $this->get_query_count('('.$sql.') as tbd');
		if(!$count || ($count == $num)){
			return FALSE; // 没有更新
		}
		
		return TRUE; // 有更新
	}
	
	// 更新今天数据
	public function updateToday($inn_id=0){
		if($inn_id){ // 指定用户
			return $this->updateTodayOne($inn_id);
		}else{ // 所有用户
			// 查询所有用户
			$yestday = strtotime(date("Y-m-d",strtotime("-1 day")));
			$tomorrow= strtotime(date("Y-m-d",strtotime("+1 day")));
			$cond = array(
					'table' => 'orders',
					'fileds' => '*',
					'where' => "create_time > '$yestday' and create_time < '$tomorrow' group by inn_id",
					'order_by' => 'create_time DESC'
			);
			$record = $this->get_all($cond); // 数据存在 更新
			if(!empty($record)){	// 更新所有数据
				foreach ($record as $value){
					//$this->updateTodayOne($value['inn_id']);
				}
			}
		}
		// 查询今天订单数量
		// 订单是否新增
		return FALSE;
	}
	
	// 更新今天指定用户数据
	public function updateTodayOne($inn_id){
		// 查询今天订单
		$yestday = strtotime(date("Y-m-d",strtotime("-1 day")));
		$tomorrow= strtotime(date("Y-m-d",strtotime("+1 day")));
// 		$sql = "select *from orders od join order_products op on od.order_num = op.order_num where od.create_time >= '$yestday' and od.create_time < '$tomorrow'";
// 		$result = $this->query($sql);
		
		$cond = array(
				'table' => 'users od',
				'fields' => '*',
				'where' => array(
						"od.create_time >= '$yestday' and od.create_time < '$tomorrow'",
				),
				'join' => array(
						'order_products op',
						'od.order_num = op.order_num'
				)
		);
		$records = $this->get_all($cond);
		
		// 组装统计数据
		if(empty($records)){
			return FALSE;
		}else{
			foreach ($records as $key=>$value){
				$data['inn_id'] = $inn_id;
			}
			$data['inn_id'] = $inn_id;
		}
		
		// 判断今天记录是否存在
		$cond = array(
				'table' => 'manager_today',
				'fileds' => '*',
				'where' => array(
						'inn_id' => $inn_id,
						'today'=> date('Ymd')
				),
				'order_by' => 'create_time DESC'
		);
		
		if($record = $this->get_one($cond)){ // 数据存在 更新
			
			// 更新今天订单
			
// 			if($type == 'create'){
// 				$order['per_transaction'] = number_format((($record['amout'] +$order['total']) / $record['order_quantity']),'');
// 				$sql = "update manager_today set total_sales = total_sales + ".$order['count'].",self_sales = self_sales + ".$order['count'].",amout = amout + ".$order['total'].",per_transaction = '".$order['per_transaction']."' where id = ".$record['id'];
// 				if($this->query($sql)){
// 					return TRUE;
// 				}
// 			}else{
// 				$order['per_transaction'] = number_format((($record['amout'] - $order['total']) / $record['order_quantity']),'');
// 				$sql = "update manager_today set total_sales = total_sales - ".$order['count'].",self_sales = self_sales - ".$order['count'].",amout = amout - ".$order['total'].",per_transaction = '".$order['per_transaction']."' where id = ".$record['id'];
// 				if($this->query($sql)){
// 					return TRUE;
// 				}
// 			}
		
// 			$cond = array(
// 					'table' => 'manager_today',
// 					'primaryKey' => 'id',
// 					'data' => $order
// 			);
// 			if($this->update($cond)){
// 				return TRUE;
// 			}
		}else{	// 数据不存在 写入数据
			
			// 更新今天订单
			
// 			if($type == 'create'){
// 				$manager['order_quantity'] = $order['count']; // 商品数量待确认
// 			}else{
// 				$manager['order_cancel'] = $order['count']; // 商品数量待确认
// 			}
		
// 			$manager['inn_id'] = $order['inn_id'];
// 			$manager['per_transaction'] = $order['total'];
// 			$manager['total_sales'] = $order['count'];
// 			$manager['self_sales'] = $order['count'];
// 			$manager['proxy_sales'] = $order['count'];
// 			$manager['amout'] = $order['total'];
// 			$manager['proxy_amout'] = $order['total'];
// 			$manager['today'] = date('Ymd');
// 			$manager['create_time'] = TIME_NOW;
		
// 			if($this->insert($manager, 'manager_today')){
// 				return TRUE;
// 			}
		}
		// 订单数量
		// 商品数量
		// 销售总额
		return FALSE;
	}
	
	// 转移今天数据至历史数据
	public function updateHistory(){
		// 更新今天数据
		$this->updateToday();
		
		// 判断现在时间是否第二天
		// 凌晨之后再迁移数据
		
		// 定时更新今天数据至历史数据表
		
		// 查询今天数据
		$cond = array(
				'table' => 'manager_today',
				'fileds' => '*',
				'where' => array(
						'today'=> date('Ymd')
				),
				'order_by' => 'create_time DESC'
		);
		$record = $this->get_one($cond);
		
		// 更新到历史数据
		if(!empty($record)){
			$this->insert($record,'manager_history');
		}else{
			return 0;
		}
		
		// 删除今天数据
		foreach ($record as $value){
			$ids[] = $value['id'];
		}
		$ids = implode(',', $ids);
		$sql = "delete from manager_today where id in ($ids)";
		$this->query($sql);
		
		return 1;
	}
}