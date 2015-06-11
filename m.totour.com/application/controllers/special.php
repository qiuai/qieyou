<?php

class Special extends MY_Controller {

	public $layout_for_title = '特卖';
	public $layout = 'homepage';

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{
		$city = input_int($this->input->cookie('cityid'),100000,FALSE,'530700');	//默认丽江
		$keyword = check_empty($this->input->get('keyword'),'');
		$category = $this->model->get_category();
		$local = $this->model->get_local($city);
		$citys = array(
			array(
				'name' => '丽江',
				'city' => '530700'
			),
			array(
				'name' => '大理',
				'city' => '532900'
			)
		);
		$this->viewData = array(
			'shouye' => 'special',
			'category' => $category,
			'local' => $local,
			'citys' => $citys,
			'keyword' => $keyword
		);
    }

	public function search()
	{
		$this->moduleTag = '搜索';
		$this->layout = 'item';
	}

	/*搜索筛选接口 可搜索关键字*/
	public function get()
	{
		$key_word = check_empty($this->input->get('keyword'),'');
		$city_id = input_int($this->input->cookie('cityid'),100000,FALSE,'530700');	//默认丽江
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$category = input_int($this->input->get('cid'),0,FALSE,0);
		$category_id = input_int($this->input->get('ccid'),0,FALSE,0);
		$dest_id = input_int($this->input->get('dest'),0,FALSE,0);
		$local_id = input_int($this->input->get('local'),0,FALSE,0);
		$sort = input_string($this->input->get('sort'),array('time','local','highp','lowp'),'time');	
		$today = input_int($this->input->get('today'),0,2,0);

		$order_by = array('time'=>'update_time DESC','local'=>'local','highp'=>'bought_count DESC','lowp'=>'price ASC');
		$type = input_string($this->input->get('type'),array('item','inn'),'item');	

		$search = array(
			'category' => $category,
			'category_id' => $category_id,
			'city_id' => $city_id,
			'local_id' => $local_id,
			'dest_id' => $dest_id,
			'state' => 'T',
			'today' => $today,
			'type' => $type,
			'key_word' => $key_word
		);
		if($order_by[$sort] == 'local')
		{
			$search['lat'] = $this->get_current_data('lat');
			$search['lon'] = $this->get_current_data('lon');
		}
		if($type=='item')
		{
			$data = $this->model->get_products($search,$order_by[$sort],build_limit($page,$perpage));			
		}
		else
		{
			$data = $this->model->get_inns($search,$order_by[$sort],build_limit($page,$perpage));
		}
		response_row($data);
	}

   /**
    * 店铺详情
	*/
	public function inn()
	{
		$inn_id = input_int($this->input->get('sid'),1000,FALSE,FALSE,'4001');
		
		$inn = $this->model->get_inn_info_by_ids($inn_id);
		if(!$inn)
		{
			response_code('2010');
		}
		$inn = $inn[$inn_id];
		if($this->get_user_id())
		{
			$is_fav = $this->model->check_inn_fav($inn_id,$this->get_user_id());
			$inn['is_fav'] =$is_fav?1:0;
		}
		$search = array(
			'sid' => $inn_id,
			'state' => 'T'
		);
		$product = $this->model->get_products($search,'update_time DESC');
		$this->moduleTag = '店铺详情';
		$this->layout = 'simple';
		$this->viewData = array(
			'inn' => $inn,
			'product' => $product
		);
	}
	public function searchlist()
	{

	}
   /**
    * 收藏店铺
	**/
	public function innlike()
	{
		$user_id = $this->get_user_id(TRUE);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,FALSE,'4001');
		$act = input_string($this->input->get('act'),array('like','unlike'),FALSE,'4001');
		$inn = $this->model->get_inn_info_by_ids($inn_id);
		if(!$inn)
		{
			response_code('2010');
		}
		if($act == 'like')
		{
			$is_like = $this->model->check_inn_fav($inn_id,$user_id);
			if($is_like)
			{
				response_code('2012');
			}
		}
		else
		{
			$is_like = $this->model->check_inn_fav($inn_id,$user_id);
			if(!$is_like)
			{
				response_code('2011');
			}
		}
		$inn_info = array(
			'dest_id' => $inn[$inn_id]['dest_id'],
			'local_id' => $inn[$inn_id]['local_id']
		);
		if($this->model->inn_fav($act,$inn_id,$user_id,$inn_info))
		{
			response_code('1');
		}
		response_code('-1');
	}
}