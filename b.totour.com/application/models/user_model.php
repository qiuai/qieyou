<?php

class user_model extends MY_Model {

	public function create_user_fav($user_id,$class,$class_id)
	{
		$data = array(
			'user_id' => $user_id,
			'create_time' => $_SERVER['REQUSET_TIME'],
			''
		);
	}

	public function get_user_inn_fav($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'favorite_inn',
			'fields' => '*',
			'where' => array(
				'user_id' => $user_id
			),
			'join' => array(
				'inn_shopfront',
				'inn_shopfront.inn_id = favorite_inn.inn_id'
			),
			'order_by' => 'favorite_inn.id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		return $this->get_all($cond,$pagerInfo);
	}
	
	public function get_user_product_fav($user_id,$cate_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'favorite_product as f',
			'fields' => 'f.product_id,p.product_name,p.state,p.price,p.agent,p.category,p.thumb,p.quantity',
			'where' => array(
				'f.user_id' => $user_id,
				'p.category_id' => $cate_id
			),
			'join' => array(
				'products as p',
				'p.product_id = f.product_id'
			),
			'order_by' => 'f.id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		if(!$cate_id)
		{
			unset($cond['where']['p.category_id']);
		}
		return $this->get_all($cond,$pagerInfo);
	}

   /**
	* 用户名获取用户信息
	* @parm char $name
	* @parm bool $info
	* @return array $user_info
	*/
	public function get_user_by_name($name,$info = FALSE)
	{
		if($info)
		{
			$cond = array(
				'table' => 'users as u',
				'fields' => 'u.*',
				'where' => array(
					'u.user_name' => $name
				),
				'join' => array(
					'user_info as ui',
					'ui.user_id = u.user_id'
				)
			);
		}
		else
		{
			$cond = array(
				'table' => 'users',
				'fields' => 'user_id,user_pass,salt,inn_id,role,state',
				'where' => array(
					'user_name' => $name
				)
			);
		}
		return $this->get_one($cond);
	}
	
	public function check_fav($class,$class_id,$user_id)
	{
		if($class == 'inn')
		{
			$sql = 'SELECT id FROM `favorite_inn` WHERE `inn_id` ='.$class_id. ' AND user_id = '.$user_id;
		}
		else
		{
			$sql = 'SELECT id FROM `favorite_product` WHERE `product_id` ='.$class_id. ' AND user_id = '.$user_id;
		}
		$rs = $this->db->query($sql)->row_array();
		if($rs)
		{
			return $rs['id'];
		}
		return '0';
	}

	public function update_fav($act,$inn_id)
	{
		if($act == 'add')
		{
			$sql = 'UPDATE `inn_shopfront` SET `inn_fav` = `inn_fav` + 1 WHERE `inn_id` = '.$inn_id;
		}
		else
		{
			$sql = 'UPDATE `inn_shopfront` SET `inn_fav` = `inn_fav` - 1 WHERE `inn_id` = '.$inn_id;
		}
		return  $this->db->query($sql);
	}

	public function modify_user_fav($act,$class,$user_id,$class_info)
	{
		if($act == 'add')
		{
			$data = array(
				'user_id' => $user_id,
				'create_time' => $_SERVER['REQUEST_TIME'],
				'inn_id' => $class_info['inn_id'],
			);
			if($class == 'inn')
			{
				$data['dest_id'] = $class_info['dest_id'];
				$data['local_id'] = $class_info['local_id'];
				$this->insert($data,'favorite_inn');
			}
			else
			{
				$data['product_id'] = $class_info['product_id']; 
				$this->insert($data,'favorite_product');
			}
		}
		else{
			if($class == 'inn')
			{
				$sql = 'DELETE FROM favorite_inn WHERE id = '.$class_info['del_id'];
			}
			else
			{
				$sql = 'DELETE FROM favorite_product WHERE id = '.$class_info['del_id'];
			}
			$this->db->query($sql);
		}
	}

	public function user_logout($token)
	{
		$sql = 'DELETE FROM `access_token` WHERE token = '.$this->db->escape($token);
	 	return $this->db->query($sql);
	}

	public function update_login_info($data) 
	{
		$data['device_name'] = '';
		$data['DeviceID'] = '';
        $arr = array(
            'login_count' => ($data['login_count'] + 1),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'],
            'last_login_time' => $_SERVER['REQUEST_TIME']
        );
		$this->model->update(array('table' => 'users', 'primaryKey'=>'user_id' ,'user_id' => $data['user_id'], 'data' => $arr));
    }
	public function user_log()
	{
		return TRUE;
	}
   /**
	* 用户密码修改
	*
	* @param array $p $_POST
	* @return array
	*/
	public function updatePassWord($p)
	{
		if(empty($p['useroldpass']) || empty($p['userpass']) || empty($p['reuserpass']))
		{
			return array('code'=>'-1','msg'=>'所有项不能为空！');
		}
		$passOld = trim($p['useroldpass']);
		$passNew = trim($p['userpass']);
		$passNewRe = trim($p['reuserpass']);

		if ($passNew !== $passNewRe)
		{
			return array('code'=>'-2','msg'=>'两次输入的密码不一致！');
		}
		if(strlen($passOld) < 6 || strlen($passOld) > 16 || strlen($passNew) < 6 || strlen($passNew) > 16 ) //检测密码长度是否正确
		{
			return array('code'=>'-3','msg'=>'密码必须大于6位，小于16位！');
		}
		$user_id = $this->model->getUserId();
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id',
			'where' => array(
				'user_id' => $user_id,
				'user_name' => $user['user_name'],		//用户登陆账号
				'user_pass' => md5(md5($user['user_id']) . md5($passOld))
		)
		);
		$rs = $this->get_one($cond);
		if(!$rs)
		{
			return array('code'=>'-4','msg'=>'密码错误！'); //
		}

		$user_pass = md5(md5($user['user_id']) . md5($passNew));
		$cond = array(
			'table' => 'users',
			'primaryKey' => 'user_id',
			'data' => array(
				'user_id' => $user_id,
				'user_pass' => $user_pass,
		)
		);
		$ret = $this->update(array('id' => $user['id'], 'data' => $arr));

		return array('code'=>$ret,'msg'=>($ret == 1)?'修改成功！':'修改失败！');
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
            'User_name' => $users['username'],
            'real_name' => $users['realnmae'],
            'user_pass' => md5($users['password']),
            'face' => $users['face'],
            'mobile_phone' => $users['mobilephone'],
            'city' => $users['city'],
            'user_sex' => $users['usersex'],
        	'last_login_ip' => $_SERVER['REMOTE_ADDR'],
            'state' => 'active',
            'email' => strtolower($email),
            'role' => 'normaluser',
            'from_site' => $users['fromsite'],
            'from_site' => 'yizhan',
            'reg_time' => time(),
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
	* @param array changed  old_user_info
	* @return bool
	*/
	public function update_user_info($changed,$user_info = array())
	{
		//update cahe
		$changed['update_time'] = $_SERVER['REQUEST_TIME'] ;
		$changed['update_by'] = $changed['user_id'] ;
		$cond = array(
			'table' => 'user_info',
			'primaryKey' => 'user_id',
			'data' => $changed
		);
		return $this->update($cond);
	}
	
	public function update_inn_info($changed,$inn_info = array())
	{
		//update cahe
		$changed['update_time'] = $_SERVER['REQUEST_TIME'] ;
		$cond = array(
			'table' => 'inn_shopfront',
			'primaryKey' => 'inn_id',
			'data' => $changed
		);
		return $this->update($cond);
	}
	public function get_user_partner_by_user_id($user_id)
	{
		$cond = array(
			'table' => 'partners',
			'fileds' => 'partner_id,real_name,mobile_phone,expenditure,identity_no',
			'where' => array(
				'user_id' => $user_id,
				'partner_del' => 'N'
			),
			'order by partner_id DESC'
		);
		return $this->get_all($cond);
	}

	public function edit_user_partner($act,$user_id,$ids)
	{
		if($act == 'rm')
		{
			$this->db->query("UPDATE partners SET `partner_del` = 'Y' WHERE `partner_id` IN (".$ids.") AND user_id = ".$user_id);
			return $this->db->affected_rows();
		}
		return TRUE;
	}
}
