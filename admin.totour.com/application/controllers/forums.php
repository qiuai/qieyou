<?php
class forums extends MY_Controller {
	public $controllerTag = 'forums';	//左侧菜单的对应

	public function __construct() {
		parent::__construct();
		$this->cklogin(); //检查是否登录
	}
	
	public function index()
	{
		$this->moduleTag = '';
		$class = input_string($this->input->get('class'),array('wenda','jianren','tour'),'wenda');
		switch($class)
		{
			case 'wenda':
				$this->moduleTag = 'wenda';	 
				break;
			case 'jianren':
				$this->moduleTag = 'jianren';
				break;
			case 'tour':
				$this->moduleTag = 'tour';	 
				break;	
		}
		$user_from = input_string($this->input->get('user_from'),array('all','min_user','user'),'all');
		
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);	
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		$keyword = input_empty(trim($this->input->get('keyword')),'');
		$is_del = input_int($this->input->get('is_del'),1,FALSE,0);
		
		if($starttime)	$starttime = strtotime($starttime);
		if($endtime)	$endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime' => $endtime,
			'class'=>$class,
			'keyword'=>$keyword,
			'is_del'=>$is_del,
			'user_from'=>$user_from
		);
		
		$rs = $this->model->get_forums($search,$page,$perpage);
		
		$total = $rs ['total'];
		$data = $rs ['list'];
		
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/15,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'frontUrl' => $this->config->item('front_url'),
			'data' => $data,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime,
			'class'=>$class,
			'is_del'=>$is_del,
			'user_from'=>$user_from
		);
	}
	/*回复管理*/
	public function reply(){
		$this->moduleTag = 'reply';	
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);	
		$class = input_string($this->input->get('class'),array('all','wenda','jianren','tour'),'all');
		$keyword = input_empty(trim($this->input->get('keyword')),'');
		$starttime = input_empty($this->input->get('starttime'),'');       
		$is_del = input_int($this->input->get('is_del'),1,FALSE,0);    	           
		$endtime = input_empty($this->input->get('endtime'),'');
		if($starttime)	$starttime = strtotime($starttime);
		if($endtime)	$endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime,
			'class'=>$class,
			'keyword'=>$keyword, 
			'is_del'=>$is_del
		);
		
		$rs = $this->model->get_reply($search,$page,$perpage);
		$total = $rs ['total'];
		$data = $rs ['list'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/15,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'frontUrl' => $this->config->item('front_url'),
			'data' => $data,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime,
			'class'=>$class,
			'is_del'=>$is_del
		);
	}
	/*帖子屏蔽*/
	public function is_delete(){
		$forum_id= input_int($this->input->post('forum_id'),1,FALSE,0);
		$rs=$this->model->is_delete($forum_id);
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*回复屏蔽*/
	public function reply_delete()
	{	
		$post_id= input_int($this->input->post('post_id'),1,FALSE,0);
		$rs=$this->model->reply_delete($post_id);
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*推荐*/
	public function recommend()
	{	
		$forum_id= input_int($this->input->post('forum_id'),1,FALSE,0);	
		$rs=$this->model->recommend_jianren($forum_id);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*置顶*/
	public function is_top(){
		$forum_id= input_int($this->input->post('forum_id'),1,FALSE,0);
		$rs=$this->model->is_top($forum_id);
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*添加帖子*/
	public function add_forum()
	{	 
	    $this->moduleTag = 'add_forum';
		if(is_post())
		{	
			$data = $this->check_forum();
			if($data)$rs = $this->model->add_forum($data);
			if(!$rs)
			{
				response_code('-1');
			}
			response_code('1');
		}
		$info=$this->suiji();
		$this->viewData = array(
			'info'=>$info
		);
	}
	public function edit_forum(){
	    $this->moduleTag = 'forums';  
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
	private function check_forum() 
	{
		$info['title'] = check_empty(strip_tags($this->input->post('title')));		
		$info['content'] = check_empty(strip_tags($this->input->post('content')));			
		$user_name = $this->input->post('user_name');
		$users = $this->model->get_userinfo($user_name);
		if(!$users)
		{
			response_code('1003');
		}
		$info['user_id'] = $users['user_id'];
		$info['group_id'] = input_int($this->input->post('group_id'),1000,FAlSE,0);
		if(!$this->model->get_group($info['group_id']))
		{
			response_code('1034');
		}
		$info['type'] = input_string($this->input->post('type'),array('tour','jianren','wenda'),FALSE,'4001');
		$tags = check_empty(trimall(strip_tags($this->input->post('tags'))),'');
		if($tags)
		{
			$info['tags'] = array();
			$tags = explode(',',$tags);
			foreach($tags as $key => $row)
			{
				if(!$row)
				{
					continue;
				}
				if(mb_strlen($row)>6)
				{
					response_json('6033','标签："'.$row.'" 字数过长');
				}
				$info['tags'][] = $row;
			}
			if(count($info['tags']) > 3)
			{
				response_code('6032');
			}
			$info['tags'] = implode(',',$info['tags']);
		}
		else
		{
			$info['tags'] = '';
		}
		$create_time = strtotime($this->input->post('create_time'));
		$info['create_time'] = $create_time > $_SERVER['REQUEST_TIME']?$_SERVER['REQUEST_TIME']:($create_time?$create_time:$_SERVER['REQUEST_TIME']);
		$info['city'] = '丽江市';
		if($this->input->post('address')!=""){
			$address_arr=array('丽江市古城区束河古镇龙泉路束河完小东60米'=>'100.213112,26.9271','100.215646,26.928155','丽江市古城区束河古镇泉居委会中和路中和村32号'=>'100.212796,26.92867','丽江市束河东康八组拐柳巷（近飞花触水）'=>'100.209386,26.927245','丽江古城区束河古镇悦榕路（近束河古镇中心位置）'=>'100.219511,26.931215','丽江束河古镇北门停车场（束河古镇，近四方街）'=>'100.215071,26.931239','云南省丽江束河古镇龙泉行政文明二社24号'=>'100.213463,26.930885','束河古镇仁里村8号九鼎龙潭西北侧'=>'100.212726,26.932672','云南省丽江市城西北7公里束河村古街旁'=>'100.210235,26.931054','云南省丽江市玉龙纳西族自治县15公里处'=>'100.232102,27.115041','丽江市白沙乡北部玉水寨旅游风景区内'=>'100.207576,27.003324','丽江香格里拉大道延伸段'=>'100.225606,26.914328','云南省丽江市古城区长水路85'=>'100.240785,26.871959','丽江义尚街文明巷81号'=>'100.250337,26.879137');
			$address_position=explode(',',$address_arr[$this->input->post('address')]);
			$info['lat'] =$address_position[0];
			$info['lon'] =$address_position[1];
		}
		$info['img'] = $this->input->post('img');
		return $info;
	}
	
	/*抓取操作*/
	public function re_forum(){
		$f_url= $this->input->post('f_url');
		$rs=$this->get_url_action($f_url);
		if(!$rs){
			response_code('-1'); 
		}
		echo json_encode($rs);
		exit;
	}
	public function suiji(){
		$num= rand(1001,11000);
		$username='qygroup'.$num;
		$user_info = $this->model->get_userinfo($username);
		
		$group_id = $this->model->suiji_group();
		$info['group_id']=$group_id;
		$info['user_name']=$user_info['user_name'];
		$info['nick_name']=$user_info['nick_name'];
		$info['headimg']=$user_info['headimg'];
		if($user_info['sex']=='M'){ 
			$sex= '男';
		 }elseif($user_info['sex']=='F'){ 
			$sex= '女';
		 }else{
			$sex= '';
		 }
		$info['sex']=$sex;
		$info['age']=$user_info['birthday']?date('Y-m-d',time())-$user_info['birthday']:'';	
		$info['time']= rand(strtotime('-1 year'), time());
		$address_arr=array('丽江市古城区束河古镇龙泉路束河完小东60米'=>'100.213112,26.9271','100.215646,26.928155','丽江市古城区束河古镇泉居委会中和路中和村32号'=>'100.212796,26.92867','丽江市束河东康八组拐柳巷（近飞花触水）'=>'100.209386,26.927245','丽江古城区束河古镇悦榕路（近束河古镇中心位置）'=>'100.219511,26.931215','丽江束河古镇北门停车场（束河古镇，近四方街）'=>'100.215071,26.931239','云南省丽江束河古镇龙泉行政文明二社24号'=>'100.213463,26.930885','束河古镇仁里村8号九鼎龙潭西北侧'=>'100.212726,26.932672','云南省丽江市城西北7公里束河村古街旁'=>'100.210235,26.931054','云南省丽江市玉龙纳西族自治县15公里处'=>'100.232102,27.115041','丽江市白沙乡北部玉水寨旅游风景区内'=>'100.207576,27.003324','丽江香格里拉大道延伸段'=>'100.225606,26.914328','云南省丽江市古城区长水路85'=>'100.240785,26.871959','丽江义尚街文明巷81号'=>'100.250337,26.879137');
		$info['address']=array_rand($address_arr, 1);
		$address_position=explode(',',$address_arr[$info['address']]);
		$info['lat'] =$address_position[0];
		$info['lon'] =$address_position[1];
		return $info;
	}
	public function suiji_user(){
		$num= rand(1001,11000);
		$username='qygroup'.$num;
		$user_info = $this->model->get_userinfo($username);
		if(!$user_info){
			response_code('1003');		
		}
		$info['user_name']=$user_info['user_name'];
		$info['nick_name']=$user_info['nick_name'];
		$info['headimg']=$user_info['headimg'];
		if($user_info['sex']=='M'){ 
			$sex= '男';
		}elseif($user_info['sex']=='F'){ 
			$sex= '女';
		}else{
			$sex= '';
		}
		$info['sex']=$sex; 
		$info['age']=$user_info['birthday']?date('Y-m-d',time())-$user_info['birthday']:'';
		echo json_encode($info);
		exit;
	}
	public function suiji_group(){
		$group_id = $this->model->suiji_group();
		if(!$group_id){
			response_code('1034');
		}
		$groups['group_id']=$group_id;
		echo json_encode($groups);
		exit;
	}
	public function suiji_address(){
		$address_arr=array('丽江市古城区束河古镇龙泉路束河完小东60米'=>'100.213112,26.9271','100.215646,26.928155','丽江市古城区束河古镇泉居委会中和路中和村32号'=>'100.212796,26.92867','丽江市束河东康八组拐柳巷（近飞花触水）'=>'100.209386,26.927245','丽江古城区束河古镇悦榕路（近束河古镇中心位置）'=>'100.219511,26.931215','丽江束河古镇北门停车场（束河古镇，近四方街）'=>'100.215071,26.931239','云南省丽江束河古镇龙泉行政文明二社24号'=>'100.213463,26.930885','束河古镇仁里村8号九鼎龙潭西北侧'=>'100.212726,26.932672','云南省丽江市城西北7公里束河村古街旁'=>'100.210235,26.931054','云南省丽江市玉龙纳西族自治县15公里处'=>'100.232102,27.115041','丽江市白沙乡北部玉水寨旅游风景区内'=>'100.207576,27.003324','丽江香格里拉大道延伸段'=>'100.225606,26.914328','云南省丽江市古城区长水路85'=>'100.240785,26.871959','丽江义尚街文明巷81号'=>'100.250337,26.879137');
		$info['address']=array_rand($address_arr, 1);
		$address_position=explode(',',$address_arr[$info['address']]);
		$info['lat'] =$address_position[0];
		$info['lon'] =$address_position[1];
		echo json_encode($info);
		exit;
	}
	/*搜索用户*/
	public function re_user(){
		$user_name= $this->input->post('user_name');
		$user_info = $this->model->get_userinfo($user_name);
		if(!$user_info){
			response_code('1003');
		}
		if($user_info['sex']=='M'){ 
			$sex= '男';
		}elseif($user_info['sex']=='F'){ 
			$sex= '女';
	    }else{
			$sex= '';
		}
		$user_info['sex']=$sex;
		$user_info['age']=$user_info['birthday']?date('Y-m-d',time())-$user_info['birthday']:'';	
		echo json_encode($user_info);
		exit;
	}
	/*搜索部落*/
	public function re_group(){
		$group_id= $this->input->post('group_id');
		$groups = $this->model->get_group($group_id);
		if(!$groups){
			response_code('1034');
		}
		exit;
	}
	/*抓取开始*/
	public function get_url_action($url){
		$text=file_get_contents($url);
		$text = iconv("gb2312","utf-8//IGNORE",$text); //8264需要转码
		preg_match('/<h1[^>]*id="thread_subject"[^>]*>(.*?)<\/h1>/si',$text,$title);
		$rs['title']=$title[1];
		preg_match('/<div[^>]*class="t_fsz_new "[^>]*>(.*?)<\/div>/si',$text,$content);
		$rs['content']=strip_tags($content[1]);
		
		$img_pattern = '/<img.*?file="(.*?)"\s class="zoom".*?>/'; //8264论坛
		preg_match_all($img_pattern, $text, $img_out); //图片
		$rs['img']=$img_out[1];
		if(empty($rs['img'])){
			$img_pattern = '/<img\sclass="zoom".*?src="(.*?)".*?>/';
			preg_match_all($img_pattern, $text, $img_out); //图片
			$rs['img']=$img_out[1];
		}
		return $rs;
	}
}