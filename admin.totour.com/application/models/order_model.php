<?php

class Order_model extends MY_Model {

	public $orderstate = array(
		'A' => '未付款',
		'P' => '已支付', 
		'S' => '订单完成', 
		'R' => '待退款',
		'C' => '已退款',		//付款后退款
		'N' => '已取消',		//未付款状态取消订单 //120分钟过期，自动取消订单
		'U' => '未消费',		//券类未消费商品
	);

   /**
	* 获取某张订单
	*
	* @param int $order_num
	* @return array
	*/
	public function get_order_by_order_num($order_num,$inn_id = '',$detail=FALSE){
		$cond =array(
            'table' => 'orders as o',
            'fields' => '*',
            'where' => array(
                'o.order_num' => $order_num,
				'o.inn_id' => $inn_id
			)
		);
		if(empty($inn_id))
		{
			unset($cond['where']['o.inn_id']);
		}
		if($detail)
		{
			$cond['join'] = array(
				'order_products as op',
				'op.order_num = o.order_num'
			);
		}
		return $this->get_one($cond);
	}

   /**
	* 获取所有订单详细产品
	* @param SQLChar $order_ids 可用于单张
	* @return array
	*/
	public function get_order_detail_by_ids($order_nums)
	{
		$cond =array(
            'table' => 'order_products',
            'fields' => 'order_num,inn_name,product_name,product_id,category,price,quantity,subtotal,start_time,end_time,coupon_info',
            'where' => 'order_num IN ('.$order_nums.')'
		);
		return $this->get_all($cond);
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
	* 获取所有订单
	*
	* @param SQLChar $order_ids 用于查看所有订单的用户
	* @return int
	*/
	public function get_orders($search,$page = 1,$per_page=20,$search_keyword=false)
	{
		$select = "SELECT o.*,i.inn_name FROM ";
		$selectjoin = 'orders as o ';
		$selectjoin .= 'JOIN inns as i ON o.inn_id = i.inn_id ';
		$where = '';
		$orderby = 'ORDER BY o.create_time DESC';
		
		switch($search['key'])
		{
			case 'inn':
				if($search['key_id'])
				{
					$where .= ' o.inn_id = '.$search['key_id'];
				}
			break;
			case 'local':
				$where .= ' i.is_qieyou = 0';
				$where .= ' AND o.order_type = "normal"';
				if($search['key_id'])
				{
					$where .= ' AND i.local_id = '.$search['key_id'];
				}
			break;
			case 'dest':
				$where .= ' i.is_qieyou = 0';
				$where .= ' AND o.order_type = "normal"';
				if($search['key_id'])
				{
					$where .= ' AND i.dest_id = '.$search['key_id'];
				}
			break;
			case 'tuan':
				$where .= ' i.is_qieyou = 0';
				$where .= ' AND o.order_type = "tuan"';
				if($search['key_id'])
				{
					$where .= ' AND i.city_id = '.$search['key_id'];
				}
				else if($search['inn_id'])
				{
					$where .= ' AND o.inn_id = '.$search['key_id'];
				}
			break;	
			case 'qieyou':
				if($search['key_id'])
				{
					$where .= ' i.inn_id = '.$search['key_id'];
				}
			break;
			default:
				$where .= ' o.order_type = "normal"';
			break;
		}
		if($search_keyword['keyword']!=""){
			if($search_keyword['key']=='1'){
				$where .= " AND o.order_num LIKE '%".$search_keyword['keyword']."%'";
			}elseif($search_keyword['key']=='2'){
				$where .= " AND o.telephone LIKE '%".$search_keyword['keyword']."%'";
			}
		}
		if($search['state'])
		{
			$where .= $where?' AND':'';
			$where .= ' o.state = '.$search['state'];
		}
		if($search['st'])
		{
			$where .= $where?' AND':'';
			$where .= ' o.create_time > '.$search['st'];
		}
		if($search['ed'])
		{
			$where .= $where?' AND':'';
			$where .= ' o.create_time < '.$search['ed'];
		}
		if($search['cid'])		//临时需求解决方案
		{
			$selectjoin .= 'JOIN order_products as op ON op.category = '.$search['cid'].' AND op.order_num = o.order_num ';
		}

		$where = $where?'WHERE'.$where.' ':'WHERE 1 ';	//防止db报错

		$totalsql = $selectjoin.$where;
		$total = $this->get_query_count($totalsql);
		$orders = array();
		if($total&&($total>($page-1)*$per_page))
		{
			$limit = build_limit($page, $per_page);
			$sql = $select.$selectjoin.$where.$orderby.$limit;
			$orders = $this->db->query($sql) -> result_array();
		}

		return array( 'total' => $total, 'list' => $orders );
	}
	
	

	public function get_orders_in_dest_ids($dest_ids, $state = '' , $page ,$per_page=20)
	{
		$cond = array(
			'table' => 'orders as o',
			'fields' => 'o.*,i.inn_name',
			'where' => array(
			),
			'join' => array(
				'inns as i',
				'i.dest_id IN('.$dest_ids.') AND o.inn_id = i.inn_id'
			),
			'order_by' => 'o.create_time DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $per_page
		);
		if($state)
		{
			$cond['where']['o.state'] = $state;
		}
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_orders_by_local_ids($local_ids, $state , $page ,$per_page=20)
	{
		$cond = array(
			'table' => 'orders as o',
			'fields' => 'o.*,i.inn_name',
			'where' => array(
			),
			'join' => array(
				'inns as i',
				'i.local_id IN('.$local_ids.') AND o.inn_id = i.inn_id'
			),
			'order_by' => 'o.create_time DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $per_page
		);
		if($state)
		{
			$cond['where']['o.state'] = $state;
		}
		return $this->get_all($cond,$pagerInfo);
	}


	/**
	 * 获取订单日志
	 * @param int $order_num
	 * @param sqlstr @order_by
	 * @return array
	 */
	public function get_order_logs_by_order_num($order_num,$order_by = 'ol.create_time ASC')
	{
		$cond = array(
			'table' => 'order_logs as ol',
			'fields' => 'ol.*,u.user_name',
			'where' => array(
				'ol.order_num' => $order_num,
			),
			'join' => array(
				'users as u',
				'u.user_id = ol.user_id',
				'left'
			),
			'order_by' => $order_by
		);
		return $this->get_all($cond);
	}

   /**
	* 锁定订单
	* @param array $order
	* @param string $comment
	* @return bool
	*/
	public function lock_order($order,$comment)
	{
		$cond = array(
			'table' => 'order_comjb',
			'primaryKey' => 'order_num',
			'data' => array(
				'order_num' => $order['order_num'],
				'lock' => 'Y',
			)
		);
		$this->update($cond);
		$this->w_order_logs($order,'lock order','订单锁定'.(empty($comment)?'':'，理由：'.$comment).'');
		$this->db->query("UPDATE orders SET `is_lock` = 1 WHERE order_num = ".$order['order_num']."");
		return TRUE;
	}

   /**
    * 订单日志
    */
	public function w_order_logs($order,$action,$note)
	{
		$data = array(
			'create_time' => $_SERVER['REQUEST_TIME'],
			'user_id' => $this->getUserId(),
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
		$this->wLog($action, '订单号：<a href="'.base_url().'order/view?oid='.$order['order_num'].'" target="_blank">'.$order['order_num'].'</a> '.$note, $level = 'U', $state = 'S');
	}
	
   /**
	* 解锁订单
	* @param array $order
	* @param string $comment
	* @return bool
	*/
	public function unLock_order($order,$comment)
	{		
		$cond = array(
			'table' => 'order_comjb',
			'primaryKey' => 'order_num',
			'data' => array(
				'order_num' => $order['order_num'],
				'lock' => 'N',
			)
		);
		$this->update($cond);
		$this->w_order_logs($order,'lock order','订单解锁'.(empty($comment)?'':'，理由：'.$comment).'');
		$this->db->query("UPDATE orders SET `is_lock` = 0 WHERE order_num = ".$order['order_num']."");
		return TRUE;
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
			if($product['category'] != 7)
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
		$order['state'] = $order_update['data']['state'];

		$this->update($order_update);
		$this->w_order_logs($order,'refund order','订单取消'.($done['comment']?'，理由：'.$done['comment'].'':''));
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
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function get_order_inninfo_by_inn_id($inn_id) 
	{
		$cond = array(
			'table' => 'inns as i',
			'fields' => 'i.inn_id,i.inn_name,sf.inn_address',
			'where' => array(
				'i.inn_id' => $inn_id
			),
			'join' => array(
				'inn_shopfront as sf',
				'sf.inn_id = i.inn_id'
			)
		);
		return $this->get_one($cond);
	}

	public function get_order_coupon_by_order_num($order_num)
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

	public function create_coupon($order)
	{
		$code = 0;
		$cond = array(
			'table' => 'coupon',
			'fields' => 'id',
			'where' => array(
				'code' => $code,
				'inn_id' => $order['inn_id']
			)
		);
		do{
			$code = $order['category'].mt_rand(10000,99999).substr($order['user_id'],-1,1).mt_rand(10000,99999);
			$cond['where']['code'] = $code;
			$rs = $this->get_one($cond);
		}while($rs != array());
		$data = array(
			'order_num' => $order['order_num'],
			'inn_id' => $order['inn_id'],
			'product_id' => $order['product_id'],
			'code' => $code,
			'create_time' => $_SERVER['REQUEST_TIME'],
			'finish_time' => 0,
			'limit_time' => $order['tuan_end_time']
		);
		return $this->insert($data,'coupon');
	}

   /**
    * 订单支付更新
	* 1、更新订单状态
	* 2、写入订单日志
	* 3、更新小伙伴消费
	* 4、删除comjob任务
	* 5、生成消费券
	* @return bool
	*/
	public function pay($order,$payInfo)
	{
		$cond = array(
			'data' => array(
				'order_id' => $order['order_id'],
				'pay_type' => $payInfo['type'],
				'code' => $payInfo['code'],
				'state' => 'U',
				'pay_time' => $_SERVER['REQUEST_TIME']
			),
			'primaryKey' => 'order_id',
			'table' => 'orders'
		);
		if($order['category']==7)		//保险等实物类不需要提供消费券
		{
			$cond['data']['state'] = 'P';
		}
		$rs = $this->update($cond);

		if(!$rs)
		{
			$data = array(
				'user_id' => '1',
				'action' => 'order pay',
				'note' => 'an error occurred on order payment ! order_num = <a href="/order/view?oid='.$order['order_num'].'">'.$order['order_num'].' </a>',
				'state' => 'S',
				'create_time' => $_SERVER['REQUEST_TIME'],
				'ip_addr' => '0',
				'url' => 'order/payment',
				'event_level' => 'U'
			);
		    $this->insert($data,'sys_logs');
			log_message('error','订单号：'.$order['order_num'].' 支付失败！');
			return FALSE;
		}
		if($order['category']!=7)
		{
			for($i=0;$i<$order['quantity'];$i++)	//创建抵用券
			{
				$this->create_coupon($order);
			}
		}

		$this->db->query("DELETE FROM order_comjb WHERE order_num = ".$order['order_num']);
		log_message('error','订单号：'.$order['order_num'].' 支付成功！');
		
		$data = array(
			'create_time' => $_SERVER['REQUEST_TIME'],
			'user_id' => $order['user_id'],
			'order_num' => $order['order_num'],
			'action' => 'order paid',
			'from_state' => 'A',
			'to_state' => 'P',
			'action_state' => 'Y',
			'from_total' => $order['total'],
			'to_total' => $order['total'],
			'note' => '订单支付完成'
		);
	    $this->insert($data,'order_logs');

		$this->db->query("UPDATE partners SET `expenditure` = `expenditure` + ".$order['total']." WHERE partner_id = ".$order['partner_id']." LIMIT 1");
		
		return TRUE;
	}
}
