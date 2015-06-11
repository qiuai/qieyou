<?php

class Group_model extends MY_Model {

	public $loadmemcache = TRUE;

	public function get_group_detail_by_id($group_id)
	{
		$group = $this->modelMemcache->get('group_detail'.$group_id);
		if(!$group &&$group !== array())
		{
			$cond = array(
				'table' => 'groups',
				'fields' => '*',
				'where' => array(
					'group_id' => $group_id
				)
			);
			$group = $this->get_one($cond);
			if($group&&$group['admins'])
			{
				$user = $this->get_user_cache_info_by_id($group['admins']);
				$group['admin'] = '';
				foreach($user as $key => $row)
				{
					$group['admin'] .= $row['nick_name'].' ';
				}
			}
			$this->modelMemcache->set('group_detail'.$group_id,$group,FALSE,600);
		}
		return $group;
	} 

	public function get_group_info_by_id($ids,$from_db = TRUE)
	{
		$info = array();
		$search_sql = array();
		$return_arr = array();		//返回的数组
		if(!$ids)
		{
			return array();
		}
		if(!$this->modelMemcache)
		{
			$this->load_memcache();
		}
		if($from_db)
		{
			if(!is_array($ids))
			{
				$id_arr = explode(',',$ids);
			}
			foreach($id_arr as $key => $id)
			{
				if(!$id) continue;
				$search_sql[] = $id;
			}
		}
		else
		{
			if(!is_array($ids))
			{
				$id_arr = explode(',',$ids);
			}
			foreach($id_arr as $key => $id)
			{
				if(!$id) continue;
				$res = $this->modelMemcache->get('group_info'.$id);
				if($res)
				{
					$info[] = $group;
				}
				else
				{
					$search_sql[] = $id;
				}
			}
		}
		if($search_sql)
		{
			$cond = array(
				'table' => 'groups',
				'fields' => '*',
				'where' => 'group_id IN ('.implode(',',$search_sql).')'
			);
			$rs = $this->get_all($cond);
			foreach($rs as $key => $row)
			{
				$new[$row['group_id']] = $row;
				$this->modelMemcache->set('group_info'.$row['group_id'],$row,FALSE,300);
			}
		}
		if($info)
		{
			foreach($info as $key => $row)
			{
				$return_arr[$row['group_id']] = $row;
			}
			unset($info);
		}

		if(isset($new))
		{
			foreach($new as $key =>$val)
			{
				$return_arr[$key] = $val;
			}
		}
		return $return_arr;
	}

	public function get_group_forum($type,$last_id,$limit)
	{
		//$type = 'live';
		//$sql = 'SELECT * FROM group';
		$cond = array(
			'table' => 'forums as f',
			'fields' => 'f.*,gs.group_name',
			'where' => 'f.is_delete = 0',
			'join' => array(
				'groups as gs',
				'gs.group_id = f.group_id',
				'left'
			),
			'order_by' => 'f.forum_id DESC',
			'limit' => $limit,
			'offset' => 0
		);
		switch($type)
		{
			case 'live':
				break;
			default:
				$cond['where'] .= ' AND f.type = "'.$type.'" ';
				break;
		}
		if($last_id)
		{
			$cond['where'] .= ' AND f.forum_id <'.$last_id.' ';
		}
		return $this->get_all($cond);
	}

	public function get_forum_detail($forum_index)
	{
		// 从缓存中获取数据 
		foreach($forum_index as $key => $row)
		{
			$forum_ids[] = $row['forum_id'];
			$forum_class[$row['type']][] = $row['forum_id'];		//分类保存id
		}

		//	print_r($forum_class);exit;
		// forum_class 未命中的数据 需要更具类型归类

		$forum_detail = array();	//缓存读取的数据

		$db_detail = array();
		foreach($forum_class as $type => $ids)
		{
			if(is_array($ids))
			{
				$ids = implode(',',$ids);
			}
			$cond = array(
				'table' => 'forum_'.$type.' as fm',
				'fields' => 'fm.*,ui.nick_name,ui.headimg,ui.sex,ui.birthday,ui.local',
				'where' => 'fm.forum_id IN ('.$ids.')',
				'join' => array(
					'user_info as ui',
					'ui.user_id = fm.create_user'
				)
			);
			$rs = $this->get_all($cond);
			foreach($rs as $key => $row)
			{
				$row['age'] = getAge($row['birthday']);
				unset($row['birthday']);
				$db_detail[] = $row;
			}
		//	$db_detail = array_merge($db_detail,$rs);
		}
		$forum_detail = array_merge($forum_detail,$db_detail);
		if($forum_detail)
		{
			$result = array();
			foreach($forum_detail as $key => $row)
			{
				$result[$row['forum_id']] = $row;
			}
			return $result;
		}
		return $forum_detail;
	}

	public function get_groups($user_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'groups as gs',
			'fields' => 'gs.*',
			'order_by' => 'gs.members DESC gs.group_id ASC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		if($user_id)
		{
			$cond['fields'] = 'gs.* ,gm.waiting , gm.create_time as join_time';
			$cond['join'] = array(
				'group_members as gm',
				'gm.group_id = gs.group_id AND gm.user_id = '.$user_id.'',
				'left'
			);
		}
		return $this->get_all($cond,$pagerInfo);
	}

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

	public function modify_forum_by_forum_id($action,$forum)
	{
		$cond = array(		//需要一个帖子操作日志表
			'table' => 'forums',
			'primaryKey' => 'forum_id',
			'data' => array(
				'forum_id' => $forum['forum_id'],
				'is_top' => 0,
				'update_time' => TIME_NOW
			)
		);
		if($action == 'set_top')
		{
			$cond['data']['is_top'] = 1;
		}
		return $this->update($cond);
	}
	
	public function update_group_info($changed)
	{
		$cond = array(
			'table' => 'groups',
			'primaryKey' => 'group_id',
			'data' => $changed
		);
		if($this->update($cond))
		{
			$this->modelMemcache->delete('group_detail'.$changed['group_id']);
			$this->modelMemcache->delete('group_info'.$changed['group_id']);
			return TRUE;
		}
		return FALSE;
	}

	public function delete_forum_by_forum_id($forum,$user_id)
	{
		$cond = array(
			'table' => 'forums',
			'primaryKey' => 'forum_id',
			'data' => array(
				'forum_id' => $forum['forum_id'],
				'is_delete' => 1,
				'delete_user' => $user_id	//需要一个帖子操作日志表
			)
		);
		if($this->update($cond))
		{
			if($forum['group_id'])
			{
				$this->db->query('UPDATE groups SET `group_topics` = `group_topics` -1 WHERE group_id ='.$forum['group_id']);
				if(!$this->modelMemcache)
				{
					$this->load_memcache();
					$this->modelMemcache->delete('forum_index'.$forum['forum_id']);
				}
			}
			return TRUE;
		}			return TRUE;
		}
		return FALSE;
	}

	public function get_group_member_by_gourp_id($type,$group_id,$page,$perpage)
	{
		$cond = array(
			'table' => 'group_members',
			'fields' => 'member_id,group_id,user_id,is_admin,set_user_id,topics,last_visited,create_time',
			'where' => array(
				'group_id' => $group_id,
				'waiting' => 0
			),
			'order_by' => 'is_admin DESC member_id DESC'
		);
		if($type == 'waiting')
		{
			$cond['where']['waiting'] = '1';
		}
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_user_cache_info_by_id($ids)
	{
		$user_info = array();
		$search_sql_user = array();
		$return_arr = array();
		if(is_array($ids))
		{
			$id_arr = $ids;
		}
		else
		{
			$id_arr = explode(',',$ids);
		}
		$this->load_memcache();
		foreach($id_arr as $key => $id)
		{
			if(!$id) continue;
			$user = $this->modelMemcache->get('user_info'.$id);
			if($user)
			{
				$user_info[] = $user;
			}
			else
			{
				$search_sql_user[] = $id;
			}
		}

		if($search_sql_user)
		{
			$cond = array(
				'table' => 'user_info',
				'fields' => 'user_id,nick_name,headimg,birthday,sex,local',
				'where' => 'user_id IN ('.implode(',',$search_sql_user).')',
			);
			$rs = $this->get_all($cond);
			foreach($rs as $key => $user)
			{
				$user['age'] = getAge($user['birthday']);
				unset($user['birthday']);
				$new[$user['user_id']] = $user;
				$this->modelMemcache->set('user_info'.$user['user_id'],$user,FALSE,1800);
			}
		}
		if($user_info)
		{
			foreach($user_info as $key => $row)
			{
				$return_arr[$row['user_id']] = $row;
			}
			unset($user_info);
		}

		if(isset($new))
		{
			foreach($new as $key =>$val)
			{
				$return_arr[$key] = $val;
			}
		}
		return $return_arr;
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

	public function join_group($group,$user_id)
	{
		$member = array(
			'user_id' => $user_id,
			'group_id' => $group['group_id'],
			'last_visited' => TIME_NOW,
			'create_time' => TIME_NOW,
		);
		if($group['join_method'] == 'verify')
		{
			$member['waiting'] = 1;
		}
		if($this->insert($member,'group_members'))
		{
			if($group['join_method'] == 'verify')	//发送消息
			{
				$member['waiting'] = 1;
			}
			else
			{
				$sql = 'UPDATE groups SET `members` = `members` + 1 WHERE `group_id` = '.$member['group_id'];
				$this->db->query($sql);
			}
			return TRUE;
		}
		return FALSE;
	}

	public function quit_group($member)
	{
		$cond = array(
			'table' => 'group_members',
			'where' => array(
				'member_id' => $member['member_id']
			)
		);
		if($this->delete($cond))
		{
			if($member['is_admin'])
			{
				$group = $this->get_group_info_by_id($member['group_id'],TRUE);
				if($group)
				{
					$group = $group[$member['group_id']];
					if($group['admins'])	//防报错
					{
						$admins = explode(',',$group['admins']);
						$new_admin = array();
						foreach($admins as $key => $row)
						{
							if(!$row||$row == $member['user_id'])
								continue;
							$new_admin[] = $row; 
						}
						if($new_admin)
						{
							$new_admin = explode(',',$new_admin);
						}
						else
						{
							$new_admin = '';
						}
						$sql = 'UPDATE groups SET `admins` = "'.$new_admin.'" WHERE `group_id` = '.$member['group_id'];
						$this->db->query($sql);	
						$this->modelMemcache->delete('group_detail'.$member['group_id']);
						$this->modelMemcache->delete('group_info'.$member['group_id']);
					}
				}
			}
			if(!$member['waiting'])
			{
				$sql = 'UPDATE groups SET `members` = `members` -1 '.($member['topics']?(',`group_topics` = `group_topics` - '.$member['topics'].''):'').' WHERE `group_id` = '.$member['group_id'];
				$this->db->query($sql);
				//发送消息
			}
			return TRUE;
		}
		return FALSE;
	}

	public function delete_group_member($member)
	{
		$cond = array(
			'table' => 'group_members',
			'where' => array(
				'member_id' => $member['member_id']
			)
		);
		if($this->delete($cond))
		{
			$sql = 'UPDATE groups SET `members` = `members` -1,`group_topics` = `group_topics` - '.$member['topics'].' WHERE `group_id` = '.$member['group_id'];
			$this->db->query($sql);
			//发送消息
			return TRUE;
		}
		return FALSE;
	}

	public function allow_group_member($member,$user_id)
	{
		$cond = array(
			'table' => 'group_members',
			'primaryKey' => 'member_id',
			'data' => array(
				'member_id' => $member['member_id'],
				'waiting' => 0,
				'set_user_id' => $user_id
			)
		);
		if($this->update($cond))
		{
			$sql = 'UPDATE groups SET `members` = `members` + 1 , `waiting_verify` = `waiting_verify` -1 WHERE `group_id` = '.$member['group_id'];
			$this->db->query($sql);
			//发送消息
			return TRUE;
		}
		return FALSE;
	}

	public function ignore_group_member($member)
	{
		$cond = array(
			'table' => 'group_members',
			'where' => array(
				'member_id' => $member['member_id']
			)
		);
		if($this->delete($cond))
		{
			$sql = 'UPDATE groups SET `waiting_verify` = `waiting_verify` -1 WHERE `group_id` = '.$member['group_id'];
			$this->db->query($sql);
			//发送消息
			return TRUE;
		}
		return FALSE;
	}

	public function get_user_member_info($member)
	{
		$cond = array(
			'table' => 'group_members',
			'fields' => '*',
			'where' => array(
				'member_id' => $member
			)
		);
		return $this->get_one($cond);
	}

	public function get_forum_list($search,$order_by,$limit)
	{
		$select = 'SELECT f.*,gs.group_name ';
		$from = 'FROM forums as f JOIN groups as gs ON gs.group_id = f.group_id ';
		$where = 'WHERE f.is_delete = 0 AND';
		if($order_by == 'local')
		{
			$select .= ',(POWER(ABS(lon - '.$search['lon'].'),2) + POWER(ABS(lat - '.$search['lat'].'),2)) AS distance ';
			$where .= ' lat != 0 AND ';
			$order_by = ' ORDER BY distance ';
		}
		else
		{
			$order_by = ' ORDER BY f.'.$order_by;
		}
		if(!empty($search['group_id']))
		{
			$where .= ' f.group_id = '.$search['group_id'].' AND ';
		}
		if(!empty($search['last_id']))
		{
			$where .= ' f.forum_id < '.$search['last_id'].' AND ';
		}
		if(!empty($search['keyword']))
		{
			$where .= ' f.forum_name LIKE "%'.$search['keyword'].'%" AND ';
		}
		if(isset($search['type'])&&$search['type'] != 'near')
		{
			$where .= ' f.is_top = 0';
		}
		if($where == 'WHERE')
		{
			$where = 'WHERE 1 ';
		}
		else
		{
			$where = rtrim($where,'AND ');
		}
		return $this->db->query($select.$from.$where.$order_by.' '.$limit)->result_array();
	}

	public function get_top_forum($group_id)
	{
		$cond = array(
			'table' => 'forums as f',
			'fields' => 'f.*,gs.group_name',
			'where' => array(
				'f.group_id' => $group_id,
				'f.is_top' => '1'
			),
			'join' => array(
				'groups as gs',
				'gs.group_id = f.group_id'
			),
			'order_by' => 'f.update_time DESC'
		);
		return $this->get_all($cond);
	}

	public function search_group_name($keyword)
	{
		$cond = array(
			'table' => 'groups',
			'fields' => '*',
			'where' => 'group_name LIKE "%'.$keyword.'%"',
			'order_by' => 'members DESC'
		);
		return $this->get_all($cond);
	}

	public function create_group($group,$done)
	{
		$group['admins'] = $done['user_id'];
		$group['create_by'] = $done['user_id'];
		$group['create_time'] = TIME_NOW;
		$group_id = $this->insert($group, 'groups');
		if($group_id)
		{
			$group_member['user_id'] = $done['user_id'];
			$group_member['group_id'] = $group_id;
			$group_member['is_admin'] = 1;
			$group_member['last_visited'] = TIME_NOW;
			$group_member['create_time'] = TIME_NOW;
			$this->insert($group_member, 'group_members');
			return $group_id;
		}
		return FALSE;
	}

	public function get_user_own_group($user_id)	//待修正 set_user_id is_admin 
	{
		$cond = array(
			'table' => 'groups',
			'where' => array(
				'create_by' => $user_id
			)
		);
		return $this->get_total($cond);
	}
}