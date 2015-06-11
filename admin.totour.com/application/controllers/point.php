<?php
class Point extends MY_Controller {
	public $controllerTag = 'point';	//左侧菜单的对应

	public function __construct() {
		parent::__construct();
		$this->cklogin(); //检查是否登录
	}
	
	public function index()
	{	
		$y=date("Y",time());
		$m=date("m",time());
		$d=date("d",time());
		$t1=mktime(0,0,0,$m,1,$y); //创建本月开始时间 
		$t2=time()-86400; //昨天 
		
		$this->moduleTag = 'point';
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),0,FALSE,15);
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		$starttime=$starttime?$starttime:date('Y-m-d',$t1);
		$endtime=$endtime?$endtime:date('Y-m-d',$t2);
		
		if($starttime)$starttime = strtotime($starttime);
		if($endtime)$endtime = strtotime($endtime)+86399;
		
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime
		);
		$rs = $this->model->get_point($search,$page,$perpage);
		$total = $rs ['total'];
		$data = $rs ['list'];
		$send_point='';
		$reduce_point='';
		foreach($data as $k=>$v){		
			$send_point += $v['send_point'];
			$reduce_point += $v['use_point'];
		}
		
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
			'send_point'=>$send_point,
			'reduce_point'=>$reduce_point,
		);
	}
}