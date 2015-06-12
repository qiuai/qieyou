<?php

class Partners extends WEBbase {
	
	public $autoLoadModel = FALSE;

    public function __construct() 
	{
        parent::__construct();
		$this->_LoadModel('partners');
	}

   /**
    * 管理中心 入口页面 采用app方法
    **/
	public function index()
	{
		// $user_id = $this->get_user_id(TRUE);
		// $inn_id = $this->get_user_id(TRUE);
		// //$inn_info = $this->manage_model->get_inn_info_by_ids($inn_id);
		// $user_info = $this->manage_model->get_user_detail($user_id);
		// $this->viewData = array(
		// 	'user' => $user_info,
		// 	'inn' => $inn_info,	
		// );
		$this->viewFile = 'manage/partners.php';
	}
	
	// 客户管理列表
	public function customerList(){
		$page = input_int($this->input->get('page'),1,FALSE,FALSE,'1015');					//分页
		$perpage = input_int($this->input->get('perpage'),1,FALSE,FALSE,'1016');			//分页
		$search = $this->input->get('search');
	
		$this->load->model('partners_model');
		$data = $this->partners_model->get_list($this->token['user_id'],$page, $perpage,$search);
	
		response_data($data);
	}
	
	// 客户备注操作
	public function customerSaveNote(){
		$partner_id = $this->input->get('partner_id');
		$note = $this->input->get('note');
		$this->load->model('partners_model');
		if($this->partners_model->saveNote($this->token['user_id'],$partner_id,$note)){
			response_msg('1');	// 成功
		}
		response_msg('-1'); // 失败
	}
}