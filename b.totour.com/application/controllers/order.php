<?php

class Order extends MY_Controller {

	public function __construct() {
		parent::__construct();
		//$this->check_token();
		$this->token['user_id'] = 1003;
	}

	public function myInn()
	{
		$data = array(
			'A' => '0',		//待付款
			'R' => '0',		//待退款
			'U' => '0',		//待消费
			'O' => '0'		//全部订单
		);
		
		$data['O'] = $this->model->get_total_order_number($this->token['inn_id']);
		if($data['O'])
		{
			$rs = $this->model->get_current_order($this->token['inn_id'],array("'A'","'R'","'U'"));
			if($rs)
			{
				foreach($rs as $key => $row)
				{
					$data[$row['state']] = $row['num'];
				}
			}
		}
		response_data($data);
	}
	
	public function get()
	{
		$page = input_int($this->input->get('page'),1,FALSE,FALSE,'1015');					//分页
		$perpage = input_int($this->input->get('perpage'),1,FALSE,FALSE,'1016');			//分页
		$state = input_string($this->input->get('state'),array('A','P','S','R','C','N','U','O'),FALSE,'3003'); //排序方法 默认创建时间最新
		if($state == 'O')
		{
			$state = '';
		}
		$orders = $this->model->get_orders_by_inn_id($this->token['inn_id'],$page, $perpage,$state);
		
		response_data($orders);
	}

	public function detail()
	{
		$order_num = input_num($this->input->get('oid'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,'',$this->token['inn_id']);
		if(!$order)
		{
			response_msg('3002');
		}
		
		$cancel_able = '0';
		if(in_array($order['state'],array('A','P','U'))&&$this->token['inn_id'] == $order['seller_inn'])
		{
			$cancel_able = '1';
		}
		$data = array(
			'order_num' => $order['order_num'],
			'order_type' => $order['order_type'],
			'order_state' => $order['state'],
			'order_paytime' => $order['pay_time'],
			'order_total' => $order['total'],
			'order_create_time' => $order['create_time'],
			'order_coupon' => $order['coupon_info'],
			'order_cancel_able' => $cancel_able,
			'contact_name' => $order['contact'],
			'contact_telephone' => $order['telephone'],
			'product_category' => $order['category'],
			'product_name' => $order['product_name'],
			'product_thumb' => $order['product_thumb'],
			'product_price' => $order['price'],
			'product_quantity' => $order['quantity'],
			'settlement_time' => $order['settlement_time']?$order['settlement_time']:'',
			'profit' => 0,
			'agent_profit' => 0,
			'qieyou_profit' => 0
		);
		if($order['state'] == 'S')
		{
			if($order['inn_id'] == $this->token['inn_id'])
			{
				$data['profit'] = $order['inns_profit'];
				$data['agent_profit'] = $order['agent_commission'];
				$data['qieyou_profit'] = $order['profit'];
			}
			else
			{
				$data['profit'] = $order['agent_commission'];
				$data['agent'] = $order['agent_commission'];
				$data['qieyou_profit'] = $order['profit'];
			}
		}
		response_data($data);
	}
	
	public function cartSubmit()
	{
		$pid = input_int($this->input->get('pid'),1,FALSE,FALSE,'2008');
		$this->_LoadModel('product');
		$product = $this->product_model->get_product_by_id($pid);
		if(!$product)
		{
			response_msg('2009');
		}
		$partners = $this->model->get_user_partner_by_user_id($this->token['user_id']);
		$data = array(
			'product' => $product,
			'partners' => $partners
		);
		response_data($data);
	}

   /**
	* 订单退订入口
	*/
	public function cancel() 
	{	
		$order_num = input_num($this->input->get('oid'),10000,FALSE,FALSE,'3001');
		$comment = trimall($this->input->post('comment',TRUE));
		$order = $this->model->get_order_detail_by_order_num($order_num,'',$this->token['inn_id'],FALSE);
		if(!$order||$order['seller_inn'] != $this->token['inn_id'])	//订单不存在 代售方可退款
		{
			response_msg('3002');
		}
		if(!in_array($order['state'] ,array('A','P','U')))	//订单不可退款
		{
			response_msg('3004');
		}
		$done = array(
			'user_id' => $this->token['user_id'],
			'comment' => $comment
		);
		if($this->model->order_cancel($order,$done))
		{
			response_msg('1');
		}
		response_msg('4000');
	}

	public function pay()
	{
		$order_num = input_num($this->input->get('oid'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,'',$this->token['inn_id']);
	//	print_r($order);exit;
	//	$order = $this->model
	/*	$payInfo = array(
			"service" => 'create_direct_pay_by_user',
			"partner" => $this->config->item('partner', 'alipay'),
			"payment_type"	=> '1',
			"notify_url"	=> $this->config->item('default_callback') . 'alipay/',
			"return_url"	=> $this->data['array']['Return'],
			"seller_email"	=> $this->config->item('seller_email', 'alipay'),
			"out_trade_no"	=> $trans_id,
			"subject"	=> $this->data['array']['SourceDesc'],
			"total_fee"	=> number_format($due/100, 2, '.', ''),
			"body"	=> $this->data['array']['SourceDesc'],
			"show_url"	=> '',
			"_input_charset"	=> $this->config->item('input_charset', 'alipay')
		);*/
		$data = array(
			'order'	=> $order
		);
		if($order['state'] =='A')
		{
			$this->load->view('pay/order_pay',$data);
		}
		else if($order['state'] == 'P'||$order['state'] == 'U')
		{
			$this->load->view('pay/pay_result',array('res' => TRUE));
		}
		else //订单已经付款 或无法付款
		{
			$this->load->view('pay/pay_result',array('res' => FALSE));
		}
	}

	public function submitOrder()	//设置用户下单3秒间隔 B端写入session
	{
		$pid = input_int($this->input->post('pid'),1,FALSE,FALSE,'2008');
		$count = input_int($this->input->post('count'),1,FALSE,FALSE,'2008');
		$product = $this->model->get_product_detail_by_product_id($pid);
		
		if(!$product)
		{
			response_msg('2009');
		}
		if(!$product['sale_license']||$product['state'] =='N' || $product['state'] =='D' || $product['quantity'] < $count )
		{
			response_msg('3009');
		}
		if($product['tuan_end_time'] < $_SERVER['REQUEST_TIME'])
		{
			response_msg('3010');
		}

		$partner['id'] = input_int($this->input->post('pn_id'),1,FALSE,0);
		$partner['name'] = input_empty($this->input->post('pn_name'),'3005');
		$partner['mobile'] = input_mobilenum($this->input->post('pn_mobile'),'3006');
		if($product['category'] == '7')
		{
			$partner['identity_no'] = input_identity_number($this->input->post('pf_identity_no'),'3013');
		}

		$partner['count'] = $count;

		$order['user_id'] = $this->token['user_id'];
		$order['contact'] = $partner['name'];
		$order['telephone'] = $partner['mobile'];
		$order['inn_id'] = $product['inn_id'];
		$order['seller_inn'] = $product['inn_id'] == $this->token['inn_id']?0:$this->token['inn_id'];
		$order['total'] = $count*$product['price'];
		
		//且游收益计算 ：如果是且游的商品 看是否有代售 代售则分配佣金 price-agent * count   不是且游商品看分佣比例
		if($product['is_qieyou'])
		{
			$order['inns_profit'] = 0;
			$order['agent_commission'] = $order['seller_inn']?($product['agent']*$count):0;
		}
		else if($product['state'] == 'T')
		{
			if($product['inn_id'] == $this->token['inn_id'])	//商户购买自己的团购
			{
				$order['inns_profit'] = ($product['purchase_price']+$product['agent'])*$count;
				$order['agent_commission'] = 0;
			}
			else	//B端不存在没有销售方的问题
			{
				$order['inns_profit'] = $product['purchase_price']*$count;
				$order['agent_commission'] = $product['agent']*$count;
			}
		}
		else //非团购商品 按照预设商户商铺抽税
		{
			$order['inns_profit'] = $order['total']*$product['profit']/100;
			$order['agent_commission'] = 0;
		}
		$order['profit'] = $order['total']-$order['agent_commission']-$order['inns_profit'];
		
		// 拼音转换
		$this->load->library('spell');
		$spell = new spell();
		$order['spell'] = $spell->getAllPY(iconv ( 'utf-8' , 'gb2312' , $order['user_name'] ),' ');
		$rs = $this->model->user_submit_order($order,$product,$partner);
		
		if($rs)
		{
			$rs = array( 'code' => '1','msg'=> $rs);
			response_data($rs);
		}
		response_msg('3007');
	}

	public function submitCoupon()
	{
		$code = input_num($this->input->post('coupon'),100000000000,700000000000,FALSE,'3011');
		$coupon = $this->model->get_order_by_coupon_code($code,$this->token['inn_id']);
		if(!$coupon)
		{
			response_msg('3011');
		}
		if($coupon['limit_time'] < $_SERVER['REQUEST_TIME'] )
		{
			response_msg('3012');
		}

		$order = $this->model->get_order_detail_by_order_num($coupon['order_num'],$this->token['inn_id'],'');

		if($order['state'] !='U')
		{
			//系统异常
		}
		
		if($this->model->settlement_coupon_order($order,$coupon))
		{

			response_data($order['order_num']);
		}
		response_msg('4000');
	}
	
	private function sendSMS($message)
	{	
		$options['accountsid'] = $this->config->item('sms_ucpaas_sid');
		$options['token'] = $this->config->item('sms_ucpaas_token');
		$this->load->library('sms_ucpaas',$options);

		$sms_ucpaas_sid = $this->config->item('sms_ucpaas_appid');
		switch($message['type'])
		{
			case 'regUser':		//注册提醒
				$templateId = '5171';
				break;
			case 'forgotUser':	//忘记密码
				$templateId = '5199';
				break;
			case 'bondMobile':	//绑定手机
				$templateId = '5200';
				break;
			default:
				exit;
		}
		$param = implode(',',$message['param']);
		return $this->sms_ucpaas->templateSMS($sms_ucpaas_sid,$message['mobile'],$templateId,$param);
	}
	
	// 客户管理列表
	public function customer(){
		$page = input_int($this->input->get('page'),1,FALSE,FALSE,'1015');					//分页
		$perpage = input_int($this->input->get('perpage'),1,FALSE,FALSE,'1016');			//分页
		$search = $this->input->get('search');
		
		$this->load->model('partners_model');
		$data = $this->partners_model->get_list($this->token['user_id'],$page, $perpage,$search);
		
		response_data($data);
	}
	
	// 客户备注操作
	public function customersaveNote(){
		$partner_id = $this->input->get('partner_id');
		$note = $this->input->get('note');
		$this->load->model('partners_model');
		if($this->partners_model->saveNote($this->token['user_id'],$partner_id,$note)){
			response_msg('1');	// 成功
		}
		response_msg('-1'); // 失败
	}
}
