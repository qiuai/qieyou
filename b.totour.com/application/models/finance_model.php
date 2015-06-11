<?php

class Finance_model extends MY_Model {

	public function get_cashout_list_by_inn_id($inn_id,$page,$perpage) 
	{
		$cond = array(
			'table' => 'cashout',
			'fileds' => '*',
			'where' => array(
				'inn_id' => $inn_id
			),
			'order_by' => 'create_time DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_cashout_detail_by_inn_id($cashout_id,$inn_id) 
	{
		$cond = array(
			'table' => 'cashout',
			'fileds' => '*',
			'where' => array(
				'id' => $cashout_id,
				'inn_id' => $inn_id
			)
		);
		return $this->get_one($cond);
	}

	public function get_cashout_log_by_cashout_id($cashout_id) 
	{
		$cond = array(
			'table' => 'cashout_log',
			'fileds' => '*',
			'where' => array(
				'cashout_id' => $cashout_id
			),
			'order_by' => 'create_time ASC'
		);
		return $this->get_all($cond);
	}

	public function get_account_records_by_inn_id($inn_id,$last_id,$limit)
	{
		$cond = array(
			'table'	=> 'account_records',
			'fields' => 'record_id,record_type,order_num,amount,comments,create_time',
			'where' => 'inn_id = '.$inn_id.'',
			'order_by' => 'record_id DESC',
			'limit' => $limit
		);
		if($last_id)
		{
			$cond['where'].= ' AND record_id < '.$last_id;
		}
		
		return $this->get_all($cond);
	}

	public function get_mouth_transflow($inn_id,$month_start,$month_end)
	{
		$cond = array(
			'table'	=> 'account_records',
			'fields' => 'record_type,amount,create_time',
			'where' => 'inn_id = '.$inn_id.' AND create_time >= '.$month_start.' AND create_time < '.$month_end.'',
		);
		$rs = $this->get_all($cond);
		$res = array('cashin' => 0 , 'cashout' => 0);
		if(!$rs)
		{
			return $res;
		}

		foreach($rs as $key => $row)
		{
			if(in_array($row['record_type'],array('sell','agent','recharge')))	//'sell','agent','cashout','refund','buy','recharge'
			{
				$res['cashin'] += $row['amount'];
			}
			else
			{
				$res['cashout'] += $row['amount'];
			}
		}
		return $res;
	}

	public function inn_apply_cashout($apply)
	{
		$apply_cashout = array (
			'account_record_id' => 0,
		 	'apply_user_id' => $apply['user_id'],
			'inn_id' => $apply['inn_id'],
  			'amount' => $apply['amount'],
 			'state' => 'applying',
 			'settlement_time' => 0,
 			'comments' => '',
			'create_time' => $_SERVER['REQUEST_TIME'],
 		);
 		$cashout_id = $this-> insert($apply_cashout,'cashout');
		
 		$cashout_log = array (
			'cashout_id' => $cashout_id,
			'operate_type' => 'apply',
			'comments' => '用户提现申请',
			'create_time' => $_SERVER['REQUEST_TIME'],
			'create_by' => $apply['user_id']
		);
		$this -> insert($cashout_log,'cashout_log');
		
		$sql = "UPDATE inns SET `withdrawing` = `withdrawing` + ".$apply['amount']." WHERE inn_id = ".$apply['inn_id']."";
		$rs = $this->db->query($sql);
		return $rs;
	}
}