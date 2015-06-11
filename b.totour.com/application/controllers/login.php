<?php

class Login extends MY_Controller {

    public function __construct() 
	{
        parent::__construct();
    }

	public function index()
	{
		$name = input_mobilenum($this->input->post('name'),'1004');
		$password = $this->input->post('password');
		$device = $this->input->post('device');
		$device_id = $this->input->post('device_id');
		if(empty($password))
		{
			response_msg('1002');
		}
		$user = $this->model->get_user_by_name($name);
		if(!$user)
		{
			response_msg('1003');
		}
		$password = md5($password.$user['salt']);
		if($user['user_pass'] != $password)
		{
			response_msg('1002');
		}
		if($user['state'] == 'locked')
		{
			response_msg('1006');
		}
		$user['inn_id'] = $this->model->get_user_inn_by_id($user['user_id']);
		if(!$user['inn_id'])
		{
			response_msg('1006');
		}
		$data['token'] = $this->model->create_token($user);
		$data['role'] = $user['role'];
		$data['state'] = $user['state'];
	//	$this->user_model->update_login_info($user);
		response_data($data);
	}

	public function get_index()
	{
		//echo md5('qieyou');exit; f61e83b9c803be5003ceddacfc6010ba
		$name = input_mobilenum($this->input->get('name'),'1004');
		$password = $this->input->get('password');
		$device = $this->input->get('device');
		$device_id = $this->input->get('device_id');
		if(empty($password))
		{
			response_msg('1002');
		}
		$user = $this->model->get_user_by_name($name);
		if(!$user)
		{
			response_msg('1003');
		}
		$password = md5($password.$user['salt']);
		if($user['user_pass'] != $password)
		{
			response_msg('1002');
		}
		if($user['state'] == 'locked')
		{
			response_msg('1006');
		}
		$user['inn_id'] = $this->model->get_user_inn_by_id($user['user_id']);
		if(!$user['inn_id'])
		{
			response_msg('1006');
		}
		$data['token'] = $this->model->create_token($user);
		$data['role'] = $user['role'];
		$data['state'] = $user['state'];
	//	$this->user_model->update_login_info($user);
		response_data($data);
	}
}