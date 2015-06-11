<?php

class Login_model extends MY_Model {
	
	public function login($username,$password)
	{
		$auth = $this->checkuser($username, $password);
        if($auth)
		{
			if($auth['state'] == 'locked')
			{
				return '-2';
			}
			elseif($auth['state'] == 'del')
			{
				return '-4';
			}
			if($auth['role'] == 'innholder')
			{
				return '-5';
			}
			else
			{
				$cond = array(
					'table' => 'city_manage as cm',
					'fields' => 'cm.city_id,cm.inn_id,ca.name as city_name',
					'where' => 'cm.user_id = '.$auth['user_id'],
					'join' => array('china_area as ca','ca.area_id = cm.city_id')
				);
				$rs = $this->get_one($cond);
				if($rs)
				{
					$auth['city_id'] = $rs['city_id'];
					$auth['inn_id'] = $rs['inn_id'];
					$auth['city_name'] = $rs['city_name'];
				}
			}
			$user_info = $this->get_user_info($auth['user_id']);
            $this->web_user->login(array_merge($auth,$user_info));
			$this->update_login_info($auth['user_id']);
			$this->wLog('Backend Login', '用户登陆','C');

            return '1';
        }
        return '0';
	}

	public function get_user_info($user_id)
	{
		$cond = array(
			'table' => 'user_info',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}

   /**
	* 验证用户账号密码
	* @return array
	*/
	public function checkuser($userName, $userPass)
	{
		$cond = array(
			'table' => 'users',
            'fields' => '*',
            'where' => 'user_name = '.$this->db->escape($userName).' AND role IN (2,3,4,6)'
		);
		$rs = $this->get_one($cond);
		if($rs && $rs['user_pass'] == md5(md5($userPass).$rs['salt']))
		{
			return $rs;
		}
		return array();
	}
	
   /**
    * 更新用户登录信息
	* @parm $user_id
	* @return bool
	*/
	public function update_login_info($user_id)
	{
		$sql = 'UPDATE `user_info` SET `login_count` = `login_count` + 1 , `last_login_ip` = "'.$_SERVER['REMOTE_ADDR'].'", `last_login_time` = '.$_SERVER['REQUEST_TIME'].' WHERE `user_id` = '.$user_id;
		return $this->db->query($sql);
    }

	public function logout()
	{
		return $this->web_user->logout();
	}
}