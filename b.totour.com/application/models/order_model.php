<?php

class Order_model extends MY_Model {

	public $orderstate = array(
		'A' => '未支付',
		'P' => '已支付', 
		'U' => '待消费',		//券类未消费商品
		'S' => '已完成', 
		'R' => '待退款',
		'C' => '已退款',		//付款后退款
		'N' => '已取消',		//未付款状态取消订单 //120分钟过期，自动取消订单
		'O' => '全部订单'
	);

   /**
	* 获取商户当前订单数
	*
	* @param int $inn_id
	* @return array
	*/
	public function get_current_order($inn_id,$state = array())
	{
		$sql = 'SELECT `state`,count(`order_id`) as num FROM `orders` WHERE (( `inn_id` ='.$inn_id.' AND `inn_del` = "N" ) OR (`seller_inn` = '.$inn_id.' AND `user_del` = "N" ))';
		if($state)
		{
			$sql .= ' AND `state` IN ('.implode(',',$state).')';
		}
		$sql .= ' GROUP BY `state`';
		$rs = $this->db->query($sql)->result_array();
		return $rs;
	}

	public function get_total_order_number($inn_id)
	{
		$number = $this->get_query_count('`orders` WHERE ( `inn_id` ='.$inn_id.' AND `inn_del` = "N" ) OR (`seller_inn` = '.$inn_id.' AND `user_del` = "N" )');
		return $number;
	}

	public function get_order_detail_by_order_num($order_num,$inn_id,$seller_inn = '',$detail = TRUE)
	{
		$cond = array(
			'table' => 'orders as o',
			'fields' => '*',
			'where' => ''
		);
		if($detail)
		{
			$cond['join'] = array(
				'order_products as op',
				'op.order_num = o.order_num'
			);
		}
		if($seller_inn)
		{
			$cond['where'] = 'o.`order_num` = '.$order_num.' AND (o.`inn_id` = '.$seller_inn.' OR o.`seller_inn` = '.$seller_inn.')';
		}
		else
		{
			$cond['where'] = array(
				'o.order_num' => $order_num,
				'o.inn_id' => $inn_id
			);
		}
		return $this->get_one($cond);
	}
	
	public function get_orders_by_inn_id($inn_id,$page,$perpage,$state = '')
	{
		$cond =array(
            'table' => 'orders as o',
            'fields' => 'o.order_num,o.state,o.contact,o.telephone,o.create_time,o.total,op.price,op.quantity,op.product_name,op.category,op.product_thumb',
            'where' => '((o.`inn_id` ='.$inn_id.' AND o.`inn_del` != "Y" ) OR ( o.`seller_inn` = '.$inn_id.' AND o.`user_del` != "Y"))',
			'order_by' => 'o.order_id DESC',
			'join' => array(
				'order_products as op',
				'op.order_num = o.order_num'
			),
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		if($state)
		{
			$cond['where'] .= ' AND o.`state` = "'.$state.'"';
		}
		return $this->get_all($cond,$pagerInfo);
	}

   /**
	* 获取某张订单验证码
	*
	* @param int $order_num
	* @return array
	*/
	public function get_coupon_by_order_num($order_num){
		$cond =array(
            'table' => 'coupon',
            'fields' => '*',
            'where' => array(
                'order_num' => $order_num
			)
		);
		return $this->get_one($cond);
	}

   /**
	* 获取所有订单详细出行人
	*
	* @param SQLChar $order_ids 可用于单张
	* @return array
	*/
	public function get_order_profile_by_ids($order_ids)
	{
		$cond =array(
            'table' => 'order_profiles',
            'fields' => '*',
            'where' => 'order_num IN ('.$order_ids.')',
            'order_by' => 'create_time desc'
		);
		return $this->get_all($cond);
	}

   /**
	* 获取所有订单总数
	*
	* @param SQLChar $order_ids 可用于单张
	* @return int
	*/
	public function get_total_orders($inns_list = array(),$state,$type = 'normal')
	{
		if(empty($inns_list) || $inns_list == 'all')
		{
			$where = "order_type = '".$type."' ";
		}
		elseif(is_array($inns_list))
		{
			$where = "inns_id IN (".implode(',',$inns_list).") AND order_type = '".$type."' ";
		}
		elseif($inns_list == 'empty')
		{
			return 0;
		}
		else
		{
			$where = "inns_id = ".$inns_list." AND order_type = '".$type."' ";
		}
		if($state == 'G')
		{
			if($this->web_user->get_role() !='admin')
			{
				$where .= 'AND state IN (2, 3, 4, 6)';
			}
		}
		else
		{
			$where .= "AND state IN (".$state.")";	
		}

		$sql = 'SELECT COUNT(order_id) as total FROM orders WHERE '.$where;
		return $this->db->query($sql)->row()->total;
	}

   /**
	* 获取所有订单
	*
	* @param SQLChar $order_ids 用于查看所有订单的用户
	* @return int
	*/
	public function get_orders($inns_list = array(),$page = 1 , $state ,$per_page=20, $type = 'normal')
	{
		$cond =array(
            'table' => 'orders',
            'fields' => '*',
            'where' => '',
			'order_by' => 'create_time DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $per_page
		);
		if(empty($inns_list) || $inns_list == 'all')
		{
			$cond['where'] = "order_type = '".$type."' ";
		}
		elseif(is_array($inns_list))
		{
			$cond['where'] = "inns_id IN (".implode(',',$inns_list).") AND order_type = '".$type."' ";
		}
		else
		{
			if($inns_list == 'empty')
			{
				return array();
			}
			else
			{
				$cond['where'] = "inns_id = ".$inns_list." AND order_type = '".$type."' ";
			}
		}
		if($state == 'G')
		{
			if($this->web_user->get_role() !='admin')
			{
				$cond['where'] .= 'AND state IN (2, 3, 4, 6)';
			}
		}
		else
		{
			$cond['where'] .= "AND state IN (".$state.")";	
		}/*
		if($state == 'G')
		{
			$cond['where'] .= "AND state IN (2, 3, 4, 6)";
		}
		else
		{
			$cond['where'] .= "AND state = '".$state."'";
		}*/
		return $this->get_all($cond,$pagerInfo);
	}

	/**
	 * 获取订单日志
	 * @param int $order_num
	 * @param sqlstr @order_by
	 * @return array
	 */
	public function get_order_logs_by_order_num($order_num,$order_by = 'create_time ASC')
	{
		$cond = array(
			'table' => 'order_logs',
			'fields' => '*',
			'where' => array(
				'order_num' => $order_num
			),
			'order_by' => $order_by
		);
		return $this->get_all($cond);
	}

   /**
    * 订单日志
    */
	public function w_order_logs($order,$user,$action,$note)
	{
		$data = array(
			'create_time' => $_SERVER['REQUEST_TIME'],
			'user_id' => $user['user_id'],
			'order_num' => $order['order_num'],
			'action' => $action,
			'from_state' => isset($order['old_state'])?$order['old_state']:$order['state'],
			'to_state' => $order['state'],
			'action_state' => 'Y',
			'from_total' => $order['total'],
			'to_total' => $order['total'],
			'note' => $note
		);
		$this->insert($data,'order_logs');
		$this->wLog($action, '订单号：<a href="'.$this->config->item('base_url').'order/view?oid='.$order['order_num'].'" target="_blank">'.$order['order_num'].'</a> '.$note, $level = 'C', $state = 'S',$user['user_id'],'/order/cencel');
	}

   /**
	* 订单退订
	* @param array $order
	* @param array $done
	* @return bool
	*/
	public function order_cancel($order,$done)
	{
		//校验订单产品有效性
		$cond = array(
			'table' => 'order_products',
			'fields' => '*',
			'where' => array(
				'order_num' => $order['order_num']
			)
		);
		$products = $this->get_all($cond);
		if(!$products)	
		{
			return FALSE;
		}
		$this->db->trans_start();
		foreach($products as $key => $product)	//释放库存  券类需要删除券信息
		{
			if($product['coupon_info'])
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			if($product['category'] != 7 && $order['state'] != 'A')
			{
				$coupon = array(
					'table' => 'coupon',	
					'where' => array(
						'order_num' => $order['order_num']
					)
				);
				$this->delete($coupon);
			}
			$sql = 'UPDATE `products` SET  `quantity` =  `quantity` + '.$product['quantity'].' WHERE  `product_id` = '.$product['product_id'].'';
			$this->db->query($sql);
		}
		$order['old_state'] = $order['state'];

		//订单数组初始化
		$order_update = array(
			'primaryKey' => 'order_id',
			'table' => 'orders',
			'data' => array(
				'order_id' => $order['order_id'],
				'state' => 'R'
			)
		);
		if($order['old_state'] == 'A')
		{
			$order_update['data']['state'] = 'N';
			$order_update['data']['inns_profit'] = 0;
			$order_update['data']['agent_commission'] = 0;
			$order_update['data']['profit'] = 0;
			$order_update['data']['settlement_time'] = $_SERVER['REQUEST_TIME'];
		}
		$order['state'] = $order_update['data']['state'];
		$this->update($order_update);
		$this->w_order_logs($order,$done,'user cancel order','用户取消订单'.($done['comment']?'，理由：'.$done['comment'].'':''));
		if($order['old_state'] != 'A')
		{
			$order_refund = array(
				'apply_user_id' => $done['user_id'],
				'inn_id' => $order['inn_id'],
				'order_num' => $order['order_num'],
				'refund_amount' => $order['total'],
				'state' => 'applying',
				'comments' => $done['comment'],
				'create_time' => $_SERVER['REQUEST_TIME']
			);
			$this->insert($order_refund,'order_refund');
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return TRUE;
	}
	
	public function get_order_inninfo_by_inns_id($inns_id) 
	{
		$sql = 'SELECT inns.inns_id,inns.inns_url,inns.inns_name,inns_shopfront.inns_address,inns_manager.manager_name 
		FROM inns,inns_shopfront,inns_manager 
		WHERE inns_manager.inns_id = '.$inns_id.' AND inns.inns_id = inns_manager.inns_id AND inns_shopfront.inns_id = inns_manager.inns_id';
		return $this->db->query($sql)->row_array();
	}

   /**
    * 获取用户联系人
	* @pram int $user_id
	* @return array()
	*/
	public function get_user_partner_by_user_id($user_id)
	{
		$cond = array(
			'table' => 'partners',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
				'partner_del' => 'N'
			),
			'order by partner_id DESC'
		);
		return $this->get_all($cond);
	}

	public function get_product_detail_by_product_id($product_id)
	{
		$cond = array(
			'table' => 'products as p',
			'fields' => 'p.product_id,p.state,p.quantity,p.inn_id,p.thumb,p.price,p.product_name,p.product_images,p.detail_images,p.note,p.agent,p.purchase_price,p.tuan_end_time,p.category,i.is_qieyou,i.profit,i.inn_name,i.sale_license',
			'where' => array(
				'p.product_id' => $product_id
			),
			'join' => array(
				'inns as i',
				'i.inn_id = p.inn_id AND i.state = "active"'
			)
		);
		return $this->get_one($cond);
	}

   /**
	* 生成驿栈订单号
	* @return string
	*/
	public function create_order_num($user_id)
	{
		$data = array(
			'user_id' => $user_id,
			'time' => $_SERVER['REQUEST_TIME']
		);
		$sql_id = $this->model->insert($data,'create_num');
		if(!$sql_id)
		{
			return FALSE;
		}
		return $sql_id.sprintf("%04d",$user_id);
	}

	/**
	* 流程说明  
	* 1、生成订单号
	* 2、保存订单商品
	* 3、扣除库存
	* 4、保存订单信息
	* 5、加入订单日志
	* 6、加入cornjob
	*/
	public function user_submit_order($order,$product,$partner)
	{
		$order_num = $this->create_order_num($order['user_id']);
		if(!$order_num)
		{
			return array('code' => '-1');
		}
		
		$this->db->trans_start();
		if(!$partner['id'])
		{
			$partner['id'] =  $this->find_partner_id_by_mobile($order['user_id'],$partner['mobile']); //新建判断
			if(!$partner['id'])
			{
				$partners = array(
					'user_id' => $order['user_id'],
					'real_name' => $partner['name'],
					'spell' => $partner['spell'],
					'mobile_phone' => $partner['mobile'],
					'create_time' => $_SERVER['REQUEST_TIME']
				);
				$partner['id'] = $this->insert($partners,'partners');
			}
		}
		if($product['category'] == '7')
		{
			$profiles = array(
				'order_num'	=> $order_num,
				'product_id' => $product['product_id'],
				'partner_id' => $partner['id'],
				'real_name' => $partner['name'],
				'identity_no' => $partner['identity_no'],
				'create_time' => $_SERVER['REQUEST_TIME'],
				'update_time' => $_SERVER['REQUEST_TIME']
			);
			$this->insert($profiles,'order_profiles');
		}

		$order_products = array(
			'order_num' => $order_num,
			'inn_id' => $product['inn_id'],
			'inn_name' => $product['inn_name'],
			'partner_id' => $partner['id'],
			'product_id' => $product['product_id'],
			'product_name' => $product['product_name'],
			'product_thumb' => $product['thumb'],
			'product_images' => $product['product_images'],
			'detail_images' => $product['detail_images'],
			'note' => $product['note'],
			'category' => $product['category'],
			'price' => $product['price'],
			'quantity' => $partner['count'],
			'subtotal' => $product['price']*$partner['count'],
			'user_id' => $order['user_id'],
			'start_time' => isset($partner['start_time'])?$partner['start_time']:0,
			'end_time' => isset($partner['end_time'])?$partner['end_time']:0,
			'tuan_end_time' => $product['tuan_end_time'],
			'update_time' => $_SERVER['REQUEST_TIME']
		);
		$order_product_id = $this->insert($order_products,'order_products');
		
		if(isset($partner['partner']))
		{
			//join order_profiles
		}

		$sql = 'UPDATE `products` SET `quantity` =  `quantity` - '.$partner['count'].' , `bought_count` = `bought_count` +1 WHERE  `product_id` = '.$product['product_id'].';';
		$this->db->query($sql);
		
		if(!$order_product_id)
		{
			return array('code' => '-2');
		}

		switch($product['state'])
		{
			case 'T':
				$order['order_type'] = 'tuan';
				break;
			case 'Y':
				$order['order_type'] = 'normal';
				break;
		}

		$order['order_num'] = $order_num;
		$order['state'] = 'A';
		$order['create_time'] = $_SERVER['REQUEST_TIME'];

		$order_id = $this->insert($order,'orders');
		if(!$order_id)
		{
			return array('code' => '-3');
		}

		$data = array(
			'create_time' => $_SERVER['REQUEST_TIME'],
			'user_id' => $order['user_id'],
			'order_num' => $order_num,
			'action' => 'create order',
			'from_state' => 'A',
			'to_state' => 'A',
			'action_state' => 'Y',
			'from_total' => $order['total'],
			'to_total' => $order['total'],
			'note' => '用户提交了订单'
		);
		$this->insert($data,'order_logs');

		$order_comjb = array(
			'order_num' => $order_num,
			'state' => 'A',
			'to_state' => 'N',
			'create_time' => $_SERVER['REQUEST_TIME'],
			'end_time' => $_SERVER['REQUEST_TIME']+7200
		);
		$this->insert($order_comjb,'order_comjb');
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return $order_num;
	}

	public function find_partner_id_by_mobile($user_id,$mobile)
	{
		$cond = array(
			'table'	=> 'partners',
			'fields' => 'partner_id',
			'where' => array(
				'user_id' => $user_id,
				'mobile_phone' => $mobile,
				'partner_del' => 'N'
			),
			'key' => 'partner_id'
		);
		return $this->get_one($cond);
	}

	public function get_order_by_coupon_code($code,$inn_id)
	{
		$cond = array(
			'table' => 'coupon as c',
			'fields' => 'c.*',
			'where' => array(
				'c.inn_id' => $inn_id,
				'c.code' => $code
			)
		);
		return $this->get_one($cond);
	}
	
	private function sendSMS($message)
	{	
		$options['accountsid'] = $this->config->item('sms_ucpaas_sid');
		$options['token'] = $this->config->item('sms_ucpaas_token');
		$this->load->library('sms_ucpaas',$options);

		$sms_ucpaas_sid = $this->config->item('sms_ucpaas_appid');
		switch($message['type'])
		{
			case 'subCoupon':		//成功购买优惠券
				$templateId = '5935';
				break;
			default:
				exit;
		}
		$param = implode(',',$message['param']);
		$rs = $this->sms_ucpaas->templateSMS($sms_ucpaas_sid,$message['mobile'],$templateId,$param);
		log_message('error',$rs);
		return $rs;
	}

	public function settlement_coupon_order($order,$coupon)
	{
		//对order_products 表写入券使用情况  在coupon表删除
		$coupon_info = array();
		if($order['coupon_info'])
		{
			$coupon_info = json_decode($order['coupon_info'],TRUE);
		}
		$coupon_info[] = array(
			'code' => $coupon['code'],
			'time' => $_SERVER['REQUEST_TIME']
		); 
		$coupon_info = json_encode($coupon_info);
		$order_products = array(
			'data' => array(
				'id' => $order['id'],
				'coupon_info' => $coupon_info,
			),
			'primaryKey' => 'id',
			'table' => 'order_products',
			'id' => $order['id']
		);
		$this->update($order_products);

		$sql = 'DELETE FROM coupon WHERE id = '.$coupon['id'];
		$this->db->query($sql);
		$cond = array(
			'table' => 'inn_shopfront',
			'fields' => 'inner_moblie_number,inner_telephone',
			'where' => array(
				'inn_id' => $order['inn_id']
			)
		);
		$inninfo = $this->get_one($cond);
		$phone = $inninfo['inner_telephone']?$inninfo['inner_telephone']:$inninfo['inner_moblie_number'];
		$message = array(
			'type' => 'subCoupon',
			'mobile' => $order['telephone'],
			'param' => array(
				$order['product_name'],$coupon['code'],date('m-d H:i')//,$phone,'4008857171'
			)
		);
		$this->sendSMS($message);
		//校验是否仍有优惠券未使用
		$cond = array(
			'table' => 'coupon',
			'fields' => '*',
			'where' => array(
				'order_num' => $order['order_num'],
				'inn_id' => $order['inn_id']
			)
		);
		$rs = $this->get_total($cond);
		
		if($rs != 0)
		{
			return TRUE;
		}
		//订单已完成	处理订单信息

		$data = array(
			'data' => array(
				'order_id' => $order['order_id'],
				'settlement_time' => $_SERVER['REQUEST_TIME'],
				'state' => 'S'
			),
			'primaryKey' => 'order_id',
			'table' => 'orders'
		);

		$this->update($data);

		$data = array(
			'create_time' => $_SERVER['REQUEST_TIME'],
			'user_id' => 0,
			'user_name' => '系统',
			'order_num' => $order['order_num'],
			'action' => 'create order',
			'from_state' => 'U',
			'to_state' => 'S',
			'action_state' => 'Y',
			'from_total' => $order['total'],
			'to_total' => $order['total'],
			'note' => '订单消费成功，欢迎您再次光临'
		);
		$this->insert($data,'order_logs');

		$this->db->query("UPDATE inns SET `account` = `account` + ".$order['inns_profit'].",`order_divide`=`order_divide`+".$order['inns_profit']." WHERE inn_id = ".$order['inn_id']."");	//订单收益分成
		$balance = $this->db->query('SELECT `account` FROM `inns` WHERE inn_id = '.$order['inn_id'])->row()->account;
		$accountRecords = array (
			'inn_id' => $order['inn_id'],
			'record_type' => 'sell',
			'order_num' => $order['order_num'],
			'amount' => $order['inns_profit'],
			'balance' => $balance, 
			'comments' => $order['product_name'],
			'create_time' => $_SERVER['REQUEST_TIME'],
			'create_by' => 0
		);
		$this->insert($accountRecords,'account_records');

		if($order['seller_inn'] && $order['agent_commission'])
		{
			$this->db->query("UPDATE inns SET `account` = `account` + ".$order['agent_commission']." , `balance_divide`=`balance_divide`+".$order['agent_commission']." WHERE inn_id = ".$order['seller_inn']."");	//代预订佣金
			$balance = $this->db->query('SELECT account FROM `inns` WHERE inn_id = '.$order['seller_inn'])->row()->account + $order['agent_commission'];
			$accountRecords = array (
				'inn_id' => $order['seller_inn'],
				'record_type' => 'agent',
				'order_num' => $order['order_num'],
				'amount' => $order['agent_commission'],
				'balance' => $balance, 
				'comments' => $order['product_name'],
				'create_time' => $_SERVER['REQUEST_TIME'],
				'create_by' => $order['user_id']
			);
			$this->insert($accountRecords,'account_records');
		}
		return TRUE;
	}
}