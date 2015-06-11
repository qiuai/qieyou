<?php

class Product_model extends MY_Model {

   /**
    * 得到店铺内商品
    * @param SQLchar $type IN格式
    * @param int $inn_id
    * @return array
    */
	public function get_products($search,$order_by = 'update_time DESC',$page,$per_page)
	{
		$select = "SELECT p.*,i.inn_name,d.dest_name,l.local_name FROM ";
		$selectfrom = 'products as p JOIN inns as i ON p.inn_id = i.inn_id ';
		$selectjoin = 'LEFT JOIN china_dest as d ON d.dest_id = i.dest_id ';
		$selectjoin .= 'LEFT JOIN china_dest_local as l ON l.local_id = i.local_id ';
		$orderby = 'ORDER BY p.'.$order_by;
		
		if($search['state'])
		{
			$where = 'WHERE p.state = '.$search['state'].' ';
		}
		else
		{
			$where = 'WHERE p.state != "D" ';
		}
		switch($search['key'])
		{
			case 'inn':
				if($search['key_id'])
				{
					$where .= ' AND p.inn_id = '.$search['key_id'].' ';
				}
			break;
			case 'local':
				if($search['key_id'])
				{
					$where .= ' AND i.local_id = '.$search['key_id'].' ';
					$where .= ' AND i.is_qieyou = 0 ';
				}
			break;
			case 'dest':
				if($search['key_id'])
				{
					$where .= ' AND i.dest_id = '.$search['key_id'].' ';
					$where .= ' AND i.is_qieyou = 0 ';
				}
			break;	
			case 'qieyou':
				if($search['key_id'])
				{
					$where .= ' AND p.inn_id = '.$search['key_id'].' ';
				}
				$select = "SELECT p.*,i.inn_name FROM ";
				$selectfrom = 'products as p LEFT JOIN inns as i ON p.inn_id = i.inn_id ';
				$selectjoin = '';
			break;
			default:
				$where .= ' AND i.is_qieyou = 0 ';
			break;
		}
		if($search['cid'])
		{
			$where .= ' AND p.category = '.$search['cid'].' ';
		}
		if($search['keyword']!=""){
			$where .= " AND p.product_name LIKE '%".$search['keyword']."%'";
		}

		$totalsql = $selectfrom.$where;
		$total = $this->get_query_count($totalsql);
		$orders = array();

		if($total&&($total>($page-1)*$per_page))
		{
			$limit = build_limit($page, $per_page);
			$sql = $select.$selectfrom.$selectjoin.$where.$orderby.$limit;
			$orders = $this->db->query($sql) -> result_array();
		}
		return array( 'total' => $total, 'list' => $orders );
	}

   /**
    * 得到商品信息
    * @param int $product_id 产品编号
	* @param int $inn_id 驿栈编号
    * @return array
    */
	public function get_product_info_by_product_id($product_id)
	{
		$cond =array(
            'table' => 'products',
            'fields' => '*',
            'where' => array(
                'product_id' => $product_id
            )
        );
	 	return $this->get_one($cond);
	}

   /**
    * 创建商品
    * @param array $product
	* @param string type 且游优品 普通商品
    * @return array
	*/
	public function create_product($product)
	{	
		$product['create_time'] = $_SERVER['REQUEST_TIME'];
		$product['update_time'] = $_SERVER['REQUEST_TIME'];
		return $this->insert($product,'products');
	}
  
   /**
	* 逻辑删除商品 更新商品状态
	* @param array product
	* @param array $done 
	* @return array
	*/
	public function quick_change_product_state($product,$done)
	{	
		switch($done['state'])
		{
			case 'D':
				$action = 'delete product';
				$producturl = ''.$product['product_name'].'';
				$note = '被删除';
				break;
			case 'N':
				$action = 'down goods shelves';
				$note = '被下架';
				break;
			case 'Y':
				$action = 'up product shelves';
				$note = '被上架至商户店铺';
				break;
			case 'T':
				$action = 'up qieyou shelves';
				$note = '被上架至且游商城';
				$product['is_tuan'] = 1;
				break;
			default:
				return FALSE;
		}
		$producturl = ' <a href="'.base_url().'product/'.($product['is_tuan']?'tuanedit':'edit').'?pid='.$product['product_id'].'" target="_blank">'.$product['product_name'].'</a> ';
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => array(
				'state' => $done['state'],
				'product_id' => $product['product_id'],
				'update_time' => $_SERVER['REQUEST_TIME']
			)
		);		
		if(isset($product['is_tuan']))
		{
				$cond['data']['is_tuan'] = $product['is_tuan'];
		}
		$this->wLog($action, '商品：'.$producturl.$note, 'D', $state = 'S',$done['user_id'],'product/changeState');
		return $this->update($cond);
	}

   /**
	* 修改商品信息
	* @param array $changed_array
	* @param array $done
	* @return array
	*/
	public function update_product($product,$done)
	{
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => $product
		);
		$producturl = ' <a href="'.base_url().'product/'.($done['is_tuan']?'tuanedit':'edit').'?pid='.$product['product_id'].'" target="_blank">'.$done['product_name'].'</a> ';
		$this->wLog('edit product', '商品：'.$producturl.'被修改', 'D', $state = 'S',$done['user_id'],'product/changeState');
		return $this->update($cond);
	}

   /**
    * 校验前端传来的$product_id
	* @param string $type
	* @param int $product_id
	* return bool
	*/
	public function check_product($product_id,$type)
	{
		if(empty($product_id)||!preg_match("/^\d*$/",$product_id))
		{
			return FALSE;
		}
		$cond = array(
			'table' => 'products',
			'fields' => 'product_name',
			'where' => array(
				'product_id' => $product_id,
				'category' => $type
			)
		);
		$rs = $this->get_one($cond);
		return $rs ? TRUE : FALSE;
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
}