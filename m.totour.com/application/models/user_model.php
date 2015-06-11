<?php

class user_model extends MY_Model {

	public $loadmemcache = TRUE;

	public function get_user_groups($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'group_members as gm',
			'fields' => 'gm.group_id,gm.member_id,gm.is_admin,gm.waiting,gm.topics,gm.last_visited,g.group_name,g.group_img,g.members,g.group_topics,g.today_topics',
			'where' => array(
				'gm.user_id' => $user_id
			),
			'join' => array(
				'groups as g',
				'g.group_id = gm.group_id'
			),
			'order_by' => 'gm.last_visited DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

   /**
    * 统计用户在论坛中的发帖 临时版
	*/
	public function get_count_user_forum($type,$user_id)
	{
		$cond = array(
			'table' => 'forums',
			'where' => array(
				'type' => $type,
				'create_user' => $user_id,
				'is_delete' => 0
			)
		);
		return $this->get_total($cond);
	}

   /**
    * 统计用户在论坛中的收藏帖 临时版
	*/
	public function get_count_user_fav_forum($type,$user_id)
	{
		$cond = array(
			'table' => 'favorite_forum',
			'where' => array(
				'type' => $type,
				'user_id' => $user_id
			)
		);
		return $this->get_total($cond);
	}

   /**
    * 统计用户在论坛中的关注的部落 临时版
	*/
	public function get_count_user_group($user_id)
	{
		$cond = array(
			'table' => 'group_members',
			'where' => array(
				'user_id' => $user_id
			)
		);
		return $this->get_total($cond);
	}

	public function get_groups_by_user_id($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'group_members',
			'fields' => 'group_id',
			'where' => array(
				'user_id' => $user_id,
				'waiting' => 0
			),
			'order_by' => 'last_visited DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_relation_groups_by_ids($ids,$user_id)
	{
		$cond = array(
			'table' => 'groups as gs',
			'fields' => 'gs.*, gm.waiting,gm.create_time as join_time',
			'where' => 'gs.group_id IN ('.$ids.')',
			'join' => array(
				'group_members as gm',
				'gm.group_id = gs.group_id AND gm.user_id = '.$user_id.'',
				'left'
			)
		);
		return $this->get_all($cond);
	}

	public function get_user_forum($type,$user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'forums as f',
			'fields' => 'f.*,gs.group_name',
			'where' => array(
				'f.create_user' => $user_id,
				'f.type' => $type,
				'f.is_delete' => 0
			),
			'join' => array(
				'groups as gs',
				'gs.group_id = f.group_id',
				'left'
			),
			'order_by' => 'forum_id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_user_fav_forum($type,$user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'favorite_forum as ff',
			'fields' => 'f.*,gs.group_name',
			'where' => array(
				'ff.user_id' => $user_id,
				'ff.type' => $type
			),
			'join' => array(
				array(
					'forums as f',
					'f.forum_id = ff.forum_id',
				),
				array(
					'groups as gs',
					'gs.group_id = f.group_id',
					'left'
				)
			),
			'order_by' => 'id DESC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_user_forum_by_id($forum_id)
	{
		$cond = array(
			'table' => 'forums',
			'fields' => 'forum_id,group_id,create_user,is_delete',
			'where' => array(
				'forum_id' => $forum_id
			)
		);
		return $this->get_one($cond);
	}

	public function delete_forum_by_id($forum)
	{
		$cond = array(
			'table' => 'forums',
			'primaryKey' => 'forum_id',
			'data' => array(
				'forum_id' => $forum['forum_id'],
				'is_delete' => '1'
			)
		);
		if($this->update($cond))
		{
			if($forum['group_id'])
			{
				$this->db->query('UPDATE group_members SET topics = topics -1 WHERE group_id = '.$forum['group_id'].' AND user_id = '.$forum['create_user']);
				$this->modelMemcache->delete('forum_index'.$forum['forum_id']);
				$this->db->query('UPDATE groups SET `group_topics` = `group_topics` -1 WHERE group_id = '.$forum['group_id']);
			}
			return TRUE;
		}
		return FALSE;
	}

	public function delete_post_by_id($post)
	{
		$cond = array(
			'table' => 'forum_post',
			'primaryKey' => 'post_id',
			'data' => array(
				'post_id' => $post['post_id'],
				'is_delete' => '1'
			)
		);
		if($this->update($cond))
		{				
			if(!$post['reply_pid'])	//一级回复
			{
				$this->modelMemcache->delete('forum_index'.$post['forum_id']);
				$this->db->query('UPDATE forums SET `comments` = `comments` -1 WHERE forum_id = '.$post['forum_id']);
			}
			if($post['reply_pid'])
			{
				$this->db->query('UPDATE forum_post SET `post_comments` = `post_comments` -1 WHERE post_id ='.$post['reply_pid']);
			}
			return TRUE;
		}
		return FALSE;
	}

	public function get_user_post_by_id($post_id)
	{
		$cond = array(
			'table' => 'forum_post',
			'fields' => 'post_id,create_user,is_delete',
			'where' => array(
				'post_id' => $post_id
			)
		);
		return $this->get_one($cond);
	}
}