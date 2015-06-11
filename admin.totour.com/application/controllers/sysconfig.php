<?php
class Sysconfig extends MY_Controller {
	public $controllerTag = 'content';	//左侧菜单的对应

	public function __construct() {
		parent::__construct();
		$this->cklogin(); //检查是否登录
	}
	
	public function index()
	{
		$class = input_string($this->input->get('class'),array('banner','group','talent','product','jianren'),'banner');
		switch($class)
		{
			case 'banner':
				$this->moduleTag = 're_banner';	 
				break; 
			case 'group':
				$this->moduleTag = 're_group';	 
				break;
			case 'product':
				$this->moduleTag = 're_product';	 
				break;
			case 'jianren':
				$this->moduleTag = 're_jianren';	 
			break;
		}
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15);
		
		$rs = $this->model->get_config($class,$page,$perpage); //查询列表	
		$data = $rs['list'];
		$total = $rs['total'];
		if($class=='banner'){
			foreach($data as $key=>$val){
				$data[$key]['up_time']=intval((time()-$val['create_time'])/86400).'天';
			}
		}
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(  //向模板传递对象
			'frontUrl' => $this->config->item('front_url'),
			'data' => $data,
			'pageInfo' => $pageInfo,
			'class' => $class
		);
	}
	public function is_sort(){
		$id = input_int($this->input->post('id'),1,FALSE,0);
		$action = $this->input->post('action'); 
		$type = $this->input->post('type'); 
		$rs=$this->model->is_sort($id,$action,$type);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*增加banner*/
	public function add_banner(){	
		$this->moduleTag = 're_banner';
		if(is_post())
		{	
			$data = $this->check_banner();	
			$info = $this->model->add_config_info($data);
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}	
	/*修改banner*/
	public function editInfo()
	{
		$this->moduleTag = 're_banner';	 
		$id = input_int($this->input->get('id'),1,FALSE,0);
		$info = $this->model->get_config_info_by_id($id);
		if(is_post())
		{		
			$data = $this->check_banner();
			$data['id']=$id;
			$rs=$this->model->update_config_info($data);
			if(!$rs){
				response_code('-1');
			}
			response_code('1');
		}
		$this->viewData = array(
			'info' => $info	
		);
	}
	private function check_banner() 
	{
		$info['type']='banner';
		$info['img'] = check_empty($this->input->post('img'),FALSE,'请上传图片');
		$info['link'] = check_empty($this->input->post('link'));
		$info['note'] = $this->input->post('note');
		return $info;
	}
	/*搜索部落 */
	public function re_group(){
		$group_id= $this->input->post('group_id');
		$rs=$this->model->get_groups_by_name($group_id);
		if(!$rs){
			response_code('1026');
		}
		//组合管理员名（暂时没用到多个）
		$admins=$this->model->get_username($rs['admins']);
		foreach($admins as $k=>$v){
			if($k==0){
				$rs['admins']=$v['user_name'];
			}else{
				$rs['admins'].=",".$v['user_name'];
			}
		}
		echo json_encode($rs);
		exit;
	}
	/*增加推荐部落*/
	public function add_re_group(){
		$this->moduleTag = 're_group';
		if(is_post())
		{	   
			$data = $this->check_re_group();	
			if(!$data){
				response_code('1026');
			}
			$info =$this->model->add_config_info($data);	
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}
	private function check_re_group() 
	{
		$group_id = check_empty($this->input->post('group_id'));	
		$rs=$this->model->get_groups_by_name($group_id);
		$isname=$this->model->get_groups_isname($group_id,'group');
		if($isname){
			response_code('1030');
		}
		if($rs){
			$info['type']='group';
			$info['type_id']=$rs['group_id'];
			$info['name']=$rs['group_name'];
			$info['note']=$rs['note'];
			return $info;
		}
	}
	
	/*搜索商品 */
	public function re_product(){
		$product_id = input_int($this->input->post('product_id'),1,FALSE,0);
		$rs=$this->model->get_product_by_id($product_id);
		if(!$rs){
			response_code('1027');
		}
		$category = array('1'=>'客栈酒店','2'=>'美食饕餮','3'=>'娱乐休闲','4'=>'当地行','5'=>'当地游','6'=>'当地购','7'=>'旅游险');
		$rs['category']=$category[$rs['category']];
		echo json_encode($rs);
		exit;
	}
	
	/*增加推荐商品*/
	public function add_re_product(){
		$this->moduleTag = 're_product';
		if(is_post())
		{	
			$data = $this->check_re_product();	
			if(!$data){
				response_code('1027');
			}
			$info = $this->model->add_config_info($data);
			if($info)
			{
				response_code('1');
			}
			response_code('-1');
		}
	}
	private function check_re_product() 
	{
		$product_id = input_int($this->input->post('product_id'),1,FALSE,0);	
		$rs=$this->model->get_product_by_id($product_id);
		$isname=$this->model->get_groups_isname($product_id,'product');
		if($isname){
			response_code('1031');
		}
		if($rs){
			$info['type']='product';
			$info['type_id']=$rs['product_id'];
			$info['name']=$rs['product_name'];
			$info['note']=$rs['note'];
		
			return $info;
		}
	}
	/*搜索捡人*/
	public function re_jianren(){
		$jianren_id = input_int($this->input->post('jianren_id'),1,FALSE,0);
		$rs=$this->model->get_jianren_by_id($jianren_id);
		if(!$rs){
			response_code('1028');
		}
		$rs['create_time']=date('Y-m-d H:i:s',$rs['create_time']);
		$rs['start_time']=date('Y-m-d H:i:s',$rs['start_time']);
		if(!$rs){
			response_code('-1');
		}
		echo json_encode($rs);
		exit;
	}
	/**
	*增加推荐捡人
	*/
	public function add_re_jianren(){
		$this->moduleTag = 're_jianren';
		if(is_post())
		{	
			$data = $this->check_re_jianren();	
			if(!$data){
				response_code('1028');
			}
			$info = $this->model->add_config_info($data);
			
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}
	
	private function check_re_jianren() 
	{
		$jianren_id = input_int($this->input->post('jianren_id'),1,FALSE,0);	
		$rs=$this->model->get_jianren_by_id($jianren_id);
		$isname=$this->model->get_groups_isname($jianren_id,'jianren');
		if($isname){
			response_code('1032');
		}
		if($rs){
			$info['type']='jianren';
			$info['type_id']=$rs['forum_id'];
			$info['name']=$rs['user_name'];
			$info['note']=$rs['note'];
			return $info;
		}
	}
	/*取消推荐 */
	public function recommend(){
		$id= input_int($this->input->post('id'),1,FALSE,0);
		$type= $this->input->post('type');
		$rs=$this->model->up_is_del($id,$type);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*部落*/
	public function groups()
	{  
	    $this->controllerTag = 'groups';	
		$this->moduleTag = 'groups';	
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15);	
		$search['keyword']=$this->input->get('keyword');
		$rs = $this->model->get_groups($search,$page,$perpage);
		
		$data = $rs['list'];
		$total = $rs['total'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/$perpage,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array( 
			'data' => $data,
			'pageInfo' => $pageInfo,
		);
	}
	/*部落推荐到首页*/
	public function recommend_group()
	{	
		$group_id= input_int($this->input->post('group_id'),1,FALSE,0);	
		$rs=$this->model->recommend_group($group_id);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*增加部落*/
	public function groups_add() 
	{
	    $this->controllerTag = 'groups';
	    $this->moduleTag = 'groups';
		if(is_post())
		{	
			$data = $this->check_groups();	
			if($data)$info = $this->model->add_group($data);
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}
	/*修改部落*/
	public function edit_groupsInfo()
	{
		$this->controllerTag = 'groups';
	    $this->moduleTag = 'groups';  
		$id = input_int($this->input->get('id'),1,FALSE,0);
		$info = $this->model->get_groups_info_by_id($id);
		if(is_post())
		{		
			$data = $this->check_groups($info);
			$data['group_id']=$id;
			
			$rs=$this->model->update_groups_info($data);
			if(!$rs)
			{
				response_code('-1');
			}
			response_code('1');
		}	
		$this->viewData = array(
			'info' => $info	
		);
	}
	private function check_groups($groups) 
	{
		$create_mobile=$this->input->post('admins');
		$create_user=$this->model->get_user_by_mobile($create_mobile); 
		$create_by=$create_user['user_id']; //创建者
		$admin_mobile =$this->input->post('admins_id');	 //管理员手机号
		$admins= $create_by; 
		if($admin_mobile){
			$str_admin=implode(',',$admin_mobile);
			$users=$this->model->get_users_by_mobiles($str_admin);
			
			$str='';
			foreach($users as $k=>$v){
				$str.=','.$v['user_id'];
			}
			$admins=$admins.$str; //组合 创建者+管理员
		}
		
		if($create_user){
			$info['group_name'] = check_empty($this->input->post('group_name'));
			$info['create_by'] = $create_by;
			$info['admins'] = $admins;
			$member1=count(explode(',',$admins)); //变动的管理员数
			$member2=count(explode(',',$groups['members']))+1; //原有的管理员数
			if($groups){ //编辑
				$info['members'] =$groups['members']+($member1-$member2);
			}else{
				$info['members'] =$member1;
			}
			$info['group_img'] = check_empty($this->input->post('group_img'),FALSE,'请上传图片');
			$info['note'] = $this->input->post('note');	
			$info['join_method']=$this->input->post('join_method');
			return $info;
		}else{
			response_code('1029');
		}
	}
	/*验证创建者*/
	public function checkusername()
	{
		$user_mobile = input_mobilenum($this->input->post('admins'),'1004');
		$user = $this -> model -> get_user_by_mobile($user_mobile);
		if (!$user) {
            $this->_echoJson(false);
		} else {
            $this->_echoJson(true);
		}
	} 
	/*验证管理员*/
	public function check_mobile(){
		$mobile =$this->input->post('mobile');
		if($mobile)$user = $this -> model -> get_user_by_mobile($mobile);
		if (!$user) {
            response_code('1029');
		} else {
            response_code(1);
		}
	}
	/*删除*/
	public function is_del_group(){
		$group_id= input_int($this->input->post('group_id'),1,FALSE,0);
		$rs=$this->model->is_del_group($group_id);
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
}