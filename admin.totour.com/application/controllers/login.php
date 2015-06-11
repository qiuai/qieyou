<?php

class Login extends MY_Controller {

    public $layout = 'home';
	public $layout_for_title = '磨房驿栈后台登录';

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 登陆
	*/
	public function index() 
	{
		if($this->web_user->get_id())
		{
			header("Location: ".base_url()."home"); 	
		}
		$this->viewFile = 'login';
    }

   /**
    * 用户登陆验证 POST
	* return bool
	*/
	public function userlogin()
	{
		$username = $this->input->post('username',TRUE);
		$password = $this->input->post('password',TRUE);
		if (empty($username) || empty($password))
		{
			echo '-1';
			exit;
		}
		//echo $this->model->login($username,$password);

		print_r( $this->model->login($username,$password));
		exit;
	}

   /**
    * 用户登出
	* return bool
	*/
	public function logout()
	{
		$this->model->logout();
		header("Location: ".base_url()."home"); 
		exit;
	}
}