<?php

class sysmanage_model extends MY_Model {

   /**
	* 时间条件获取后台日志
	* @param int $page
	* @return array
	*/
	public function get_backend_logs($search,$page,$perpage)
	{	
		$cond = array(
			'table' => 'sys_logs',
			'fields' => '*',
			'order_by' => 'id DESC'
		);
		$pageInfo = array(
			'cur_page' => $page,
			'per_page' => $perpage
		);
		if($search['starttime'])
		{
			$cond['where'] = '';
			$cond['where'] .= 'create_time > '.$search['starttime'];
		}
		if($search['endtime'])
		{
			$cond['where'] = empty($cond['where'])?'':$cond['where'].' AND ';
			$cond['where'] .= 'create_time < '.$search['endtime'];
		}
		if($search['type']!='all'){
			$cond['where'] = empty($cond['where'])?'':$cond['where'].' AND ';
			$cond['where'] .= "event_level = '".$search['type']."'";
		}
		$total = $this->get_total($cond);
		$list = array();
		if($total)
		{
			$list = $this->get_all($cond,$pageInfo);
		}
		return array('total' => $total, 'list' => $list);
	}

   /**
	* 用户日志真实姓名获取
	* @param sqlstring $ids
	* @return array
	*/
	public function get_user_name_by_user_ids($ids)
	{
		$cond = array(
			'table' => 'users',
			'fields' => 'user_id,user_name',
			'where' => 'user_id IN ('.$ids.')'
		);
		$rs = $this->get_all($cond);
		$data = array();
		foreach($rs as $key => $val)
		{
			$data[$val['user_id']] = $val['user_name'];
		}
		return $data;
	}
}