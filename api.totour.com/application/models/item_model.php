<?php

class Item_model extends MY_Model {
	
	public function get_products($search,$order_by,$limit)
	{
		if($order_by == 'local')
		{
			$cond = array(
				'table' => 'products',
				'fields' => '*',
				'where' => array(
					'state' => 'Y'
				),
				'order_by' => $order_by
			);
			$local_info;
			return array();		//位置搜索
		}	
		
		$select = 'SELECT p.product_id,p.product_name,p.is_qieyou,p.price,p.old_price,p.purchase_price,p.agent,p.content,p.thumb,p.gallery,p.quantity,p.bought_count,p.category,i.inn_name,i.lon,i.lat,i.bdgps FROM ';
		$selectjoin = 'products as p ';
		$selectjoin .= 'JOIN inns as i ON i.inn_id = p.inn_id ';
		$where = 'WHERE ';
		$order_by = 'ORDER BY p.'.$order_by;
		
		$wherequery = '';
		if(!empty($search['sid']))
		{
			$wherequery .= 'AND p.inn_id = "'.$search['sid'].'" ';
		}
		if($search['state'])
		{
			if($search['state'] == 'A')
			{
				$wherequery .= 'AND p.state IN (1,3) ';
			}
			else
				$wherequery .= 'AND p.state = "'.$search['state'].'" ';
		}
		if($search['category'])
		{
			$wherequery .= 'AND p.category = '.$search['category'].' ';
		}
		if($search['category_id'])
		{
			$wherequery .= 'AND p.category_id = '.$search['category_id'].' ';
		}
		if($search['local_id'])
		{
			$wherequery .= 'AND i.local_id = '.$search['local_id'].' ';
		}
		$where .= $wherequery?ltrim($wherequery,'AND'):' 1 ';
		return $this->db->query($select.$selectjoin.$where.$order_by.$limit)->result_array();
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
	
	public function get_rand_product_by_inn_category($inn_id,$category)
	{
		$sql = 'SELECT product_id,product_name,thumb,price,old_price FROM products WHERE inn_id = '.$inn_id.' AND state = "t" ORDER BY rand() LIMIT 3';
		return $this->db->query($sql)->result_array();
	}

   /**
	* 二维数组排序
	* @param array $arr
	* @param $keys 键值
	* @return array 
	*/
	public function array_sort($arr,$keys,$type='asc'){
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}
		else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}

	public function get_comment_by_product_id($product_id,$act,$page,$perpage)
	{
		$cond = array(
			'table' => 'product_comment as pc',
			'fields' => 'pc.*,ui.user_name,ui.headimg',
			'where' => 'product_id = '.$product_id.'',
			'join' => array(
				'user_info as ui',
				'ui.user_id = pc.user_id'
			)
		);
		switch($act)
		{
			case 'all':
				break;
			case 'good':
				$cond['where'] .= ' AND pc.points IN (4,5)';
				break;
			case 'between':
				$cond['where'] .= ' AND pc.points IN (2,3)';
				break;
			case 'bad':
				$cond['where'] .= ' AND pc.points = 1';
				break;
			case 'pic':
				$cond['where'] .= ' AND pc.has_pic = 1';
				break;
		}
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		return $this->get_all($cond,$pagerInfo);
	}
	
	public function check_comment_fav($comment_id,$user_id)
	{
		$cond = array(
			'table' => 'favorite_product_comment',
			'fields' => 'id',
			'where' => array(
				'comment_id' => $comment_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}

	public function comment_fav($act,$comment_id,$user_id)
	{
		if($act == 'like')
		{
			$like = array(
				'comment_id' => $comment_id,
				'user_id' => $user_id,
				'create_time' => TIME_NOW
			);
			if($this->insert($like,'favorite_product_comment'))
			{
				$this->db->query('UPDATE product_comment SET `likeNum` = `likeNum` + 1 WHERE comment_id = '.$comment_id);
				return TRUE;
			}
		}
		else{
			$cond = array(
				'table' => 'favorite_product_comment',
				'where' => array(
					'comment_id' => $comment_id
				)
			);
			if($this->delete($cond))
			{
				$this->db->query('UPDATE product_comment SET `likeNum` = `likeNum` - 1 WHERE comment_id = '.$comment_id);
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function reply_comment(){
		
	}	
	
	public function get_comment_score_by_product_id($product_id)
	{
		$cond = array(
			'table' => 'product_score',
			'fields' => '*',
			'where' => array(
				'product_id' => $product_id
			)
		);
		return $this->get_one($cond);
	}

	public function get_comment_detail($comment_id)
	{
		$cond = array(
			'table' => 'product_comment as pc',
			'fields' => 'pc.*,ui.nick_name,ui.headimg',
			'where' => array(
				'pc.comment_id' => $comment_id
			),
			'join' => array(
				'user_info as ui',
				'ui.user_id = pc.user_id'
			)
		);
		return $this->get_one($cond);
	}
	
	public function get_comment_reply($comment_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'product_comment_reply',
			'fields' => '*',
			'where' => array(
				'comment_id' => $comment_id
			)
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function check_product_fav($class_id,$user_id)
	{
		$cond = array(
			'table' => 'favorite_product',
			'fields' => 'id',
			'where' => array(
				'product_id' => $class_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}
	
	public function product_fav($act,$product_id,$user_id)
	{
		if($act == 'like')
		{
			if($this->db->query('UPDATE `products` SET `product_fav` = `product_fav` + 1 WHERE `product_id` = '.$product_id))
			{
				$like = array(
					'product_id' => $product_id,
					'user_id' => $user_id,
					'create_time' => TIME_NOW
				);
				$this->insert($like,'favorite_product');
				return TRUE;
			}
		}
		else
		{
			if($this->db->query('UPDATE `products` SET `product_fav` = `product_fav` - 1 WHERE `product_id` = '.$product_id))
			{	
				$cond = array(
					'table' => 'favorite_product',
					'where' => array(
						'product_id' => $product_id,
						'user_id' => $user_id
					)
				);
				$this->delete($cond);
				return TRUE;
			}
		}
		return FALSE;
	}

	public function comment_reply($comment_id,$contnet,$user_id,$reply_user)
	{
		$data = array(
			'comment_id' => $comment_id,
			'create_user_id' => $user_id,
			'reply_user_id' => $reply_user,
			'note' => $contnet,
			'create_time' => TIME_NOW
		);
		$rs = $this->insert($data,'product_comment_reply');
		if($rs)
		{
			$this->db->query('UPDATE `product_comment` SET `replyNum` = `replyNum` + 1 WHERE `comment_id` = '.$comment_id);
			return TRUE;
		}
		return FALSE;
	}
}