<?php 
class Forums_model extends MY_Model {
	public function get_forums($search,$page,$perpage)
	{	
		$select = "SELECT f.*,i.*,u.user_name,g.group_name FROM ";		
		switch($search['class']){
			 case 'wenda': 
				$selectfrom = ' forums as f JOIN forum_wenda as i ON f.forum_id = i.forum_id';
			 break;
			 case 'jianren': 
				$selectfrom = ' forums as f JOIN forum_jianren as i ON f.forum_id = i.forum_id';
			 break;
			 case 'tour': 
				$selectfrom = ' forums as f JOIN forum_tour as i ON f.forum_id = i.forum_id';
			 break;
		}
		$selectjoin = ' LEFT JOIN user_info as u ON f.create_user = u.user_id LEFT JOIN groups as g ON f.group_id=g.group_id';
		
		$where=" where f.type ='".$search['class']."'";	
		$where.=' AND f.is_delete = '.$search['is_del'];
			
		if($search['user_from']=='min_user'){
			$where.=' AND f.create_user>=10000 AND f.create_user<=20000';	
		}elseif($search['user_from']=='user'){
			$where.=' AND (f.create_user<=10000 or f.create_user>=20000)';
		}
		if($search['starttime'])$where .= ' AND f.create_time > '.$search['starttime'];	
		if($search['endtime'])$where .= ' AND f.create_time < '.$search['endtime'];
		if($search['keyword']){
			$groups=$this->get_id_by_group_name($search['keyword']);
			if($groups) $where .= ' AND f.group_id = '.$groups['group_id'];
			else $where .=' AND 1!=1';
		}
		$totalsql = $selectfrom.$where;	
		$total = $this->get_query_count($totalsql);
	
		$list = array();
		if($total&&($total>($page-1)*$perpage))
		{
			$limit = build_limit($page, $perpage);
			$sql = $select.$selectfrom.$selectjoin.$where." order by is_top DESC,f.update_time DESC,f.create_time DESC".$limit;
			$list = $this->db->query($sql) -> result_array();
		}
		/*用于捡人推荐*/
		foreach($list as $k=>$v){
			$sql = "SELECT is_delete FROM recommend_config WHERE type='jianren'  AND type_id=".$v['forum_id'];
			$rs=$this->db->query($sql) -> row_array();
			if(!$rs){
				$list[$k]['is_recommend']=0;
			}else{
				$list[$k]['is_recommend']=$rs['is_delete']?0:1;
			}
		}
		return array( 'total' => $total, 'list' => $list );
	}
	
	public function get_reply($search,$page,$perpage)
	{
		$select = "SELECT p.*,f.type,u.user_name,i.user_name as reply_username,g.group_name FROM ";
		$selectfrom = ' forum_post as p LEFT JOIN forums as f ON p.forum_id = f.forum_id';
		$selectjoin = ' LEFT JOIN user_info as u ON p.create_user = u.user_id  LEFT JOIN user_info as i ON p.reply_user = i.user_id LEFT JOIN groups as g ON f.group_id=g.group_id';
		
		$where=' where p.is_delete = '.$search['is_del'].' AND reply_pid=0';
		if($search['starttime'])$where .= ' AND p.create_time > '.$search['starttime'];	
		if($search['endtime'])$where .= ' AND p.create_time < '.$search['endtime'];
		if($search['class']){
			if($search['class']!='all'){
				$where.=" AND f.type='".$search['class']."'";
			}
		}
		if($search['keyword']){
			$groups=$this->get_id_by_group_name($search['keyword']);
			if($groups) $where .= ' AND f.group_id = '.$groups['group_id'];
			else $where .=' AND 1!=1';
		}
		$totalsql = $selectfrom.$where;
		$total = $this->get_query_count($totalsql);
		$list = array();
		if($total&&($total>($page-1)*$perpage))
		{
			$limit = build_limit($page, $perpage);
			$sql = $select.$selectfrom.$selectjoin.$where." order by p.create_time DESC".$limit;	
			$list = $this->db->query($sql) -> result_array();
		}
		return array( 'total' => $total, 'list' => $list );
	}
	public function get_id_by_group_name($group_name){
		$sql="SELECT group_id FROM groups WHERE group_name='".$group_name."'";
		$result = $this->db->query($sql) -> row_array();
		return $result;
	}
	
	/*屏蔽*/
	public function is_delete($forum_id)
	{	
		$where=array('forum_id'=>$forum_id);
		$sql='SELECT is_delete,group_id FROM forums WHERE forum_id='.$forum_id;
		$rs = $this->db->query($sql) -> row_array();
		$data['is_delete']=$rs['is_delete']?0:1;
		if($forum_id){	
			if($rs['group_id']){	
				if($rs['is_delete']){
					$sql = "UPDATE groups SET group_topics= group_topics+1 WHERE group_id=".$rs['group_id']; //取消屏蔽 +1总帖数
					$this->db->query($sql); 
				}else{
					$sql = "UPDATE groups SET group_topics= group_topics-1 WHERE group_id=".$rs['group_id']; //屏蔽 -1总帖数
					$this->db->query($sql); 
				}
			}
			$rs=$this->db->update('forums',$data,$where); //更新帖子
			
			/*同步删除首页推荐，并更新排序*/
			$sql = "SELECT sort FROM recommend_config WHERE type='jianren' AND is_delete=0 AND type_id=".$forum_id;	 
			$row=$this->db->query($sql)-> row_array();  //首页推荐捡人
			if($row){
				$sql = "UPDATE recommend_config SET sort=sort-1 WHERE type='jianren' AND is_delete=0  AND sort>".$row['sort'];
				$this->db->query($sql); //更新排序
				$sql =  "DELETE FROM recommend_config WHERE type='jianren' AND is_delete=0 AND type_id=".$forum_id;	
				$this->db->query($sql);//删除首页推荐
			}
		}
		return $rs;
	}
	public function reply_delete($post_id)
	{	
		$where=array('post_id'=>$post_id);
		$sql='SELECT forum_id,reply_pid,is_delete FROM forum_post WHERE post_id='.$post_id;
		$result = $this->db->query($sql) -> row_array();
		if(!$result)
		{
			return FALSE;
		}
		$data['is_delete']=$result['is_delete']?0:1; //已屏蔽为1  设显示为0，改已1
		$rs = $this->db->update('forum_post',$data,$where);	
		if(!$rs)
		{
			return FALSE;
		}
		if($data['is_delete'])
		{
			if($result['reply_pid'])
			{
				$sql = "UPDATE forum_post SET post_comments = post_comments - 1 WHERE post_id = ".$result['reply_pid'];
			}
			else
			{
				$sql = "UPDATE forums SET comments = comments - 1 WHERE forum_id = ".$result['forum_id'];
			}
			$this->db->query($sql);
		}
		else	//恢复被删除的回帖  上级回帖数加1 判断是否存在reply_pid 有则为二级回复
		{
			if($result['reply_pid'])
			{
				$sql = "UPDATE forum_post SET post_comments = post_comments + 1 WHERE post_id = ".$result['reply_pid'];
			}
			else
			{
				$sql = "UPDATE forums SET comments = comments + 1 WHERE forum_id = ".$result['forum_id'];
			}
			$this->db->query($sql);
		}
		return TRUE;
	}
	/*置顶*/
	public function is_top($forum_id)
	{	
		$where=array('forum_id'=>$forum_id);
		$sql='SELECT is_top FROM forums WHERE forum_id='.$forum_id;
		$rs = $this->db->query($sql) -> row_array();
		$data['is_top']=$rs['is_top']?0:1;
		$data['update_time']=time();
		if($forum_id){
			$rs=$this->db->update('forums',$data,$where);
		}
		return $rs;
	}
	
	/*捡人推荐 */
	public function recommend_jianren($forum_id){
		$type='jianren';
		$sql = "SELECT id,sort,is_delete FROM recommend_config WHERE type='$type' AND type_id=".$forum_id;	
		$row=$this->db->query($sql)-> row_array();  
		if($row){ //已经存在推荐表中
			if($row['is_delete']){ 
				$sql = "UPDATE recommend_config SET sort=sort+1 WHERE type='$type' AND is_delete=0";
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
			$sql = "SELECT u.user_name,f.note FROM forum_jianren as f LEFT JOIN user_info as u ON f.create_user=u.user_id  WHERE f.forum_id=".$forum_id;
			$jianren=$this->db->query($sql)->row_array();
			$info['create_time']=time();
			$info['type']='jianren';
			$info['type_id']=$forum_id;
			$info['name']=$jianren['user_name'];
			$info['note']=$jianren['note'];
			$info['sort']=1;
			$rs=$this->db->insert('recommend_config', $info);
		}
		return $rs;
	}
	public function get_userinfo($username){
		$sql = "SELECT user_id,user_name,nick_name,headimg,sex,birthday FROM user_info WHERE user_name='$username'";	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	public function get_group($group_id){
		$sql = "SELECT * FROM groups WHERE group_id='$group_id' AND is_del=0";	
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	/*随机部落*/
	public function suiji_group(){
		$sql="SELECT group_id FROM groups WHERE is_del=0";
		$row = $this->db->query($sql) -> result_array();
		$arr=array();
		foreach($row as $k=>$v){
			$arr[$v['group_id']]=$v['group_id'];
		} 
		$group_id = array_rand($arr, 1);
		return $group_id;
	}
	
	public function add_forum($data)
	{
		$forums['forum_name']=$data['title'];
		$forums['group_id']=$data['group_id'];
		$forums['type']=$data['type'];
		$forums['city']=$data['city'];
		$forums['lat']=$data['lat'];
		$forums['lon']=$data['lon'];
		$forums['create_time']=$data['create_time'];
		$forums['create_user']=$data['user_id'];
		$forums['user_from']=1;
		$forum_id = $this->insert($forums,'forums');
		if(!$forum_id)
		{
			return FALSE;
		}
		$forum_detail['forum_id']=$forum_id;
		$forum_detail['create_user']=$data['user_id'];
		$forum_detail['note']=$data['content'];
		$str_img = '';
		
		if($data['img'])
		{
			foreach ($data['img'] as $k => $v) 
			{
				if(strpos($v, 'http://') !== false) 
				{
					$str_img .= $this->save_network_img($v).',';
				}
				else
				{
					$str_img .= $v.',';
				}
			}
		}
		$forum_detail['pictures']= rtrim($str_img,',');  
		$forum_detail['tags']=$data['tags'];
		$this->insert($forum_detail,'forum_'.$data['type']);
		
		$sql = 'UPDATE groups SET `group_topics` = `group_topics` +1 ,`today_topics` = `today_topics` +1 WHERE group_id = '.$data['group_id'].' LIMIT 1';
		$this->db->query($sql);
		
		$member=$this->get_group_members($data['user_id']); //部落成员
		if(!$member){ //没有就添加
			$group_members['user_id']=$data['user_id'];
			$group_members['group_id']=$data['group_id'];
			$group_members['create_time']=$data['create_time'];
			$member_id=$this->insert($group_members,'group_members'); 
			$member['member_id']=$member_id;
		}
		$sql = 'UPDATE group_members SET `topics` = `topics` +1 WHERE member_id = '.$member['member_id'].' LIMIT 1';
		$this->db->query($sql);
		return $forum_id;
	}
	public function get_group_members($user_id){
		$sql="SELECT user_id,member_id FROM group_members WHERE user_id='$user_id'";
		$row = $this->db->query($sql) -> row_array();
		return $row;
	}
	
	public function save_network_img($url,$type='forum'){
		$thumbs = array();
		switch($type)
		{
			case 'userhead':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'user/headimg/';
				break;
			case 'topic':
			case 'forum':
				$thumbs[] = array(
				'width' => 150,
				'height' => 150,
				'thumb_marker' => 's',
				'maintain_ratio' => TRUE,
				'master_dim' => 'width'
						);
				$thumbs[] = array(
						'width' => 640,
						'height' => 640,
						'thumb_marker' => 'm',
						'maintain_ratio' => TRUE,
						'master_dim' => 'width'
				);
				$link = 'forum/';
				break;
			case 'grouphead':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'forum/group/';
				break;
			case 'comments':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => TRUE,
				'master_dim' => 'width'
						);
						$link = 'uploads/comment/';
						break;
			case 'feedback':
				$link = 'uploads/feedback/';
				break;
			default:
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'uploads/';
				break;
		}
		$this->load->model('bkupload_model');
		$rs = $this->bkupload_model->getUrlImgNames($url,$link); //保存图片 得到图片链接
		if($rs['code'] == '1' && $thumbs)	//生成缩略图
		{
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->bkupload_model->thumbConfig, $thumb);
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$rs['msg'];
				$this->image_lib->initialize($thumb);
				$this->image_lib->resize();
			}
			return $rs['msg'];
		}
		return $rs['msg'];
	}
}
