<?php 
class message_model extends MY_Model {
	public function get_message($search,$page,$perpage)
	{	
		$cond = array(
			'table' => 'sys_message as c',
			'fields' => 'c.*,i.user_name',
			'join' => array('user_info as i','c.user_id = i.user_id'),
			'order_by'=>'c.create_time DESC'
		);
		$pageInfo = array(
			'cur_page' => $page,
			'per_page' => $perpage
		);
		if($search['starttime'])
		{
			$cond['where'] = '';
			$cond['where'] .= 'c.create_time > '.$search['starttime'];
		}
		if($search['endtime'])
		{
			$cond['where'] = empty($cond['where'])?'':$cond['where'].' AND ';
			$cond['where'] .= 'c.create_time < '.$search['endtime'];
		}
		$total = $this->get_total($cond);
		$list = array();
		if($total)
		{
			$list = $this->get_all($cond,$pageInfo);
		}
		return array('total' => $total, 'list' => $list);
	}
	
	public function get_user_feedback($search,$page,$per_page)
	{
		$select = "SELECT q.*,i.user_name FROM ";
		$selectfrom = ' feedbacks as q LEFT JOIN user_info as i ON q.user_id = i.user_id';
		$selectjoin = ' ';
		$where= " where q.is_del=0 ";
		if($search['starttime'])
		{
			$where .= ' AND q.create_time > '.$search['starttime'];
		}
		if($search['endtime'])
		{
			$where .= ' AND q.create_time < '.$search['endtime'];
		}
		
		$totalsql = $selectfrom.$where;
		$total = $this->get_query_count($totalsql);
		$list = array();
		if($total&&($total>($page-1)*$per_page))
		{
			$limit = build_limit($page, $per_page);
			$sql = $select.$selectfrom.$selectjoin.$where." order by create_time DESC".$limit;
			$list = $this->db->query($sql) -> result_array();	
		}
		return array( 'total' => $total, 'list' => $list );
	}

	public function add_message_info($data)
	{
		$data['create_time'] =time();
		$data['user_id']= $this->getUserId();
		$data['receive_num']=$this->user_sum($data['role']);
		
		$sys_message_id =$this->insert($data, 'sys_message');
		
	//	if($data['role'] == 'innholder'||$data['role'] == 'all')	//所有消息直接发送至收件箱 待修正  改为主动抓取系统消息
	//	{
			$users=$this->get_userid($data['role']);
			$value = '';
			foreach($users as $k => $v)
			{
				$value .= '(0,'.$v['user_id'].','.$sys_message_id.'),';
			}
			$value = rtrim($value,',');
			$sql = "INSERT INTO messages (sender,receiver,message_id) VALUES ".$value;
			$row = $this->db->query($sql);
	//	}
		return $sys_message_id;
	}

	/*得到接收人的用户id*/
	public function get_userid($role)
	{
		if($role=="all"){
			$sql = "SELECT user_id FROM users WHERE state='active' AND (role='user' OR role='innholder')";
		}else if($role=="group"){
			$sql = " SELECT distinct user_id FROM group_members WHERE is_admin=1 ";
		}else{
			$sql = "SELECT user_id FROM users WHERE state='active' AND role='".$role."'";
		}
		$row = $this->db->query($sql) -> result_array();
		return $row;
	}
	/*计算总发送人数*/
	public function user_sum($role){
		if($role=="all"){
			$sql = "SELECT count(user_id) as sum  FROM users WHERE state='active' AND (role='user' OR role='innholder')";
		}else if($role="group"){
			$sql = "SELECT count(distinct user_id) as sum FROM group_members WHERE is_admin=1 ";
		}else{
			$sql = "SELECT count(user_id) as sum  FROM users WHERE state='active' AND role='".$role."'";
		}
		
		$row = $this->db->query($sql) -> row_array();
		return $row['sum'];
	}
	public function is_del_feedback($feed_id)
	{	
		$where=array('feed_id'=>$feed_id);
		$data['is_del']=1;
		if($feed_id){
			$rs=$this->db->update('feedbacks',$data,$where);
		}
		return $rs;
	}
}