<?php

class Login extends MY_Controller {

   /**
    * 用户token获取
	**/
	public function userLogin()
	{
		$username = check_empty(trimall(strip_tags($this->input->post('username',TRUE))),FALSE,'1002');
		$password = check_empty(trimall(strip_tags($this->input->post('password',TRUE))),FALSE,'1003');	//f61e83b9c803be5003ceddacfc6010ba

		$namelen = strlen($username);
		if($namelen<4||$namelen>16)
		{
			response_code('1002');
		}
		$passlen = strlen($password);
		if($passlen != 32)
		{
			response_code('1003');
		}

		$user = $this->model->get_user_auth_by_name($username);
		if(!$user)	//用户不存在
		{
			response_code('1004');
		}
		
		//密码错误
		if(md5($password.$user['salt']) != $user['user_pass'])
		{
			response_code('1004');
		}
		
		if($user['role'] == 'innholder')
		{
			$inn = $this->model->get_user_inn($user['user_id']);
			$user['inn_id'] = $inn['inn_id'];
		}
		$data['token'] = $this->create_token($user);
		response_data($data);
	}

   /**
    * 创建用户凭证
	* @parm array $user
	* @return string token
	*/
	private function create_token($user)
	{
		if(!$this->tokenMemcache)
		{
			$this->load_token_memcache();
		}
		$token = md5(md5($user['user_id']).TIME_NOW.$user['salt'].mt_rand(1,100000));
		$data = array(
			'user_id' => $user['user_id'],
			'create_time' => TIME_NOW
		);
		if(isset($user['inn_id']))
		{
			$data['inn_id'] = $user['inn_id'];
			$data['role'] = 'innholder';
		}
		$this->tokenMemcache->set('token_'.$token,$data,FALSE,600);
		return $token;
	}

   /**
    * 第三方登录
	* get
	*/
	public function thirdpart()
	{
		$third = input_string($this->input->get('to'),array('sina','qq'),FALSE,'1001');
		if($this->get_user_id())
		{
			jsBack('您已经成功登录！');
		}
		$arr = array('state'=> md5(uniqid(rand(), TRUE)),'url' => $this->input->get('url'));//CSRF protection
		$this->web_user->set_userdata($arr);
		switch($third)
		{
			case 'qq':
				$appid = $this->config->item('qq_appid');
				$callback = base_url().'login/qq_call?url='.$this->input->get('url');
				$scope = 'get_user_info';
				$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appid . "&redirect_uri=" . urlencode($callback). "&state=" . $arr['state']. "&scope=".$scope;
			break;
			case 'sina':
				$appkey = $this->config->item('wb_key');
				$callback = base_url().'login/wb_call?url='.$this->input->get('url');
				$scope = $this->config->item('wb_scope');
				$login_url = "https://api.weibo.com/oauth2/authorize?response_type=code&client_id=".$appkey."&redirect_uri=".urlencode($callback)."&state=".$arr['state']."&scope=".$scope;
			break;
		}
		header("Location:$login_url");
		exit;
	}

	public function qq_call()
	{
		//获取用户授权令牌，Access_Token。
		$state = $this->input->get('state');
		$code = $this->input->get('code');
		if($this->session->userdata('state') != $state ){echo "lost session, key：state";exit;}
		$appid = $this->config->item('qq_appid');
		$appkey = $this->config->item('qq_appkey');
		$callback = base_url().'user/qq_callback?url='.$this->session->userdata('url');
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&" . "client_id=" . $appid. "&redirect_uri=" . urlencode($callback) . "&client_secret=" . $appkey. "&code=" . $code;
		$response = file_get_contents($token_url);
		if(strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$str  = substr($response, $lpos + 1, $rpos - $lpos -1); 
			$msg = json_decode($response);
            if (isset($msg->error)){ echo "<h3>error:</h3>" . $msg->error; echo "<h3>msg  :</h3>" . $msg->error_description; exit;}
		}
        $params = array();
        parse_str($response, $params);
		$this->session->set_userdata('access_token',$params['access_token']);
	
		//获取用户授权ID
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $params['access_token'];
		$get_openid = file_get_contents($graph_url);
		if (strpos($get_openid, "callback") !== false)
		{
			$lpos = strpos($get_openid, "(");
			$rpos = strrpos($get_openid, ")");
			$get_openid  = substr($get_openid, $lpos + 1, $rpos - $lpos -1);
		}
		$user = json_decode($get_openid);
		if (isset($user->error)){ echo "<h3>error:</h3>" . $msg->error; echo "<h3>msg  :</h3>" . $msg->error_description; exit;}
		$this->session->set_userdata('openid',$user->openid);	//607E4E2A9FCF1B73EBBD1783C481D621
		//获取用户基本信息 注册|登陆
		
		$rs = $this->third_party_login($appid,$params['access_token'],$user->openid,'tencent');
		if($rs){ header("Location: ".base_url().$this->session->userdata('url').""); }
		else{ echo "<h3>error:</h3> -1"; echo "<h3>msg  :</h3> network error !"; }
		exit;
	}

	public function wb_callback()
	{
		$state = $this->input->get('state');
		$code = $this->input->get('code');
		if($this->session->userdata('state') != $state ){echo "lost session, key：state";exit;}
		$appkey = $this->config->item('wb_key');
		$appsecret = $this->config->item('wb_secret');
		$callback = base_url().'user/wb_callback?url='.$this->session->userdata('url');
		$url = 'https://api.weibo.com/oauth2/access_token';
		$request_param = array(
			'client_id' => $appkey,
			'client_secret' => $appsecret,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $callback,
			'code' => $code
		);
		$context = array();
		$context['http'] = array ( 
			'method' => 'post', 
			'content' => http_build_query($request_param, '', '&')
		);
		$response = file_get_contents($url, false, stream_context_create($context));
		//this url return {"access_token":"2.00xklo_Ct_8LNC5b90fd10b2SgGP4B","remind_in":"157679999","expires_in":157679999,"uid":"2125116027","scope":"follow_app_official_microblog"} 
		//出错情况{"error":"invalid_request","error_code":21323,"request":"/oauth2/access_token","error_uri":"/oauth2/access_token","error_description":"miss redirect uri."}
		if(strpos($response, "error") !== false)	//错误处理	
		{
			$msg = json_decode($response);
            if (isset($msg->error)){ echo "<h3>error:</h3>" . $msg->error; echo "<h3>code :</h3>" . $msg->error_code; echo "<h3>msg  :</h3>" . $msg->error_description; exit;}
		}
		$params = json_decode($response,TRUE);
		$this->session->set_userdata('access_token',$params['access_token']);
		$this->session->set_userdata('uid',$params['uid']);		//微博用户id
		//获取用户信息 注册|登陆

		$rs = $this->third_party_login('',$params['access_token'],$params['uid'],'weibo');
		if($rs){ header("Location: ".base_url().$this->session->userdata('url').""); }
		else{ echo "<h3>error:</h3> -1"; echo "<h3>msg  :</h3> network error !"; }
		exit;
	}

	private function third_party_login($appid,$access_token,$openid,$from_site)
	{
		if(!in_array($from_site,array('tencent','weibo')))
		{
			return FALSE;
		}
		$cond = array(
			'table' => 'users',
			'fields' => '*',
			'where' => array(
				'from_site' => $from_site,
				'site_id' => $openid
			)
		);
		$user = $this->get_one($cond);
		if($user)
		{
			return $this->user_login($user);
		}
		$get_user_info = NULL;
		switch($from_site){
			case 'tencent':
				$get_user_info_url = 'https://graph.qq.com/user/get_user_info?access_token='.$access_token.'&oauth_consumer_key='.$appid.'&openid='.$openid.'';
				$get_user_info = file_get_contents($get_user_info_url);
				if(strpos($get_user_info, "callback") !== false)
				{
					$lpos = strpos($get_user_info, "(");
					$rpos = strrpos($get_user_info, ")");
					$user_info  = substr($get_user_info, $lpos + 1, $rpos - $lpos -1);
					$user_info = json_decode($user_info);
					if (isset($user_info->error)){ echo "<h3>error:</h3>" . $user_info->error; echo "<h3>msg  :</h3>" . $user_info->error_description; exit; }
				}
				break;
			case 'weibo':
				$get_user_info_url = 'https://api.weibo.com/2/users/show.json?access_token='.$access_token.'&uid='.$openid.'';
				$get_user_info = file_get_contents($get_user_info_url);
				//{"error":"User does not exists!","error_code":20003,"request":"/2/users/show.json"}
				if(strpos($get_user_info, "error") !== false)	//错误处理	
				{
					$msg = json_decode($get_user_info);
					if (isset($msg->error)){ echo "<h3>error:</h3>" . $msg->error; echo "<h3>code :</h3>" . $msg->error_code; exit;}
				}
				break;
			default:
				return FALSE;
		}
		if($get_user_info)
		{
			$user_info = json_decode($get_user_info,true);
			$user_info['site_id'] = $openid;
			$user_info['access_token'] = $access_token;
			$user_id = $this->reg_from_third_party($user_info,$from_site);
			if($user_id)
			{
				$user = $this->get_one($cond);
				return $this->user_login($user);
			}
		}
		return FALSE;
	}

   /**
	* 用户登陆
	* @param array $userinfo
	* @return bool
	*/
	private function user_login($userinfo)	//写session
	{
		// 未读消息
		$this->load->model('message_model');
		$userinfo['unread_msg'] = $this->message_model->is_has_message_unread($userinfo['user_id']);
		
		$this->web_user->login($userinfo);
		/* start update user logininfo*/
	/*	switch($user['from_site'])
		{
			case 'tencent':
			case 'weibo':
				$arr['access_token'] = $this->session->userdata('access_token');
				$arr['last_login_ip'] = $this->session->userdata('ip_addr');
				break;
			default:
				break;
		}
		$this->web_user->login($user);*/
		$this->model->update_user_login($userinfo);
		/* end update user logininfo*/

		return TRUE;
	}

	public function changePwd()
	{
		$userId = $this->get_user_id(TRUE);
		$oldpassword = check_empty($this->input->post('password'),FALSE,'1004');		//仅验证是否填写
		$new_password = check_empty($this->input->post('newPassword'),FALSE,'5004');
		$repassword = check_empty($this->input->post('confirm'),FALSE,'5004');
		if($new_password!=$repassword)
		{
			response_code('5005');
		}
		if(strlen($oldpassword) != 32)
		{
			response_code('1004');
		}
		$passlen = strlen($new_password);
		if($passlen<6||$passlen>16)
		{
			response_code('5004');
		}

		$user = $this->model->get_user_auth_by_name($userId,TRUE);
		if(!$user)	//用户不存在
		{
			response_code('4005');
		}
		if(md5($oldpassword.$user['salt']) !=$user['user_pass'])
		{
			response_code('5006');
		}
		if($oldpassword != $new_password)
		{
			$user['user_id']=$userId;
			$user['salt'] = getRandChar(4);
			$user['user_pass']=md5(md5($new_password).$user['salt']);
			
			if(!$this->model->update_user_password($user))
			{
				response_code('4000');
			}
		}
		response_code('1');
	}

	public function logout()
	{
		if($this->get_user_id(TRUE))
		{
			$this->tokenMemcache->delete('token_'.$this->input->get('token'));
		}
		response_code('1');
	}

   /**
    * 发送用户注册短信
    **/
	public function userRegSMS()
	{
		$mobile = input_mobilenum($this->input->post('mobile'),'5001');
		$rs = $this->model->get_user_auth_by_mobile($mobile,TRUE);
		if($rs)
		{
			response_code('1007');
		}

		$mobile_cache = $this->model->check_mobile_send($mobile);				//缓存取数据 
		if($mobile_cache&&$mobile_cache['sms_sendtime'] > (TIME_NOW -60))		//短信间隔太短
		{
			response_code('5008');
		}

		$mobile_identify = make_mobile_identify_code();
	/*	$current = array('check_mobile'=>$mobile,'mobile_identify' => $mobile_identify ,'sms_sendtime' => TIME_NOW);
		$this->set_current_data($current);*/

		$mobile_cache = array('mobile_identify' => $mobile_identify ,'sms_sendtime' => TIME_NOW);
		$this->model->save_mobile_identify($mobile,$mobile_cache);

		$message = array(
			'type' => 'regUser',
			'mobile' => $mobile,
			'param' => array(
				$mobile_identify,'5'
			)
		);
		
		$rs = $this->sendSMS($message);
		$rs = json_decode($rs,TRUE);
		if(isset($rs['respCode']))
		{
			log_message('ERROR',json_encode($rs));
			if($rs['respCode'] == '105122')
			{
				response_row(array('code' => '-1','msg' => '同一手机每天只能发送8条短信'));
			}
		}
		response_code('1');
	}

   /**
    * 忘记密码短信接口
    **/
	public function forgotPassSMS()
	{
		$mobile = input_mobilenum($this->input->post('mobile'),'5001');
		$rs = $this->model->get_user_auth_by_mobile($mobile,TRUE);
		if(!$rs)
		{
			response_code('4005');
		}
		
		$mobile_cache = $this->model->check_mobile_send($mobile);	//缓存取数据 
		if($mobile_cache&&$mobile_cache['sms_sendtime'] < (TIME_NOW -60))		//短信间隔太短
		{
			response_code('5008');
		}

		$mobile_identify = make_mobile_identify_code();
	/*	$current = array('check_mobile'=>$mobile,'sms_sendtime' => TIME_NOW);
		$this->set_current_data($current);*/

		$mobile_cache = array('mobile_identify' => $mobile_identify ,'sms_sendtime' => TIME_NOW);
		$this->model->save_mobile_identify($mobile,$mobile_cache);

		$message = array(
			'type' => 'forgotUser',
			'mobile' => $mobile,
			'param' => array(
				$mobile_identify,'5'
			)
		);
		
		$rs = $this->sendSMS($message);
		$rs = json_decode($rs,TRUE);
		if(isset($rs['respCode']))
		{
			log_message('ERROR',json_encode($rs));
			if($rs['respCode'] == '105122')
			{
				response_row(array('code' => '-1','msg' => '同一手机每天只能发送8条短信'));
			}
		}
		response_code('1');
	}

   /**
	* 找回密码
	*/
    public function forgetPwd()
    {
		$user_mobile = checkUserName($this->input->post('mobile'),'1002');
		$identifycode = check_empty($this->input->post('verifycode'),FALSE,'5002');
		$password = check_empty($this->input->post('password'),FALSE,'1003');
	//	$token = check_empty($this->input->post('token'),FALSE,'5013');		//需要session
		$passlen = strlen($password);
		if($passlen<6||$passlen>16)
		{
			response_code('1003');
		}
	/*	$check_mobile = $this->get_current_data('check_mobile');
		if($check_mobile != $user_mobile)
		{
			response_code('5002');
		}*/
	/*	if($token != $this->get_current_data('token'))
		{
			response_code('5013');
		}*/
		$user = $this->model->get_user_auth_by_mobile($user_mobile,TRUE);
		if(!$user)
		{
			response_code('4005');
		}
		$mobile_cache = $this->model->check_mobile_send($user_mobile);	//缓存取数据 
		if(!$mobile_cache)		//过期
		{
			response_code('5012');
		}
		if($identifycode != $mobile_cache['mobile_identify'])		//验证码错误
		{
			response_code('5002');
		}

		$data['user_id']=$user['user_id'];
		$data['salt'] = getRandChar(4);
		$data['user_pass']=md5(md5($password).$data['salt']);

		if($this->model->update_user_password($data))
		{
			$this->model->delete_mobile_identify($user_mobile);
			response_code('1');
		}
		response_code('4000');
    }

   /**
    * 注册用户
	**/
	public function userregpost()
	{
		$username = checkUserName($this->input->post('username'),'1002');
		$identifycode = check_empty($this->input->post('identifycode'),FALSE,'5002');
		$password = check_empty($this->input->post('password'),FALSE,'1003');
		$passlen = strlen($password);
		if($passlen<6||$passlen>16)
		{
			response_code('1003');
		}
	/*	$user_mobile = $this->get_current_data('check_mobile');
		if($user_mobile != $username)
		{
			response_code('5002');
		}*/
		$user_mobile = $username;
		$mobile_cache = $this->model->check_mobile_send($user_mobile);	//缓存取数据 
		if(!$mobile_cache)		//过期
		{
			response_code('5012');
		}
		if($identifycode != $mobile_cache['mobile_identify'])		//验证码错误
		{
			response_code('5002');
		}
		$user = array(
			'password' => $password,
			'mobile' => $user_mobile
		);
		$user['salt'] = getRandChar(4);
		$user_id = $this->model->reg_user($user);
		if($user_id)
		{
			$this->model->delete_mobile_identify($user_mobile);
			/*$session = array(
				'user_id' => $user_id,
				'user_name' => $user_mobile,
				'nick_name' => '手机用户',
			);
			$this->set_current_data($session);*/
			//帮助用户登录
			$user['user_id'] = $user_id;
			$token = $this->create_token($user);
			response_json('1',array('token' => $token));
		}
		response_code('4000');
	}

	public function regDevice()
	{
		
	}

	
	private function sendSMS($message)
	{	
		$options['accountsid'] = $this->config->item('sms_ucpaas_sid');
		$options['token'] = $this->config->item('sms_ucpaas_token');
		$this->load->library('sms_ucpaas',$options);

		$sms_ucpaas_sid = $this->config->item('sms_ucpaas_appid');
		switch($message['type'])
		{
			case 'regUser':		//注册提醒
				$templateId = '5171';
				break;
			case 'forgotUser':	//忘记密码
				$templateId = '5199';
				break;
			case 'bondMobile':	//绑定手机
				$templateId = '5200';
				break;
			default:
				exit;
		}
		$param = implode(',',$message['param']);
		return $this->sms_ucpaas->templateSMS($sms_ucpaas_sid,$message['mobile'],$templateId,$param);
	}

}