<?php

class Help extends MY_Controller {

	public $layout_for_title = '帮助中心';
	public $layout = 'simple';
	public $autoLoadModel = FALSE;

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{

    }
	
	/**
	* 关于且游
	*/
	public function qieyou() 
	{
		$this->moduleTag = '关于且游';
		$this->viewData = array(
			'refer' => $this->input->get('refer')?TRUE:FALSE
		);
    }
	/**
	* 积分规则
	*/
	public function jifen() 
	{
		$this->moduleTag = '积分规则';
		$this->viewData = array(
			'refer' => $this->input->get('refer')?TRUE:FALSE
		);
    }

	public function download()
	{	
		$this->useLayout = FALSE;
	    $this->directView = FALSE;
		$this->viewFile = 'downloadApp';
		
	}
}