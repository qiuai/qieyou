<?php

class user_model extends MY_Model {

	public function check_username($username)
	{
		$cond = array(
			'fileds' => 'user_id',
			'table' => 'users',
			'where' => array(
				'username' => $username
			)
		);
		$this->get_one($cond);
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $userInfo
	 */
	public function create_backend_user ($userInfo) {
		$userInfo['user_pass'] = $userInfo['user_pass'];
		$userInfo['create_time'] = $_SERVER['REQUEST_TIME'];
		$user_id = $this->insert( $userInfo,'users');
		
		$data['user_id'] = $user_id;
		$data['user_name'] = $userInfo['user_name'];
		$data['real_name'] = $this->input->post('real_name');
		$data['identity_no'] = $this->input->post('identity_no');
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['update_time'] = $_SERVER['REQUEST_TIME'];
		$data['create_by'] = $this->getUserId();
		$data['update_by'] = $this->getUserId();
		$this->insert($data,'user_info');
		return $user_id;

	}

	/**
	 * Enter description here ...
	 * @param unknown_type $userInfo
	 * @return Ambigous <number, NULL, unknown>
	 */
	public function update_backend_user($userInfo) {
		$userInfo['updated_time'] = time();
		$userInfo['updated_by'] = $this-> getUserId();
		$this->db->where('user_id', $userInfo['user_id']);
		$this->db->update('users', $userInfo);
		return $userInfo['user_id'];
	}

	public function search_backend_users($startCreateDate, $endCreateDate, $limit = 20, $offset = 0 , $role = "") {
		$this->db->select('user_id','user_name','real_name','province','city',
		'state','mobile_phone','role','last_login_time','login_count','created_time');
		if (empty($role)) {
			//get all backend users
			$this->db->where('role !=', ROLE_NORMAL_USER);
		} else {
			$this->db->where('role =', $role);
		}
		$this->db->where('created_time >', $startCreateDate);
		$this->db->where('created_time <', $endCreateDate);
		$result = array();
		$records = $this->db-> get_where("users",$where,$limit,$offset)->result_array();
		foreach ($records as $row){
			$row['created_time'] = format_time($row['created_time']);
			$row['last_login_time'] = format_time($row['last_login_time']);
			array_push($result, $row);
		}
		return $result;
	}

	/**
	 * create user
	 *
	 * @param array $input
	 * @return user array
	 */
	public function reg_user($users = array()) {

		$email = preg_replace("#[\;\#\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/\s]#", "", $users['email']);

		$userValues = array(
            'user_name' => $users['username'],
            'user_pass' => md5($users['password']),
            'face' => $users['face'],
            'mobile_phone' => $users['mobilephone'],
            'state' => 'active',
            'email' => strtolower($email),
            'role' => 'normaluser',
            'from_site' => $users['fromsite'],
            'from_site' => 'yizhan',
            'last_login_time' => time(),
            'create_time' => time(),
            'update_time' => time()
		);
		$this->db->insert('users', $userValues);
		$userid = $this->db->insert_id();
		$this->wLog('Reg User', '添加新账户：'.$users['username'],'C');
		$user = $this->db->query("SELECT * FROM users WHERE user_id = " . intval($userid))->row_array();
		return $user;
	}

	/**
	 *
	 * @param string $type
	 * @param timestamp starttime
	 * @param timestamp endtime
	 * @return int
	 */
	public function get_backend_users_by_param($search,$page,$per_page)
	{
		$starttime=$search['starttime'];
		$endtime=$search['endtime'];
		$type=$search['type'];
		$user_name=$search['user_name'];
		$nick_name=$search['nick_name'];
		$select = 'SELECT * FROM ';
		$where = 'where';
		$selectjoin = 'JOIN user_info as ui ON u.user_id = ui.user_id ';
		$where .= $starttime?' u.create_time >= '.$starttime.' ':'';
		$where .= $endtime? $where=='where'?' u.create_time <= '.($endtime+86399).' ':' AND u.create_time <= '.($endtime+86399).' ':'';
		if($user_name){
			if($where=='where'){
				$where .= " u.user_name = '".$user_name."'";
			}else{
				$where .= " AND u.user_name = '".$user_name."'";
			}
		}
		
		if($type != 'all')
		{
			$where .= $where == 'where'?' u.role = "'.$type.'"':' AND '.' u.role = "'.$type.'"'; 
			if($type == 'innholder')
			{
				$select = 'SELECT u.*,ui.*,i.inn_name,d.dest_name,l.local_name FROM ';
				$selectjoin .= ' LEFT JOIN inns as i ON i.innholder_id = u.user_id ';
				$selectjoin .= ' LEFT JOIN china_dest as d ON d.dest_id = i.dest_id ';
				$selectjoin .= ' LEFT JOIN china_dest_local as l ON l.local_id = i.local_id ';
			}
		}

		if($where =='where')
			$where .= ' 1 ';
		$selecttable = 'users as u ';					
		$totalsql = $selecttable.$where;
		$total = $this->get_query_count($totalsql);
		$users = array();

		if($total&&($total>($page-1)*$per_page))
		{
			$limit = build_limit($page, $per_page);
			if($nick_name){
			
				if($where=='where'){
					$where .= " ui.nick_name = '".$nick_name."'";
				}else{
					$where .= " AND ui.nick_name = '".$nick_name."'";
				}
			}
			$sql = $select.$selecttable.$selectjoin.$where.' ORDER BY u.create_time DESC '.$limit;
			$users = $this->db->query($sql) -> result_array();
		}
		return array( 'total' => $total, 'list' => $users );
	}

   /**
	* 得到用户组驿栈信息
	* @param sqlstring $innholder_ids
	* @return array
	*/
	public function get_user_inns_info_by_innholder_id($innholder_ids)
	{
		$cond = array(
			'table' => 'inns',
			'fields' => 'inns.innholder_id,inns.inn_id,inns.inn_name,',//destination.dest_name,destination.city,destination.province',
			'where' => 'inns.innholder_id IN ('.$innholder_ids.')',
		//	'join' => array(
		//		'destination',
		//		'destination.dest_id = inns.dest_id'
		//	)
		);
		return $this->get_all($cond);
	}

   /**
	*当前用户基本信息
	*/
	public function getUserInfo($userId = 0,$info = TRUE) 
	{
		if(!$userId)
		{
			$userId = $this->web_user->get_id();
			if(!$userId)
			{
				return array();
			}
		}
		$cond = array(
			'table' => 'users as u',
			'fields' => 'u.user_id,u.user_name,u.user_pass,salt',
			'where' => 'u.user_id = '.$userId.' '
		);
		if($info)
		{
			$cond['fields'] = 'u.user_id,u.user_name,ui.nick_name,ui.real_name,ui.email,ui.mobile_phone,ui.create_time,ui.last_login_time,ui.last_login_ip,u.role';
			$cond['join'] = array(
				'user_info as ui',
				'ui.user_id = u.user_id'
			);
		}
		return $this->get_one($cond);   
    }

	/**
	 * 得到指定驿栈的用户列表
	 */
	public function get_users_by_innsid($innsid) {
		$result = array();
		$sql = "SELECT u.user_name,u.real_name, u.mobile_phone, u.last_login_time,u.login_count,
				u.created_time , u.state FROM users u 
		   		INNER JOIN r_users_inns i on u.user_id = i.user_id WHERE i.inn_id = $innsid ";
		$records = $this->db->query($sql) -> result_array();
		foreach ($records  as $row) {
			$row['last_login_time'] = format_time($row['last_login_time']);
			$row['created_time'] = format_time($row['created_time']);
			array_push($result, $row);
		}
		return $result;
	}

	public function get_dest_by_userid($user_id) {
		$this->db->select('r.user_id, d.dest_id, d.dest_name');
		$this->db->where('r.user_id', $user_id);
		$this->db->from('r_users_dest r');
		$this->db->join('destination d ', 'r.dest_id = d.dest_id');
		return $this->db->get()->result_array();
	}

	public function get_inner_info_by_innsid($inn_id) {
		$sql = "SELECT u.* FROM users u 
		   		INNER JOIN inns i on u.user_id = i.innholder_id
		   		WHERE i.inn_id = $inn_id 
		   		AND u.role = 'innholder' ";
		$row = $this->db->query($sql) -> row_array();
		if (!empty($row)) {
			$row['last_login_time'] = format_time($row['last_login_time']);
			$row['created_time'] = format_time($row['created_time']);
			$row['updated_time'] = format_time($row['updated_time']);
		}
		return $row;
	}
	
	public function get_user_by_mobile($user_name)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id,user_name,user_pass,salt,user_mobile,role',
			'where' => array(
				'user_mobile' => $user_name
			)
		);
		if($user_name)
		{
			$cond['where'] = 'user_mobile = "'.$user_name.'" OR user_name = "'.$user_name.'" ';
		}
		return $this->get_one($cond);
	}
	
	public function create_inn_user($userdata,$inndata,$done)
	{
		$this->db->trans_start();
		if(isset($userdata['user_id']))
		{
			$cond = array(
				'table' => 'users',
				'primaryKey' => 'user_id',
				'data' => array(
					'user_id' => $userdata['user_id'],
					'role' => 'innholder'
				)
			);
			$this->update($cond);
			$cond = array(
				'table' => 'user_info',
				'primaryKey' => 'user_id',
				'data' => array(
					'user_id' => $userdata['user_id'],
					'local' => $done['city_id'],
					'real_name' => $userdata['real_name'],
					'update_time' => $_SERVER['REQUEST_TIME'],
					'update_by' => $done['user_id']
				)
			);
			$this->update($cond);
			$user_id = $userdata['user_id'];
		}
		else{
			$user = array(
				'user_name' => $userdata['user_name'],
				'user_pass' => $userdata['user_pass'],
				'salt' => $userdata['salt'],
				'user_mobile' => $userdata['user_name'],
				'role' => 'innholder',
				'state' => $userdata['state'],
				'create_time' => $_SERVER['REQUEST_TIME']
			);
			$user_id = $this->insert($user,'users');
			$userInfo = array(
				'user_id' => $user_id,
				'user_name' => $userdata['user_name'],
				'nick_name' => $userdata['real_name'],
				'real_name' => $userdata['real_name'],
				'mobile_phone' => $userdata['user_name'],
				'local' => $done['city_id'],
				'create_time' => $_SERVER['REQUEST_TIME'],
				'create_by' => $done['user_id'],
				'update_time' => $_SERVER['REQUEST_TIME'],
				'update_by' => $done['user_id']
			);
			$this->insert($userInfo,'user_info');
		}
		$inn = array(
			'inn_name' => $inndata['innInfo']['inn_name'],
			'state' => $userdata['state'],
			'is_qieyou' => 0,
			'city_id' => $done['city_id'],
			'dest_id' => $inndata['innInfo']['dest_id'],
			'local_id' => $inndata['innInfo']['local_id'],
			'innholder_id' => $user_id,
			'lat' => $inndata['innInfo']['lat'],
			'lon' => $inndata['innInfo']['lon'],
			'bdgps' => $inndata['innInfo']['bdgps'],
			'create_time' => $_SERVER['REQUEST_TIME'],
			'create_by' => $done['user_id']
		);
		$inn_id = $this->insert($inn,'inns');

		$inn_shopfront = array(
			'inn_id' => $inn_id,
			'inn_name' => $inndata['innInfo']['inn_name'],
			'inner_contacts' => $inndata['innShopfront']['inner_contacts'],
			'inner_moblie_number' => $inndata['innShopfront']['inner_moblie_number'],
			'inner_telephone' => $inndata['innShopfront']['inner_telephone'],
			'inn_address' => $inndata['innShopfront']['inn_address'],
			'bank_info' => $inndata['innShopfront']['bank_info'],
			'bank_account_name' => $inndata['innShopfront']['bank_account_name'],
			'bank_account_no' => $inndata['innShopfront']['bank_account_no'],
			'create_time' => $_SERVER['REQUEST_TIME'],
			'create_by' => $done['user_id'],
			'update_time' => $_SERVER['REQUEST_TIME'],
			'update_by' => $done['user_id']
		);
		$this->insert($inn_shopfront,'inn_shopfront');
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return $user_id;
	}
	
	/**
	* 修改用户信息
	* @param array $userInfo
	* @param array $done
	* @return array
	*/
	public function update_user_info($userInfo,$done)
	{
		$cond = array(
			'table' => 'user_info',
			'primaryKey' => 'user_id',
			'data' => $userInfo
		);
		$rs = $this->update($cond);
		if($rs)
		{
			$this->wLog('edit user', '用户：<a href="javascript:void(0);" class="viewUserInfo" ref="'.$userInfo['user_id'].'">'.$done['user_name'].'</a>信息被<a  href="'.base_url().'user/editinfo?uid='.$userInfo['user_id'].'" target="_blank">修改</a>', 'I', $state = 'S',$done['user_id'],'user/edit');
		}
		return $rs;
	}
	/**
	* 修改用户信息
	* @param array $userInfo
	* @param array $done
	* @return array
	*/
	public function update_user($userInfo)
	{
		$cond = array(
			'table' => 'users',
			'primaryKey' => 'user_id',
			'data' => $userInfo
		);
		return $this->update($cond);
		
	}
}