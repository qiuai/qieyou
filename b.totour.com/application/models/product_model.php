<?php

class Product_model extends MY_Model {

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

	public function get_product_by_id($product_id,$inn_summary = FALSE)
	{
		$cond = array(
			'table' => 'products as p',
			'fields' => '*',
			'where' =>'p.product_id = '.$product_id.' AND p.state != "D"'
		);
		if($inn_summary)
		{
			$sql = 'SELECT p.*,i.inn_name,sf.inn_address,sf.inn_summary,sf.inner_telephone,sf.inner_moblie_number,i.lon,i.lat,i.bdgps,i.sale_license FROM products as p JOIN inns as i ON p.inn_id = i.inn_id JOIN inn_shopfront as sf ON sf.inn_id = i.inn_id WHERE p.product_id = '.$product_id.' AND p.state != "D"' ;
			return $this->db->query($sql)->row_array();
		}
		return $this->get_one($cond);
	}
	
	public function get_rand_product_by_inn_category($inn_id,$category)
	{
		$sql = 'SELECT product_id,product_name,price,old_price FROM products WHERE inn_id = '.$inn_id.' AND state = "t" ORDER BY rand() LIMIT 2';
		return $this->db->query($sql)->result_array();
	}
	
	public function update_fav($act,$product_id)
	{
		if($act == 'add')
		{
			$sql = 'UPDATE `products` SET `product_fav` = `product_fav` + 1 WHERE `product_id` = '.$product_id;
		}
		else
		{
			$sql = 'UPDATE `products` SET `product_fav` = `product_fav` - 1 WHERE `product_id` = '.$product_id;
		}
		return  $this->db->query($sql);
	}
	
   /**
	* 逻辑删除商品 更新商品状态
	* @param int product_id
	* @param string $category 
	* @return bool
	*/
	public function updata_state_by_product_id($product_id,$state = 'D')
	{	
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => array(
				'product_id' => $product_id,
				'state' => $state,
				'update_time' => $_SERVER['REQUEST_TIME']
			)
		);
		if($state == 'D')
		{
			$cond['data']['tuan_end_time'] = 0;
		}
		return $this->update($cond);
	}

   /**
    * 创建便利店商品
    * @param array $_post
	* @param string type
    * @return array
	*/
	public function create_goods_product($inns_id,$p)
	{	
		if(!$inns_id)
		{
			return array('code'=>'-1','msg'=>'未找到您在磨房驿栈的店铺！');
		}
		//check product_name
		$product_name = rtrim($p['product_name']);
		if(empty($product_name))
		{
			return array('code'=>'-2','msg'=>'未填写商品名称');
		}
		if($this->check_product_name($inns_id,$product_name,'goods'))
		{
			return array('code'=>'-3','msg'=>'该分类下存在相同名称的商品名！');
		}

	//	$product_code = $this->create_product_code($inns_id['dest_id'],$innInfo['inns_id'],'goods');

		if(empty($p['gallery']))
		{
			return array('code'=>'-4','msg'=>'请上传商品图片！');
		}

		$data = array();
		$data['inns_id'] = $inns_id;
		$data['category'] = 'goods';
	//	$data['product_code'] = $product_code;
		$data['product_name'] = $product_name;
		$data['product_type'] = rtrim($p['product_type']);
		$data['price'] = floatval($p['price']);
		$data['festive_price'] = $data['price'];
		$data['quantity'] = intval($p['quantity']);
		$data['content'] = $p['content'];
		$data['gallery'] = $p['gallery'];
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		
		$product_id = $this->insert($data,'products');
	//	$this->create_quota($product_id,$data['quantity'],$data['price'],$data['festive_price']);
	//	$inventory = array();
		if(!$this->modified_goods_inventory($product_id,$data))
		{
			return array('code'=>'-5','msg'=>'创建库存失败，请重试！');
		}
		return $product_id;
	}
	   
   /**
	* 修改玩法类商品
	* @param array $_POST
	* @param array $product
	* @return array
	*/
	public function edit_game_product_by_product_id($p,$product)
	{
		if($p['type'] == 'quantity')
		{
			return $this->update_properties($p,TRUE);
		}
		if(empty($p['product_name']))	return array('code' => '-1','msg' => '您尚未填写玩法名称！');
		if(empty($p['facility']))	return array('code' => '-1','msg' => '您尚未填写标签！');
		if(empty($p['days']))	return array('code' => '-1','msg' => '您尚未选择行程时间！');
		if(empty($p['content']))	return array('code' => '-1','msg' => '您尚未填写玩法简介！');
		if(empty($p['rule']))	return array('code' => '-1','msg' => '您尚未填写出行规则！'); 
		if(empty($p['thumb']))	return array('code' => '-1','msg' => '您尚未上传首页推荐位图片！');
		if(empty($p['gallery']))	return array('code' => '-1','msg' => '您尚未上传封面！');
		if(empty($p['note']))	return array('code' => '-1','msg' => '您尚未填写磨房带你玩！');
		if(empty($p['content_book']))	return array('code' => '-1','msg' => '您尚未填写预定须知！');

		$data = array();
		$data['product_id'] = $p['pid'];
		$data['product_name'] = $p['product_name'];
		$data['product_type'] = json_encode(array('days' => $p['days'],'rule'=>$p['rule']));
		$data['facility'] = $p['facility'];
		$data['content_book'] = $p['content_book'];
		$data['thumb'] = $p['thumb'];
		$data['gallery'] = $p['gallery'];
		$data['content'] = $p['content'];
		$data['note'] = $p['note'];
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => $data
		);
		$this->update($cond);
		return array('code' => '1','msg' => '修改成功！');	
	}

   /**
	* 修改便利店商品
	* @param array $_POST
	* @param array $product
	* @return array
	*/
	public function edit_goods_product_by_product_id($p,$product)
	{
		if($p['type'] = 'quantity')	//批量修改特殊需求
		{
			$p = array_merge($product,$p);
		}
		if(empty($p['product_name']))	return array('code' => '-1','msg' => '产品名不可为空！');
		if(empty($p['product_type']))	return array('code' => '-1','msg' => '产品类型不可为空！');
		if(empty($p['quantity'])||(!preg_match("/^\d*$/",$p['quantity'])))	return array('code' => '-1','msg' => '产品数量必须为大于0的整数！');
		if(empty($p['price'])||($p['price']<=0))	return array('code' => '-1','msg' => '产品价格必须大于0！');

		$data = array();
		$data['product_id'] = $p['pid'];
		$data['product_name'] = $p['product_name'];
		$data['product_type'] = $p['product_type'];
		$data['quantity'] = $p['quantity'];
		$data['price'] = $p['price'];
		$data['content'] = $p['content']; 
		$data['gallery'] = $p['gallery'];
		$data['category'] = 'goods';
		
		$changedkeys = array_diff_assoc($data,$product);
		if(empty($changedkeys))
		{
			return array('code' => '1','msg' => '未发现任何修改！');
		}
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => $data
		);
		if($this->update($cond))
		{
			if(isset($changedkeys['quantity'])||isset($changedkeys['price']))	//如果有其中之一发生变化 需要修改库存表
			{
				if(!$this->modified_goods_inventory($data['product_id'],$data))
				{
					return array('code' => '-2','msg' => '修改库存失败，请重试！');
				}
			}
			return array('code' => '1','msg' => '修改成功！');
		}
		return array('code' => '-3','msg' => '修改失败，请重试！');
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

	public function get_category()
	{
		if(empty($this->localMemcache))
		{
			$this->localMemcache = new memcache;
			$this->localMemcache->connect('127.0.0.1','34096');
		}
		$cat = $this->localMemcache->get('ApisysCategory');
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
			'order_by' => 'category_id ASC'
		);
		$cat['list'] = $this->model->get_all($cond);
		$this->localMemcache->set('ApisysCategory',$cat,FALSE,1800);
		return $cat;
	}

	public function get_local($city_id)
	{		
		if(empty($this->localMemcache))
		{
			$this->localMemcache = new memcache;
			$this->localMemcache->connect('127.0.0.1','34096');
		}
		$local = $this->localMemcache->get('ApisysLocal'.$city_id);
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
			'order_by' => 'dest_id ASC'
		);
		$local = array();
		$rs = $this->model->get_all($cond);
		if(!$rs)
		{
			$this->localMemcache->set('ApisysLocal'.$city_id,array(),FALSE,1800);
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
			'order_by' => 'local_id ASC'
		);
		$local['list'] = $this->model->get_all($cond);
		$this->localMemcache->set('ApisysLocal'.$city_id,$local,FALSE,1800);
		return $local;
	}
}