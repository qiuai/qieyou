<?php
class User extends MY_Controller {

	public $controllerTag = 'user';
//	public $usertype = array('user' => '普通用户','admin' => '管理员','cservice' => '客服','innholder' => '商户老板', 'smanager' => '店长','treasurer' => '财务');
//	public $ARRAY_ROLE = array(ROLE_NORMAL_USER,ROLE_ADMIN,ROLE_CLIENT_SERVICE,ROLE_INNHOLDER,ROLE_SHOP_MANAGER,ROLE_TREASURER);
	
	public function __construct()
	{
		parent::__construct();
		$this->cklogin();
	}

   /**
	* 用户管理 用户列表
	* web page
	*/
	public function index()
	{
		$this->moduleTag = 'userIndex';
		
		$type = input_string($this->input->get('type'),array('all','user','innholder','cservice','smanager'),'all');
		$user_name=$this->input->get('user_name');
		$nick_name=$this->input->get('nick_name');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		if($starttime)$starttime = strtotime($starttime);
		if($endtime)$endtime = strtotime($endtime)+86399;
		
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime,
			'type'	=> $type,
			'user_name'	=> $user_name,
			'nick_name'	=> $nick_name
		);
		$data = $this->model->get_backend_users_by_param($search,$page,$perpage);
		
		$total = $data['total'];
		$users = $data['list'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/15,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'users' => $users,
			'type' => $type,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime
		);
	}
   /**
	* 添加用户
	* web page & ajax POST
	*/
	public function add() 
	{
		$this->controllerTag = 'inn';
		$this->moduleTag = 'addUser';
		
		if(is_post())
		{
			$newuser = $this->check_userInfo('add');
			$user = $this -> model -> get_user_by_mobile($newuser['user_name']);
			if($user)
			{
				if($user['role']=='innholder')
					response_code('4006');
				else{
					$newuser['user_id'] = $user['user_id'];
				}
			}
			$newuser['role'] = 'innholder';
			$innInfo = $this->check_inn_info('add',$newuser);
			$destInfo = $this->model->get_dest_info_by_dest_id($innInfo['innInfo']['dest_id']);
			$done = array(
				'user_id' => $this->get_user_id(),
				'city_id' => $destInfo['city']
			);
			$create_user_id = $this->model->create_inn_user($newuser,$innInfo,$done);

			if($create_user_id)
			{
				response_code('1');
			}
			response_code('-1');
		}
	}

   /*
    * 用户信息修改
	* web page & ajax POST
	*/
	public function editinfo()
	{
		$uid = input_int($this->input->get('uid'),1,FALSE,0);
		$userInfo = $this->model->get_user_info_in_ids($uid,'*',TRUE,FALSE);
		
		if(is_post())
		{
			$changeuser = $this->check_userInfo('edit');
			$changedkeys = array_diff_assoc($changeuser,$userInfo);
			if($changedkeys)
			{
				$done['user_id'] = $this->get_user_id();
				$done['user_name'] = $userInfo['user_name'];
				$changedkeys['user_id'] = $userInfo['user_id'];
				$changedkeys['update_time'] = $_SERVER['REQUEST_TIME'];
				$changedkeys['update_by'] = $done['user_id'];
				if(!$this->model->update_user_info($changedkeys,$done))
				{
					response_code('-1');
				}
			}
			response_code('1');
		}
		$this->viewData = array(
			'userInfo' => $userInfo	
		);
	}
	/**
	* 修改密码
	* web page & ajax POST
	*/
	public function changepwd()
	{
		if(is_post())
		{
			$userId =  $this->get_user_id();
			$userInfo = $this->model->getUserInfo($userId,FALSE);
			$oldpassword = check_empty(trimall(strip_tags($this->input->post('user_pass'))),FALSE,'1021');
			$new_password = check_empty(trimall(strip_tags($this->input->post('new_password'))),FALSE,'1022');
			$repassword = check_empty(trimall(strip_tags($this->input->post('repeat_password'))),FALSE,'1023');
			if($new_password!=$repassword)
			{
				response_code('1024');
			}
			if($oldpassword != $new_password)
			{
				$oldpassword=md5(md5($oldpassword).$userInfo['salt']);
				if($oldpassword!=$userInfo['user_pass'])
				{
					response_code('1021');
				}
				$user['user_id']=$userId;
				$user['user_pass']=md5(md5($new_password).$userInfo['salt']);
			
				$this->model->update_user($user);
			}
			response_code('1');
		}
	}
	public function getUserInfo() {
		$user_id = $this -> input-> get('user_id');
		if(empty($user_id) ||!preg_match('/^\d*$/',$user_id))
		{
			$this-> errorMsg(0,"参数不正确！");
		}
        $this->useLayout = FALSE;
		$userInfo = $this->model->getUserInfo($user_id,TRUE);
		if(!$userInfo){
			$this-> errorMsg(0,"用户不存在！");
		}
        $this->viewData['userInfo'] = $userInfo;
	}

   /**
    * 更新用户状态
	* ajax POST
	
	public function update_state() 
	{
		$userid = $this-> input -> post('uid');
		if (empty($userid)) {
			$this-> errorMsg(0,"用户ID不能为空！");
		}
		$user = $this->model -> getUserInfo($userid,FALSE);
		if (empty($user)) {
			$this-> errorMsg(0,"用户不存在！");
		}
		if(is_post()) {
			$state = $this->input->post('state');
			$userInfo= array (
				'user_id' => $userid,
				'state' => $state
			);
			$rs = $this-> user_model-> update_backend_user($userInfo);
			if($rs)
			{
				if($state != 'suspend')
				{
					$state = 'active';
				}
				$inns = $this->model->get_user_inns($userid);
				if($inns['state'] != $state)
				{
					$this->load->model('inns_model');
					$this-> inns_model-> update_inns($inns['inn_id'], array('state' => $state));
				}
			}
		}
		$this->_echoJson(array('code' => '1', 'msg' => 'Success !'));
	}*/

   /*
	* check the user name is existing
	*/
	public function checkusername()
	{
		$username = input_mobilenum($this->input->post('userName'),'1004');
		$user = $this -> model -> get_user_by_mobile($username);
		if ($user) {
			if($user['role'] == 'user')
			{
            	response_json('2',$user['user_id']);
			}
			else if($user['role'] == 'innholder')
			{
            	response_json('-1','error');
			}
		} else {
            response_json('1','success!');
		}
	} 
	
	private function check_userInfo($actiontype = 'add') 
	{
		$userInfo['real_name'] = check_empty(trimall(strip_tags($this->input->post('real_name'))),FALSE,'1010');
		$userInfo['state'] = input_string($this->input->post('state'),array('active','suspend'),'active');
		
		if($actiontype == 'add') 
		{
			$userInfo['user_name'] = input_mobilenum($this->input->post('user_name'),'1004');
			$real_user_pass = substr($userInfo['user_name'], -6 );
			$userInfo['salt'] = getRandChar(4);
			$userInfo['user_pass'] = md5(md5($real_user_pass).$userInfo['salt']);
		}
		else{		//修改个人信息
			$userInfo['nick_name'] = check_empty(trimall(strip_tags($this->input->post('nick_name'))),FALSE,'1010');
			$userInfo['user_mobile'] = input_mobilenum($this->input->post('user_mobile'),'1004');
		}
		return $userInfo;
	}

	private function check_inn_info($actiontype = '',$userInfo)
	{
		$innInfo = array();
		$innInfo['inn_name'] = check_empty(trimall(strip_tags($this->input->post('inn_name',TRUE))),FALSE,'1010');
		$innInfo['dest_id'] = input_int($this->input->post('dest_id'),1,FALSE,FALSE,'1011');
		$innInfo['local_id'] = input_int($this->input->post('local_id'),1,FALSE,FALSE,'1012');
        $profit = check_empty($this->input->post('profit'),FALSE,'1013');
		$innInfo['profit'] = sprintf("%.2f", $profit);
		if($innInfo['profit']<0||$innInfo['profit']>100)
		{
			response_code('1013');
		}
		$bdlon = number_format(check_empty($this->input->post('bdlon'),FALSE,'1016'),7,'.',"");
        $bdlat = number_format(check_empty($this->input->post('bdlat'),FALSE,'1016'),7,'.',"");
		$gps = BD09LLtoWGS84($bdlon,$bdlat);
		$innInfo['lon'] = $gps[0];
		$innInfo['lat'] = $gps[1];
		$innInfo['bdgps'] = $bdlon.','.$bdlat;
		$innShopfront = array();
	//	$innShopfront['bank_info'] = check_empty(trimall(strip_tags($this->input->post('bank_info'))),FALSE,'1017');//strip_tags('');//$this->input->post('bank_info',TRUE);
	//	$innShopfront['bank_account_no'] = input_num(trimall($this->input->post('bank_account_no')),FALSE,FALSE,FALSE,'1018');
	//	$innShopfront['bank_account_no'] = check_luhn($innShopfront['bank_account_no'],'1018');
	//	$innShopfront['bank_account_name'] = check_empty(trimall(strip_tags($this->input->post('bank_account_name'))),FALSE,'1019');

		$innShopfront['bank_info'] = $this->input->post('bank_info',TRUE);
		$innShopfront['bank_account_no'] = $this->input->post('bank_account_no',TRUE);
		$innShopfront['bank_account_name'] = $this->input->post('bank_account_name',TRUE);

		$innShopfront['inner_telephone'] = check_empty(trimall(strip_tags($this->input->post('inner_telephone'))),'');
		$innShopfront['inn_address'] = check_empty(trimall(strip_tags($this->input->post('inn_address'))),FALSE,'1020');
		$innShopfront['inner_contacts'] = check_empty(trimall(strip_tags($this->input->post('inner_contacts'))),'');

		if(!$innShopfront['inner_contacts'])
		{
			$innShopfront['inner_contacts'] = $userInfo['real_name'];
		}

		$innShopfront['inner_moblie_number'] = input_mobilenum($this->input->post('inner_moblie_number'),FALSE,'');	//默认为用户账号（手机号）
		if(!$innShopfront['inner_moblie_number'])
		{
			$innShopfront['inner_moblie_number'] = $userInfo['user_name'];
		}
		return array('innInfo' => $innInfo,'innShopfront' => $innShopfront);
	}
	
	
	private function get_input_inn_shopfront_info($inn_id)
	{
		$inn_shopfront = array();
		$inn_shopfront['inner_telephone'] = $this->input->post('inner_telephone');
		$inn_shopfront['inn_address'] = $this->input->post('inn_address');
		$inn_shopfront['inner_contacts'] = $this->input->post('inner_contacts');
		$inn_shopfront['inner_moblie_number'] = $this->input->post('inner_moblie_number');
		$inn_shopfront['inn_id'] = $inn_id;
		if (empty($inn_shopfront['inn_id'])) {
			$this-> errorMsg(0,"驿栈ID不能为空！");
		}
		return $inn_shopfront;
	}
}