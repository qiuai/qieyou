<?php

class Order extends MY_Controller {

	public $layout_for_title = '我的订单';
	public $layout = 'payment';
	
	public function index()
	{
		show_404();
	}

	public function view()
	{
		$user_id = $this->get_user_id(TRUE);
		$order_num = input_num($this->input->get('oid'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id);
		if(!$order)
		{
			response_code('3001');
		}
		$inninfo = $this->model->get_inn_info_by_inn_id($order['inn_id']);
		$unspend_coupon = array();
		if(!in_array($order['state'],array('A','N','C','S'))&&!$order['is_express']&&$order['category'] != 7)	//查询消费券
		{
			$unspend_coupon = $this->model->get_order_coupon($order['order_num']);
		}
		$this->moduleTag = '订单详情';
		$this->layout = 'simple';
		$this->viewData = array(
			'backUrl' => base_url().'home/order',
			'order' => $order,
			'inninfo' => $inninfo,
			'unspend_coupon' => $unspend_coupon
		);
	}

	public function confirm()
	{
		$product_id = input_int($this->input->get('pid'),1,FALSE,FALSE,'2001');
		$product = $this->model->get_product_by_product_id($product_id);
		if(!$product)
		{
			response_code('2001');
		}
		$user_address = array();
		$user_identify = array();
		$user_id = $this->get_user_id();
		if($user_id)
		{
			if($product['category'] == 7)
			{
				$user_identify = $this->model->get_user_identify_by_user_id($user_id);
			}
			else if($product['is_express'])
			{
				$user_address = $this->model->get_user_address_by_user_id($user_id);
			}
		}
		$this->viewData = array(
			'product' => $product,
			'user_address' => $user_address,
			'user_identify' => $user_identify
		);
        setcookie('returnurl', base_url().'order/confirm?pid='.$this->input->get('pid'), time()+86400, '/');
	}

	public function payment()
	{
		$user_id = $this->get_user_id(TRUE);
		$order_num = input_num($this->input->get('order'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id);
		if(!$order)
		{
			response_code('3001');
		}
		if($order['state'] != 'A')
		{
			response_code('3004');
		}
		$inn = array();
		$quan = $this->model->get_user_quan($user_id);
		$inn_id = $this->get_user_inn_id();
		if($inn_id)
		{
			$inn = $this->model->get_inn_info_by_inn_id($inn_id,FALSE);
		}
		$this->viewData = array(
			'order' => $order,
			'quan' => $quan,
			'inn' => $inn
		);
	}

   /**
	* 订单退订入口
	*/
	public function cancel() 
	{	
		$order_num = input_num($this->input->post('order'),10000,FALSE,FALSE,'3001');
		$comment = trimall(strip_tags($this->input->post('comment',TRUE)));
		$user_id = $this->get_user_id(TRUE);
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id,FALSE);
		if(!$order||$order['user_id'] != $user_id)	//订单不存在 代售方可退款
		{
			response_code('3009');
		}
		if(!in_array($order['state'] ,array('A','P','U')))	//订单不可退款
		{
			response_code('3010');
		}
		$done = array(
			'user_id' => $user_id,
			'comment' => $comment
		);
		if($this->model->order_cancel($order,$done))
		{
			response_code('1');
		}
		response_code('4000');
	}

	public function submit()
	{
		//下单步骤
		//1、验证商品id  验证商品数量
		//2、验证用户登录状态  登录则通过商品需求信息验证用户信息   否则进入注册用户阶段   将新注册的用户与相关信息绑定
		//3、生成订单阶段	（商品信息  用户id 放入model）
		//4、返回订单号

		$product_id = input_int($this->input->post('pid'),1,FALSE,FALSE,'2001');
		$count = input_int($this->input->post('count'),1,FALSE,FALSE,'2015');
		$product = $this->model->get_product_by_product_id($product_id,TRUE);
		if(!$product)
		{
			response_code('2001');
		}
		if($product['state'] =='N' || $product['state'] =='D' || $product['tuan_end_time'] < TIME_NOW)
		{
			response_code('2008');
		}
		if($product['quantity'] < $count )
		{
			response_code('2009');
		}

		$user_id = $this->get_user_id();
		$address = array();
		$identify = array();
		if(!$user_id)	//是否正确的验证码
		{
			$regName = input_mobilenum($this->input->post('mobile'),'5001');
			$regIdentify = input_int($this->input->post('identify'),1000,9999,FALSE,'5002');
			$mobile = $this->get_current_data('check_mobile');
			$mobile_identify = $this->get_current_data('mobile_identify');
			if($mobile != $regName || $mobile_identify != $regIdentify)	//手机号 验证码验证
			{
				response_code('5002');
			}
			
			if($product['is_express'])	//需要物流的实物商品
			{
				$address = $this->check_user_data('address');
			}
			else if($product['category'] == '7')
			{
				$identify = $this->check_user_data('identify');
			}
			//验证完商品之后 开始注册用户
			$user_id = $this->model->reg_user($mobile);
			if(!$user_id)
			{
				response_code('5002');
			}
			$session = array(
				'user_id' => $user_id,
				'user_name' => $mobile,
				'nick_name' => '手机用户',
			);
			$this->set_current_data($session);
			// 完成登录 判断是否需要写入 联系表
			if($address)
			{
				$address['user_id'] = $user_id;
				$address['create_time'] = TIME_NOW;
				$address['mobile'] = $mobile;
				$address['is_default'] = '1';
				$address_id = $this->model->insert($address,'user_address');
			}
			else if($identify)
			{
				$identify['user_id'] = $user_id;
				$identify['create_time'] = TIME_NOW;
				$identify['is_default'] = '1';
				$identify_id = $this->model->insert($identify,'user_identify');	
			}
		}
		else
		{
			if($product['is_express'])	//需要物流的实物商品
			{
				$address_id = input_int($this->input->post('address_id'),1,FALSE,FALSE,'3002');
				$address = $this->model->check_user_address($user_id,$address_id);
				if(!$address)
				{
					response_code('3002');
				}
			}
			else if($product['category'] == '7')
			{
				$identify_id = input_int($this->input->post('identify_id'),0,FALSE,FALSE,'3003');
				$identify = $this->model->check_user_identify($user_id,$identify_id);
				if(!$identify)
				{
					response_code('3003');
				}
			}
			$mobile = $this->get_current_data('band_mobile');
		}

		//信息验证完毕 开始订单流程
		
		
		$partner = array();		//用于存储订单附加信息 如收货地址 数量 姓名等

		$order['telephone'] = $mobile;
		if(!empty($address['real_name']))		//如果是实物订单
		{
			$order['contact'] = $address['real_name'];
			$order['telephone'] = $address['mobile'];
			$partner = $address;
			$partner['type'] = 'address';
		}
		else if(!empty($identify['real_name']))		//如果是保险订单
		{
			$order['contact'] = $identify['real_name'];
			$partner = $identify;
			$partner['type'] = 'identify';
		}

		$order['user_id'] = $user_id;
		$order['inn_id'] = $product['inn_id'];
		$order['total'] = $count*$product['price'];
		$partner['count'] = $count;
		$partner['id'] = $user_id;
		
		//C端所有商品为团购商品 不考虑商户代售
		if($product['is_qieyou'])
		{
			$order['inns_profit'] = 0;
			$order['agent_commission'] = 0;
		}
		else
		{
			$order['inns_profit'] = $product['purchase_price']*$count;
			$order['agent_commission'] = 0;
		}
		$order['profit'] = $order['total']-$order['agent_commission']-$order['inns_profit'];
	
		$rs = $this->model->user_submit_order($order,$product,$partner);
		
		if($rs)
		{
			response_json('1',$rs);
		}
		response_code('3007');
	}

	private function check_user_data($type)
	{
		if($type == 'address')
		{
			$address = array();
			$address['real_name'] = check_empty(trimall(strip_tags($this->input->post('real_name'))),FALSE,'1014');
			$address['location_id'] = input_int($this->input->post('local_id'),100000,1000000,FALSE,'1015');
			$address['address'] = check_empty(trimall(strip_tags($this->input->post('address'))),FALSE,'1016');
			$this->load->model('home_model');
			$local = check_empty($this->home_model->get_local_info($address['location_id']),FALSE,'1015');
			$address['location'] = $local['sheng']['name'].$local['shi']['name'].$local['city']['name'];
			return $address;
		}
		else if($type == 'identify')
		{
			$identify = array();
			$identify['real_name'] = check_empty(trimall(strip_tags($this->input->post('real_name'))),FALSE,'1014');
			$identify['idcard'] = input_identity_number($this->input->post('idcard'),'1019');
			return $identify;
		}
		else
		{
			response_code('4001');
		}
	}

   /**
    * 订单商品评价
	* 
    **/
	public function comment()
	{
		$user_id = $this->get_user_id(TRUE);
		$order_num = input_num($this->input->get('order'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id,FALSE);
		if(!$order)
		{
			response_code('3001');
		}
		$commented = ''; 
		if($order['commented']){	//(存在的话隐藏提交按钮）
			$commented=1;
		}
		$this->viewData = array(
			'commented' => $commented,
		);
	}

   /**
    * 商品评价提交
	* 
    **/
	public function commentPost()
	{
		$user_id = $this->get_user_id(TRUE);

		$order_num = input_num($this->input->post('order'),10000,FALSE,FALSE,'3001');
		$data['points']= input_int($this->input->post('stars'),1,5,FALSE,'3011');
		$data['note'] = $this->input->post('note');
		$data['picture'] = rtrim($this->input->post('images'),',');
		if(!$data['picture']&&!$data['note'])
		{
			response_code('3012');
		}
		$data['has_pic']= $data['picture']?1:0;
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id);
		if(!$order)
		{
			response_code('3001');
		}
		if($order['commented'])
		{
			response_code('3013');
		}

		$data['user_id']= $order['user_id'];
		$data['product_id']= $order['product_id'];

		$rs=$this->model->add_product_comment($order_num,$data);
		if($rs)
		{
			response_code('1');
		}
		response_code('4000');
	}

   /**
    * 订单支付
    **/
	public function pay()
	{
		$user_id = $this->get_user_id(TRUE);
		$order_num = input_num($this->input->get('order'),10000,FALSE,FALSE,'3001');
		$order = $this->model->get_order_detail_by_order_num($order_num,$user_id);
		if(!$order)
		{
			response_code('3001');
		}
		if($order['state']!== 'A' || ($order['create_time'] < TIME_NOW -7200))	//未支付订单
		{
			response_code('3004');
		}
		header("Location: http://www.totour.com/trans/payCenter?from=Wap_C_Phone&oid=".$order_num."");
		exit;
	}
}