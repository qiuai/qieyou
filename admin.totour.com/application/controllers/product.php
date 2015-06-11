<?php

class Product extends MY_Controller {

	public $controllerTag = 'product';
	public $moduleTag = 'productlist';
	public $category = array('room' => '房间','game' => '玩法','goods'=>'便利店商品');
      
    public function __construct() 
	{
        parent::__construct();
		$this->cklogin();
    }

   /**
	* 商品管理入口
	* web page
	*/
	public function index() 
	{
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$inn_id = input_int($this->input->get('sid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15);
		$cid = input_int($this->input->get('cid'),1,FALSE,0);
		$state = input_int($this->input->get('state'),0,3,0);
		$seq = input_string($this->input->get('seq'),array('timea','timed','ctimea','ctimed','pricea','priced','sella','selld'),'timed');

		$order_by = array('timea'=>'update_time ASC','timed'=>'update_time DESC','ctimea'=>'create_time ASC','ctimed'=>'create_time DESC','pricea'=>'price ASC','priced'=>'price DESC','sella'=>'bought_count ASC','selld'=>'bought_count DESC');

		$search = array(
			'cid' => $cid,
			'state' => $state,
			'seq' => $seq
		);
		$arr = $this->model->get_localArr($inn_id,$local_id,$dest_id);
		$destInfo = $arr['destInfo'];
		$localArr = $arr['localArr'];
		$Innlist = $arr['Innlist'];
		
		if($inn_id)		//查看单个商户
		{
			$search['key'] = 'inn';
			$search['key_id'] = $destInfo['inn_id'];
		}
		else if($local_id)	//查看街道商户
		{
			$search['key'] = 'local';
			$search['key_id'] = $destInfo['local_id'];
		}
		else if($dest_id)
		{
			$search['key'] = 'dest';
			$search['key_id'] = $destInfo['dest_id'];
		}
		else{	//未指定位置使用默认值 0
			$search['key'] = 'default';
			$search['key_id'] = 0;
		}
		$search['keyword']=$this->input->get('keyword');
			
		$products = $this->model->get_products($search,$order_by[$seq],$page,$perpage);	
		$total = $products['total'];

		$pageInfo = array(
			'total' => $total,
			'totalpage' => $total/$perpage,
			'perpage' => $perpage,
			'curpage' => $page,
			'url' => makePageUrl($page)
		);

		$this->viewData = array(
			'pageInfo' => $pageInfo,
			'products' => $products['list'],
			'search' => $search,
			'localArr' => $localArr,
			'Innlist' => $Innlist,
			'destInfo' => $destInfo
		);
    }
	
	public function qieyou()
	{
		$this-> controllerTag = 'qieyou';
		$this-> moduleTag = 'pqieyoulist';
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15);
		$cid = input_int($this->input->get('cid'),1,FALSE,0);
		$state = input_int($this->input->get('state'),0,3,0);
		$seq = input_string($this->input->get('seq'),array('timea','timed','ctimea','ctimed','pricea','priced','sella','selld'),'timed');
		$keyword=$this->input->get('keyword');
		$inn_id = $this->get_user_inn_id();
		
		$order_by = array('timea'=>'update_time ASC','timed'=>'update_time DESC','ctimea'=>'create_time ASC','ctimed'=>'create_time DESC','pricea'=>'price ASC','priced'=>'price DESC','sella'=>'bought_count ASC','selld'=>'bought_count DESC');

		$search = array(
			'key' => 'qieyou',
			'key_id' => $inn_id,
			'cid' => $cid,
			'state' => $state,
			'seq' => $seq,
			'keyword'=>$keyword
		);
		$products = $this->model->get_products($search,$order_by[$seq],$page,$perpage);	
		$total = $products['total'];

		$pageInfo = array(
			'total' => $total,
			'totalpage' => $total/$perpage,
			'perpage' => $perpage,
			'curpage' => $page,
			'url' => makePageUrl($page)
		);

		$this->viewData = array(
			'pageInfo' => $pageInfo,
			'products' => $products['list'],
			'search' => $search
		);
	}

   /**
	* 编辑商品所有普通商品
	* web page
	*/
    public function edit() 
	{  
		$product_id = input_int($this->input->get('pid'),1,FALSE,FALSE,'参数错误');
		$product = $this->check_product($product_id);
		if(!$product)
		{
			_jsGo(base_url()."product",'您查看的商品已删除');
		}
		if($product['is_tuan'])
		{
			header("Location:".base_url()."product/tuanedit?pid=".$product_id."");
		}
		
		$this->viewData = array(
			'product' => $product
		);
	//	$this->viewData['key_auth'] = $this->model->get_create_user_authcode();
    }

	//新建商户普通商品  通过修改商品至团购 变成团购商品
	//团购商品无法改变会普通商品  只能下架
	//下架商品 可以上架为先前状态的商品 即原先如果是 团购则为团购  原先为商户商铺则恢复为在售

   /**
	* 编辑所有团购商品  有添加普通商品上架团购功能 需要判断post new_state
	* web page
	*/
    public function tuanedit() 
	{  
		$product_id = input_int($this->input->get('pid'),1,FALSE,FALSE,'参数错误');
		
		$product = $this->check_product($product_id);
		if(!$product)
		{
			_jsGo(base_url()."product",'您查看的商品已删除');
		}
		
		if(!$product['is_tuan']&&($this->input->get('act') !='addtuan'))
		{
			header("Location:".base_url()."product/edit?pid=".$product_id."");
		}
		$innInfo = array();
		if($product['inn_id'] == $this->get_user_inn_id())
		{
			$this-> controllerTag = 'qieyou';
			$this-> moduleTag = 'pqieyoulist';
		}
		else
		{
			$innInfo = $this->model->get_inn_info_by_inn_id($product['inn_id']);	
		}
		$this->viewData = array(
			'innInfo' => $innInfo,
			'product' => $product
		);
	//	$this->viewData['key_auth'] = $this->model->get_create_user_authcode();
    }

   /**
	* 为商户添加商品普通商品
	* web page
	* ajax post
	*/
    public function add() 
	{
		//$key_auth = $this->model->get_create_user_authcode();
		$inn_id = input_int($this->input->get('sid'),1,FALSE,FALSE,'1010');
		if(!$inn_id)
		{
			if($this->get_user_role() == INNHOLDER)
			{
				$inn_id = $this->get_user_inn_id();
			}
			else if(!$inn_id)
			{
				_jsBack('您尚未选择一个商户');
			}
		}
		$innInfo = $this->model->get_inn_info_by_inn_id($inn_id,FALSE);
		$this->viewData = array(
		//	'key_auth' => $key_auth,
			'innInfo' => $innInfo
		);
		$this->viewFile = 'product/add';
    }
	
	public function addProduct()
	{
		$inn_id = input_int($this->input->post('sid'),1,FALSE,FALSE,'1010');
		$innInfo = $this->model->get_inn_info_by_inn_id($inn_id,FALSE);
		if(!$innInfo)
		{
			response_code('1010');
		}
		$product = $this->check_product_value('normal');
		$product['is_qieyou'] = '0';
		$product['state'] = 'Y';
		$product['inn_id'] = $inn_id;
		$product_id = $this->model->create_product($product);
		if($product_id)
		{
			$contant = '新建商户商品，商户：<a class="viewInnsInfo" href="javascript:void(0);" ref="'.$innInfo['inn_id'].'"> '.$innInfo['inn_name'].'</a> 商品名：<a href="'.base_url().'product/edit?pid='.$product_id.'">'.$product['product_name'].'<a>';
			$this->model->wLog('New Merchandise' , $contant ,'I');
			_ajaxJson('1',$product_id); 
		}
		_ajaxJson('-1','添加失败！'); 
	}

   /**
    * 添加且游优品独立模块
	* web page
	* ajax post
	*/
	public function addqieyou()
	{
		$this->controllerTag = 'qieyou';
        $this->moduleTag = 'add_pqieyou';
		$inn_id = $this->get_user_inn_id();
		if(is_post())
		{
			$product = $this->check_product_value('tuan');
			$product['inn_id'] = $this->get_user_inn_id();
			$product['is_qieyou'] = '1';
			$product['state'] = 'T';
			$product_id = $this->model->create_product($product);
			if($product_id)
			{
				$contant = '新建且游优品：<a href="'.base_url().'product/qieyouedit?pid='.$product_id.'">'.$this->input->post('product_name').'<a>';
				$this->model->wLog('New Merchandise' , $contant ,'I');
				_ajaxJson('1',$product_id); 
			}
			_ajaxJson('-1','添加失败！'); 
		}
		$this->viewFile = 'product/addpqieyou';
	}

   /**
	* 编辑商品	编辑所有商品(商品未删除)
	* ajax POST
	*/
	public function editProduct() 
	{
		$product_id = input_int($this->input->post('pid'),1,FALSE,FALSE,'2004');
		$product = $this->check_product($product_id);
		if(!$product)
		{
			response_code('2004');
		}
		if($product['state'] == 'T' || $this->input->post('new_state') == 'T')
		{
			$changeproduct = $this->check_product_value('tuan');
		}
		else
		{
			$changeproduct = $this->check_product_value('normal');
		}
		$changedkeys = array_diff_assoc($changeproduct,$product);
		if($changedkeys)
		{
			$changedkeys['product_id'] = $product_id;
			$changedkeys['update_time'] = $_SERVER['REQUEST_TIME'];
			$done['user_id'] = $this->get_user_id();
			$done['state'] = $product['state'];
			$done['product_name'] = $product['product_name'];
			$done['is_tuan'] = $product['is_tuan'];
			if(!$this->model->update_product($changedkeys,$done))
			{
				response_code('-1');
			}
		}
		if($this->input->post('new_state') == 'T')	//添加商品到团购
		{
			$product['product_id'] = $product_id;
			$done['user_id'] = $this->get_user_id();
			$done['state'] = 'T';
			$done['is_tuan'] = 1;
			$this->model->quick_change_product_state($product,$done);
		}
		response_code('1');
    }

   /**
	* 快速上架/下架/删除
	* ajax POST
	*/
	public function changeState() 
	{
		$product_id = input_int($this->input->post('pid'),1,FALSE,FALSE,'2015');
		$state = input_string($this->input->post('state'),array('T','Y','N','D'),FALSE,'2001');
		$product = $this->check_product($product_id);
		if(!$product)
		{
			$this -> jsonAjax('-1','商品信息不正确，或您没有修改该商品的权限！');
		}
		if($product['state'] != $state)
		{
			$done = array(
				'state' => $state,
				'user_id' => $this->get_user_id()
			);
			if($state == 'Y' && $product['purchase_price'] !=0)
			{
				$done['state'] = 'T';
			}
			if(!$this->model->quick_change_product_state($product,$done))
			{
				response_code('2002');
			}
		}
		response_code('1');
	}

   /**
    * 本控制器内需要验证$product_id权限处
	*/
	private function check_product($product_id)
	{
		$product = $this->model->get_product_info_by_product_id($product_id);
		if(!$product||$product['state']=='D')
		{
			return FALSE;
		}
		if($this->check_inn_id_in_controller($product['inn_id']))			//权限验证
		{
			return $product;
		}
		return FALSE;
	}

	private function check_product_value($type)
	{
		$data = array();
		$price = input_empty($this->input->post('price'),FALSE,'2001');
		$price = sprintf("%.2f", $price);
		if($price < 0)
		{
			response_code('2001');
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
				response_code('2002');
			}
		}
		$data['category'] = input_int($this->input->post('category'),1,FALSE,FALSE,'2003');
		$data['category_id'] = input_int($this->input->post('category_id'),1,FALSE,FALSE,'2004');
		$data['tuan_end_time'] = $this->input->post('tuan_end_time')?strtotime($this->input->post('tuan_end_time')):'';
		$data['tuan_end_time'] = input_int($data['tuan_end_time'],$_SERVER['REQUEST_TIME'],$_SERVER['REQUEST_TIME']+31536000,$_SERVER['REQUEST_TIME']+31536000);	//有效期1年以内 过期下架

		if($type == 'tuan')
		{
			$profit = input_empty($this->input->post('profit'),FALSE,'2005');
			$profit = sprintf("%.2f", $profit);
			if($profit < 0)
			{
				response_code('2005');
			}
			$purchase_price = input_empty($this->input->post('purchase_price'),FALSE,'2006');
			$purchase_price = sprintf("%.2f", $purchase_price);
			if($purchase_price < 0)
			{
				response_code('2006');
			}		
			$agent = $price - $profit - $purchase_price;
			
			if($agent < 0)
			{
				response_code('2007');
			}
			$agent = sprintf("%.2f", $agent);
			$facility = $this->input->post('receipt')?'receipt':'';
			$data['agent'] = $agent;
			$data['purchase_price'] = $purchase_price;
			$data['facility'] = $facility;
		}
		$data['is_express'] = $this->input->post('is_express')?'1':'0';
		$data['product_name'] = input_empty($this->input->post('product_name',TRUE),FALSE,'2008');
		$data['price'] = $price;
		$data['old_price'] = $old_price;
		$data['quantity'] = input_int($this->input->post('quantity'),0,FALSE,FALSE,'2009');
		$data['note'] = $this->input->post('note',TRUE);
		$data['booking_info'] = $this->input->post('booking_info',TRUE);
		$data['thumb'] = input_empty($this->input->post('thumb'),'');
		$data['product_images'] = input_empty($this->input->post('images',TRUE),FALSE,'2010');
		$data['detail_images'] = input_empty($this->input->post('detail_images'),array());
		$gallery = array();
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
			response_code('2011');
		}
		$data['product_images'] = implode(',',$data['product_images']);
		$data['gallery'] = implode(',',$gallery);
		$data['detail_images']  = implode(',',$data['detail_images']);
		$data['content'] = input_empty($this->input->post('content'),'');
		return $data;
	}

   /**
    * 本控制器内需要验证$inn_id的地方 
	* return bool
	*/
	private function check_inn_id_in_controller($inn_id)
	{
		$role = $this->get_user_role();
		if($inn_id)
		{
			switch($role)
			{	
				case ROLE_INNHOLDER:
					return $inn_id == $this->get_user_inn_id();
				case ROLE_CUSTOM_SERVICE:
				case ROLE_ADMIN:
					return TRUE;
					break;
				case ROLE_TREASURER:
				default:
					return FALSE;
					break;
			}
		}
		else
		{
			switch($role)
			{
				case ROLE_INNHOLDER:
				case ROLE_CUSTOM_SERVICE:
					return $this->get_user_inn_id();
				case ROLE_ADMIN:
				case ROLE_TREASURER:
				default:
					return FALSE;
					break;
			}
		}
		return FALSE;
	}

	/**
	 * 推荐信息列表
	 * 团购中商品推荐信息
	 * 虚拟信息 后台添加
	 */
	public function optionList(){
		$page = input_int($this->input->get_post('page'),1,FALSE,1);
		$perpage = input_int($this->input->get_post('perpage'),1,20,10);

		$this->load->model('product_option_model');
    	$data = $this->product_option_model->optList($page,$perpage);

		$total = $data['total'];
		$list = $data['list'];
		
		/**************页面载入相关信息处理**************/
		$pageInfo = array(
			'total' => $total,
			'perpage' => $per_page,
			'curpage' => $page,
			'totalpage' => $total/$per_page,
			'url' => makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $list,
		);
	}

	// 添加推荐信息
	public function optionAdd(){
		if(IS_POST){
			$opt['product_id'] = input_int($this->input->get_post('product_id'),1,FALSE,FALSE,'2002');
			$opt['note'] = $this->input->get_post('note');
			
			$data = $this->optionConfig();

			$opt['img'] = $data['img'];
			$opt['type'] = $data['type'];
			$opt['create_time'] = time();

			$this->load->model('product_option_model');
			if($this->product_option_model->optAdd($opt)){
				response_json(1);
			}else{
				response_json(-1);
			}
		}
	}

	// 删除推荐信息
	public function optionDel(){
		$id = input_int($this->input->get_post('id'),1,FALSE,FALSE,'-1');
		
		$data = $this->optionConfig();

		$this->load->model('product_option_model');
    	if($this->product_option_model->optDel($id)){
			response_json(1);
		}else{
			response_json(-1);
		}
	}

	// 编辑推荐信息
	public function optionEdit(){
		if(IS_POST){
			$opt['id'] = input_int($this->input->get_post('id'),1,FALSE,FALSE,'-1');
			$opt['note'] = $this->input->get_post('note');
			
			$data = $this->optionConfig();

			$opt['img'] = $data['img'];
			$opt['type'] = $data['type'];

			$this->load->model('product_option_model');
			if($this->product_option_model->optEdit($opt)){
				response_json(1);
			}else{
				response_json(-1);
			}
		}
	}

	// 推荐信息内置信息
	private function optionConfig(){
		$option['type'] = array(
			'旅行达人',
			'客栈老板',
		); // 用户类型

		$option['img'] = array(
			'user/headimg/rand/1/14335915736034.jpg',
			'user/headimg/rand/1/14335915813822.jpg',
			'user/headimg/rand/1/14335915864965.jpg',
			'user/headimg/rand/1/14335916043612.jpg',
		);	// 用户头像

		return $option;
	}
}
