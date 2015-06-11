<?php

class Inn extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->check_token();
	}
	
   /**
	* 查看当前驿栈的账户
	* return array
	*/
	public function balance()
	{
		$innInfo = $this->model->get_inn_info_by_inn_id($this->token['inn_id'],FALSE);	
		if(!$innInfo){
			response_msg('2007');
		}
		$data['account'] = $innInfo['account'];
		$data['balance'] = $innInfo['account'] - $innInfo['withdrawing'];
		response_data($data);
	}
	
   /**
	* 查看当前商户的交易流水
	* return
	*/
	public function transflow()
	{
		$last_id = input_int($this->input->get('lastid'),0,FALSE,0);
		$limit = input_int($this->input->get('limit'),1,50,20);

		$this->_LoadModel('finance');
		$data = $this->finance_model->get_account_records_by_inn_id($this->token['inn_id'],$last_id,$limit);
		
		if(!$data)
		{
			$res = array(
				'title' => array( array('month' =>strtotime(date('Ym').'01'),'month_end'=>$_SERVER['REQUEST_TIME'],'cashin'=>'0','cashout'=>'0','lastid'=>'0')),
				'list' => array()
			);
			response_data($res);
		}
		$month = $data[0]['create_time'];
		$first_month = date('Ym',$month); 
		$month_start = strtotime($first_month.'01');
		$month_end = strtotime('+1 month',$month_start) -1 ;
		$res['title'][0]['month_start'] = $month_start;
		$res['title'][0]['month_end'] = $month_end;
		$i = 0;
		foreach($data as $key => $row)
		{
			if($row['create_time'] >= $month_start)
			{
				$res['title'][$i]['lastid'] = $row['record_id'];
			}
			else
			{
				$month_start = date('Ym',$row['create_time']);
				$month_start = strtotime($month_start.'01');
				$month_end = strtotime('+1 month',$month_start) -1 ;
				$i ++;
				$res['title'][$i]['month_start'] = $month_start;
				$res['title'][$i]['month_end'] = $month_end;
				$res['title'][$i]['lastid'] = $row['record_id'];
			}
			$res['list'][] = $row;
		}
		foreach($res['title'] as $key => $row)
		{
			$rs = $this->finance_model->get_mouth_transflow($this->token['inn_id'],$row['month_start'],$row['month_end']);
	
			$res['title'][$key]['cashin'] = $rs['cashin'];
			$res['title'][$key]['cashout'] = $rs['cashout'];
		}
		response_data($res);
	}

   /**
	* 查看当前商户的提现记录
	* return array
	*/
	public function cashoutlist()
	{
		$page = input_int($this->input->get('page'),1,FALSE,1,'1015');
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15,'1016');
		
		$this->_LoadModel('finance');
		$data = $this->finance_model->get_cashout_list_by_inn_id($this->token['inn_id'],$page,$perpage);
		response_data($data);
	}

   /**
	* 查看当前商户提现处理记录
	* return array
	*/
	public function cashoutlog()
	{
		$cashout_id = input_int($this->input->get('id'),1,FALSE,1,'1007');
		$this->_LoadModel('finance');
		$cashout = $this->finance_model->get_cashout_detail_by_inn_id($cashout_id,$this->token['inn_id']);
		if(!$cashout)
		{
			response_msg('1008');
		}
		$cashoutlog = $this->finance_model->get_cashout_log_by_cashout_id($cashout['id']);
		$data = array(
			'cashout' => $cashout,
			'logs' => $cashoutlog
		);
		response_data($data);
	}

   /**
	* 商户子账户管理
	* 访问权限：innholder only
	*/
	public function sublist()
	{
		$innInfo = $this->model->get_inn_info_by_inn_id($this->token['inn_id'],FALSE);	
		if($this->token['user_id'] != $innInfo['innholder_id'])
		{
			response_msg('1018');
		}
		$data = $this->model->get_inn_subs_by_inn_id($this->token['inn_id']);
		response_data($data);
	}

   /**
	* 商户子账户管理
	* 访问权限：innholder only
	*/
	public function subview()
	{
		$user_id = input_int($this->input->get('uid'),1,FALSE,FALSE,'1019');
		$innInfo = $this->model->get_inn_info_by_inn_id($this->token['inn_id'],FALSE);
		if($this->token['user_id'] != $innInfo['innholder_id'])
		{
			response_msg('1018');
		}
		$data = $this->model->get_sub_detail_by_user_id($this->token['inn_id'],$user_id);
		if(!$data)
		{
			response_msg('1020');
		}	
		response_data($data);
	}

   /**
	* 商户子账户管理
	* 访问权限：innholder only
	*/
	public function submanage()
	{
		$action = input_string($this->input->get('act'),array('add','del','wake','stop'),FALSE,'4001');
		$innInfo = $this->model->get_inn_info_by_inn_id($this->token['inn_id'],FALSE);
		if($this->token['user_id'] != $innInfo['innholder_id'])
		{
			response_msg('1018');
		}

		if(in_array($action,array('del','wake','stop')))
		{
			$user_id = input_int($this->input->get('uid'),1,FALSE,FALSE,'1019');
			$data = $this->model->get_sub_detail_by_user_id($this->token['inn_id'],$user_id);
			if(!$data)
			{
				response_msg('1020');
			}
			$rs = $this->model->modify_inn_sub_by_user_id($action,$this->token['inn_id'],$user_id);
			if($rs)
			{
				response_msg('1');
			}
			response_msg('4000');
		}
		response_msg('1');
	}

   /**
	* submit the cash out apply
	*/
	public function applyCash()
	{
		$apply_amount = input_empty($this->input->post('apply_amount'),FALSE,'4001');
		$apply['amount'] = sprintf("%.2f", $apply_amount);
		if($apply['amount'] <= 0)
		{
			response_msg('4001');
		}

		$innInfo = $this->model->get_inn_info_by_inn_id($this->token['inn_id']);
		if (!$innInfo)
		{
			response_msg('1018');
		}
		if (($innInfo['account']-$innInfo['withdrawing']) < $apply_amount)
		{
			response_msg('1022');
		}

		$apply['user_id'] = $this->token['user_id'];
		$apply['inn_id'] = $this->token['inn_id'];
		
		$this->_LoadModel('finance');
		$rs = $this->finance_model->inn_apply_cashout($apply);
		if(!$rs)
		{
			response_msg('5001');
		}
		response_msg('1');
	}
}
