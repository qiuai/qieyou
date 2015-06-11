<?php

class Home_model extends MY_Model {

	public $loadmemcache = TRUE;

	public function get_user_quan($user_id,$quanID = FALSE)
	{
		$cond = array(
			'table' => 'user_quan as uq',
			'fields' => 'uq.id as coupon_id,uq.quan_id,uq.type,uq.amount,uq.start_time,uq.end_time,uq.overdue,uq.use_time,uq.create_time,cc.quan_name',
			'where' => array(
				'user_id' => $user_id
			),
			'join' => array(
				'cash_coupon as cc',
				'cc.quan_id = uq.quan_id'
			),
			'order_by' => 'overdue DESC id DESC'
		);
		$rs = $this->get_all($cond);
		if($rs&&$quanID)
		{
			$user_quan = array();
			foreach($rs as $key => $row)
			{
				$user_quan[$row['quan_id']] = $row;
			}
			return $user_quan;
		}
		return $rs;
	}

	public function get_user_address($user_id)
	{
		$cond = array(
			'table' => 'user_address',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
				'is_delete' => 0
			),
			'order_by' => 'is_default DESC address_id DESC'
		);
		return $this->get_all($cond);
	}
		
	public function get_user_address_by_id($user_id,$address_id)
	{
		$cond = array(
			'table' =>'user_address',
			'fields' => '*',
			'where' => array(
				'address_id' => $address_id,
				'user_id' => $user_id,
				'is_delete' => 0
			)
		);
		return $this->get_one($cond);
	}
	
	public function get_user_identify($user_id)
	{
		$cond = array(
			'table' => 'user_identify',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id,
				'is_delete' => 0
			),
			'order_by' => 'is_default DESC identify_id DESC'
		);
		return $this->get_all($cond);
	}
		
	public function get_user_identify_by_id($user_id,$identify_id)
	{
		$cond = array(
			'table' =>'user_identify',
			'fields' => '*',
			'where' => array(
				'identify_id' => $identify_id,
				'user_id' => $user_id,
				'is_delete' => 0
			)
		);
		return $this->get_one($cond);
	}

	public function create_user_data($type,$data,$setDefault = FALSE)
	{
		$data['create_time'] = TIME_NOW;
		if($type == 'address')
		{
			$rs = $this->insert($data,'user_address');

		}
		else if($type=='identify')
		{
			$rs = $this->insert($data,'user_identify');
		}		
		if($rs&&$setDefault)
		{
			$this-> set_user_default($type,$rs,$data['user_id']);
		}
		return $rs;
	}

	public function update_user_data($type,$data,$class_id)
	{
		if($type == 'address')
		{
			$data['address_id'] = $class_id;
			$cond = array(
				'table' => 'user_address',
				'primaryKey' => 'address_id',
				'data' => $data
			);
		}
		else if($type=='identify')
		{
			$data['identify_id'] = $class_id;
			$cond = array(
				'table' => 'user_identify',
				'primaryKey' => 'identify_id',
				'data' => $data
			);
		}
		else
		{
			return FALSE;
		}
		return $this->update($cond);
	}
	
	public function delete_user_data($type,$data_id)
	{
		if($type == 'address')
		{
			$cond = array(
				'table'	=> 'user_address',
				'primaryKey' => 'address_id',
				'data' => array(
					'address_id' => $data_id,
					'is_delete' => 1
				)
			);
		}
		else if($type =='identify')
		{
			$cond = array(
				'table'	=> 'user_identify',
				'primaryKey' => 'identify_id',
				'data' => array(
					'identify_id' => $data_id,
					'is_delete' => 1
				)
			);
		}
		else
		{
			return FALSE;
		}
		return $this->update($cond);
	}
	
   /**
    * 设置用户子表默认值
	* return bool
	*/
	public function set_user_default($type,$class_id,$user_id)
	{
		if($type == 'address')
		{
			$this->db->query('UPDATE `user_address` SET is_default = 0 WHERE `user_id` = '.$user_id);
			$cond = array(
				'table' => 'user_address',
				'primaryKey' => 'address_id',
				'data' => array(
					'address_id' => $class_id,
					'is_default' => '1'
				)
			);
		}
		else if($type == 'identify')
		{
			$this->db->query('UPDATE `user_identify` SET is_default = 0 WHERE `user_id` = '.$user_id);
			$cond = array(
				'table' => 'user_identify',
				'primaryKey' => 'identify_id',
				'data' => array(
					'identify_id' => $class_id,
					'is_default' => '1'
				)
			);
		}
		else
		{
			return FALSE;
		}
		return $this->update($cond);
	}

   /**
    * 通过local_id 获取china_area表信息
	* 关联省市
	**/
	public function get_local_info($local_id)
	{
		$local = $this->modelMemcache->get('localInfo'.$local_id);
		if(!$local)
		{
			$cond = array(
				'table' => 'china_area',
				'fields' => 'area_id,extends,layer,name',
				'where' => array(
					'area_id' => $local_id
				)
			);
			$city = $this->get_one($cond);
			if($city)
			{
				$cond['fields'] = 'area_id,layer,name';
				$cond['where'] = 'area_id IN ('.$city['extends'].')';
				$ext = $this->get_all($cond);
				if($ext[0]['layer'] == '1')
				{
					$local['sheng'] = $ext[0];
					$local['shi'] = $ext[1];
				}
				else
				{
					$local['sheng'] = $ext[1];
					$local['shi'] = $ext[0];
				}
				unset($cond['extents']);
				$local['city'] = $city;
				$this->modelMemcache->set('localInfo'.$local_id,$local,FALSE,86400);
			}
		}
		return $local;
	}

	public function get_user_product_fav($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'favorite_product as f',
			'fields' => 'f.product_id,p.product_name,p.content,p.state,p.price,p.old_price,p.category,p.thumb,p.score,p.comments,p.quantity,p.bought_count,inn.lat,inn.lon,inn.bdgps',
			'where' => array(
				'f.user_id' => $user_id
			),
			'join' => array(
				array(
					'products as p',
					'p.product_id = f.product_id'
				),
				array(
					'inns as inn',
					'inn.inn_id = p.inn_id'
				)
			),
			'order_by' => 'f.id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_user_inn_fav($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'favorite_inn',
			'fields' => 'id,inn_id,create_time',
			'where' => array(
				'user_id' => $user_id
			),
			'order_by' => 'id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		$data = $this->get_all($cond,$pagerInfo);
		if($data)
		{
			$inn_ids = array();
			foreach($data as $key => $row)
			{
				$inn_ids[] = $row['inn_id'];
			}
			$inn = $this->model->get_inn_info_by_ids(implode(',',$inn_ids));
			foreach($data as $key => $row)
			{
				$data[$key] = array_merge($row,$inn[$row['inn_id']]);
			}
		}
		return $data;
	}

	public function get_user_pointlist_by_user_id($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'user_point',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id
			),
			'order_by' => 'id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_local_list($city_id)
	{
		$cond = array(
			'table' => 'china_area',
			'fields' => 'area_id,name',
			'where' => array(
				'parent_id' => $city_id
			)
		);
		return $this->get_all($cond);
	}

	public function get_user_inn_by_inn_id($inn_id,$detail = FALSE)
	{
		$cond = array(
			'table' => 'inns as i',
			'fields' => '*',
			'where' => array(
				'i.inn_id' => $inn_id
			)
		);
		if($detail)
		{
			$cond['join'] = array(
				'inn_shopfront as isp',
				'isp.inn_id = i.inn_id'
			);
		}
		return $this->get_one($cond);
	}

	public function get_account_records_by_inn_id($inn_id,$last_id,$limit)
	{
		$cond = array(
			'table'	=> 'account_records',
			'fields' => 'record_id,record_type,order_num,amount,comments,create_time',
			'where' => 'inn_id = '.$inn_id.'',
			'order_by' => 'record_id DESC',
			'limit' => $limit,
			'offset' => 0
		);
		if($last_id)
		{
			$cond['where'].= ' AND record_id < '.$last_id;
		}
		
		return $this->get_all($cond);
	}

	public function get_mouth_transflow($inn_id,$month_start,$month_end)
	{
		$cond = array(
			'table'	=> 'account_records',
			'fields' => 'record_type,amount,create_time',
			'where' => 'inn_id = '.$inn_id.' AND create_time >= '.$month_start.' AND create_time < '.$month_end.'',
		);
		$rs = $this->get_all($cond);
		$res = array('cashin' => 0 , 'cashout' => 0);
		if(!$rs)
		{
			return $res;
		}

		foreach($rs as $key => $row)
		{
			if(in_array($row['record_type'],array('sell','agent','recharge')))	//'sell','agent','cashout','refund','buy','recharge'
			{
				$res['cashin'] += $row['amount'];
			}
			else
			{
				$res['cashout'] += $row['amount'];
			}
		}
		return $res;
	}

	public function get_sys_quan()
	{
		$cond = array(
			'table' => 'cash_coupon',
			'fields' => 'quan_id,quan_name,amount,require,quantity,total,start_time,end_time',
			'where' => 'end_time > '.TIME_NOW.' AND is_delete = 0 AND is_public = 1',
			'order_by' => 'quan_id DESC'
		);
		return $this->get_all($cond);
	}

	public function get_cash_coupon_by_id($quan_id)
	{
		$cond = array(
			'table' => 'cash_coupon',
			'fields' => '*',
			'where' => array(
				'quan_id' => $quan_id,
				'is_delete' => 0,
			)
		);
		return $this->get_one($cond);
	}

	public function bond_user_quan($quan,$user_id)
	{
		$this->db->trans_start();
		$sql = "UPDATE cash_coupon SET `quantity` = `quantity` + 1 WHERE `quan_id` = ".$quan['quan_id']." LIMIT 1 ";
		$this->db->query($sql);
		$user_quan = array(
			'user_id' => $user_id,
			'quan_id' => $quan['quan_id'],
			'amount' => $quan['amount'],
			'start_time' => $quan['start_time'],
			'end_time' => $quan['end_time'],
			'create_time' => TIME_NOW
		);
		$this->insert($user_quan,'user_quan');
		if($quan['require'])
		{
			$sql = "UPDATE users SET `point` = `point` - ".$quan['require']." WHERE `user_id` = ".$user_id;
			$this->db->query($sql);
			$user_point = array(
				'user_id' => $user_id,
				'action' => '1',
				'content' => '领取代金券',
				'point' => '-'.$quan['require'],
				'create_time' => TIME_NOW
			);
			$this->insert($user_point,'user_point');
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		$this->modelMemcache->delete('detail'.$user_id);
		return TRUE;
	}

	public function get_orders_by_user_id($user_id,$page,$perpage,$state = '')
	{
		$cond =array(
            'table' => 'orders as o',
            'fields' => 'o.order_num,o.state,o.contact,o.telephone,o.create_time,o.total,op.price,op.quantity,op.product_name,op.category,op.product_thumb',
            'where' => array(
				'o.user_id' => $user_id
			),
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
		switch($state)
		{
			case 'O':
				break;
			case 'U':
			case 'P':
				$cond['where'] = 'o.user_id = '.$user_id.' AND o.state IN ("P","U") ';
				break;
			case 'C':
				$cond['where'] = 'o.user_id = '.$user_id.' AND o.state IN ("C","N") ';
				break;
			default:
				$cond['where']['o.state']= $state;
				break;
		}
		return $this->get_all($cond,$pagerInfo);
	}

	public function update_user_info($changed)
	{
		//update cahe
		$changed['update_time'] = $_SERVER['REQUEST_TIME'] ;
		$changed['update_by'] = $changed['user_id'] ;
		$cond = array(
			'table' => 'user_info',
			'primaryKey' => 'user_id',
			'data' => $changed
		);
		if($this->update($cond))
		{
			$this->modelMemcache->delete('user_detail'.$changed['user_id']);
			return TRUE;
		}
		return FALSE;
	}

	public function search_user_mobile($mobile)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id',
			'where' => array(
				'user_mobile' => $mobile
			)
		);
		return $this->get_one($cond);
	}
}