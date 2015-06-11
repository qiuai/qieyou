<?php
class coupon extends MY_Controller {
	public $controllerTag = 'coupon';	//左侧菜单的对应

	public function __construct() {
		parent::__construct();
		$this->cklogin(); //检查是否登录
	}
	
	public function index()
	{
		$this->moduleTag = 'coupon';
		$state="all";
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);	
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		$status = input_string($this->input->get('status'),array('all','N','Y','Z'),'all');
		$keyword = trim($this->input->get('keyword'));
		if($starttime)	$starttime = strtotime($starttime);
		if($endtime)  $endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime,
			'keyword'	=> $keyword,
			'status'	=> $status
		);
		
		$rs = $this->model->get_coupon($search,$page,$perpage);
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
			'state'=>$state,
			'status'=>$status
		);
	}
	public function use_coupon()
	{
		$this->moduleTag = 'coupon';
		$state="use";
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
		
		$rs = $this->model->use_coupon($search,$page,$perpage);
		
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
			'state'=>$state
		);	
	}
	public function add_coupon(){
		$this->moduleTag = 'coupon';
		if(is_post())
		{	
			$data=$this->check_coupon();
			$info = $this->model->add_coupon($data);
			if(!$info)
			{
				response_code('-1');
			}
			response_code('1');
		}
	}
	public function edit_coupon(){
		$this->moduleTag = 'coupon';	
		$quan_id = input_int($this->input->get('quan_id'),1,FALSE,0);	
		if($quan_id)$info = $this->model->get_coupon_info_by_id($quan_id);
		if(is_post())
		{	
			$data=$this->check_coupon();
			$data['quan_id']=input_int($this->input->post('quan_id'),1,FALSE,0);	
			$rs = $this->model->edit_coupon($data);
			if(!$rs)
			{
				response_code('-1');
			}
			response_code('1');
		}
		$this->viewData = array(
			'info' => $info	
		);
	}
	private function check_coupon() 
	{
		$data['quan_name']=check_empty($this->input->post('quan_name'));
		$data['amount']=check_empty($this->input->post('amount'));
		$data['total']=$this->input->post('total');
		$data['require']=$this->input->post('require');
		$data['end_time']=strtotime($this->input->post('end_time'));
		return $data;
	}
	/*发放*/
	public function is_provide(){
		$quan_id= input_int($this->input->post('quan_id'),1,FALSE,0);
		$rs=$this->model->is_provide($quan_id);
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
	/*删除*/
	public function is_del(){
		$quan_id= input_int($this->input->post('quan_id'),1,FALSE,0);
		$rs=$this->model->del_coupon($quan_id);	
		if(!$rs){
			response_code('-1');
		}
		response_code('1');
	}
}