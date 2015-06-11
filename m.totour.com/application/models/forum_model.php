<?php

class Forum_model extends MY_Model {

	public $loadmemcache = TRUE;

	public function get_forum_by_forum_id($forum_id)
	{
		$forum = $this->modelMemcache->get('forum_index'.$forum_id);
		if(!$forum&&$forum !==array())
		{
			$cond = array(
				'table' => 'forums as f',
				'fields' => 'f.*,gs.group_name',
				'where' => array(
					'f.forum_id' => $forum_id,
					'f.is_delete' => 0
				),
				'join' => array(
					'groups as gs',
					'gs.group_id = f.group_id',
					'left'
				)
			);
			$forum = $this->get_one($cond);
			$this->modelMemcache->set('forum_index'.$forum_id,$forum,FALSE,60);
		}
		return $forum;
	}

	/*获取话题详情*/
	public function get_forum_detail_by_forum_id($type,$forum_id)
	{
		$forum_detail = $this->modelMemcache->get('forum_detail'.$forum_id);
		if(!$forum_detail)
		{
			$cond = array(
				'table' => 'forum_'.$type.'',
				'fields' => '*',
				'where' => array(
					'forum_id' => $forum_id,
				)
			);
			$forum_detail = $this->get_one($cond);
			if($forum_detail)
			{
				$user = $this->get_users_info_by_ids($forum_detail['create_user']);
				$forum_detail = array_merge($forum_detail,$user[$forum_detail['create_user']]);
				$this->modelMemcache->set('forum_detail'.$forum_id,$forum_detail,FALSE,60);
			}
		}
		return $forum_detail;
	}

	public function get_user_group_by_group($group_id,$user_id)
	{
		$cond = array(
			'table' => 'group_members',
			'fields' => 'member_id,group_id,is_admin,topics,waiting,create_time',
			'where' => array(
				'group_id' => $group_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond);
	}

	public function check_forum_fav($forum_id,$user_id)
	{
		$cond = array(
			'table' => 'favorite_forum',
			'fields' => 'id',
			'where' => array(
				'forum_id' => $forum_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond)?'1':'0';
	}

	public function check_forum_like($forum_id,$user_id)
	{
		$cond = array(
			'table' => 'forum_user_like',
			'fields' => 'id',
			'where' => array(
				'forum_id' => $forum_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond)?'1':'0';
	}

	public function check_post_like($post_id,$user_id)
	{
		$cond = array(
			'table' => 'forum_post_like',
			'fields' => 'post_like_id',
			'where' => array(
				'post_id' => $post_id,
				'user_id' => $user_id
			)
		);
		return $this->get_one($cond)?'1':'0';
	}

	public function forum_fav($act,$forum,$user_id)
	{
		if($act == 'fav')
		{
			$like = array(
				'forum_id' => $forum['forum_id'],
				'forum_user' => $forum['create_user'],
				'type' => $forum['type'],
				'user_id' => $user_id,
				'create_time' => TIME_NOW
			);
			if($this->insert($like,'favorite_forum'))
			{
				$this->db->query('UPDATE forums SET `favorites` = `favorites` + 1 WHERE forum_id = '.$forum['forum_id']);
				return TRUE;
			}
		}
		else{
			$cond = array(
				'table' => 'favorite_forum',
				'where' => array(
					'forum_id' => $forum['forum_id'],
					'user_id' => $user_id
				)
			);
			if($this->delete($cond))
			{
				$this->db->query('UPDATE forums SET `favorites` = `favorites` - 1 WHERE forum_id = '.$forum['forum_id']);
				return TRUE;
			}
		}
		return FALSE;
	}

	public function forum_like($forum_id,$user_id)
	{
		$like = array(
			'forum_id' => $forum_id,
			'user_id' => $user_id,
			'create_time' => TIME_NOW
		);
		if($this->insert($like,'forum_user_like'))
		{
			$this->db->query('UPDATE forums SET `likes` = `likes` + 1 WHERE forum_id = '.$forum_id);
			return TRUE;
		}
		return FALSE;
	}

	public function post_like($post_id,$user_id)
	{
		$like = array(
			'post_id' => $post_id,
			'user_id' => $user_id,
			'create_time' => TIME_NOW
		);
		if($this->insert($like,'forum_post_like'))
		{
			$this->db->query('UPDATE forum_post SET `post_likes` = `post_likes` + 1 WHERE `post_id` = '.$post_id);
			return TRUE;
		}
		return FALSE;
	}

	public function forum_given_point($forum_id,$point)
	{
		$this->db->query("UPDATE forums SET points = points + ".$point." WHERE forum_id = ".$forum_id);
	}

	public function post_given_point($post_id,$point)
	{
		$this->db->query("UPDATE forum_post SET post_points = post_points + ".$point." WHERE post_id = ".$post_id);
	}

	public function user_post_forum($type,$data,$user_id,$member = array())
	{
		$forum = $data['forum'];
		$forum['create_user'] = $user_id;
		$forum['create_time'] = TIME_NOW;

		$this->db->trans_start();
		$forum_id = $this->insert($forum,'forums');
		if(!$forum_id)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$forum_detail = $data['detail'];
		$forum_detail['forum_id'] = $forum_id;
		$forum_detail['create_user'] = $user_id;
		$this->insert($forum_detail,'forum_'.$type);
		if($member)
		{
			$sql = 'UPDATE groups SET `group_topics` = `group_topics` +1 ,`today_topics` = `today_topics` +1 WHERE group_id = '.$member['group_id'].' LIMIT 1';
			$this->db->query($sql);
			$sql = 'UPDATE group_members SET `topics` = `topics` +1 ,`last_visited` = '.TIME_NOW.' WHERE member_id = '.$member['member_id'].' LIMIT 1';
			$this->db->query($sql);
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		return $forum_id;
	}

	public function forum_look($forum_id)
	{
		$this->db->query('UPDATE forums SET `look` = `look` +1 WHERE forum_id = '.$forum_id.' LIMIT 1');
	}

	public function get_forum_post($forum_id,$page,$perpage)
	{
		//此处需要缓存
		$cond = array(
			'table' => 'forum_post',
			'fields' => '*',
			'where' => array(
				'forum_id' => $forum_id,
				'reply_pid' => 0,
				'is_delete' => 0
			)
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		$list = $this->get_all($cond,$pagerInfo);
		if($list)
		{
			$searchReply = array();
			foreach($list as $key => $row)
			{
				$user_ids[] = $row['create_user'];
				if(!$row['replys'])
				{
					continue;
				}
				$tmp = explode(',',$row['replys']);
				foreach($tmp as $k => $r)
				{
					if(!$r||$k==2)
					{
						break;
					}
					$searchReply[] = $r;
				}
			}
			if($searchReply)
			{
				$cond['fields'] = 'post_id,reply_pid,post_detail,create_user,reply_user';
				$cond['where'] = 'post_id in ('.implode(',',$searchReply).') AND is_delete =0'; 
				$rs = $this->get_all($cond);
				$post_reply = array();
				if($rs)
				{
					foreach($rs as $key => $r)
					{
						$post_reply[$r['reply_pid']][] = $r;
						$user_ids[] = $r['create_user'];
						if($r['reply_user'])
							$user_ids[] = $r['reply_user'];
					}
				}
				unset($rs);
			}
			
			//取出用户信息 拼装回帖的回帖
			$user_ids = array_unique($user_ids);
			$user_info = $this->get_users_info_by_ids(implode(',',$user_ids));
			foreach($list as $key => $row)
			{
				$list[$key] = array_merge($list[$key],$user_info[$row['create_user']]);
				$list[$key]['create_time'] = showTime($list[$key]['create_time']);
				if(isset($post_reply[$row['post_id']]))
				{
					foreach($post_reply[$row['post_id']] as $k => $r)
					{
						$r['create_name'] = $user_info[$r['create_user']]['nick_name'];
						$r['reply_name'] = $user_info[$r['reply_user']]['nick_name'];
						$list[$key]['reply'][] = $r;
					}
				}
				else
				{
					$list[$key]['reply'] = array();
				}
			}
		}
		return $list;
	}

	public function get_forum_post_by_id($post_id,$second_part = FALSE)
	{
		$cond = array(
			'table' => 'forum_post as fp',
			'fields' => '*',
			'where' => array(
				'fp.post_id' => $post_id,
				'fp.reply_pid' => 0,
				'fp.is_delete' => 0
			),
		);
		if($second_part)
		{
			unset($cond['where']['fp.reply_pid']);
		}
		$post = $this->get_one($cond);
		if($post)
		{
			$user_info = $this->get_users_info_by_ids($post['create_user']);
			$post = array_merge($post,$user_info[$post['create_user']]);
		}
		return $post;
	}

	public function get_post_reply_list($post_id,$page,$perpage)
	{
		//此处需要缓存
		$cond = array(
			'table' => 'forum_post',
			'fields' => 'post_id,reply_pid,post_detail,create_user,reply_user,create_time',
			'where' => array(
				'reply_pid' => $post_id,
				'is_delete' => 0
			)
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		$list = $this->get_all($cond,$pagerInfo);
		if(!$list) return $list;
		$searchReply = array();
		foreach($list as $key => $row)
		{
			$user_ids[] = $row['create_user'];
			if($row['reply_user'])
				$user_ids[] = $row['reply_user'];
		}
		
		//取出用户信息 拼装回帖的回帖
		$user_ids = array_unique($user_ids);
		$user_info = $this->get_users_info_by_ids(implode(',',$user_ids));
		foreach($list as $key => $row)
		{
			$list[$key]['create_name'] = $user_info[$row['create_user']]['nick_name'];
			$list[$key]['headimg'] = $user_info[$row['create_user']]['headimg'];
			$list[$key]['reply_name'] = $row['reply_user']?$user_info[$row['reply_user']]['nick_name']:'';
		}
		return $list;
	}

	public function create_forum_post($post)
	{
		$post['create_time'] = TIME_NOW;
		return $this->insert($post,'forum_post');
	}

	public function update_forum_comments_by_id($forum_id)
	{
		$sql = "UPDATE forums SET comments = comments +1 WHERE forum_id = ".$forum_id;
		$this->modelMemcache->delete('forum_index'.$forum_id);
		return $this->db->query($sql);
	}

	public function update_forum_post_by_id($post)
	{
		$sql = "UPDATE forum_post SET post_comments = post_comments +1 ".(isset($post['replys'])?(', replys = "'.$post['replys'].'"'):'')." WHERE post_id = ".$post['post_id'];
		return $this->db->query($sql);
	}
}