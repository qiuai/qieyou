<?php 
class point_model extends MY_Model {
	public function get_point($search,$page,$perpage){
	 	$cond = array(
			'table' => 'user_point_count as p',
			'fields' => '*',		
            'order_by' => 'create_time desc'
		);
		$pageInfo = array(
			'cur_page' => $page,
			'per_page' => $perpage
		);
		if($search['starttime'])
		{
			$cond['where'] = '';
			$cond['where'] .= 'p.create_time > '.$search['starttime'];
		}
		if($search['endtime'])
		{
			$cond['where'] = empty($cond['where'])?'':$cond['where'].' AND ';
			$cond['where'] .= 'p.create_time < '.$search['endtime'];
		}
		$total = $this->get_total($cond);
		$list = array();
		if($total>($page-1)*$perpage)
		{
			$list = $this->get_all($cond,$pageInfo);
		}
		$this->get_user_point();	
		return array('total' => $total, 'list' => $list);
	}
	//统计昨天 
	public function get_user_point(){
		$day=' TO_DAYS(FROM_UNIXTIME(create_time))+1=TO_DAYS(now()) '; 
		$sql='SELECT sum(point) as send_point FROM user_point where '.$day.' AND point>0'; 
		$row = $this->db->query($sql) -> row_array();
		if($row['send_point']){
			$send_point=$row['send_point'];  //发放总积分
			$sql='SELECT sum(point) as use_point FROM user_point where '.$day.' AND point<0';
			$row = $this->db->query($sql) -> row_array();
			$use_point=$row['use_point']?$row['use_point']:0;  //使用总积分
			
			$sql='SELECT COUNT(distinct user_id) as get_user FROM user_point WHERE '.$day.' AND point>0 ';
			$row = $this->db->query($sql) -> row_array();
			$get_user=$row['get_user'];  //获取积分人数
			
			$sql='SELECT COUNT(distinct user_id) as use_user FROM user_point WHERE '.$day.' AND point<0 ';
			$row = $this->db->query($sql) -> row_array();
			$use_user=$row['use_user']?$row['use_user']:0;  //使用积分人数
	
			$sql='SELECT id FROM user_point_count where '.$day;
			$row = $this->db->query($sql) -> row_array();
			$create_time=time()-86400;
			if(!$row){
				$sql="INSERT INTO user_point_count(send_point,use_point,get_user,use_user,create_time) VALUES ('$send_point','$use_point','$get_user','$use_user','$create_time')";
				$this->db->query($sql);
			}	
		}else{
			return false;
		}
	}
	
}