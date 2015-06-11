<?php

class Finance_model extends MY_Model {

	public function search_account_records_by_inn_id($inn_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'account_records',
			'fields' => '*',
			'where' => array(
				'inn_id' => $inn_id
			),
			'order_by' => 'record_id DESC',
		);
		$total = $this->get_total($cond);
		$list = array();
		if($total&&($total>($page-1)*$perpage))
		{
			$pagerInfo = array(
				'cur_page' => $page,
				'per_page' => $perpage
			);
			$list = $this->get_all($cond,$pagerInfo);
		}
		return array('total' => $total,'list' => $list);
	}

	public function search_cash_apply_by_state($limit, $state = null ) {
		$records = array();
		$select = "SELECT ca.* , i.inn_name as innsName FROM ";
		$selectfrom = "cashout ca ";
		$selectjoin = "JOIN inns i on i.inn_id = ca.inn_id  ";
		$where = 'WHERE 1 = 1';
		$orderby = "  order by ca.id asc ";
		if ($state) {
			$where = $where." AND ca.state = '".$state."' ";
		}
		if($state == 'settled')
		{
			$orderby = "  order by ca.id desc ";
		}
		$sql = $select.$selectfrom.$selectjoin.$where.$orderby.$limit;
		$totalsql = $selectfrom.$where;
		$total = $this-> get_query_count($totalsql);
		$result = array();
		if($total)
		{
			$result = $this->db->query($sql) -> result_array();
		}
		return array ('data' => $result, 'total' => $total);
	}
	
	public function search_refund_apply_records( $limit, $state= null, $regionManagerId = NULL) {
		$records = array();
		$select = "SELECT ra.*, u.user_name as applyUserName,o.order_num,
			   i.inn_name, o.total, o.pay_time
			   FROM order_refund ra 
			   INNER JOIN users u on ra.apply_user_id = u.user_id 
			   INNER JOIN orders o on o.order_id = ra.order_id 
			   LEFT JOIN inns i ON o.inn_id = i.inn_id ";
		$orderby =" order by ra.create_time DESC ";
		$where = 'WHERE 1 = 1';
		$state = $this-> db -> escape($state);
		if (!empty($state)) {
			$where = $where." AND ra.state = $state ";
		}
		if (!empty($regionManagerId)) {
			$select = $select. " INNER JOIN r_users_dest r on r.dest_id = i.dest_id ";
			$where = $where. " AND r.user_id = $regionManagerId ";
		}
		$sql = $select.$where.$orderby.$limit;
		$result = $this->db->query($sql) -> result_array();
		$total = $this-> get_query_count($select.$where);
		foreach ($result as $row) {
			$row['create_time'] = format_time($row['create_time']);
			$row['settlement_time'] = format_time($row['settlement_time']);
			$row['order_income'] = $row['total'] - $row['refund_amount'];
			$row['pay_time'] = format_time($row['pay_time']);
			$order[] = $row['order_num'];
			$records[$row['order_num']] = $row;
		}
		if (!empty($records)) {
			$this->load->model('order_model');
			$order_products = $this->order_model->get_order_detail_by_ids(implode(',',$order));
			foreach($order_products as $key => $product)
			{
				$records[$product['order_num']]['products_info'][] = $product;
			}
		}
		return array ('data' => $records, 'total' => $total);
	}

	/**
	 * Enter description here ...
	 * @param int $inn_id
	 * @return array()
	 */
	public function search_recharge_records($inn_id, $limit) {
		$records = array();
		$select = "SELECT ar.*, u.user_name as operator FROM account_records ar
		   LEFT JOIN users u on ar.created_by = u.user_id WHERE ar.inn_id = $inn_id  
		   AND ar.record_type = 'recharge' ";
        $orderby = " order by ar.create_time desc ";
		$result = $this->db->query($select.$orderby.$limit) -> result_array();
		$total = $this-> get_query_count($select);
		$totalAmount = $this-> get_sum_by_column($select,'amount');
		foreach ($result as $row){
			$row['create_time'] = format_time($row['create_time']);
			array_push($records, $row);
		}
		return array ('data' => $records, 'total' => $total,'totalAmount' => $totalAmount);
	}
	
   /**
    * 获取单条提现记录
	* return array
	*/
	public function get_cashout_apply_by_id($apply_id) 
	{
		$cond = array(
			'table' => 'cashout',
			'fields' => '*',
			'where' => array(
				'id' => $apply_id
			)
		);
		return $this->get_one($cond);
	}
		

	public function settle_apply_cashout($action,$apply)
	{	
		$account_record_id = 0;
		$this->db->trans_start();
		if ($action == 'settled')	//确认转账
		{
			$inn = 'UPDATE inns SET `withdrawing` = `withdrawing` - '.$apply['amount'].' , `account` = `account` - '.$apply['amount'].' WHERE inn_id = '.$apply['inn_id'];
			
			$account_record = array(
				'inn_id' => $apply['inn_id'],
				'record_type' => 'cashout',
				'order_num' => $apply['id'],
				'amount' => $apply['amount'],
				'balance' => $apply['inn_account'] - $apply['amount'],
				'comments' => '提现成功',
				'create_time' => $_SERVER['REQUEST_TIME'],
				'create_by' => $apply['cashier_id']
			);
			
			$account_record_id = $this->insert($account_record,'account_records');
		}
		else if($action == 'rejected')	//拒绝转账
		{
			$inn = 'UPDATE inns SET `withdrawing` = `withdrawing` - '.$apply['amount'].' WHERE inn_id = '.$apply['inn_id'];
		}

		$this->db->query($inn);

		$cashout_log = array (
			'cashout_id' => $apply['id'],
			'operate_type' => $action,
			'comments' => $apply['comments'],
			'create_time' => $_SERVER['REQUEST_TIME'],
			'create_by' => $apply['cashier_id']
		);
		$this->insert($cashout_log,'cashout_log');

		$cashout = array(
			'table' => 'cashout',
			'primaryKey' => 'id',
			'data' => array(
				'id' => $apply['id'],
				'account_record_id' => $account_record_id,
				'state' =>  $action,
				'comments' => $apply['comments'],
				'settlement_time' => $_SERVER['REQUEST_TIME'],
				'cashier_id' => $apply['cashier_id']
			)
		); 
		$this->update($cashout);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return TRUE;
	}


	public function get_order_refund_by_id($refund_id)
	{
		$cond = array(
			'table' => 'order_refund',
			'fields' => '*',
			'where' => array(
				'refund_id' => $refund_id
			)
		);
		return $this->get_one($cond);
	}

	
	public function update_refund_apply($id,$applyInfo) {
		$applyInfo['updated_time'] = time();
		$applyInfo['updated_by'] = $this->getUserId();
		$this->db->where('id', $id);
		$this->db->update('order_refund', $applyInfo);
	}
	
	/**
	 * Enter description here ...
	 * @param text $comments
	 * @param int $log_id
	 */
	public function update_cash_comments($comments,$log_id) {
		$data = array(
                'comments' => $comments,
                'updated_time' => time(),
                'updated_by' => $this->getUserId()
		);
		$this->db->where('id', $log_id);
		$this->db->update('cash_operate_log', $data);
	}

	public function insert_account_records($recordInfo) {
		$recordInfo['create_time'] = time();
		$recordInfo['created_by'] = $this->getUserId();
		$this->db->insert('account_records', $recordInfo);
		return $this->db->insert_id();
	}

   /**
    * 充值成功回调接口
	* @param int order_num
	* @return bool
	*/
	public function recharge($order_num)
	{
		$cond = array(
			'table' => 'orders',
			'fields' => 'order_id,inn_id,order_num,state,total,order_type,create_time,finish_time,Remark',
			'where' => array(
				'order_num' => $order_num,
				'order_type' => 'recharge'
			)
		);
		$chargeorder = $this->get_one($cond);
		$cond = array(
			'data' => array(
				'order_id' => $chargeorder['order_id'],
				'state' => 'P',
				'pay_time' => $_SERVER['REQUEST_TIME'],
				'update_time' => $_SERVER['REQUEST_TIME']
			),
			'primaryKey' => 'order_id',
			'table' => 'orders'
		);
		if($this->update($cond))
		{
			$sql = 'SELECT account_balance FROM inns WHERE `inn_id` = '.$chargeorder['inn_id'];
			$this->db->query();
			//insert the account record for case in
			$account_balance = $this-> inns_model->get_inns_account_balance($inn_id);
			$new_account_balance = $account_balance +  $cashin_amount;
			$accountRecords = array (
				'inn_id' => $chargeorder['inn_id'],
				'record_desc' => '充值',
				'record_type' => 'recharge',
				'amount' => $chargeorder['total'],
				'balance' => $new_account_balance, 
				'comments' => $chargeorder['Remark'],
			);
			$account_record_id = $this->insert_account_records($accountRecords);
			//update the account balance of inns
			$innsInfo = array (
				'account_balance' => $new_account_balance
			);
			$this-> inns_model-> update_inns($inn_id,$innsInfo);
			$this->_echojson(array('code' => '1','msg' => "Success"));
		}
	}

	public function get_order_refund($state,$page,$per_page=20)
	{
		$cond = array(
			'table' => 'order_refund',
			'fields' => '*',
			'where' => array(
				'state' => $state
			),
			'order_by' => 'create_time DESC'
		);
		$total = $this->get_total($cond);
		if(!$total)
		{
			return array('total' => 0,'data' => array());
		}

		$pagerInfo = array(
			'cur_page' => $page,
			'per_page' => $per_page
		);
		$rs = $this->get_all($cond,$pagerInfo);
		if(!$rs)
		{
			return array('total' => $total,'data' => array());
		}

		$records = array();
		$order_list = array();

		foreach($rs as $key => $apply)
		{
			$order_list[] = $apply['order_num'];
			$user_ids[$apply['apply_user_id']] = $apply['apply_user_id'];
			$user_ids[$apply['cashier_id']] = $apply['cashier_id'];
		}
		
		if(isset($user_ids['0'])){ unset($user_ids['0']); }
		if($user_ids)
		{
			$users = $this->get_user_info_in_ids(implode(',',$user_ids),'ui.user_id,ui.real_name',FALSE,TRUE);
			foreach($users as $key => $row)
			{
				$userinfo[$row['user_id']]['real_name'] = $row['real_name']?$row['real_name']:$row['user_name'];
			}
		}
		$userinfo['0']['real_name'] = '系统';

		$product_list = $this->db->query("SELECT o.order_num,o.pay_time,o.pay_type,o.code,o.total,p.product_name,p.inn_name,p.product_id FROM orders as o JOIN order_products as p ON o.order_num = p.order_num WHERE o.order_num IN (".implode(',',array_unique($order_list)).")")->result_array();
		$order_list = array();
		foreach($product_list as $key => $product)
		{
			$product_info['product_id'] = $product['product_id'];
			$product_info['product_name'] = $product['product_name'];
			$product_info['inn_name'] = $product['inn_name'];
			$data[$product['order_num']]['pay_type'] = $product['pay_type'];
			$data[$product['order_num']]['pay_time'] = $product['pay_time'];
			$data[$product['order_num']]['code'] = $product['code'];
			$data[$product['order_num']]['total'] = $product['total'];
			$data[$product['order_num']]['inn_name'] = $product['inn_name'];
			$data[$product['order_num']]['products_info'][] = $product_info;
		}
		$records = array();
		foreach($rs as $key => $apply)
		{
			$apply['apply_user_name'] = $userinfo[$apply['apply_user_id']]['real_name'];
			$apply['cashier_user_name'] = $userinfo[$apply['cashier_id']]['real_name'];
			$records[] = array_merge($apply,$data[$apply['order_num']]);
		}
		return array ('total' => $total,'data' => $records);
	}

	/*处理退款请求*/ //1处理订单更新  2更新order_refund状态 3写订单日志 4写系统日志
	public function settled_order_refund($refund,$done)
	{
		$cond = array(
			'table' => 'orders',
			'fields' => '*',
			'where' => array(
				'order_num' => $refund['order_num'],
				'state' => 'R'
			)
		);
		$order = $this->get_one($cond);
		if(empty($order))
		{
			return FALSE;
		}
		$this->db->trans_start();
		$order_update = array(
			'primaryKey' => 'order_id',
			'table' => 'orders',
			'data' => array(
				'order_id' => $order['order_id'],
				'state' => 'C',
				'settlement_time' => $_SERVER['REQUEST_TIME'],
				'inns_profit' => 0.00,
				'agent_commission' => 0.00,
				'profit' => 0.00
			)
		);
		$this->update($order_update);

		$order_refund = array(
			'table' => 'order_refund',
			'primaryKey' => 'refund_id',
			'data' => array(
				'refund_id' => $refund['refund_id'],
				'state' => 'settled',
				'cashier_id' => $done['user_id'],
				'settlement_time' => $_SERVER['REQUEST_TIME'],
				'sanction' => $done['sanction'],
			)		
		);
		$this->update($order_refund);

		$this->load->model('order_model');
		$order['old_state'] = 'R';
		$action = 'order refund';
		$note = '订单退款完成'.($done['sanction']?'，备注：'.$done['sanction'].'':'');
		$this->order_model->w_order_logs($order,$action,$note);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function get_inns_account_list($search,$page,$perpage)
	{
		$cond = array(
			'table' => 'inns',
			'fields' => 'inn_id,inn_name,account,withdrawing,state,order_divide,balance_divide,create_time',
			'where' => 'is_qieyou = 0',
			'order_by' => 'inn_id ASC'
		);

		switch($search['key'])
		{
			case 'local':
				if($search['key_id'])
				{
					$cond['where'] .=' AND local_id = '.$search['key_id'];
				}
			break;
			case 'dest':
				if($search['key_id'])
				{
					$cond['where'] .=' AND local_id = '.$search['key_id'];
				}
			break;
			default:
			break;
		}

		$total = $this->get_total($cond);
		$list = array();
		if($total&&($total>($page-1)*$perpage))
		{
			$pagerInfo = array(
				'cur_page' => $page,
				'per_page' => $perpage
			);
			$list = $this->get_all($cond,$pagerInfo);
		}
		return array('total' => $total,'list' => $list);
	}
	
	
	public function search_balance_order($search,$limit='')
	{
		$records = array();
		$select =
			"SELECT o.order_num ,op.inn_name,op.product_name,op.quantity,i.inn_name as seller, o.state,o.contact , o.telephone , o.total , o.inns_profit , o.create_time , o.settlement_time,o.agent_commission ,o.profit 
		     FROM ";
		$selectfrom = 
			 "orders o LEFT JOIN inns i ON (o.seller_inn != 0 AND i.inn_id = o.seller_inn)  ";
		$selectjoin =
			 "
			  LEFT JOIN order_products as op ON op.order_num = o.order_num 
			 ";
			 
		$where = "WHERE o.state IN ('P','U','S','R','C') ";
		/*$selectjoin = 
			"orders o 
			 LEFT JOIN inns i ON o.inn_id = i.inn_id 
			 LEFT JOIN destination dest on dest.dest_id = i.dest_id 
			 LEFT JOIN users u on i.innholder_id = u.user_id 
			 LEFT JOIN users u2 on o.user_id = u2.user_id ";*/
		
		switch($search['key'])
		{
			case 'inn':
				if($search['key_id'])
				{
					$where .= ' AND ( o.inn_id = '.$search['key_id'].' OR o.seller_inn = '.$search['key_id'].') ';
				}
			break;
			case 'local':
				$where .= ' AND  i.is_qieyou = 0';
				if($search['key_id'])
				{
					$where .= ' AND i.local_id = '.$search['key_id'];
				}
			break;
			case 'dest':
				$where .= ' AND i.is_qieyou = 0';
				if($search['key_id'])
				{
					$where .= ' AND i.dest_id = '.$search['key_id'];
				}
			break;
			case 'qieyou':
				if($search['key_id'])
				{
					$where .= ' AND o.inn_id = '.$search['key_id'];
				}
			break;
			default:
			break;
		}

		$orderby =" order by o.create_time DESC ";
		if($search['starttime'])
		{
			$where .= ' AND o.create_time > '.$search['starttime'];
		}
		if($search['endtime'])
		{
			$where .= ' AND o.create_time < '.$search['endtime'];
		}
		
		$sql = $select.$selectfrom.$selectjoin.$where.$orderby.$limit;
		$totalsql = $selectfrom.$where;
		$total = $this-> get_query_count($totalsql);
		$result = $this->db->query($sql) -> result_array();
	 //	$total = $this-> get_query_count($select.$where);
		return array ('data' => $result, 'total' => $total);
	}
}