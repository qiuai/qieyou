<?php
class Finance extends WEBbase {
	
	public $autoLoadModel = FALSE;

    public function __construct() 
	{
        parent::__construct();
		$this->_LoadModel('finance');
	}

    // 财富管理入口
	public function index(){
		$this->viewPath='finance/index';
	}
	
	// 账户 详情
	public function detail(){
		$inn_id = $this->get_inn_id(TRUE);
		$inn = $this->finance_model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn){
			response_code('1006');
		}
		
		$data['account'] = $inn['account'];	// 账号余额
		$data['order_divide'] = $inn['order_divide'];	// 自营收入
		$data['balance_divide'] = $inn['balance_divide'];	// 代销收入
		$data['amount'] = floatval($data['order_divide']) + floatval($data['balance_divide']);	// 累计收入
// 		$data['amount'] = $inn['amount'];	// 待确认
		$data['withdrawing'] = $inn['withdrawing'];	// 提现中
// 		$data['amount'] = $inn['amount'];	// 已提现
		
		response_data($data);
	}
	
	// 交易流水
	public function tranflow(){
		$inn_id = $this->get_inn_id(TRUE);
		$inn = $this->finance_model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn)
		{
			response_code('1006');
		}
		$last_id = input_int($this->input->get('lastid'),0,FALSE,0);
		$limit = input_int($this->input->get('limit'),1,50,20);

		$res = array();
		$data = $this->finance_model->get_account_records_by_inn_id($inn_id,$last_id,$limit);
		if($data)
		{
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
				$rs = $this->finance_model->get_mouth_transflow($inn_id,$row['month_start'],$row['month_end']);
		
				$res['title'][$key]['cashin'] = $rs['cashin'];
				$res['title'][$key]['cashout'] = $rs['cashout'];
			}
		}
		else
		{
			$res = array(
				'title' => array( array('month' =>strtotime(date('Ym').'01'),'month_end'=>$_SERVER['REQUEST_TIME'],'cashin'=>'0','cashout'=>'0','lastid'=>'0')),
				'list' => array()
			);
		}
		response_json('1',$res);
	}
	
	// 提现
	public function withDraw(){
		
	}
	
}