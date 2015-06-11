<?php

class Inns extends MY_Controller {

	public $controllerTag = 'inn';

	public function __construct() {
		parent::__construct();
		$this->cklogin();
	}
	
	public function index()
	{
		$user_id = $this->get_user_id();
		$inn_user_id = $this->model->get_inn_user_id_by_shopmanage_id($user_id);
		if(!$inn_user_id){//驿栈老板ID不存在 店长账号已被驿栈老板删除 或者其他错误
			$this->web_user->logout();
			header("Location: ".base_url()."login"); 	
			exit;
		}			
		$innInfo = $this->model->get_user_inns($inn_user_id,TRUE);
		$innInfo = $this->model->get_user_inns($user_id,TRUE);
		$orders = $this->model->get_orders_by_inn_id($innInfo['inn_id']);
		if($orders)
		{
			$order_nums = array();
			$total = $this->model->get_count_today_orders_by_inn_id($innInfo['inn_id']);
			foreach($orders as $key => $val)
			{
				$order_nums[] = $val['order_num'];
				$order_products[$val['order_num']] = array('product_name'=>'','starttime'=>'','price'=>'','quantity'=>'');
				$orders[$key]['state'] = '<span class="'.$val['state'].'">'.$this->model->orderState[$val['state']].'</span>';
			}
			$this->load->model('order_model');
			$products = $this->order_model->get_order_detail_by_ids(implode(',',$order_nums));
			foreach($products as $key => $val)
			{
				$order_products[$val['order_num']]['product_name'] .= '<p class="tl">'.$val['product_name'].'</p>';
				if($val['category'] == 'goods')
				{
					$order_products[$val['order_num']]['starttime'] .= '<p><br/></p>';
				}
				else
				{
					$order_products[$val['order_num']]['starttime'] .= '<p>'.date('Y-m-d',$val['start_time']).'</p>';
				}
				$order_products[$val['order_num']]['price'] .= '<p><cite>¥'.number_format($val['price'],2).'</cite></p>';
				$order_products[$val['order_num']]['quantity'] .= '<p>'.$val['quantity'].'</p>';
			}
		}
		$this->viewData = array(			
			'orders' => $orders,						//最近订单
			'order_products' => $order_products,		//最近订单产品
			'innInfo' => $innInfo,
			'total_order' => $total						//今天订单
		);
	}

	/**
	 * 我的驿栈首页
	 */
	public function info()
	{
		$this->moduleTag = 'innsIndex';
		$inn_id = $this -> check_user_inns_permission();
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id,TRUE);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
		$this->viewData['innsInfo'] = $innsInfo;
	}

   /**
	* 我的驿栈-驿栈图片
	*/
	public function picture()
	{
		$this->moduleTag = 'innsIndex';
		$inn_id = null;
		$inn_id = $this -> check_user_inns_permission();
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id,TRUE);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}	
		$innsInfo['banner_pic_list'] = json_decode($innsInfo['banner_pic_list'],TRUE);
		$this->viewData['innsInfo'] = $innsInfo;
		$this->viewData['key_auth'] = $this->model->get_create_user_authcode();
	}

	/**
	 * 我的驿栈-掌柜资料
	 */
	public function manager()
	{
		$this->moduleTag = 'innsIndex';
		$inn_id = $this->check_user_inns_permission();
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
		$inn_manager = $this->model->get_inn_manager_by_inn_id($inn_id);
		$this->viewData = array(
			'innsInfo' => $innsInfo,
			'inn_manager' => $inn_manager
		);
	}

   /**
	* 我的驿栈-驿栈故事
	*/
	public function story()
	{
		$this->moduleTag = 'innsIndex';
		$inn_id = $this -> check_user_inns_permission();
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id,TRUE);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
		$this->viewData['innsInfo'] = $innsInfo;
	}

   /**
	* 我的驿栈-预订须知
	*/
	public function bookingInfo()
	{
		$this->moduleTag = 'innsIndex';
		$inn_id = $this -> check_user_inns_permission();
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id,TRUE);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
		$this->viewData['innsInfo'] = $innsInfo;
	}

	public function updateInninfo()
	{
		$inn_id = $this->input->post('sid');
		$this->check_user_inns_permission($inn_id);
		$innsInfo = $this->model->get_inn_info_by_inn_id($inn_id);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
		$this->_echoJson($this->model->update_inninfo_by_inn_id($this->input->post(),$innsInfo));
	}

	/**
	 * 查看充值记录
	 */
	public function cashin() {
		$this->moduleTag = 'innsCashin';
		$inn_id = $this->model->getUserInnId();
		$page = $this->input->get('page')? $this->input->get('page'):'1';
		$perpage = DEFAULT_PERPAGE;
		$this->load->model('finance_model');
		$data = $this-> finance_model -> search_recharge_records($inn_id,build_limit($page, $perpage));
		$account_balance = $this-> inns_model->get_inns_account_balance($inn_id);
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data['data'],
			'pageInfo' => $pageInfo,
			'balance' => $account_balance,
			'totalAmout' => $data['totalAmount']
		);
	}
	
	public function addcashin()
	{
        $this->moduleTag = 'innsCashin';
        $inn_id = $this->model->getUserInnId();
        if (is_post()) {
            if (empty($inn_id)) {
                $this-> errorMsg(0,"驿栈不存在！");
            }
            $cashin_amount = $this->input->post("amount");
            $comments = $this->input->post("comments");
            if (empty($cashin_amount)||!preg_match('/^\d*$/',$cashin_amount))
            {
                $this-> errorMsg(0,"充值金额不正确！");
            }
            if($cashin_amount<100 || $cashin_amount >5000)
            {
                $this-> errorMsg(0,"充值金额必须大于100，小于5000！");
            }
            $order['order_type'] = 'recharge';
            $order['inn_id'] = $inn_id;
            $order['user_id'] = $this->model-> getUserId();
            $order['order_num'] = date('Ymd').mt_rand(10000,99999).substr($_SERVER['REQUEST_TIME'],-6);
            $order['state'] = 'A';
            $order['total'] = $cashin_amount;
            $order['create_time'] = $_SERVER['REQUEST_TIME'];
            $order['update_time'] = $_SERVER['REQUEST_TIME'];
            $order['Remark'] = $comments;
			$inn_name = $this->db->query("SELECT inn_name FROM inns WHERE inn_id = ".$inn_id)->row()->inn_name;
			$order_Info['name'] = '['.$inn_name.'充值订单]';
			$order_Info['order_num'] = array($order['order_num']);
			$order_Info['total'] = $order['total'];
			$PayInfo = $this->model->getPayInfo($order_Info);
			
            if($this->model->insert($order,'orders'))
			{
				$this->_echoJson(array('code' => '1','msg' => $PayInfo));
			}
			else
			{
				$this-> errorMsg(0,"生成充值订单失败！");
			}
        } else {
            $this->load->model('finance_model');
            $data = $this-> finance_model -> search_recharge_records($inn_id,build_limit(0,100));
            $account_balance = $this-> inns_model->get_inns_account_balance($inn_id);
            $this->viewData = array(
                'balance' => $account_balance,
                'totalAmout' => $data['totalAmount']
            );
        }
	}

	/**
	 * 查看当前用户的提现记录
	 */
	public function cashout()
	{
		$this->moduleTag = 'innsCashout';
		$inn_id = $this->model->getUserInnId();
		$page = $this->input->get('page')? $this->input->get('page'):'1';
		$perpage = DEFAULT_PERPAGE;
		$data = $this-> model -> search_cash_apply_by_inns(build_limit($page, $perpage), $inn_id);

		$account = $this-> inns_model->get_inns_account_balance($inn_id,true);
		$pageInfo = array(
			'total' => $data['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $data['total']/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data['data'],
			'account' => $account,
			'totalAmout' => $data['totalAmount'],
			'pageInfo' => $pageInfo
		);
	}

   /**
	* 驿栈账户管理
	* 访问权限：innholder only
	*/
	public function assets()
	{
		$this->moduleTag = 'innsAssets';
		$users = array();
		$inn_id = $this->get_inn_id_by_innholder();
		$users = $this->model->get_smanagers_by_inn_id($inn_id);
		$this->viewData = array(
			'users' => $users	
		);
	}

	/**
	 * 修改密码
	 */
	public function changepwd()
	{
		$this->model->updatePassWord($this->input->post());
	}

   /**
    * 商户信息修改页面
	* 需要判断权限
    */
	public function editinfo()
	{
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		if(!$this->check_inn_id_in_controller($inn_id))
		{
			response_code('1018');
		}
		$inninfo = $this->model->get_inn_info_by_inn_id($inn_id);
		if(is_post())
		{
			$changeinn = $this->check_inn_info();
			$changedkeys = array_diff_assoc($changeinn,$inninfo);
			if($changedkeys)
			{
				$done['inn_id'] = $inn_id;
				$done['user_id'] = $this->get_user_id();
				$done['inn_name'] = $inninfo['inn_name'];
				if(!$this->model->update_inn_info($changedkeys,$done))
				{
					response_code('-1');
				}
			}
			response_code('1');
		}
		$this->moduleTag = 'innInfo';
		$this->viewData = array(
			'inninfo' => $inninfo	
		);
	}

	private function check_inn_info()
	{
		$innInfo = array();
		$innInfo['inn_name'] = check_empty(trimall(strip_tags($this->input->post('inn_name',TRUE))),FALSE,'1010');
		$innInfo['dest_id'] = input_int($this->input->post('dest_id'),1,FALSE,FALSE,'1011');
		$innInfo['local_id'] = input_int($this->input->post('local_id'),1,FALSE,FALSE,'1012');
        $profit = check_empty($this->input->post('profit'),FALSE,'1013');
		$innInfo['profit'] = sprintf("%.2f", $profit);
		if($innInfo['profit']<0||$innInfo['profit']>100)
		{
			response_code('1013');
		}
		$innInfo['inner_contacts'] = check_empty(trimall(strip_tags($this->input->post('inner_contacts'))),FALSE,'1014');
		$innInfo['inner_moblie_number'] = input_mobilenum($this->input->post('inner_moblie_number'),'1015');	//默认为用户账号（手机号）

		$bdlon = number_format(check_empty($this->input->post('bdlon'),FALSE,'1016'),7,'.',"");
        $bdlat = number_format(check_empty($this->input->post('bdlat'),FALSE,'1016'),7,'.',"");
		$gps = BD09LLtoWGS84($bdlon,$bdlat);
		$innInfo['lon'] = $gps[0];
		$innInfo['lat'] = $gps[1];
		$innInfo['bdgps'] = $bdlon.','.$bdlat;

	/*	$innInfo['bank_info'] = check_empty(trimall(strip_tags($this->input->post('bank_info'))),FALSE,'1017');
		$innInfo['bank_account_no'] = input_num(trimall($this->input->post('bank_account_no')),FALSE,FALSE,FALSE,'1018');
		$innInfo['bank_account_no'] = check_luhn($innInfo['bank_account_no'],'1018');
		$innInfo['bank_account_name'] = check_empty(trimall(strip_tags($this->input->post('bank_account_name'))),FALSE,'1019');
	*/
		$innInfo['bank_info'] = $this->input->post('bank_info',TRUE);
		$innInfo['bank_account_no'] = $this->input->post('bank_account_no',TRUE);
		$innInfo['bank_account_name'] = $this->input->post('bank_account_name',TRUE);

		$innInfo['inner_telephone'] = check_empty(trimall(strip_tags($this->input->post('inner_telephone'))),'');
		$innInfo['inn_address'] = check_empty(trimall(strip_tags($this->input->post('inn_address'))),FALSE,'1020');

		return $innInfo;
	}

	/**
    * 本控制器内需要验证$inn_id的地方 
	* return bool
	*/
	private function check_inn_id_in_controller($inn_id)
	{
		$role = $this->get_user_role();
		if($inn_id)
		{
			switch($role)
			{	
				case ROLE_INNHOLDER:
					return $inn_id == $this->get_user_inn_id();
				case ROLE_CUSTOM_SERVICE:
				case ROLE_ADMIN:
					return TRUE;
					break;
				case ROLE_TREASURER:
				default:
					return FALSE;
					break;
			}
		}
		else
		{
			switch($role)
			{
				case ROLE_INNHOLDER:
				case ROLE_CUSTOM_SERVICE:
					return $this->get_user_inn_id();
				case ROLE_ADMIN:
				case ROLE_TREASURER:
				default:
					return FALSE;
					break;
			}
		}
		return FALSE;
	}
}