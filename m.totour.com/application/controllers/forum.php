<?php

class Forum extends MY_Controller {

	public $layout_for_title = '且游社区';
	public $layout = 'default';

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index()
	{
		header('location:'.base_url().'group');
		exit;
    }

	public function sendtour()
	{
		$user_id = $this->get_user_id(TRUE);
		$this->moduleTag = '发游记';
		$this->layout = 'item';
	}
	
	public function sendwen()
	{
		$user_id = $this->get_user_id(TRUE);
		$this->moduleTag = '发问答';
		$this->layout = 'item';
	}
	
	public function sendjian()
	{
		$user_id = $this->get_user_id(TRUE);
		$this->moduleTag = '发捡人';
		$this->layout = 'item';
	}
	
   /**
    * 话题详情
    **/
	public function detail()
	{
		$forum_id = input_int($this->input->get('forum'),1000,FALSE,FALSE,'6003');
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if(!$forum||$forum['is_delete'])
		{
			response_code('6004');
		}
		$forum_detail = $this->model->get_forum_detail_by_forum_id($forum['type'],$forum_id);

		$forum['is_admin'] = 0;
		$user_id = $this->get_user_id();
		if($user_id)
		{
			$forum['is_fav'] = $this->model->check_forum_fav($forum_id,$user_id);
			$forum['is_like'] = $this->model->check_forum_like($forum_id,$user_id);
			if($forum['group_id'])		//这里可以用读写session代替
			{
				$group_admin = $this->model->get_user_group_by_group($forum['group_id'],$user_id);
				if($group_admin)
				{
					$forum['is_admin'] = $group_admin['is_admin'];
				}
			}
			$forum['is_owner'] = $user_id == $forum['create_user']?'1':'0';
		}
		else
		{
			$forum['is_owner'] = 0;
		}

		$this->model->forum_look($forum_id);
		//print_r($forum);
		$this->layout = 'simple';
		$this->moduleTag = $forum['forum_name'];
		$this->viewData = array(
			'forum' => $forum,
			'forum_detail' => $forum_detail,
			'admin' => isset($_GET['admin']) && $_GET['admin'] == 'yes' ? true : false
		);
	}
	
   /**
	* 贴子搜索
	*/
	public function search() 
	{
		$this->moduleTag = '贴子搜索';
	//	$this->layout = 'default';
    }


  /**
	* 贴子搜索列表
	*/
	public function searchlist() 
	{
		$this->moduleTag = '搜索结果';
	//	$this->layout = 'default';
    }


   /**
    * 用户对话题的操作
	**/
	public function favForum()
	{
		$user_id = $this->get_user_id(TRUE);
		$forum_id = input_int($this->input->post('forum'),1000,FALSE,FALSE,'6003');
		$act = input_string($this->input->post('act'),array('like','fav','unfav'),FALSE,'4001');
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if(!$forum||$forum['is_delete'])
		{
			response_code('6004');
		}
		switch($act)
		{
			case 'fav':
				$is_fav = $this->model->check_forum_fav($forum_id,$user_id);
				if($is_fav) response_code('6019');
				break;
			case 'unfav':
				$is_fav = $this->model->check_forum_fav($forum_id,$user_id);
				if(!$is_fav) response_code('6018');
				break;
			case 'like':	//顶 只能顶 不能取消
				$is_like = $this->model->check_forum_like($forum_id,$user_id);
				if($is_like) response_code('1');
				if($this->model->forum_like($forum_id,$user_id))
				{
					response_code('1');
				}
				response_code('4000');
				break;
		}
		if($this->model->forum_fav($act,$forum,$user_id))
		{
			response_code('1');
		}
		response_code('4000');
	}

   /**
    * 用户对评论的操作
	**/
	public function favPost()
	{
		$user_id = $this->get_user_id(TRUE);
		$post_id = input_int($this->input->post('post'),1,FALSE,FALSE,'6003');
		$act = input_string($this->input->post('act'),array('like'),FALSE,'4001'); //顶 只能顶 不能取消
		$post = $this->model->get_forum_post_by_id($post_id);
		if(!$post||$post['is_delete'])
		{
			response_code('6025');
		}
		$is_like = $this->model->check_post_like($post_id,$user_id);
		if($is_like)
		{
			response_code('1');
		}
		if($this->model->post_like($post_id,$user_id))
		{
			response_code('1');
		}
		response_code('4000');
	}

	public function givenPoint()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->post('type'),array('forum','post'),FALSE,'4001');
		$typeid = input_int($this->input->post('typeid'),1,FALSE,FALSE,'4003');
		$user = $this->model->get_user_detail($user_id);
		if($user['point'] < 10)
		{
			response_code('6027');
		}
		switch($type)
		{
			case 'forum':
				$forum = $this->model->get_forum_by_forum_id($typeid);
				if(!$forum||$forum['is_delete'])
				{
					response_code('6004');
				}
				if($forum['type'] == 'wenda')
				{
					response_code('6029');
				}
				$given_user = $forum['create_user'];
				if($user_id == $given_user)
				{
					response_code('6028');
				}
				$content = '打赏帖子积分';
				$given_content = '帖子获得打赏积分';
				$act = 131;
				$given_act = 31;
				$this->model->forum_given_point($typeid,10);
				break;
			case 'post':
				$post = $this->model->get_forum_post_by_id($typeid);
				if(!$post||$post['is_delete'])
				{
					response_code('6025');
				}		
				$given_user = $post['create_user'];
				if($user_id == $given_user)
				{
					response_code('6028');
				}
				$content = '打赏回帖积分';
				$given_content = '回帖获得打赏积分';
				$act = 132;
				$given_act = 32;
				$this->model->post_given_point($typeid,10);
				break;
		}
		$this->model->update_user_point($user_id,-10,$content,$act,FALSE);
		$this->model->update_user_point($given_user,10,$given_content,$given_act);
		response_code('1');
	}
	
   /**
    * 用户回帖一级列表
	**/
	public function getForumPost()
	{
		$forum_id = input_int($this->input->get('forum'),1000,FAlSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);		//性能考虑 不使用last_id
		$perpage = input_int($this->input->get('perpage'),1,50,10);
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if(!$forum||$forum['is_delete'])
		{
			response_code('6004');
		}
		$list = array();
		if(!$forum['comments']||$forum['comments'] < ($page-1 *$perpage))
		{
			response_json('1',$list);
		}
		$list = $this->model->get_forum_post($forum_id,$page,$perpage);
		response_json('1',$list);
	}

   /**
    * 用户回帖详情
	**/
	public function postdetail()
	{
		$post_id = input_int($this->input->get('post'),1,FAlSE,FALSE,'6025');
		$post = $this->model->get_forum_post_by_id($post_id);
		if(!$post||$post['is_delete'])
		{
			response_code('6025');
		}
		$forum = $this->model->get_forum_by_forum_id($post['forum_id']);
		if(!$forum||$forum['is_delete'])
		{
			response_code('6004');
		}
		$post['is_like'] = 0;
		$post['is_admin'] = 0;
		$post['is_owner'] = 0;
		$user_id = $this->get_user_id();
		if($user_id)
		{
			$post['is_like'] = $this->model->check_post_like($post_id,$user_id);
			if($forum['group_id'])		//这里可以用读写session代替
			{
				$group_admin = $this->model->get_user_group_by_group($forum['group_id'],$user_id);
				if($group_admin)
				{
					$post['is_admin'] = $group_admin['is_admin'];
				}
			}
			$post['is_owner'] = $user_id == $post['create_user']?'1':'0';
		}
		$this->moduleTag = '回复详情';
		$this->layout = 'simple';
		$this->viewData = array(
			'post' => $post	
		);
	}

   /**
    * 用户回帖 二级列表
	**/
	public function getPostReply()
	{
		$post_id = input_int($this->input->get('post'),1,FAlSE,FALSE,'6025');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,50,10);
		$post = $this->model->get_forum_post_by_id($post_id);
		if(!$post)
		{
			response_code('6025');
		}		//可添加纠错
		$list = array();
		if(!$post['post_comments']||$post['post_comments'] < ($page-1) *$perpage)
		{
			response_json('1',$list);
		}
		$list = $this->model->get_post_reply_list($post_id,$page,$perpage);
		response_json('1',$list);
	}

   /**
    * 用户发帖
	**/
	public function postForum()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->post('type'),array('tour','jianren','wenda'),FALSE,'4001');
		$group_id = input_int($this->input->post('group'),1000,FAlSE,0);
		$member = array();
		$data = $this->check_forum_post($type);
		if($group_id)
		{	
			$member = $this->model->get_user_group_by_group($group_id,$user_id);
			if(!$member || $member['waiting'] )
			{
				response_code('6011');
			}
			$data['forum']['group_id'] = $group_id;
		}
		$forum_id = $this->model->user_post_forum($type,$data,$user_id,$member);
		if($forum_id)
		{
			response_json('1','发帖成功');
		}
		response_code('4000');
	}

   /**
    * 用户回帖 一级回复
	**/
	public function forumReply()
	{
		$user_id = $this->get_user_id(TRUE);
		$post_reply = $this->check_post_reply();

		$forum_id = input_int($this->input->post('forum'),1000,FALSE,FALSE,'6003');	//如果是回复一级回复
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if(!$forum||$forum['is_delete'])
		{
			response_code('6004');
		}

		$post_reply['create_user'] = $user_id;
		$post_reply['forum_id'] = $forum_id;
		$post_reply_id = $this->model->create_forum_post($post_reply);
		
		// 发送消息
		$this->addForumMessage($forum_id,$user_id,$forum['create_user'],strip_tags($this->input->post('note')));
		
		if($post_reply_id)
		{
			$this->model->update_forum_comments_by_id($forum_id);
			response_code('1');
		}
		response_code('4000');
	}

   /**
    * 用户回帖 二级回复
	**/
	public function forumReReply()
	{
		$user_id = $this->get_user_id(TRUE);
		$post_reply = $this->check_post_reply();
		$reply_post = input_int($this->input->post('replypost'),1,FALSE,FALSE,'6025');				//回复对象 一级或二级的post id
		$reply_post_detail = $this->model->get_forum_post_by_id($reply_post,TRUE);
		if(!$reply_post_detail)
		{
			response_code('6025');
		}

		if($reply_post_detail['reply_pid'] == 0)	//回复一级回复
		{
			$post = $reply_post_detail;
		}
		else
		{
			$post = $this->model->get_forum_post_by_id($reply_post_detail['reply_pid']);
			if(!$post)
			{
				response_code('6025');
			}
		}	//$post 作为被回复的一级评论

		$post_reply['forum_id'] = $post['forum_id'];
		$post_reply['create_user'] = $user_id;
		$post_reply['reply_pid'] = $post['post_id'];
		$post_reply['reply_user'] = $reply_post_detail['create_user'];		//回复对象

		$post_reply_id = $this->model->create_forum_post($post_reply);
		if($post_reply_id)	//插入成功 更新一级回复   
		{
			$data = array();
			if($post['replys'])
			{
				if(substr_count($post['replys'],',')<10)
				{
					$data['replys'] = $post['replys'].','.$post_reply_id;
				}
			}
			else
			{
				$data['replys'] = $post_reply_id;
			}
			$data['post_id'] = $post['post_id'];
			$this->model->update_forum_post_by_id($data);
			
			// 发送消息
			$this->addForumMessage($post['forum_id'],$user_id,$post_reply['reply_user'],strip_tags($this->input->post('note')),$post['post_id']);
		}
		response_code('1');
	}
	
	/**
	 * 帖子回复发送消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 下午4:38:19
	 */
	private function addForumMessage($forum_id,$user_id,$reply_id,$content,$reply_pid=0){
		$user = $this->model->get_user_detail($user_id);
		$forum = $this->model->get_forum_by_forum_id($forum_id);
		if($reply_pid){ // 回复评论
			$post = $this->model->get_forum_post_by_id($reply_pid);
			$data['content'] = $post['post_detail'];
		}else{ // 回帖
		}
		
		$data['user_id'] = $user_id;
		$data['user_name'] = $user['user_name'];
		$data['nick_name'] = $user['nick_name'];
		$data['forum_id'] = $forum_id;
		$data['forum_name'] = $forum['forum_name'];
		$data['type'] = $forum['type'];
		$data['post_detail'] = $content;
		$data['receiver'] = $reply_id;
		
		$this->load->model('message_model');
		$this->message_model->add_message($data,'forum');
		
		return TRUE;
	}

	private function check_post_reply()
	{
		$post = array();
		$post['city'] = check_empty(trimall(strip_tags($this->input->post('city'))),'');
		$post['lat'] = checkLocationPoint($this->input->post('lat'),'lat','');	//坐标可不传
		$post['lon'] = checkLocationPoint($this->input->post('lon'),'lon','');
		if(empty($post['lat'])||empty($post['lon']))
		{
			$post = array();
		}

		$post['post_detail'] = check_empty(strip_tags($this->input->post('note')),'','6026');
		$post['pictures'] = check_empty(trimall(strip_tags($this->input->post('images',TRUE))),'');

		return $post;
	}

	private function check_forum_post($type)
	{	
		$forum = array();
		$forum['city'] = check_empty(trimall(strip_tags($this->input->post('city'))),'');
		$forum['lat'] = checkLocationPoint($this->input->post('lat'),'lat','');	//坐标可不传
		$forum['lon'] = checkLocationPoint($this->input->post('lon'),'lon','');
		if(empty($forum['lat'])||empty($forum['lon']))
		{
			$forum = array();
		}

		$forum['forum_name'] = check_empty(trimall(strip_tags($this->input->post('title'))),'','6014');
		$forum['type'] = $type;
		$tags = check_empty(trimall(strip_tags($this->input->post('tags'))),'');
		if($tags)
		{
			$detail['tags'] = array();
			$tags = explode(',',$tags);
			foreach($tags as $key => $row)
			{
				if(!$row)
				{
					continue;
				}
				if(mb_strlen($row)>6)
				{
					response_json('6033','标签："'.$row.'" 字数过长');
				}
				$detail['tags'][] = $row;
			}
			if(count($detail['tags']) > 3)
			{
				response_code('6032');
			}
			$detail['tags'] = implode(',',$detail['tags']);
		}
		else
		{
			$detail['tags'] = '';
		}
		$detail['note'] = check_empty(strip_tags($this->input->post('note')),'');
		$detail['pictures'] = trimall(strip_tags($this->input->post('images',TRUE)));

		if($type == 'jianren')
		{
			$detail['line'] = check_empty(trimall(strip_tags($this->input->post('line',TRUE))),FALSE,'6016');
			if(empty($forum['forum_name']))
			{
				$forum['forum_name'] = $detail['line'];
			}
			$start_time = check_empty($this->input->post('start_time'),FALSE,'6017');
			if(substr_count($start_time,'-') != 2)
			{
				response_code('6024');
			}
			list($year, $month, $day) = explode('-', $start_time);
			if(!$year||!$month||!$day||!checkdate($month,$day,$year))
			{
				response_code('6024');
			}
			$start_time = strtotime($start_time);
			if(!$start_time || $start_time < TIME_NOW-86500 || $start_time > TIME_NOW + 31536000)	//一年内可选
			{
				response_code('6024');
			}
			$detail['start_time'] = $start_time;
			$detail['day'] = input_int($this->input->post('day'),0,250,FAlSE,'6015');
		}
		else	//非问答需要上传图片
		{
			$detail['pictures'] = check_empty($detail['pictures'],FALSE,'6013');
		}
		if(empty($forum['forum_name']))
		{
			response_code('6014');
		}
		return array('forum' => $forum, 'detail' => $detail);
	}
}