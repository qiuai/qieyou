<?php

class Order extends MY_Controller {

	public $controllerTag = 'order';
	public $moduleTag = 'orderList';

	public function __construct() {
		parent::__construct();
		$this->cklogin();
	}
	/**
	 * 订单中心入口
	 */
	public function index()
	{
		$this->moduleTag = 'orderList';
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);
		$state = input_string($this->input->get('state'),array('all','paid','finished','refund','refunded','unpaid','cancel','waiting'),'all');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$cid = input_int($this->input->get('cid'),1,FALSE,0);

		$orders = array('list'=> array(),'total' => 0);
		$orderstate = array('all' => 0 , 'unpaid'=> 1 , 'paid' => 2, 'finished' => 3, 'refunded' => 4, 'cancel' => 5 , 'refund' => 6 ,'waiting'=> 7);

		$per_page = 20;
		
		$search = array(
			'cid' => $cid,
			'state' => $orderstate[$state],
			'st' => $starttime,
			'ed' => $endtime
		);
		$arr = $this->model->get_localArr($inn_id,$local_id,$dest_id);
		$destInfo = $arr['destInfo'];
		$localArr = $arr['localArr'];
		$Innlist = $arr['Innlist'];

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
		
		/* 搜索订单号、手机*/
		$key=$this->input->get('key');
		$keyword= $this->input->get('keyword');
		$search_keyword=array(
			'key'=>$key,
			'keyword'=>$keyword
		);
		$orders = $this->model->get_orders($search,$page,$per_page,$search_keyword);

		/*******统一数据获取函数******** inns_list 可以为 error、单个驿栈id、驿栈id数组*/
		
		/**************页面载入相关信息处理**************/
		$total = $orders['total'];	
		$order_products = array();
		$orderlist = array();
		if($total){
			$orderview = $this->orderview( $orders);
			$orderlist = $orderview['orders'];
			$order_products = $orderview['order_products'];
		}
		$pageInfo = array(
			'total' => $total,
			'perpage' => $per_page,
			'curpage' => $page,
			'totalpage' => $total/$per_page,
			'url' => makePageUrl()
		);
		$this->viewData = array(
			'orders' => $orderlist,						//最近订单
			'order_products' => $order_products,		//最近订单产品
			'pageInfo' => $pageInfo,
			'Innlist' => $Innlist,
			'state' => $state,
			'destInfo' => $destInfo,
			'starttime' => $starttime,
			'endtime' => $endtime,
			'localArr' => $localArr,
			'cid' => $cid
		);
	}

   /**
	* 且游团购订单
	*/
	public function tuan()
	{
		$this->moduleTag = 'tuanList';
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);
		$state = input_string($this->input->get('state'),array('all','paid','finished','refund','refunded','unpaid','cancel','waiting'),'all');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$cid = input_int($this->input->get('cid'),1,FALSE,0);
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$city_id = $this->get_user_city_id();
		
		$per_page = 20;
		
		$orders = array('list'=> array(),'total' => 0);
		$orderstate = array('all' => 0 , 'unpaid'=> 1 , 'paid' => 2, 'finished' => 3, 'refunded' => 4, 'cancel' => 5 , 'refund' => 6 ,'waiting'=> 7);
		$search = array(
			'key' => 'tuan',
			'key_id' => $city_id,
			'sid' => $inn_id,
			'cid' => $cid,
			'state' => $orderstate[$state],
			'st' => $starttime,
			'ed' => $endtime
		);
		/* 搜索订单号、手机*/
		$key=$this->input->get('key');
		$keyword= $this->input->get('keyword');
		$search_keyword=array(
			'key'=>$key,
			'keyword'=>$keyword
		);
		$orders = $this->model->get_orders($search,$page,$per_page,$search_keyword);	

		/**************页面载入相关信息处理**************/
		$total = $orders['total'];	
		$order_products = array();
		$orderlist = array();
		if($total){
			$orderview = $this->orderview( $orders);
			$orderlist = $orderview['orders'];
			$order_products = $orderview['order_products'];
		}
		$pageInfo = array(
			'total' => $total,
			'perpage' => $per_page,
			'curpage' => $page,
			'totalpage' => $total/$per_page,
			'url' => makePageUrl()
		);
		$this->viewData = array(
			'orders' => $orderlist,						//最近订单
			'order_products' => $order_products,		//最近订单产品
			'pageInfo' => $pageInfo,
			'state' => $state,
			'cid' => $cid,
			'starttime' => $starttime,
			'endtime' => $endtime
		);
	}

   /**
	* 且游订单
	*/
	public function qieyou()
	{
		$this->controllerTag = 'qieyou';
		$this->moduleTag = 'qieyouList';
		$starttime = input_int($this->input->get('st'),1000000000,2000000000,0);
		$endtime = input_int($this->input->get('ed'),1000000000,2000000000,0);

		$state = input_string($this->input->get('state'),array('all','paid','finished','refund','refunded','unpaid','cancel','waiting'),'all');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$cid = input_int($this->input->get('cid'),1,FALSE,0);
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = $this->get_user_inn_id();

		$per_page = 20;
		
		$orders = array('list'=> array(),'total' => 0);
		$orderstate = array('all' => 0 , 'unpaid'=> 1 , 'paid' => 2, 'finished' => 3, 'refunded' => 4, 'cancel' => 5 , 'refund' => 6 ,'waiting'=> 7);
		$search = array(
			'key' => 'qieyou',
			'key_id' => $inn_id,
			'sid' => '',
			'cid' => $cid,
			'state' => $orderstate[$state],
			'st' => $starttime,
			'ed' => $endtime
		);
		$key=$this->input->get('key');
		$keyword= $this->input->get('keyword');
		
		$search_keyword=array(
			'key'=>$key,
			'keyword'=>$keyword
		);
		$orders = $this->model->get_orders($search,$page,$per_page,$search_keyword);
		
		/**************页面载入相关信息处理**************/
		$total = $orders['total'];	
		$order_products = array();
		$orderlist = array();
		if($total){
			$orderview = $this->orderview( $orders);
			$orderlist = $orderview['orders'];
			$order_products = $orderview['order_products'];
		}
		$pageInfo = array(
			'total' => $total,
			'perpage' => $per_page,
			'curpage' => $page,
			'totalpage' => $total/$per_page,
			'url' => makePageUrl()
		);
		$this->viewData = array(
			'orders' => $orderlist,						//最近订单
			'order_products' => $order_products,		//最近订单产品
			'pageInfo' => $pageInfo,
			'state' => $state,
			'cid' => $cid,
			'starttime' => $starttime,
			'endtime' => $endtime
		);
	}

	public function orderview($orders){
		$order_nums = '';
		$order_products = array();
		$orderlist = $orders['list']; 
		if($orders['list'])
		{ 
			foreach($orderlist as $key => $val)
			{
				$order_nums .= $val['order_num'].',';
				$order_products[$val['order_num']] = array('product_name'=>'','price'=>'','quantity'=>'');
				$orderlist[$key]['state'] = '<span class="'.$val['state'].'">'.$this->model->orderstate[$val['state']].'</span>';
			}
			$order_nums = rtrim($order_nums,',');
			$products = $this->model->get_order_detail_by_ids($order_nums);
			foreach($products as $key => $val)
			{
				$order_products[$val['order_num']]['product_name'] .= '<p class="tl">'.$val['product_name'].'</p>';
				$order_products[$val['order_num']]['price'] .= '<p><cite>¥'.number_format($val['price'],2).'</cite></p>';
				$order_products[$val['order_num']]['quantity'] .= '<p>'.$val['quantity'].'</p>';
			}
		}
		return array('orders'=>$orderlist, 'order_products'=>$order_products);
	}

	/**
	 * 订单修改入口
	 */
	public function edit()
	{
	}

   /**
	* 订单退订入口
	* ajax POST
	*/
	public function cancel() 
	{	
		$order_num = input_num($this->input->post('order_num'),10000);
		$comment = check_empty($this->input->post('comment',TRUE),FALSE,'备注不能为空！');
		if(!$this->check_order_manage_auth_in_controller())
		{
			response_code('-1','权限验证失败！');
		}
		$order = $this -> check_order_exist_post($order_num);
		if(!in_array($order['state'],array('P','U')))
		{
			response_code('-2','当前订单不能退订');
		}
		$done = array(
			'user_id' => $this->get_user_id(),
			'comment' => $comment
		);
		$rs = $this->model->order_cancel($order,$done);
		response_code($rs?'1':'4000');
	}

   /**
	* 锁定订单入口
	
	public function order_lock()
	{
		$order_num = $this->input->post('order_num');
		$comment = $this->input->post('comment');
		$order = $this -> check_order_exist_post($order_num);
		if(!$this->check_order_manage_auth_in_controller($order['inn_id']))
		{
			$this-> jsonAjax('-3','权限验证失败！');
		}
		if($this-> model->lock_order($order,$comment))
		{
			$this-> jsonAjax('1','Success');
		}
		$this-> jsonAjax('-4','修改失败！');
	}
	*/
   /**
	* 解锁订单入口
	*/
	public function order_unlock()
	{
		$order_num = $this->input->post('order_num');
		$comment = $this->input->post('comment');
		$order = $this -> check_order_exist_post($order_num);
		if(!$this->check_order_manage_auth_in_controller($order['inn_id']))
		{
			$this -> jsonAjax('-3',"权限验证失败！");
		}
		if($this-> model->unLock_order($order,$comment))
		{
			$this -> jsonAjax('1',"Success");
		}
		$this -> jsonAjax('-4',"修改失败！");
	}

   /**
	* 单张订单查看入口
	*/
	public function view()
	{
		$order_num = rtrim($this->input->get('oid'));
		$order = $this->check_order_exist($order_num);
		if(!$this->check_order_view_auth_in_controller($order['inn_id']))
		{
			$this-> _jsBack('权限验证失败！');
		}
		if($this->get_user_city_id() && $order['inn_id'] == $this->get_user_inn_id())
		{
			$this->controllerTag = 'qieyou';
			$this->moduleTag = 'qieyouList';
		}
		else if($order['order_type'] == 'tuan')
		{
			$this->moduleTag = 'tuanList';
		}
		
		//基础变量
		$user_ids = array();
		$userinfo = array();
		$order_coupon = array();
		$order_profiles = array();
        
		$order_products = $this->model->get_order_detail_by_ids($order_num);
		if($order_products[0]['category'] == '7')	//保险存在时获取订单参与者信息
		{
			$order_profiles = $this->model->get_order_profile_by_ids($order_num);
		}
		$order_logs = $this->model->get_order_logs_by_order_num($order_num);

		foreach($order_logs as $key => $row)
		{
			$user_ids[$row['user_id']] = $row['user_id'];
		}
		if(isset($user_ids['0'])){ unset($user_ids['0']); }
		if($user_ids)
		{
			$users = $this->model->get_user_info_in_ids(implode(',',$user_ids),'ui.user_id,ui.user_name,ui.real_name',FALSE,TRUE);
			foreach($users as $key => $row)
			{
				$userinfo[$row['user_id']]['real_name'] = $row['real_name']?$row['real_name']:$row['user_name'];
			}
		}
		$userinfo['0']['real_name'] = '系统';
		$userinfo['user_id']=$row['user_id'];   

		$order_inninfo = $this->model->get_order_inninfo_by_inn_id($order['inn_id']);
		
		if($order['seller_inn'])
		{
			$order['seller']['inn'] = $this->model->get_inn_info_by_inn_id($order['seller_inn']);
		}
		if($order['state'] == 'U')
		{
			$order_coupon = $this->model->get_order_coupon_by_order_num($order['order_num']);
		}
		
		$order['order_state'] = $this->model->orderstate[$order['state']];
		$this->viewData = array (
			'order'	=> $order,
			'order_coupon' => $order_coupon,
			'order_products' => $order_products,
			'order_profiles' => $order_profiles,
			'order_logs' => $order_logs,
			'userinfo' => $userinfo,
			'order_inninfo' => $order_inninfo
		);
	}

   /**
    * 订单控制器 订单浏览权限
	* @return bool
	*/
	private function check_order_view_auth_in_controller($inn_id)
	{
		$user_role = $this->get_user_role();
		switch($user_role)
		{
			case ROLE_CUSTOM_SERVICE:
			case ROLE_TREASURER:
			case ROLE_ADMIN:
				return TRUE;
			default:
				return FALSE;
				break;
		}
		return FALSE;
	}

   /**
    * 订单控制器 操作订单权限
	* @return bool
	*/
	private function check_order_manage_auth_in_controller($inn_id = 0)
	{
		$user_role = $this->get_user_role();
		switch($user_role)
		{
			case ROLE_CUSTOM_SERVICE:
			case ROLE_TREASURER:
			case ROLE_ADMIN:
				return TRUE;
			default:
				return FALSE;
				break;
		}
	}
    
   /**
    * 订单有效性验证 ajax
	* @return array
    */
	private function check_order_exist_post($order_num) 
	{
		if(empty($order_num)||!preg_match('/^\d*$/',$order_num))
		{
			$this -> jsonAjax('-1','参数错误！');
		}
		$order = $this->model->get_order_by_order_num($order_num);
		if(empty($order))
		{
			$this -> jsonAjax('-2','订单不存在！');
		}
		return $order;
	}

   /**
    * 订单有效性验证 get
	* @return array
    */
	private function check_order_exist($order_num) 
	{
		if(empty($order_num)||!preg_match('/^\d*$/',$order_num))
		{
			$this -> _jsBack('参数错误！');
		}
		$order = $this->model->get_order_by_order_num($order_num);
		if(empty($order))
		{
			$this -> _jsBack('订单不存在！');
		}
		return $order;
	}

	
   /**
    * 测试跳过支付流程的接口
    
	public function passpay()
	{
		$order_num = $this->input->get('oid');
	
		$order = $this->order_model->get_order_by_order_num($order_num,'',TRUE);
		$payInfo = array(
			'type' => 'alipay',
			'code' => '36513230697843656'
		);
		$rs = $this->model->pay($order,$payInfo);
		
		print_r($order);exit;
		if(empty($order_num))
		{
			show_404();
		}
		if(strstr(',',$order_num))
		{
			$orders = implode(',',$order_num);
			foreach($orders as $key => $val)
			{
				if(!preg_match('/^\d{19}$/',$val))
				{
					$this->_jsBack('参数错误！');
				}
			}
		}
		$order = $this->model->search_user_order($order_num,0,TRUE);
		if(!$order)
		{
			$this->_jsBack('订单不存在或已被删除！');
		}
		
		if($this->model->pay(strstr(',',$order_num)?$order_num:array($order_num)))
		{
			$this->_jsBack('订单状态更新成功！');
		}
		else
		{
			$this->_jsBack('订单状态更新失败！');
		}
	}*/
}