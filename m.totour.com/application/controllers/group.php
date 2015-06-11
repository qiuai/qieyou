<?php

class Group extends MY_Controller {

	public $layout_for_title = '圈子';
	public $layout = 'homepage';

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{
		// 更新未读消息信息
		$this->load->model('message_model');
		$data['unreadmsg'] = $this->message_model->is_has_message_unread($this->get_user_id());
		$this->set_current_data($data);
		$this->viewData = array(
			'shouye' => 'group'
		);
    }
	
   /**
    * 部落首页
	*/
	public function detail()
	{
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->model->get_group_detail_by_id($group_id); 
		if(!$group)
		{
			response_code('6002');
		}
		$user_id = $this->get_user_id();
		$group['join_time'] = '0';
		$group['is_admin'] = '0';
		$group['waiting'] = '0';
		if($user_id)
		{
			$is_fav = $this->model->get_user_group_by_group($group_id,$user_id);
			if($is_fav)
			{
				$group['join_time'] = $is_fav['create_time'];
				$group['is_admin'] = $is_fav['is_admin'];
				$group['waiting'] = $is_fav['waiting'];
			}
		}
		$this->moduleTag = $group['group_name'];
		$this->viewData = array(
			'group' => $group
		);
	}

   /**
    * 加入部落
	*/
	public function groupJoin()
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->post('group'),1000,FALSE,FALSE,'6001');
		$group = $this->model->get_group_info_by_id($group_id); 
		$group = $group[$group_id];
		if(!$group)
		{
			response_code('6002');
		}
		$action = input_string($this->input->post('act'),array('join','quit'),FALSE,'4001');
		if($action == 'join')
		{
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if($member)	//已经加入
			{
				if($member['waiting'])
				{
					response_code('6009');
				}
				response_code('6008');
			}
			else
			{
				if($group['join_method'] == 'noable')
				{
					response_code('6012');
				}
				$rs = $this->model->join_group($group,$user_id);
				if($group['join_method'] == 'verify')
				{
					// 发送消息
					 $this->addGroupMessage($group_id,$user_id);
				}
				if($rs)
				{
					response_code('1');
				}
			}
		}
		else
		{
			if($user_id == $group['create_by'])	//所有者只能解散
			{
				response_code('6010');
			}
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if($member)	//已经加入
			{
				$member['user_id'] = $user_id;
				$rs = $this->model->quit_group($member);
				if($rs)
				{
					response_code('1');
				}
			}
			else
			{
				response_code('6011');
			}
		}
		response_code('4000');
	}

   /**
    * 加入部落
	*/
	public function groupJoinAll()
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->post('group'),1000,FALSE,FALSE,'6001');
		$group = $this->model->get_group_info_by_id($group_id); 
		$group = $group[$group_id];
		if(!$group)
		{
			response_code('6002');
		}
		$action = input_string($this->input->post('act'),array('join','quit'),FALSE,'4001');
		if($action == 'join')
		{
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if($member)	//已经加入
			{
				if($member['waiting'])
				{
					response_code('6009');
				}
				response_code('6008');
			}
			else
			{
				if($group['join_method'] == 'noable')
				{
					response_code('6012');
				}
				$rs = $this->model->join_group($group,$user_id);
				if($rs)
				{
					response_code('1');
				}
			}
		}
		else
		{
			if($user_id == $group['create_by'])	//所有者只能解散
			{
				response_code('6010');
			}
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if($member)	//已经加入
			{
				$member['user_id'] = $user_id;
				$rs = $this->model->quit_group($member);
				if($rs)
				{
					response_code('1');
				}
			}
			else
			{
				response_code('6011');
			}
		}
		response_code('4000');
	}

   /**
    * 部落首页数据
    **/
	public function groupForum()
	{
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$type = input_string($this->input->get('type'),array('hot','near','live','manage'),'hot');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),10,50,10);
		$list = array();
		$top_forumn = array();
		$limit = build_limit($page,$perpage);
		$last_id = 0;
		$search = array(
			'type' => $type,
			'group_id' => $group_id,
			'last_id' => $last_id
		);
		switch($type)
		{
			case 'hot':
				if($page == '1')
				{
					$top_forumn = $this->model->get_top_forum($group_id);
				}
				$order_by = 'look DESC';
				break;
			case 'near':
				$lat = checkLocationPoint($this->input->get('lat'),'lat',0);
				if(!$lat) $lat = $this->get_current_data('lat');
				if(!$lat) response_json('1',$list);
				$lon = checkLocationPoint($this->input->get('lon'),'lon',0);
				if(!$lon) $lat = $this->get_current_data('lon');
				if(!$lon) response_json('1',$list);

				$search['lat'] = $lat;
				$search['lon'] = $lon;
				$order_by = 'local';
				break;
			case 'live':
				$order_by = 'create_time DESC';
				$search['last_id'] = input_int($this->input->get('lastid'),1000,FALSE,0);
				$limit = 'LIMIT 0,'.$perpage;
				break;
			case 'manage':
				$order_by = 'create_time DESC';
				$search['last_id'] = input_int($this->input->get('lastid'),1000,FALSE,0);
				if(!$search['last_id'] )
				{
					$top_forumn = $this->model->get_top_forum($group_id);
				}
				$limit = 'LIMIT 0,'.$perpage;
				break;
		}
		if($top_forumn)
		{
			$forum_detail = $this->model->get_forum_detail($top_forumn);		//获取列表所需数据  用户信息等
			foreach($top_forumn as $key => $row)
			{
				$row['create_time'] = showTime($row['create_time']);
				$list[] = array_merge($row,$forum_detail[$row['forum_id']]);
			}
		}
		$forum_index = $this->model->get_forum_list($search,$order_by,$limit);
		if($forum_index)
		{
			$forum_detail = $this->model->get_forum_detail($forum_index);		//获取列表所需数据  用户信息等
			foreach($forum_index as $key => $row)
			{
				$row['create_time'] = showTime($row['create_time']);
				$list[] = array_merge($row,$forum_detail[$row['forum_id']]);
			}
		}
		response_json('1',$list);
	
	}
	
	/**
	 * 加入部落申请消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 上午11:22:14
	 * @param unknown $group_id
	 * @param number $user_id
	 */
	private function addGroupMessage($group_id,$user_id=0){
		$user_id = $user_id ? $user_id : $this->get_user_id(TRUE);
		
		$group = $this->model->get_group_info_by_id($group_id);
		$group = $group[$group_id];
		
		$user = $this->model->get_user_detail($user_id);
		
		$group_member = $this->model->get_user_group_by_group($group_id,$user_id);
		
		$group['user_name'] = $user['user_name'];
		$group['nick_name'] = $user['nick_name'];
		$group['user_id'] = $user['user_id'];
		$group['waiting'] = $group_member['waiting'];
		$group['member_id'] = $group_member['member_id'];
		if($group['waiting'] != 1){
			$group['set_user_name'] = $this->model->get_user_info_by_id($group_member['set_user_id']);
		}else{
			$group['set_user_name'] = '';
		}
		
		$this->load->model('message_model');
		$this->message_model->add_message($group,'group');
		return TRUE;
	}

	public function search()
	{
		$this->moduleTag = '贴子搜索';
		$this->layout = 'item';
	}

	public function searchKeyWord()
	{
		$keyword = check_empty(trimall(strip_tags($this->input->get('keyword',TRUE))),'');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$list = array();
		if(!$keyword)
		{
			response_row($list);
		}
		if($page == 1)
		{
			$list['group'] = $this->model->search_group_name($keyword);
		}
		$limit = build_limit($page,$perpage);
		$search = array(
			'keyword' => $keyword	
		);
		$order_by = 'create_time DESC';
		$forum = $this->model->get_forum_list($search,$order_by,$limit);
		if($forum)
		{
			$forum_detail = $this->model->get_forum_detail($forum);		//获取列表所需数据  用户信息等
			foreach($forum as $key => $row)
			{
				$row['create_time'] = showTime($row['create_time']);
				$list['forum'][] = array_merge($row,$forum_detail[$row['forum_id']]);
			}
		}
		else
		{
			$list['forum'] = array();
		}
		response_row($list);
	}

   /**
	* 贴子搜索列表
	*/
	public function searchlist() 
	{
		$this->moduleTag = '搜索结果';
		$this->layout = 'simple';
    }

	/**
	* 部落资料
	*/
	public function groupdata() 
	{
		$user_id = $this->get_user_id();
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->model->get_group_info_by_id($group_id);
		if(!$group)
		{
			response_code('6002');
		}
		$group = $group[$group_id];
		$group['is_join'] = '0';
		if($user_id)
		{
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if($member)
			{
				$group['is_join'] = '1';
			}
		}
		$group_member = $this->model->get_group_member_by_gourp_id('verified',$group_id,1,6);
		if($group_member)
		{
			$ids = array();
			foreach($group_member as $key => $row)
			{
				$ids[] = $row['user_id'];
			}
			$user = $this->model->get_user_cache_info_by_id($ids);
			foreach($group_member as $key => $row)
			{
				$group_member[$key] = array_merge($row,$user[$row['user_id']]);
			}
		}

		$this->layout = 'simple';
		$this->moduleTag = '部落资料';
		$this->viewData = array(
			'group' => $group,
			'group_member' => $group_member
		);
    }
	
   /**
	* 部落成员
	*/
	public function member() 
	{
		$this->moduleTag = '部落成员';
		$this->layout = 'simple';
    }

   /**
	* 选择部落
	*/
	public function choosegroup() 
	{
		$this->moduleTag = '选择部落';
		$this->layout = 'simple';
    }

   /**
	* 推荐部落
	*/
	public function recommend() 
	{
		$this->layout = 'item';

    }

  /**
	* 所在位置
	*/
	public function position() 
	{
		$this->moduleTag = '所在位置';
		$this->layout = 'simple';
    }

   /**
    * 圈子首页获取列表 
	**/
	public function get()
	{
		$type = input_string($this->input->get('type'),array('live','wenda','jianren','tour','rank'),'live');
		$last_id = input_int($this->input->get('lastid'),0,FALSE,0);
		$limit = input_int($this->input->get('limit'),1,50,10);
		$list = array();
		if($type == 'rank')
		{
			$page = input_int($this->input->get('page'),1,FALSE,1);
			$perpage = input_int($this->input->get('limit'),1,50,20);
			$user_id = $this->get_user_id();
			$list = $this->model->get_groups($user_id,$page,$perpage);
		}
		else
		{
			$forum_index = $this->model->get_group_forum($type,$last_id,$limit);
			if($forum_index)
			{
				$forum_detail = $this->model->get_forum_detail($forum_index);		//获取列表所需数据  用户信息等
				foreach($forum_index as $key => $row)
				{
					$row['create_time'] = showTime($row['create_time']);
					$list[] = array_merge($row,$forum_detail[$row['forum_id']]);
				}
			}
		}
		response_row($list);
	}

   /**
	* 部落管理
	*/
	public function groupadmin() 
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$this->moduleTag = '部落管理';
		$this->layout = 'simple';
		$this->viewData = array(
			'group' => $group
		);
    }
   
   /**
	* 部落设置
	*/	
	public function groupseting()
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$this->moduleTag = '部落设置';
		$this->layout = 'simple';
		$this->viewData = array(
			'group' => $group
		);
	}
   
   /**
	* 创建部落 POST
	*/		
	public function groupCreate()
	{
		$user_id = $this->get_user_id(TRUE);
		$inn_id = $this->get_user_inn_id();
		if(!$inn_id)
		{
			response_code('6030');	//只有商户才能创建部落
		}
		$group_num = $this->model->get_user_own_group($user_id);
		if($group_num > 2)
		{
			response_code('6031');
		}
		$group = $this->check_group_info_value('add');
		$done = array(
			'user_id' => $user_id
		);
		$group_id = $this->model->create_group($group,$done);
		if($group_id)
		{
			response_json('1',$group_id);
		}
		response_code('4000');
	}

   /**
	* 创建部落 view
	*/	
	public function newgroup()
	{
		$this->moduleTag = '创建部落';
		$this->layout = 'simple';
	}

   /**
	* 部落设置 POST
	*/	
	public function groupSet()
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->post('group'),1000,FALSE,FALSE,'6001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$check_info = $this->check_group_info_value();
		$changedkeys = array_diff_assoc($check_info,$group);
		if($changedkeys)
		{
			$changedkeys['group_id'] = $group_id;
			$rs = $this->model->update_group_info($changedkeys);
			if(!$rs)
			{
				response_code('4000');
			}
		}
		response_code('1');
	}

	private function check_group_info_value($type = 'edit')
	{
		$group_name = $this->input->post('groupname',TRUE);
		$group_img = $this->input->post('groupimg',TRUE);
		$note = $this->input->post('note',TRUE);
		$join_method = $this->input->post('joinmethod');
		$group = array();
		if($type == 'edit')
		{
			if($group_name)
			{
				$group['group_name'] = check_empty(trimall(strip_tags($group_name)),FALSE,'6020');
			}
			if($group_img)
			{
				$group['group_img'] = check_empty(trimall(strip_tags($group_img)),FALSE,'6021');
			}
			if($note)
			{
				$group['note'] = check_empty(trimall(strip_tags($note)),FALSE,'6022');	
			}
			if($join_method)
			{
				$group['join_method'] = input_string($join_method,array('able','verify','noable'),FALSE,'6023');
			}
		}
		else
		{
			$group['group_name'] = check_empty(trimall(strip_tags($group_name)),FALSE,'6020');
			$group['group_img'] = check_empty(trimall(strip_tags($group_img)),FALSE,'6021');
			$group['note'] = check_empty(trimall(strip_tags($note)),FALSE,'6022');	
			$group['join_method'] = input_string($join_method,array('able','verify','noable'),FALSE,'6023');
		}
		return $group;
	}

   /**
	* 贴子管理
	*/
	public function admintopic() 
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$this->layout = 'simple';
		$this->moduleTag = '贴子管理';
		$this->viewData = array(
			'group' => $group
		);
	}

   /**
	* 成员管理
	*/
	public function adminmember() 
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$this->layout = 'simple';
		$this->moduleTag = '成员管理';
		$this->viewData = array(
			'group' => $group
		);
    }

   /**
    * 获取成员列表
	**/
	public function getMember()
	{
		$group_id = input_int($this->input->get('group'),1000,FALSE,FALSE,'6001');

		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$type = input_string($this->input->get('type'),array('verified','waiting'),'verified');
		
		$group = $this->model->get_group_info_by_id($group_id,TRUE);	//这里需要直连DB
		if(!$group)
		{
			response_code('6002');
		}
		$group = $group[$group_id];
		
		if($type =='waiting')	//若看审核中的人
		{
			$user_id = $this->get_user_id(TRUE);
			$admins = explode(',',$group['admins']);
			if(!in_array($user_id,$admins))
			{
				response_code('6005');
			}
		}	//优化性能可以先取出 admin 取消排序

		$group_member = $this->model->get_group_member_by_gourp_id($type,$group_id,$page,$perpage);
		if($group_member)
		{
			$ids = array();
			foreach($group_member as $key => $row)
			{
				$ids[] = $row['user_id'];
			}
			$user = $this->model->get_user_cache_info_by_id($ids);
			foreach($group_member as $key => $row)
			{
				$group_member[$key] = array_merge($row,$user[$row['user_id']]);
			}
		}
		response_json('1',$group_member);
	}

   /**
    * 圈子管理员帖子管理
 	**/
	public function forumManage()
	{
		$user_id = $this->get_user_id(TRUE);
		$action = input_string($this->input->post('act'),array('set_top','unset_top','delete','del_post'),FALSE,'4001');
		if($action == 'del_post')
		{
			$post_id = input_int($this->input->post('post'),1,FAlSE,FALSE,'6003');
			$this->load->model('forum_model');
			$post = $this->forum_model->get_forum_post_by_id($post_id,TRUE);
			if(!$post||$post['is_delete'])
			{
				response_code('6025');
			}
			$forum_id = $post['forum_id'];
		}
		else
		{
			$forum_id = input_int($this->input->post('forum'),1000,FALSE,FALSE,'6003');
		}
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if(!$forum)
		{
			response_code('6004');
		}
		$this->check_user_group_auth($user_id,$forum['group_id']);

		switch($action)
		{
			case 'set_top':
				if($forum['is_top'])
				{
					response_code('1');
				}
			case 'unset_top':
				if($action == 'unset_top'&&!$forum['is_top'])
				{
					response_code('1');
				}
				if($this->model->modify_forum_by_forum_id($action,$forum))
				{
					response_code('1');
				}
				break;
			case 'delete':
				if($this->model->delete_forum_by_forum_id($forum,$user_id))
				{
					response_code('1');
				}
				break;
			case 'del_post':
				$this->load->model('user_model');
				if($this->user_model->delete_post_by_id($post))
				{
					response_code('1');
				}
				break;
		}
		response_code('4000');
	}

   /**
    * 会员管理  删除用户 允许加入 拒绝加入
    **/
	public function modifyMember()
	{
		$user_id = $this->get_user_id(TRUE);
		$group_id = input_int($this->input->post('group'),1000,FALSE,FALSE,'6001');
		$member_id = input_int($this->input->post('member'),1000,FALSE,FALSE,'4003');
		$act = input_string($this->input->post('act'),array('delmember','allow','ignore'),FALSE,'4001');
		$group = $this->check_user_group_auth($user_id,$group_id);
		$member = $this->model->get_user_member_info($member_id);
		if(!$member||$member['group_id'] != $group_id)
		{
			response_code('6006');
		}
		$rs = FALSE;
		switch($act)
		{
			case 'delmember':
				if($member['is_admin'] && $user_id != $group['create_by'])	//创建者才能删除管理员
				{
					response_code('6005');
				}
				if($member['user_id'] == $group['create_by'])		//创建者不能被删除
				{
					response_code('6005');
				}
				$rs = $this->model->delete_group_member($member);	//删除成员
				break;	
			case 'setadmin':
			//	$rs = $this->model->delete_group_member($member);	//删除成员
				break;
			case 'unsetadmin':
			//	$rs = $this->model->delete_group_member($member);	//删除成员
				break;
			case 'setgroupown':
				if($user_id != $group['create_by'])		//群主才可以转让
				{
					response_code('6005');
				}
				if($member['user_id'] == $group['create_by'])		//不能转让给自己
				{
					response_code('6005');
				}
				break;
			case 'allow':				
				if(!$member['waiting'])
				{
					response_code('6007');
				}
				$rs = $this->model->allow_group_member($member,$user_id);	//允许加入部落
				if($rs){	// 修改已发消息
					$this->load->model('message_model');
					$msg_detail['waiting'] = 0;
					$msg_detail['member_id'] = $member['member_id'];
					$msg_detail['set_user_name'] = $this->get_current_data('nick_name');
					$this->message_model->update_message_detail($msg_detail);
				}
				break;
			case 'ignore':						
				if(!$member['waiting'])
				{
					response_code('6007');
				}
				$rs = $this->model->ignore_group_member($member);	//删除请求
				if($rs){	// 修改已发消息
					$this->load->model('message_model');
					$msg_detail['waiting'] = 2;
					$msg_detail['member_id'] = $member['member_id'];
					$msg_detail['set_user_name'] = $this->get_user_name();
					$this->message_model->update_message_detail($msg_detail);
				}
				break;
		}
		if($rs)
		{
			response_code('1');
		}
		response_code('4000');
	}

	private function check_user_group_auth($user_id,$group_id)
	{
		$group = $this->model->get_group_info_by_id($group_id,TRUE);	//这里需要直连DB
		if(!$group)
		{
			response_code('6002');
		}
		$group = $group[$group_id];
		$admins = explode(',',$group['admins']);
		if(!in_array($user_id,$admins))
		{
			response_code('6005');
		}
		return $group;
	}
}