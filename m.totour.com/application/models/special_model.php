<?php

class Special_model extends MY_Model {
	
	public function get_inns($search,$order_by,$limit = '')
	{
		$select = 'SELECT i.inn_id,i.dest_id,i.local_id,i.inn_name,i.lon,i.lat,i.bdgps,i.create_time,
			it.inn_head,it.features,it.inn_summary,it.inn_address,it.inner_moblie_number,it.inner_telephone ';
		$selectjoin = 'FROM inns as i ,inn_shopfront as it ';
		$where = 'WHERE ';
		
		$order_by = 'ORDER BY i.create_time DESC ';

		if(!empty($search['key_word']))
		{
			$wherequery = ' i.inn_name like "%'.$search['key_word'].'%" ';
		}
		else
		{
			return array();
		}
		$where .= $wherequery;
		return $this->db->query($select.$selectjoin.$where.$order_by.$limit)->result_array();
	}

	public function get_products($search,$order_by,$limit = '')
	{
		$select = 'SELECT p.product_id,p.product_name,p.price,p.old_price,p.score,p.comments,p.content,p.thumb,p.quantity,p.bought_count,p.category,p.category_id,i.lon,i.lat,i.bdgps ';
		$selectjoin = 'FROM products as p ';
		$selectjoin .= 'JOIN inns as i ON i.inn_id = p.inn_id ';
		$where = 'WHERE ';
		if($order_by == 'local')
		{
			if(empty($search['lat'])||empty($search['lon']))
			{
				return array();
			}
			$select .= ',(POWER(ABS(i.lon - '.$search['lon'].'),2) + POWER(ABS(i.lat - '.$search['lat'].'),2)) AS distance ';
			$order_by = 'ORDER BY distance';
		}
		else
		{
			$order_by = 'ORDER BY p.'.$order_by;
		}

		$wherequery = '';
		
		if($search['state'] == 'A')
		{
			$wherequery .= ' p.state IN (1,3) ';
		}
		else
			$wherequery .= ' p.state = "'.$search['state'].'" ';

		if(!empty($search['category_id']))
		{
			$wherequery .= 'AND p.category_id = '.$search['category_id'].' ';
		}
		else
		{
			if(!empty($search['category']))
			{
				$wherequery .= 'AND p.category = '.$search['category'].' ';
			}
		}
		
		if(!empty($search['sid']))
		{
			$wherequery .= 'AND p.inn_id = "'.$search['sid'].'" ';
		}
		else
		{
			/*目的地区域 优先local*/
			if(!empty($search['local_id']))
			{
				$wherequery .= 'AND i.local_id = '.$search['local_id'].' ';
			}
			else
			{
				if(!empty($search['dest_id']))
				{
					$wherequery .= 'AND i.dest_id = '.$search['dest_id'].' ';
				}
				else
				{
					if(!empty($search['city_id']))
					{
						$wherequery .= 'AND i.city_id = '.$search['city_id'].' ';
					}
				}
			}
		}
		
		if(!empty($search['key_word']))
		{
			$wherequery .= ' AND p.product_name like "%'.$search['key_word'].'%" ';
		}
		else
		{
			if(!empty($search['today']))
			{
				$wherequery .= ' AND p.create_time > '.strtotime("today").' ';
			}
		}
		$where .= $wherequery;
		return $this->db->query($select.$selectjoin.$where.$order_by.$limit)->result_array();
	}

	public function get_category()
	{
		$cat = $this->localMemcache->get('sysCategory');
		if($cat)
		{
			return $cat;
		}
		$cond = array(
			'table' => 'product_category',
			'fields' => '*',
			'where' => array()
		);
		$cat = array();
		$rs = $this->model->get_all($cond);
		foreach($rs as $key => $row)
		{
			$cat['title'][] = $row;
		}
		$cond = array(
			'table' => 'product_categories',
			'fields' => 'category_id,category,name',
			'where' => array(
				'del' => '0'
			),
		);
		$list = $this->model->get_all($cond);
		foreach($list as $key => $row)
		{
			$cat['list'][$row['category']][] = $row;
		}
		$this->localMemcache->set('sysCategory',$cat,FALSE,1800);
		return $cat;
	}

	public function get_local($city_id)
	{
		$local = $this->localMemcache->get('sysLocal'.$city_id);
		if($local)
		{
			return $local;
		}
		$cond = array(
			'table' => 'china_dest',
			'fields' => 'dest_id,dest_name',
			'where' => array(
				'parent_id' => $city_id,
				'is_display' => 'Y'
			),
		);
		$local = array();
		$rs = $this->model->get_all($cond);
		if(!$rs)
		{
			$this->localMemcache->set('sysLocal'.$city_id,array(),FALSE,1800);
			return array();
		}
		foreach($rs as $key => $row)
		{
			$local['title'][] = $row;
			$dests[] = $row['dest_id'];
		}
		$cond = array(
			'table' => 'china_dest_local',
			'fields' => 'local_id,local_name,dest_id',
			'where' => 'dest_id IN ('.implode(',',$dests).')',
		);
		$list = $this->model->get_all($cond);
		foreach($list as $key => $row)
		{
			$local['list'][$row['dest_id']][] = $row;
		}
		$this->localMemcache->set('sysLocal'.$city_id,$local,FALSE,1800);
		return $local;
	}

	public function check_inn_fav($class_id,$user_id)
	{
		$cond = array(
			'table' => 'favorite_inn',
			'fields' => 'id',
			'where' => array(
				'inn_id' => $class_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}

	public function inn_fav($act,$inn_id,$user_id,$inn_info)
	{
		if($act == 'like')
		{
			if($this->db->query('UPDATE `inn_shopfront` SET `inn_fav` = `inn_fav` + 1 WHERE `inn_id` = '.$inn_id))
			{
				$like = array(
					'inn_id' => $inn_id,
					'user_id' => $user_id,
					'dest_id' => $inn_info['dest_id'],
					'local_id' => $inn_info['local_id'],
					'create_time' => TIME_NOW
				);
				$this->insert($like,'favorite_inn');
				return TRUE;
			}
		}
		else
		{
			if($this->db->query('UPDATE `inn_shopfront` SET `inn_fav` = `inn_fav` - 1 WHERE `inn_id` = '.$inn_id))
			{	
				$cond = array(
					'table' => 'favorite_inn',
					'where' => array(
						'inn_id' => $inn_id,
						'user_id' => $user_id
					)
				);
				$this->delete($cond);
				return TRUE;
			}
		}
		return FALSE;
	}
}