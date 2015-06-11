<?php

class User extends MY_Controller {

	public $layout_for_title = '我的社区';
	public $layout = 'simple_temp';

    public function __construct() 
	{
        parent::__construct();
    }

	public function localGps()
	{
		$user['lon'] = $this->input->post('lon');
		$user['lat'] = $this->input->post('lat');
	//	$this->model->get_user_local($user);
	//	exit;
		log_message('error',json_encode($user));
		$this->set_current_data($user);
		echo TRUE;exit;
	}

   /**
	* 首页
	*/
	public function index() 
	{
		$user_id = $this->get_user_id();
		$user = array();
		if($user_id)
		{
			$user = $this->model->get_user_detail($user_id);
		}
		$this->layout = 'default';
		$this->viewData = array(
			'user' => $user
		);
    }

   /**
	* 消息
	*/
	public function message() 
	{
		$user_id = $this->get_user_id(TRUE);
		$this->moduleTag = '我的消息';
    }
    
    /**
     * 获取消息列表
     *
     * @author Vonwey <vonwey@163.com>
     * @CreateDate: 2015-5-29 下午5:49:49
     */
    public function getMessage()
    {
    	$data = array();
    	$user_id = $this->get_user_id(TRUE);
    	$last_id = input_int($this->input->get('lastId'),0,FALSE,0);
    	$type = input_string($this->input->get('type'),array('forum','system'),FALSE,'4001');
    	$page = input_int($this->input->get('page'),1,FALSE,1);
    	$perpage = input_int($this->input->get('perpage'),1,20,10);
    	$type = $type=='system' ? 'sys' : 'group';
    	
    	$this->load->model('message_model');
    	$data = $this->message_model->get_message_list($user_id,$type,$page,$perpage,$last_id);
    	$data = $this->formatMessage($data);
    	
    	response_json('1', $data);
    }
    
    /**
     * 删除消息
     *
     * @author Vonwey <vonwey@163.com>
     * @CreateDate: 2015-5-29 下午5:50:03
     */
    public function delMessage()
	{
    	$id = input_int($this->input->get('id'));
    	$user_id = $this->get_user_id(TRUE);
    	
    	$this->load->model('message_model');
    	
    	// 判断消息是否已删除
    	$status = $this->message_model->is_message_deled($id,$user_id);
    	if($status == 1){
    		response_code('1026');
    	}elseif($status == 0){
    		response_code('1027');
    	}
    	
    	response_json(intval($this->message_model->del_message($id)));
    }
    
    /**
     * 格式化消息数据
     *
     * @author Vonwey <vonwey@163.com>
     * @CreateDate: 2015-5-29 下午5:50:15
     * @param unknown $data
     * @return Ambigous <string, unknown>
     */
    private function formatMessage($data){
    	if(!empty($data)){
    		foreach ($data as $key=>$value){
    			if($value['message_type']=='sys'){ // 系统消息
    				$data[$key]['msgtype'] = $value['type'];
    			}else{
    				$data[$key]['note'] = unserialize($value['note']);
    			}
    			$read_ids[] = $value['id'];
    			$data[$key]['type'] = $value['message_type']=='group' ? 'forum' : $value['message_type'];
    			$data[$key]['type'] = $value['message_type']=='sys' ? 'system' : $value['message_type'];
    		}
    		$ids = implode(',', $read_ids);
    	}
    	return $data;
    }
    
    // 标记消息已读
    private function updateMessage($ids){
    	$this->load->model('message_model');
    	$status = $this->message_model->updateMessage($ids);
    }
	
   /**
	* 他人首页
	*/
	public function card() 
	{
		$user_id = input_int($this->input->get('user_id'),1,FALSE,FALSE,'4005');
		$user = $this->model->get_user_info_by_id($user_id);
		if(!$user)
		{
			response_code('4005');
		}
		$user = $user[$user_id];
		$group_list = array();
		$user['group_count'] = $this->model->get_count_user_group($user_id);
		if($user['group_count'])
		{
			$now_user = $this->get_user_id();
			if($now_user)
			{
				$group_ids = $this->model->get_groups_by_user_id($user_id,1,3);
				foreach($group_ids as $key => $row)
				{
					$ids[] = $row['group_id'];
				}
				$group_list = $this->model->get_relation_groups_by_ids(implode(',',$ids),$now_user);
			}
			else
			{
				$group_list = $this->model->get_user_groups($user_id,1,3);
			}
		}
		$user['tour_count'] = $this->model->get_count_user_forum('tour',$user_id);
		$user['jianren_count'] = $this->model->get_count_user_forum('jianren',$user_id);
		$user['wenda_count'] = $this->model->get_count_user_forum('wenda',$user_id);
		$this->viewData = array(
			'user' => $user,
			'group_list' => $group_list
		);
		$this->layout = 'default';
    }

   /**
	* 他人部落内容
	*/
	public function forum() 
	{
		$user_id = input_int($this->input->get('user_id'),1,FALSE,FALSE,'4005');
		$action = input_string($this->input->get('act'),array('group','wenda','jianren','tour'),FALSE,'4001');
		switch($action)
		{
			case 'group':
				$this->moduleTag = 'Ta加入的部落';
				break;
			case 'wenda':
				$this->moduleTag = 'Ta的问答';
				break;
			case 'jianren':
				$this->moduleTag = 'Ta的捡人';
				break;
			case 'tour':
				$this->moduleTag = 'Ta的游记';
				break;
		}
		$this->viewData = array(
			'class' => $action,
			'user_id' => $user_id
		);
	}

   /**
	* 圈子
	*/
	public function group() 
	{
		$user_id = $this->get_user_id(TRUE);
		$groups = $this->model->get_user_groups($user_id);
		$admin = array();
		if($groups)
		{
			foreach($groups as $key => $row)
			{ 
				if($row['is_admin'])
				{
					$admin[] = $row;
				}
				else if($row['waiting'])
				{
					unset($groups[$key]);
				}
			}
		}
		$this->moduleTag = '我的部落';
		$this->viewData = array(
			'groups' => $groups,
			'admin' => $admin,
			'backUrl' => base_url().'user'
		);
    }

   /**
	* 捡人
	*/
	public function jianren() 
	{
		$user_id = $this->get_user_id(TRUE);
		$count = $this->model->get_count_user_forum('jianren',$user_id);
		$favorites = $this->model->get_count_user_fav_forum('jianren',$user_id);
		$this->moduleTag = '我的捡人';
		$this->viewData = array(
			'count' => $count,
			'favorites' => $favorites,
			'backUrl' => base_url().'user'
		);
    }

   /**
	* 问答
	*/
	public function wenda() 
	{
		$user_id = $this->get_user_id(TRUE);
		$count = $this->model->get_count_user_forum('wenda',$user_id);
		$favorites = $this->model->get_count_user_fav_forum('wenda',$user_id);
		$this->moduleTag = '我的问答';
		$this->viewData = array(
			'count' => $count,
			'favorites' => $favorites,
			'backUrl' => base_url().'user'
		);
    }

   /**
	* 游记
	*/
	public function tour() 
	{
		$user_id = $this->get_user_id(TRUE);
		$count = $this->model->get_count_user_forum('tour',$user_id);
		$favorites = $this->model->get_count_user_fav_forum('tour',$user_id);
		$this->moduleTag = '我的游记';
		$this->viewData = array(
			'count' => $count,
			'favorites' => $favorites,
			'backUrl' => base_url().'user'
		);
    }

   /**
	* 获取自己的论坛活动
	**/
	public function getMyForum()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->get('type'),array('group','tour','jianren','wenda'),FALSE,'4001');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);

		$data = array();
		switch($type)
		{
			case 'group':
				$data = $this->model->get_user_groups($user_id,$page,$perpage);
				break;
			case 'tour':
			case 'jianren':
			case 'wenda':
				$act = input_string($this->input->get('act'),array('post','collect'),'post');//发表、收藏
				if($act == 'post')
				{
					$forum_index = $this->model->get_user_forum($type,$user_id,$page,$perpage);
				}
				else
				{
					$forum_index = $this->model->get_user_fav_forum($type,$user_id,$page,$perpage);
				}
				if($forum_index)
				{
					$this->load->model('group_model');
					$forum_detail = $this->group_model->get_forum_detail($forum_index);		//获取列表所需数据  用户信息等
					foreach($forum_index as $key => $row)
					{
						$row['create_time'] = showTime($row['create_time']);
						$data[] = array_merge($row,$forum_detail[$row['forum_id']]);
					}
				}
				break;
		}
		response_json('1',$data);
	}

   /**
	* 获取他人的论坛活动
	**/
	public function getUserForum()
	{
		$user_id = input_int($this->input->get('user'),1,FALSE,FALSE,'4005');
		$type = input_string($this->input->get('type'),array('group','tour','jianren','wenda'),FALSE,'4001');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);

		$data = array();
		switch($type)
		{
			case 'group':
				$now_user = $this->get_user_id();	//登录的用户
				if($now_user)
				{
					$group_ids = $this->model->get_groups_by_user_id($user_id,$page,$perpage);
					if($group_ids)
					{
						foreach($group_ids as $key => $row)
						{
							$ids[] = $row['group_id'];
						}
						$data = $this->model->get_relation_groups_by_ids(implode(',',$ids),$now_user);
					}
				}
				else
				{
					$data = $this->model->get_user_groups($user_id,$page,$perpage);
					break;
				}
				break;
			case 'tour':
			case 'jianren':
			case 'wenda':
				$forum_index = $this->model->get_user_forum($type,$user_id,$page,$perpage);
				if($forum_index)
				{
					$this->load->model('group_model');
					$forum_detail = $this->group_model->get_forum_detail($forum_index);		//获取列表所需数据  用户信息等
					foreach($forum_index as $key => $row)
					{
						$row['create_time'] = showTime($row['create_time']);
						$data[] = array_merge($row,$forum_detail[$row['forum_id']]);
					}
				}
				break;
		}
		response_json('1',$data);
	}

	public function delMyForum()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->post('type'),array('forum','post'),FALSE,'4001');
		$type_id = input_int($this->input->post('typeid'),1,FALSE,FALSE,'4001');
		switch($type)
		{
			case 'forum':
				$forum = $this->model->get_user_forum_by_id($type_id);
				if(!$forum||$forum['is_delete'])
				{
					response_code('6004');
				}
				if($forum['create_user'] != $user_id)
				{
					response_code('6005');
				}
				$rs = $this->model->delete_forum_by_id($forum);
				break;
			case 'post':
				$post = $this->model->get_user_post_by_id($type_id);
				if(!$post||$post['is_delete'])
				{
					response_code('6025');
				}
				if($post['create_user'] != $user_id)
				{
					response_code('6005');
				}
				$rs = $this->model->delete_post_by_id($post);
				break;
		}
		if($rs)
		{
			response_code('1');
		}
		response_code('4000');
	}

	public function huatidetail(){
		//
	}
	public function huaticomment(){
		//
	}
}