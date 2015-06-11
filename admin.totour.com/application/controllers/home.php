<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public $autoLoadModel = FALSE;

	function __construct() {  
        parent::__construct();
		$this->cklogin();
	}

	public function index() 
	{
		if(empty($_COOKIE['changeSlider']))
		{
			$userRole = $this->get_user_role();
			switch($userRole)
			{
				case 'innholder':
					header("Location: ".base_url()."inns");
					break;
				case 'admin':
					header("Location: ".base_url()."order/qieyou");
					break;
				default:
					header("Location: ".base_url()."order/qieyou");
					break;
			}
		}
		else
		{
			header("Location: ".base_url()."sysconfig?class=group");
		}
		exit;
	}
}