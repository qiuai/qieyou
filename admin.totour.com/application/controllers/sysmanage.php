<?php
class Sysmanage extends MY_Controller {

	public $controllerTag = 'system';

    public function __construct() 
	{
        parent::__construct();
		$this->cklogin();
    }
	
	public function userlog()
	{  
		$this->moduleTag = 'userlog';
		$type = input_string($this->input->get('type'),array('all','C','I','D','U'),'all');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,FALSE,15);
		$starttime = input_empty($this->input->get('starttime'),'');
		$endtime = input_empty($this->input->get('endtime'),'');
		if($starttime)$starttime = strtotime($starttime);
		if($endtime)$endtime = strtotime($endtime)+86399;
		$search = array(
			'starttime'	=> $starttime,
			'endtime'	=> $endtime,
			'type'=>$type
		);
		
		$rs = $this->model->get_backend_logs($search,$page,$perpage);
		$data = $rs['list'];
		$users = array();
		if($data)
		{
			foreach($data as $key => $val)
			{
				$ids[] = $val['user_id'];
			}
			$ids = implode(',',array_unique($ids));
			$users = $this->model->get_user_name_by_user_ids($ids);
		}

		$total = $rs['total'];
		$pageInfo = array(
			'total' => $total,
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $total/$perpage,
			'url' => makePageUrl($page)
		);
		$this->viewData = array(
			'data' => $data,
			'users' => $users,
			'pageInfo' => $pageInfo,
			'starttime'=> $starttime,
			'endtime'=> $endtime,
			'type'=> $type
		);
	}
}