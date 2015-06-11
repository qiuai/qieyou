<?php

class Finance extends MY_Controller {

    public $controllerTag = 'finance';
    public $moduleTag = '';
	function __construct() {
		parent::__construct();
		$this->cklogin();
		$this->check_finance_auth_in_controller();
	}

   /**
	* 查看账户明细
	*/
	public function balance() 
	{
        $this->moduleTag = 'balance';
	//	$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,500,20);
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);
		
		$arr = $this->model->get_localArr($inn_id,$local_id,$dest_id);
		$destInfo = $arr['destInfo'];
		$localArr = $arr['localArr'];
		$Innlist = $arr['Innlist'];

        $search = array(
			'starttime' => $starttime,
			'endtime' => $endtime
		);
		if($inn_id)		//查看单个商户
		{
			$search['key'] = 'inn';
			$search['key_id'] = $destInfo['inn_id'];
		}
		else if($local_id)	//查看街道商户
		{
			$search['key'] = 'local';
			$search['key_id'] = $destInfo['local_id'];
		}
		else if($dest_id)
		{
			$search['key'] = 'dest';
			$search['key_id'] = $destInfo['dest_id'];
		}
		else{	//未指定位置使用默认值 0
			$search['key'] = 'default';
			$search['key_id'] = 0;
		}
		
		$data = $this-> model -> search_balance_order($search,build_limit($page, $perpage));
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data['data'],
            'starttime'=> $starttime,
            'endtime'=> $endtime,
			'pageInfo' => $pageInfo,
			'destInfo' => $destInfo,
			'Innlist' => $Innlist,
			'localArr' => $localArr
		);
	}
	
	public function qieyoubalance()
	{
		$this->controllerTag = 'qieyou';
		$this->moduleTag = 'qieyoubalance';
	//	$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,500,20);
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);
		$inn_id = $this->get_user_inn_id();
        $search = array(
			'key' => 'qieyou',
			'key_id' => $inn_id,
			'starttime' => $starttime,
			'endtime' => $endtime
		);
		
		$data = $this-> model -> search_balance_order($search,build_limit($page, $perpage));
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data['data'],
            'starttime'=> $starttime,
            'endtime'=> $endtime,
			'pageInfo' => $pageInfo,
		);
	}

	/**
	 * 查看所有的提现记录
	 */
	public function cashout() 
	{
        $this->moduleTag = 'cashout';
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,500,20);
        $state = $this->input->get("state")?$this->input->get("state"):'applying';
		$data = $this-> finance_model -> search_cash_apply_by_state(build_limit($page, $perpage),$state);
		$userinfo = array();
		if($data['data'])
		{
			$user_ids = array();
			foreach($data['data'] as $key => $row)
			{
				$user_ids[$row['apply_user_id']] = $row['apply_user_id'];
				$user_ids[$row['cashier_id']] = $row['cashier_id'];
			}
			if(isset($user_ids['0'])){ unset($user_ids['0']); }
			if($user_ids)
			{
				$user_ids = $this->model->get_user_info_in_ids(implode(',',$user_ids),'ui.user_id,ui.real_name',FALSE,TRUE);
				foreach($user_ids as $key => $row)
				{
					$userinfo[$row['user_id']]['real_name'] = $row['real_name']?$row['real_name']:$row['user_name'];
				}
			}
			$userinfo['0']['real_name'] = '系统';
		}
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'state' => $state,
			'users' => $userinfo,
			'data' => $data['data'],
			'pageInfo' => $pageInfo
		);
	}

	/**
	 * process the apply of cash out
	 */
	public function settleCashout()
	{
		$action = input_string($this->input->post("action"),array('settled','rejected'),'rejected');
		$comments = check_empty($this->input->post("comments"),FALSE,'请输入处理记录内容！');
		$apply_id = input_int($this->input->post("apply_id"),'1',FALSE,FALSE,'错误请求！');

		$apply = $this-> model-> get_cashout_apply_by_id($apply_id);
		if (!$apply||$apply['state'] != 'applying') 
		{
			response_msg("申请不存在！");
		}
		$apply_inn = $this->model->get_inn_info_by_inn_id($apply['inn_id'],FALSE);
		if($apply['amount']>$apply_inn['withdrawing'])
		{
			response_msg("系统异常！");
		}
		$apply['comments'] = $comments;
		$apply['inn_account'] = $apply_inn['account'];
		$apply['cashier_id'] = $this->get_user_id();
		$rs = $this->model->settle_apply_cashout($action,$apply);
		response_code($rs?'1':'-1');
	}

	public function cash_comment() {
		$comments = $this->input->post('comments');
		$log_id = $this->input->post('log_id');
		if (!empty($log_id)) {
			$this-> finance_model -> update_cash_comments($comments,$log_id);
		}
	}

   /**
	* 查看退款申请记录
	* web page 
	*/
	public function refund() 
	{
        $this->moduleTag = 'refund';
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,500,20);
        $state = input_string($this->input->get("state"),array('applying','rejected','settled'),'applying');
		
		$data = $this->model->get_order_refund($state,$page,$perpage);
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'state' => $state,
			'data' => $data['data'],
			'pageInfo' => $pageInfo
		);
	}

   /**
	* 处理退款申请
	* ajax POST
	*/
	public function orderRefund() 
	{
		$this->check_finance_auth_in_controller();
		$action = input_string($this->input->post('action'),array('rejected','settled'),'rejected');
		$sanction = $this->input->post('sanction',TRUE);
		$refund_id = input_int($this->input->post('refund_id'),1,FALSE,FALSE,'请求错误！');
		
		switch($action)
		{
			case 'rejected':	//关闭入口
				show_404();
			case 'settled':
				$refund = $this-> model-> get_order_refund_by_id($refund_id);
				break;
		}

		if (!$refund ||$refund['state'] != 'applying') 
		{
			response_msg("请求错误！");
		}
		$done = array(
			'user_id' => $this->get_user_id(),
			'sanction' => $sanction
		);
		$rs = $this->model->settled_order_refund($refund,$done);
		response_code($rs?'1':'-1');
	}
	
	public function account()
	{
        $this->moduleTag = 'account';
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,500,15);

		$userinfo = array();

		$arr = $this->model->get_localArr($inn_id,$local_id,$dest_id);
		$destInfo = $arr['destInfo'];
		$localArr = $arr['localArr'];
		$Innlist = $arr['Innlist'];

		if($inn_id)
		{
			$data = $this->model->search_account_records_by_inn_id($inn_id,$page,$perpage);
			$list = $data['list'];
			$records = array();
			if($list)
			{
				$user_ids = array();
				foreach($list as $key => $row)
				{
					$user_ids[$row['create_by']] = $row['create_by'];
					$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
					$records[] = $row;
				}
				if(isset($user_ids['0'])){ unset($user_ids['0']); }
				if($user_ids)
				{
					$users = $this->model->get_user_info_in_ids(implode(',',$user_ids),'ui.user_id,ui.real_name',FALSE,TRUE);
					foreach($users as $key => $row)
					{
						$userinfo[$row['user_id']]['real_name'] = $row['real_name']?$row['real_name']:$row['user_name'];
					}
				}
				$userinfo['0']['real_name'] = '系统';
			}
		}
		else
		{
			if($dest_id)
			{
				$search['key'] = 'dest';
				$search['key_id'] = $destInfo['dest_id'];
			}
			else if($local_id)
			{
				$search['key'] = 'local';
				$search['key_id'] = $destInfo['local_id'];
			}
			else
			{
				$search['key'] = 'default';
			}
			$data = $this->model->get_inns_account_list($search,$page,$perpage);
			$records = $data['list'];
		}
		
		$total = $data['total'];
		$pageInfo = array(
			'total' => $total,
			'totalpage' => $total/$perpage,
			'perpage' => $perpage,
			'curpage' => $page,
			'url' => makePageUrl($page)
		);
		$this->viewData = array(
			'localArr' => $localArr,
			'destInfo' => $destInfo,
			'Innlist' => $Innlist,
			'records' => $records,
			'inn_id' => $inn_id,
			'userinfo' => $userinfo,
			'pageInfo' => $pageInfo
		);
	}
	
	public function downloadBalance()
	{
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$this->check_finance_auth_in_controller();
		$search = array(
			'starttime' => $starttime,
			'endtime' => $endtime
		);
		if($inn_id)		//查看单个商户
		{
			$search['key'] = 'inn';
			$search['key_id'] = $inn_id;
		}
		else if($local_id)	//查看街道商户
		{
			$search['key'] = 'local';
			$search['key_id'] = $local_id;
		}
		else if($dest_id)
		{
			$search['key'] = 'dest';
			$search['key_id'] = $dest_id;
		}
		else{	//未指定位置使用默认值 0
			$search['key'] = 'default';
			$search['key_id'] = 0;
		}
		$data = $this-> model -> search_balance_order($search);
		$csv = $this->bulitcsv($data['data']);
		$this->load->helper('download');
		force_download(date('Y-m-d H:i:s') . '.csv', $csv);
	}
	
	private function bulitcsv($list)
	{
		$export_fields = array(
			'order_num' => '订单号',
			'inn_name' => '商户名称',
			'product_name' => '商品名称',
			'quantity' => '商品数量',
			'seller' => '销售方',
			'contact' => '联系人',
			'telephone' => '联系人手机号',
			'create_time' => '交易时间',
			'settlement_time' => '结算时间',
			'state' => '订单状态',
			'total' =>	'订单总额',
			'inns_profit' => '商户分润',
			'agent_commission' => '代预订佣金',
			'profit' => '平台收入'
		);
		
		$data = implode(",", $export_fields);
		$data = "{$data}\n";
		$orderState = array('P' => '已支付','U' => '待消费' , 'S' => '已结算','R' => '退款中','C' => '已退款');
		$count = count($list);
		foreach ($list as $order) {
			$line = array();
			foreach ($export_fields as $export_field => $export_name) {
				$value = str_replace("\n", "", $order[$export_field]);
				switch($export_field){
					case 'order_num':
					case 'inn_name':
					case 'product_name':
					case 'quantity':
					case 'contact':
					case 'telephone':
						break;
					case 'seller':
						$value = $value?$value:'且游平台';
						break;
					case 'state':
						$value = $orderState[$value];
						break;
					case 'create_time':
					case 'settlement_time':
						$value = $value?date('Y-m-d H:i:s', $value):'';
						break;
					case 'total':
					case 'inns_profit':
					case 'agent_commission':
					case 'profit':
						break;
				}
				$line[] = $value;
			}
			$line = implode(",", $line);
			$data .="{$line}\n";
		}
		$data .= ',,,,,,,,,合计：,=SUM(K2:K'.($count+1).'),=SUM(L2:L'.($count+1).'),=SUM(M2:M'.($count+1).'),=SUM(N2:N'.($count+1).')';
		return iconv('utf-8', 'gbk//IGNORE', $data);
	}

	private function check_finance_auth_in_controller()
	{
		$user_role = $this->get_user_role();
		switch($user_role)
		{
			case ROLE_CUSTOM_SERVICE:
			case ROLE_TREASURER:
			case ROLE_ADMIN:
				return TRUE;
			default:
				show_404();
				break;
		}
	}
}