<?php 
class Coupon_model extends MY_Model {
	public function get_coupon($search,$page,$perpage)
	{	
		$cond = array(
			'table' => 'cash_coupon as c',
			'fields' => 'c.*,i.user_name',
			'join' => array('user_info as i','c.create_by = i.user_id'),
			'where'=>'is_delete = 0',
			'order_by'=>'c.create_time DESC'
		);
		$pageInfo = array(
			'cur_page' => $page,
			'per_page' => $perpage
		);
		if($search['starttime'])$cond['where'] .= ' AND c.create_time > '.$search['starttime'];
		if($search['endtime'])$cond['where'] .= ' AND c.create_time < '.$search['endtime'];
		if($search['keyword'])$cond['where'] .=  " AND quan_name LIKE '%".$search['keyword']."%'";
		
		if($search['status']!='all'){
			if($search['status']=='Z'){	
				$cond['where'].=" AND c.overdue=1";
			}else{
				if($search['status']=='N'){
					$is_public=0;
				}else if($search['status']=='Y'){
					$is_public=1;
				}
				$cond['where'].=" AND c.is_public='".$is_public."' AND c.overdue=0";
			}
		}	
		$total = $this->get_total($cond);
		$list = array();
		if($total)
		{
			$list = $this->get_all($cond,$pageInfo);
			foreach($list as $k=>$v){
				$sql ="SELECT count(*) as use_num,quan_id FROM user_quan WHERE quan_id=".$v['quan_id']." AND overdue=1 AND use_time<>0 GROUP BY quan_id";
				$row = $this->db->query($sql) -> row_array();
				$list[$k]['use_num']=$v['quantity']?$row['use_num']:''; //使用张数
				$list[$k]['use_liu']=$v['quantity']?sprintf("%.2f", ($row['use_num']/$v['quantity']*100)):''; //使用率
				
			}	
		}
		return array('total' => $total, 'list' => $list);
	}
	public function use_coupon($search,$page,$per_page)
	{
		$select = "SELECT q.*,i.user_name,c.quan_name FROM ";
		$selectfrom = ' user_quan as q LEFT JOIN user_info as i ON q.user_id = i.user_id';
		$selectjoin = ' LEFT JOIN cash_coupon as c ON q.quan_id = c.quan_id';
		$where=" WHERE q.overdue=1 AND q.use_time<>0";
		if($search['starttime'])$where.= ' AND q.use_time > '.$search['starttime'];
		if($search['endtime'])$where .= ' AND q.use_time < '.$search['endtime'];
		
		$totalsql = $selectfrom.$where;
		$total = $this->get_query_count($totalsql);
		$list = array();
		if($total&&($total>($page-1)*$per_page))
		{
			$limit = build_limit($page, $per_page);
			$sql = $select.$selectfrom.$selectjoin.$where." order by q.use_time DESC".$limit;
			$list = $this->db->query($sql) -> result_array();	
		}
		return array( 'total' => $total, 'list' => $list );
	}
	public function get_coupon_info_by_id($quan_id){
		$sql = "SELECT * FROM cash_coupon WHERE quan_id=".$quan_id;	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	
	public function add_coupon($data)
	{	
		$data['create_time']= time();
		$data['create_by']= $this->getUserId();
		if($data){
			$rs=$this->db->insert('cash_coupon', $data);
		}
		return $rs;
	}
	public function edit_coupon($data)
	{	
		$where=array('quan_id'=>$data['quan_id']);
		if($data){
			$rs=$this->db->update('cash_coupon', $data,$where);
		}
		return $rs;
	}
	public function del_coupon($quan_id)
	{	
		$where=array('quan_id'=>$quan_id);
		$data['is_delete']=1;
		if($quan_id){
			$rs=$this->db->update('cash_coupon',$data,$where);
		}
		return $rs;
	}
	public function is_provide($quan_id)
	{	
		$where=array('quan_id'=>$quan_id);
		$data['is_public']=1;
		$data['start_time']=time();
		if($quan_id){
			$rs=$this->db->update('cash_coupon',$data,$where);
		}
		return $rs;
	}
}