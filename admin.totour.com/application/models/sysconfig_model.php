<?php 
class sysconfig_model extends MY_Model {
 	 public function get_config($class,$page,$perpage)
	 {	
	    $select = '';
		$selectjoin = '';
		switch($class){
			 case 'banner': 
			 	$select = "SELECT r.* FROM ";
				$selectfrom = ' recommend_config as r ';
			 break;
			 case 'group':
			    $select = "SELECT r.*,g.*,u.user_mobile FROM ";
				$selectfrom = ' recommend_config as r LEFT JOIN groups as g ON r.type_id = g.group_id';
				$selectjoin = ' LEFT JOIN users as u ON u.user_id = g.create_by';
			 break;
			 case 'product': 
				$select = "SELECT r.*,g.*,i.inn_name,d.dest_name,l.local_name FROM ";
				$selectfrom = ' recommend_config as r LEFT JOIN products as g ON r.type_id = g.product_id';
			    $selectjoin = ' LEFT JOIN inns as i ON g.inn_id = i.inn_id';
				$selectjoin .= ' LEFT JOIN china_dest as d ON d.dest_id = i.dest_id';
				$selectjoin .= ' LEFT JOIN china_dest_local as l ON l.local_id = i.local_id';
			 break;
			 case 'jianren': 
			    $select = "SELECT r.*,g.*,u.user_name FROM ";
				$selectfrom = ' recommend_config as r LEFT JOIN forum_jianren as g ON r.type_id = g.forum_id';
				$selectjoin = ' LEFT JOIN users as u ON g.create_user = u.user_id';
			 break;
		}
		$where= " where r.is_delete=0 AND r.type ='".$class."'";
		
		$totalsql = $selectfrom.$where;
		$total = $this->get_query_count($totalsql);
		$list = array();
		if($total&&($total>($page-1)*$perpage))
		{
			$limit = build_limit($page, $perpage);
			$sql = $select.$selectfrom.$selectjoin.$where." order by r.sort ASC".$limit;	
			$list = $this->db->query($sql) -> result_array();
		}
		return array( 'total' => $total, 'list' => $list );
	 }
	 
	 public function get_config_info_by_id($id) {
		$sql = "SELECT * FROM recommend_config WHERE id=".$id;	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	 }
	/**
	* 修改信息
	*/
	public function update_config_info($data)
	{
		$where=array('id'=>$data['id']);
		$rs=$this->db->update('recommend_config', $data,$where);
		return $rs;
	}
	/**排序*/
	public function is_sort($id,$action,$type){
		$sql = "SELECT sort FROM recommend_config WHERE id=".$id;
		$row=$this->db->query($sql)-> row_array();
		$sort=$row['sort'];
		if($action=='up'){
			if($sort>1)$upsort=$sort-1;
			else $upsort=1;
			$sql = "UPDATE recommend_config SET sort=$sort WHERE type='$type' AND is_delete=0 AND sort=$upsort";
			$this->db->query($sql);
			$sql = "UPDATE recommend_config SET sort=$upsort WHERE type='$type' AND id=$id";
			$this->db->query($sql);
			return 1;
		}elseif($action=='down'){
			$sql = "SELECT max(sort) as max_sort FROM recommend_config WHERE type='$type' AND is_delete=0";
			$row = $this->db->query($sql) -> row_array();
			if($row['max_sort']!=$sort){
				$upsort=$sort+1;
				$sql = "UPDATE recommend_config SET sort=$sort WHERE type='$type' AND is_delete=0 AND sort=$upsort";
				$this->db->query($sql);
				$sql = "UPDATE recommend_config SET sort=$upsort WHERE type='$type' AND id=$id";
				$this->db->query($sql);	
			}
			return 1;
		}elseif($action=='top'){
			 if($sort>1){	
				 $sql = "UPDATE recommend_config SET sort=sort+1 WHERE type='$type' AND is_delete=0  AND sort<".$sort;
				 $this->db->query($sql);
				 $sql = "UPDATE recommend_config SET sort=1 WHERE id=".$id;
				 $this->db->query($sql);
			 }
			 return 1;
		}elseif($action=='bottom'){
			$sql = "SELECT max(sort) as max_sort FROM recommend_config WHERE type='$type' AND is_delete=0";
			$row = $this->db->query($sql) -> row_array();
			$maxsort=$row['max_sort'];
			$sql = "UPDATE recommend_config SET sort=sort-1 WHERE type='$type' AND is_delete=0  AND sort>".$sort;
			$this->db->query($sql);	
			$sql = "UPDATE recommend_config SET sort=$maxsort WHERE id=".$id;
			$this->db->query($sql);
			return 1;
		}	
	}
	/**
	*增加推荐
	*/
	public function add_config_info($data){ 
		$data['create_time'] =time();
		$type=$data['type'];
		$data['sort']=1;
		if($data){
			$sql = "UPDATE recommend_config SET sort=sort+1 WHERE type='$type' AND is_delete=0";
			$this->db->query($sql);
			$sql="SELECT * FROM recommend_config WHERE type='$type' AND type_id=".$data['type_id'];
			$row = $this->db->query($sql) -> row_array();
			if($row){
				$where=array('type_id'=>$data['type_id'],'type'=>$type);
				$info['is_delete']=0;
				$rs=$this->db->update('recommend_config',$info,$where);
			}else{
				$rs=$this->db->insert('recommend_config', $data);
			}
		}
		return $rs;
	}
	/*取消推荐 */
	public function up_is_del($id,$type){
		$where=array('id'=>$id);
		$data['is_delete']=1;	
		$sql = "SELECT sort FROM recommend_config WHERE id=".$id;
		$row=$this->db->query($sql)-> row_array();
		$sql = "UPDATE recommend_config SET sort=sort-1 WHERE type='$type' AND is_delete=0  AND sort>".$row['sort'];
		$this->db->query($sql);
		$rs=$this->db->update('recommend_config',$data,$where);
		return $rs;
	}
	public function get_groups_by_name($group_id){
		$sql = "SELECT * FROM groups WHERE group_id='".$group_id."'";	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	/*验证搜索存在*/
	public function get_groups_isname($key,$type){
		$sql = "SELECT id FROM recommend_config WHERE type='$type' AND is_delete=0 AND type_id='".$key."'";	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}		
	
	public function get_username($admins){
		$cond = array(
			'table' => 'user_info',
			'fields' => 'user_name',
			'where' => 'user_id IN ('.$admins.')'
		);
		$row =  $this->get_all($cond);
		return $row;
	}
	/**
    * 得到捡人信息
    */
	public function  get_jianren_by_id($jianren_id) {
		$sql = "SELECT f.*,i.create_time,u.user_name FROM forum_jianren as f LEFT JOIN forums as i ON f.forum_id=i.forum_id LEFT JOIN user_info as u ON f.create_user=u.user_id WHERE i.is_delete=0 AND f.forum_id=".$jianren_id;	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	 }
	/**
    * 得到商品信息
    */
	public function get_product_by_id($product_id)
	{
		$sql = "SELECT p.*,i.inn_name,d.dest_name,l.local_name FROM
	    products as p JOIN inns as i ON p.inn_id = i.inn_id
		LEFT JOIN china_dest as d ON d.dest_id = i.dest_id 
		LEFT JOIN china_dest_local as l ON l.local_id = i.local_id
		WHERE product_id=".$product_id;	
	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	/*部落*/
	public function get_groups($search,$page,$perpage){
		$cond = array(
			'table' => 'groups',
			'fields' => '*',
			'where'=>'is_del = 0',
			'order_by' => 'create_time DESC'
		);
		
		if($search['keyword'])
		{
			$cond['where'] .=  " AND group_name LIKE '%".$search['keyword']."%'";
		}
		$pageInfo = array(
			'cur_page' => $page,
			'per_page' => $perpage
		);
		$total = $this->get_total($cond);
		$list = array();
		if($total>($page-1)*$perpage)
		{
			$list = $this->get_all($cond,$pageInfo);
		}
		
		foreach($list as $k=>$v){
			/* 判断是否可删除 */
			if($v['group_topics']==0 && $v['members']==1){ 
				$list[$k]['allow_del']=1;	//可删除
			}else{
				$list[$k]['allow_del']=0;
			}
			$create_by=$this->get_mobile_by_name($v['create_by']);
			$list[$k]['create_by']=$create_by['user_mobile'];
			
			$sql = "SELECT is_delete FROM recommend_config WHERE type='group'  AND type_id=".$v['group_id'];
			$rs=$this->db->query($sql) -> row_array();
			if(!$rs){
				$list[$k]['is_recommend']=0;
			}else{
				$list[$k]['is_recommend']=$rs['is_delete']?0:1;
			}
		}
		return array('total' => $total, 'list' => $list);
	}
	public function get_groups_info_by_id($id){
		$sql = "SELECT * FROM groups WHERE group_id=".$id;
		$row = $this->db->query($sql) -> row_array();
		if($row){
			$sql = "SELECT user_id,user_mobile FROM users WHERE user_id IN (".$row['admins'].")";	
			$rs = $this->db->query($sql) -> result_array();
			foreach ( $rs as $k => $v)
			{
				 $admin_mobile[$v['user_id']] = $v['user_mobile'];
			}
			$row['admin_mobile']=$admin_mobile;
		}
		return $row;
	}
	/*部落推荐到首页*/
	public function recommend_group($group_id){
		$type='group';
		$sql = "SELECT id,sort,is_delete FROM recommend_config WHERE type='$type' AND type_id=".$group_id;	
		$row=$this->db->query($sql)-> row_array();  
		if($row){ //已经存在推荐表中
			if($row['is_delete']){ 
				$sql = "UPDATE recommend_config SET sort=sort+1 WHERE type='$type' AND is_delete=0 ";
				$rs=$this->db->query($sql);
				$sql = "UPDATE recommend_config SET sort=1,is_delete=0 WHERE id=".$row['id'];
				$rs=$this->db->query($sql);
			}else{
				$sql = "UPDATE recommend_config SET is_delete=1 WHERE id=".$row['id'];
				$rs=$this->db->query($sql);
				$sql = "UPDATE recommend_config SET sort=sort-1 WHERE type='$type' AND is_delete=0 AND sort>".$row['sort'];
				$rs=$this->db->query($sql);
			}
		}else{  //新增一个推荐
			$sql = "UPDATE recommend_config SET sort=sort+1 WHERE type='$type' AND is_delete=0";
			$this->db->query($sql);
			$info['create_time']=time();
			$info['type']=$type;
			$info['type_id']=$group_id;
			$info['sort']=1;
			$rs=$this->db->insert('recommend_config', $info);
		}
		return $rs;
	}
	/**
	* 修改部落信息
	*/
	public function update_groups_info($data)
	{
		$sql='SELECT admins FROM groups WHERE group_id='.$data['group_id'];
		$row = $this->db->query($sql) -> row_array();
		
		if($row['admins']!=$data['admins']){
			
			$old_member=explode(',',$row['admins']);
			foreach($old_member as $k=>$v){
				$sql =  "DELETE FROM group_members WHERE user_id=".$v." AND group_id=".$data['group_id'];
				$this->db->query($sql);  //删除原有的成员
			}
			$new_member=explode(',',$data['admins']);
			foreach($new_member as $k=>$v){
				$info['user_id']=$v;
				$info['group_id']=$data['group_id'];
				$info['is_admin']=1;
				$info['create_time'] =time();
				$this->insert($info, 'group_members');//添加修改的成员
			}
		}
		$where=array('group_id'=>$data['group_id']);
		if($data){
			$rs=$this->db->update('groups', $data,$where);
		}
		return $rs;
	}
	/**
	*增加部落
	*/
	public function add_group($data)
	{
		$data['create_time'] =time();
		if($data['admins']){
			$rs=$this->insert($data, 'groups');
			$mobile=explode(',',$data['admins']);
			
			foreach($mobile as $k=>$v){
				$info['user_id']=$v;
				$info['group_id']=$rs;
				$info['is_admin']=1;
				$info['create_time'] =time();
				$this->insert($info, 'group_members');
			}
		}
		return $rs;
	}
	/*用户*/
	public function get_mobile_by_name($user_id) {
		$sql = "SELECT user_id,user_mobile FROM users WHERE user_id=".$user_id;	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	/*手机号查用户*/
	public function get_user_by_mobile($user_mobile) {
		$sql = "SELECT user_id FROM users WHERE user_mobile ='".$user_mobile."'";	
		$row = $this->db->query($sql) -> row_array();	
		return $row;
	}
	/*多个手机号查用户*/
	public function get_users_by_mobiles($user_mobile) {
		$sql = "SELECT user_id FROM users WHERE user_mobile IN (".$user_mobile.")";	
		$row = $this->db->query($sql) -> result_array();	
		return $row;
	}
		
	//删除
	public function is_del_group($group_id){
		$where=array('group_id'=>$group_id);
		$data['is_del']=1;
		if($group_id){
			$this->db->where('group_id', $group_id); 
			$rs=$this->db->delete('groups',$where); //删除部落
			if($rs){	
				$sql="DELETE FROM group_members WHERE group_id=$group_id ";
				$this->db->query($sql);
				$sql = "SELECT sort FROM recommend_config WHERE type='group' AND is_delete=0 AND type_id=".$group_id;
				$row=$this->db->query($sql)-> row_array();
				if($row){
					$sql = "UPDATE recommend_config SET sort=sort-1 WHERE type='group' AND is_delete=0  AND sort>".$row['sort'];
					$this->db->query($sql); //更新排序
					$sql =  "DELETE FROM recommend_config WHERE type='group' AND is_delete=0 AND type_id=".$group_id;	
					$row = $this->db->query($sql);//删除首页推荐
				}
			}
		}
		return $rs;
	}
}