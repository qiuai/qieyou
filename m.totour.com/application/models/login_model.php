<?php

class Login_model extends MY_Model {
	
	public $loadmemcache = TRUE;

	public function get_user_auth_by_name($parm,$id = FALSE)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id,user_name,user_pass,salt,user_mobile,role',
			'where' => array(
				'user_name' => $parm	
			)
		);
		if($id) 
		{
			$cond['where'] = array('user_id'=>$parm);
		}
		return $this->get_one($cond);
	}
	
	public function get_user_auth_by_mobile($mobile,$user_name = TRUE)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id,user_name,user_pass,salt,user_mobile,role',
			'where' => array(
				'user_mobile' => $mobile
			)
		);
		if($user_name)
		{
			$cond['where'] = 'user_mobile = "'.$mobile.'" OR user_name = "'.$mobile.'" ';
		}
		return $this->get_one($cond);
	}

	public function get_user_inn($user_id)
	{
		$cond = array(
			'table' => 'inns',
			'fields' => 'inn_id',
			'where' => array(
				'innholder_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}

	public function update_user_login($user)
	{
		$arr = array(
			'user_id' => $user['user_id'],
            'login_count' => ((int)$user['login_count'] + 1),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'],
            'last_login_time' => $_SERVER['REQUEST_TIME']
        );

		$cond = array(
			'table'	=> 'user_info',
			'primaryKey' => 'user_id',
			'data' => $arr
		);
		if(!$user['today_login'])
		{
			$this->update_user_point($user['user_id'],5,'每日登陆奖励',20);
			$cond['data']['today_login'] = 1;
		}
		if($this->update($cond))
		{
			return TRUE;
		}
		return FALSE;
	}

	public function update_user_password($user)
	{
		$cond = array(
			'table' => 'users',	
			'primaryKey' => 'user_id',
			'data' => $user
		);
		if($this->update($cond))
		{
			return TRUE;
		}
		return FALSE;
	}
	
	public function reg_user($data)
	{
		$real_user_pass = $data['password'];
		$salt = getRandChar(4);
		$userpwd = md5(md5($real_user_pass).$salt);
		$user = array(
			'user_name' => $data['mobile'],
			'user_pass' => $userpwd,
			'salt' => $salt,
			'user_mobile' => $data['mobile'],
			'role' => 'user',
			'create_time' => TIME_NOW
		);
		$this->db->trans_start();
		$user_id = $this->insert($user,'users');
		$userInfo = array(
			'user_id' => $user_id,
			'user_name' => $data['mobile'],
			'nick_name' => '手机用户',
			'last_login_time' => TIME_NOW,
			'last_login_ip' => $_SERVER['REMOTE_ADDR'],
			'create_time' => TIME_NOW,
			'create_by' => $user_id,
			'update_time' => TIME_NOW,
			'update_by' => $user_id
		);
		$this->insert($userInfo,'user_info');
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return $user_id;
	}
}