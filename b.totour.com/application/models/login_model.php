<?php

class Login_model extends MY_Model {
 
   /**
    * 创建用户凭证
	* @parm array $user
	* @return string token
	*/
	public function create_token($user)
	{
		$token = md5($user['user_id'].$_SERVER['REQUEST_TIME'].$user['salt'].$user['inn_id'].mt_rand(1,100000));
		$data = array(
			'token' => $token,
			'user_id' => $user['user_id'],
			'inn_id' => $user['inn_id'],
			'create_time' => $_SERVER['REQUEST_TIME']
		);
		$this->db->insert('access_token',$data);
		return $token;
	}
	
   /**
	* 用户名获取用户信息
	* @parm char $name
	* @return array $user_info
	*/
	public function get_user_by_name($name)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id,user_pass,salt,role,state',
			'where' => array(
				'user_name' => $name
			)
		);
		return $this->get_one($cond);
	}

	public function get_user_inn_by_id($user_id)
	{
		$cond = array(
			'table' => 'inns',
			'fields' => 'inn_id',
			'where' => array(
				'innholder_id' => $user_id
			)
		);
		$rs = $this->get_one($cond);
		return isset($rs['inn_id'])?$rs['inn_id']:'';
	}
}