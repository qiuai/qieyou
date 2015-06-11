<?php
class message extends MY_Controller {
	public $controllerTag = 'message';	//左侧菜单的对应

	public function __construct() {
		parent::__construct();
		$this->cklogin(); //检查是否登录
	}
	public function index()
	{
		$this->moduleTag = 'message';
		$state="all";
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);	
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		$keyword = $this->input->get('keyword');
		if($starttime)	$starttime = strtotime($starttime);
		if($endtime)	$endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime,
			'keyword'	=> $keyword
		);		
		$rs = $this->model->get_message($search,$page,$perpage);
		$total = $rs ['total'];
		$data = $rs ['list'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/15,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime,
		);
	}
	/*发布消息*/
	public function add_message(){	
		$this->moduleTag = 'message';
		if(is_post())
		{	
			$data = $this->check_message();	
			$info = $this->model->add_message_info($data);
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}	
	private function check_message() 
	{	
		//$info['img'] = $this->input->post('img');
		$info['note'] = $this->input->post('note');
		$info['role'] =$this->input->post('role');
		return $info;
	}
	/*意见反馈*/
	public function feedback()
	{
		$this->moduleTag = 'feedback';
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);	
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		if($starttime)	$starttime = strtotime($starttime);
		if($endtime)	$endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime
		);
		
		$rs = $this->model->get_user_feedback($search,$page,$perpage);
		
		$total = $rs ['total'];
		$data = $rs ['list'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/15,
			'url' =>makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime,
		);	
	}
	
	
	/*删除*/
	public function is_del_feedback(){
		$feed_id= input_int($this->input->post('feed_id'),1,FALSE,0);
		$rs=$this->model->is_del_feedback($feed_id);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
}