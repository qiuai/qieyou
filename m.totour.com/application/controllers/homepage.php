<?php

class Homepage extends MY_Controller {

	public $layout_for_title = '且游旅行';
	public $useLayout = FALSE;
	public $directView = FALSE;

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{
		$home = $this->model->home_config();
		$this->viewFile = 'homepage';
		$this->viewData = array(
			'groups' => isset($home['groups'])?$home['groups']:array(),
			'products' => isset($home['products'])?$home['products']:array(),
			'jianren' => isset($home['jianren'])?$home['jianren']:array()
		);
    }

    public function sendEmail()
    {

    	$this->load->library('email');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.exmail.qq.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'liusanjiang@tour0888.com';
		$config['smtp_pass'] = 'qy2015lsj';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n";
		$config['crlf'] = "\r\n"; 

		$this->email->initialize($config);

		$this->email->from('liusanjiang@tour0888.com', 'Your Name');
		$this->email->to('sanonz@126.com'); 
		$this->email->cc('yang-toxy@hotmail.com');

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.'); 

		$this->email->send();

		echo $this->email->print_debugger();
    }
}