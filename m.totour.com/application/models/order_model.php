<?php

class Order_model extends MY_Model {

	public function get_order_detail_by_order_num($order_num,$user_id,$detail = TRUE)
	{
		$cond = array(
			'table' => 'orders as o',
			'fields' => '*',
			'where' => array(
				'o.user_id' => $user_id,
				'o.order_num' => $order_num
			)
		);
		if($detail)
		{
			$cond['join'] = array(
				'order_products as op',
				'op.order_num = o.order_num'
			);
		}
		return $this->get_one($cond);
	}

	public function get_user_quan($user_id)
	{
		$cond = array(
			'table' => 'user_quan as uq',
			'fields' => 'uq.id as coupon_id,uq.quan_id,uq.type,uq.amount,uq.start_time,uq.end_time,uq.overdue,uq.use_time,uq.create_time,cc.quan_name',
			'where' => array(
				'uq.user_id' => $user_id,
				'uq.overdue' => 0,
				'uq.use_time' => 0
			),
			'join' => array(
				'cash_coupon as cc',
				'cc.quan_id = uq.quan_id'
			),
		); 
		return $this->get_all($cond);
	}

	public function get_order_coupon($order_num)
	{
		$cond = array(
			'table' => 'coupon',
			'fields' => '*',
			'where' => array(
				'order_num' => $order_num
			)
		);
		return $this->get_all($cond);
	}

	public function get_product_by_product_id($product_id,$inn_summary = FALSE)
	{
		$cond = array(
			'table' => 'products as p',
			'fields' => '*',
			'where' =>'p.product_id = '.$product_id.' AND p.state != "D"'
		);
		if($inn_summary)
		{
			$sql = 'SELECT p.*,i.inn_name,i.dest_id,i.local_id,sf.inn_address,sf.inn_summary,sf.inn_head,sf.inner_telephone,sf.inner_moblie_number,i.lon,i.lat,i.bdgps,i.sale_license FROM products as p JOIN inns as i ON p.inn_id = i.inn_id JOIN inn_shopfront as sf ON sf.inn_id = i.inn_id WHERE p.product_id = '.$product_id.' AND p.state != "D"' ;
			return $this->db->query($sql)->row_array();
		}
		return $this->get_one($cond);
	}
	
	public function get_user_address_by_user_id($user_id)
	{
		$cond = array(
			'table' => 'user_address',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id
			),
			'order_by' => 'is_default DESC address_id DESC'
		);
		return $this->get_one($cond);
	}

	public function get_user_identify_by_user_id($user_id)
	{
		$cond = array(
			'table' => 'user_identify',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
			),
			'order_by' => 'is_default DESC identify_id DESC'
		);
		return $this->get_one($cond);
	}

	public function check_user_address($user_id,$address_id)
	{
		$cond = array(
			'table' => 'user_address',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
				'address_id' => $address_id
			)
		);
		return $this->get_one($cond);
	}

	public function check_user_identify($user_id,$identify_id)
	{
		$cond = array(
			'table' => 'user_identify',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
				'identify_id' => $identify_id
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

		if(isset($partner['type']))
		{
			$profiles = array(
				'order_num'	=> $order_num,
				'product_id' => $product['product_id'],
				'partner_id' => isset($partner['id'])?$partner['id']:'',
				'real_name' => isset($partner['real_name'])?$partner['real_name']:'',
				'location' => isset($partner['location'])?$partner['location']:'',
				'address' => isset($partner['address'])?$partner['address']:'',
				'identity_no' => isset($partner['idcard'])?$partner['idcard']:'',
				'create_time' => TIME_NOW,
				'update_time' => TIME_NOW
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

	public function reg_user($mobile)
	{
		$real_user_pass = substr($mobile, -6 );
		$salt = getRandChar(4);
		$userpwd = md5(md5($real_user_pass).$salt);
		$user = array(
			'user_name' => $mobile,
			'user_pass' => $userpwd,
			'salt' => $salt,
			'user_mobile' => $mobile,
			'role' => 'user',
			'create_time' => TIME_NOW
		);
		$this->db->trans_start();
		$user_id = $this->insert($user,'users');
		$userInfo = array(
			'user_id' => $user_id,
			'user_name' => $mobile,
			'nick_name' => '手机用户',
			'last_login_time' => TIME_NOW,
			'last_login_ip' => $_SERVER['REMOTE_ADDR'],
			'create_time' => TIME_NOW,
			'create_by' => $user_id,
			'update_time' => TIME_NOW,
			'update_by' => $user_id
		);
		$this->insert($userInfo,'user_info');
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return $user_id;
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
			if($product['coupon_info'])		//已经消费了券类的无法退款
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			if($product['category'] != 7 && !$product['is_express'] && $order['state'] != 'A')	//券类为消费订单退款
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
		if($order['old_state'] != 'A')		//订单退款
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
    * 添加商品评价
    */
	public function add_product_comment($order_num,$data)
	{
		$arr = array('1'=>'one','2'=>'two','3'=>'three','4'=>'four','5'=>'five');
		$cond = array(
			'table' => 'products',
			'fields' => 'score,comments',
			'where' => array(
				'product_id' => $data['product_id']
			)
		);
		$product =$this->get_one($cond);
		if(!$product)
		{
			return FALSE;
		}

		if(!$product['comments'])		//商品评分统计插入数据
		{
			$product_score[$arr[$data['points']]] = 1;
			$product_score['picture'] = $data['has_pic']?1:0;
			$this->insert($product_score,'product_score');
		}
		else
		{
			$update = $arr[$data['points']].' = '. $arr[$data['points']].'+1';
			$update .= $data['picture']?',picture=picture+1':'';
			$sql='UPDATE product_score SET '.$update.' WHERE product_id='.$row['product_id'];
			$this->db->query($sql); 
		}

		$product_comment = array(
			'product_id' => $data['product_id'],
			'user_id' => $data['user_id'],
			'points' => $data['points'],
			'has_pic' => $data['has_pic']?1:0,
			'note' => $data['note'],
			'picture' => $data['picture'],
			'create_time' => TIME_NOW
		);
		$this->insert($product_comment,'product_comment');  //商品评价表
		
		$avg_score=($product['score']*$product['comments']+$data['points'])/($product['comments']+1); //重新计算平均分
		$sql='UPDATE products SET score='.$avg_score.',comments=comments+1 WHERE product_id='.$data['product_id'];
		$this->db->query($sql); //更新评价平均分,评价人数+1
		
		$sql='UPDATE order_products SET commented = 1 WHERE order_num ='.$order_num.'  AND product_id = '.$data['product_id'];
		$this->db->query($sql);  //更新订单商品已评价
		return TRUE;
	}
}