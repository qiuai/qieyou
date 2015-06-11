<?php

class Product extends MY_Controller {
      
    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 店铺商品 可以搜索团购 或店铺所有
	*/
	public function inn() 
	{
		$category = input_int($this->input->get('cid'),1,FALSE,0);
		$category_id = input_int($this->input->get('ccid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,FALSE,'1015');
		$perpage = input_int($this->input->get('perpage'),1,FALSE,FALSE,'1016');
		$sort = input_string($this->input->get('sort'),array('time','lowp','highp'),'time');	
		$state = input_string($this->input->get('state'),array('T','Y','N','A'),'A');
		$sid = input_int($this->input->get('sid'),0,FALSE,0);
		if($sid&&$state=='N')
		{
			response_msg('1018');
		}
		$order_by = array('time'=>'update_time DESC','lowp'=>'price ASC','highp'=>'price DESC');

		if(!$sid && $this->get_user_id())
		{
			$sid = $this->token['inn_id'];
		}
		if(!$sid)
		{
			response_msg('2001');
		}

		$search = array(
			'sid' => $sid,
			'category' => $category,
			'category_id' => $category_id,
			'state' => $state,
			'local_id' => 0
		);
		$products = $this->model->get_products($search,$order_by[$sort],build_limit($page,$perpage));
		response_data($products);
	}

   /**
	* 获取其他商户资料
	* @parm int sid 
	* @return 
	*/
	public function inninfo()
	{
		$sid = input_int($this->input->get('sid'),1000,FALSE,0);	
		if(!$sid && $this->get_user_id())
		{
			$sid = $this->token['inn_id'];
		}
		if(!$sid)
		{
			response_msg('2001');
		}
		$innInfo = $this->model->get_inn_info_by_inn_id($sid,TRUE);
		if(!$innInfo)
		{
			response_msg('2007');
		}
		if($this->get_user_id())
		{
			$this->_LoadModel('user');
			$innInfo['is_fav'] = $this->user_model->check_fav('inn',$this->token['inn_id'],$this->token['user_id']);
		}
		else
		{
			$innInfo['is_fav'] = 0;
		}

		unset($innInfo['bank_info']);
		unset($innInfo['bank_account_name']);
		unset($innInfo['bank_account_no']);

		response_data($innInfo);
	}

   /**
	* get product detail
	* return 
	**/
	public function detail()
	{
		$product_id = input_int($this->input->get('pid'),0,FALSE,FALSE,'2008');		//商品id	
		$product = $this->model->get_product_by_id($product_id,TRUE);
		if(!$product)
		{
			response_msg('2009');
		}
		if($this->get_user_id())
		{
			$this-> _LoadModel('user');
			$is_fav = $this->user_model->check_fav('product',$product_id,$this->token['user_id']);
			$product['is_fav'] =$is_fav?1:0;
		}
		else
		{
			$product['is_fav'] = 0;
		}
		$tuan = $this->model->get_rand_product_by_inn_category($product['product_id'],$product['inn_id'],'tuan');
		$product['tuan'] = $tuan;
		response_data($product);
	}

   /**
	* 团购入口
	*/
	public function tuan() 
	{
		$category = input_int($this->input->get('cid'),0,FALSE,0);
		$category_id = input_int($this->input->get('ccid'),0,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$city_id = input_int($this->input->get('city'),100000,FALSE,'530700');	//默认丽江
		$dest_id = input_int($this->input->get('dest'),0,FALSE,0);
		$local_id = input_int($this->input->get('local'),0,FALSE,0);
		$key_word = input_empty($this->input->get('keyword'),'');
		$sort = input_string($this->input->get('sort'),array('time','local','highp','lowp'),'time');	
		$today = input_int($this->input->get('today'),0,2,0);
		$order_by = array('time'=>'update_time DESC','local'=>'local','highp'=>'bought_count DESC','lowp'=>'price ASC');

		$search = array(
			'category' => $category,
			'category_id' => $category_id,
			'city_id' => $city_id,
			'local_id' => $local_id,
			'dest_id' => $dest_id,
			'state' => 'T',
			'today' => $today,
			'key_word' => $key_word
		);
		if($order_by[$sort] == 'local')
		{
			$search['lat'] = $this->input->get('lat');
			$search['lon'] = $this->input->get('lon');
		}

		$products = $this->model->get_products($search,$order_by[$sort],build_limit($page,$perpage));
		response_data($products);
    }

   /**
	* 首页选项
	*/
	public function option() 
	{
		$city_id = input_int($this->input->get('city'),100000,FALSE,FALSE,'4006');
		$category = $this->model->get_category();
		$local = $this->model->get_local($city_id);
		$data = array(
			'category' => $category,
			'local' => $local
		);
		response_data($data);
    }

	public function city()
	{
		$city = array(
			array(
				'name' => '丽江',
				'city' => '530700'
			),
			array(
				'name' => '大理',
				'city' => '532900'
			)
		);
		response_data($city);
	}

   /**
	* 修改商品状态
	*/
	public function changeState() 
	{
		$product = $this->check_edit_product_permission();
		
		$state = input_string($this->input->post('state'),array('N','D','Y'),FALSE,'3003');

		if($state == $product['state'])
		{
			response_msg('1');
		}

		$rs = $this->model->updata_state_by_product_id($product['product_id'],$state);
		response_msg($rs?'1':'4000');
    }

   /**
	* 编辑商品	POST
	*/
	public function editProduct() 
	{
		$product = $this->check_edit_product_permission();
		
		/***数据校验***/
		$data = check_product_value();
		$changedkeys = array_diff_assoc($data,$product);
		if(empty($changedkeys))
		{
			response_msg('1');
		}
		$data['product_id'] = $product['product_id'];
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		$cond = array(
			'table' => 'products',
			'primaryKey' => 'product_id',
			'data' => $data
		);
		$rs = $this->model->update($cond);
		response_msg($rs?'1':'4000');
    }

   /**
	* 添加商品 POST
	* 各种类型商品数据类型差别很大 SO分开处理 
	*/
    public function addProduct() 
	{  
		$this->check_token();
		$data = $this->check_product_value('add');
		$data['inn_id'] = $this->token['inn_id'];
		$data['state'] = 'Y';
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		$product_id = $this->model->insert($data,'products');
		if($product_id)
		{
			response_data($product_id);
		}
		response_msg('4000');
    } 

	private function check_edit_product_permission()
	{
		$this->check_token();
		$product_id = input_int($this->input->post('pid'),0,FALSE,FALSE,'2008');		//商品id

		$product = $this->model->get_product_by_id($product_id,FALSE);
		if(!$product)
		{
			response_msg('2009');
		}
		if($this->token['inn_id'] != $product['inn_id'])
		{
			response_msg('2013');
		}

		if($product['state'] == 'T')
		{
			response_msg('2014');
		}
		return $product;
	}
	
	private function check_product_value($add = FALSE)
	{
		log_message('error',json_encode($this->input->post()));
		$data = array();
		$inn = $this->model->get_inn_info_by_inn_id($this->token['inn_id'],FALSE);
		$price = input_empty($this->input->post('price'),FALSE,'4003');
		$price = sprintf("%.2f", $price);
		if($price < 0)
		{
			response_msg('2016');
		}
		$old_price = input_empty($this->input->post('old_price'),0);
		$old_price = sprintf("%.2f", $old_price);
		if($old_price == 0)
		{
			$old_price = $price;
		}
		else
		{
			if($old_price < $price)
			{
				response_msg('2017');
			}
		}
		if($add)
		{
			$data['category'] = input_int($this->input->post('cid'),0,FALSE,FALSE,'2004');
			if($data['category'] == 6)
			{
				response_msg('4001');
			}
			$data['category_id'] = input_int($this->input->post('ccid'),0,FALSE,FALSE,'2006');
			$data['tuan_end_time'] = $this->input->post('tuan_end_time')?strtotime($this->input->post('tuan_end_time')):'';
			$data['tuan_end_time'] = input_int($data['tuan_end_time'],$_SERVER['REQUEST_TIME'],$_SERVER['REQUEST_TIME']+31536000,$_SERVER['REQUEST_TIME']+31536000);	//有效期1年以内 过期下架
		}

		$data['product_name'] = input_empty($this->input->post('product_name'),FALSE,'4003');
		$data['price'] = $price;
		$data['old_price'] = $old_price;
		$data['quantity'] = input_int($this->input->post('quantity'),0,FALSE,FALSE,'2015');
		$data['note'] = $this->input->post('note',TRUE);
		$data['booking_info'] = $this->input->post('booking_info',TRUE);
		$data['thumb'] = input_empty($this->input->post('thumb'),0);
		$data['product_images'] = input_empty($this->input->post('product_images'),FALSE,'2019');
		$data['product_images'] = explode(',',$data['product_images']);
		foreach($data['product_images'] as $key => $row)
		{  
			$info = explode('.', strrev($row));
			$gallery[]  = strrev($info[1]).'m.'.strrev($info[0]);
			if($key == 0)
			{
				$data['thumb'] = strrev($info[1]).'s.'.strrev($info[0]);
			}
		}
		if(empty($data['thumb']))
		{
			response_msg('2018');
		}
		$data['product_images'] = implode(',',$data['product_images']);
		$data['gallery'] = implode(',',$gallery);
		$data['detail_images'] = input_empty($this->input->post('detail_images'),'');
		$data['content'] = mb_substr($data['note'], 0, 50, 'utf-8');
		return $data;
	}
}