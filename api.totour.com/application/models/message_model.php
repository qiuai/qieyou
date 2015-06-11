<?php

class Message_model extends MY_Model {
	
	/**
	 * 获取消息列表
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 上午10:52:38
	 */
	function get_message_list($user_id,$type,$page,$perpage,$last_id=0)
	{
		if($type == 'group')
		{
			$where = '(m.message_type = "group" OR m.message_type = "forum") AND m.receiver_del = 0 AND m.receiver = '.$user_id.'';
		}
		else
		{
			$where = 'm.message_type = "'.$type.'" AND m.receiver_del = 0 AND m.receiver = '.$user_id.'';
		}
		$cond = array(
				'table' => 'messages as m',
				'fields' => '*,m.id',
				'where' => $where,
				'order_by' => 'm.receiver_read ASC m.id DESC'
		);
		if($type == 'sys')
		{
			$cond['join'] = array(
					'sys_message as sm',
					'sm.id = m.message_id'
			);
		}elseif($type == 'group'){
			$cond['join'] = array(
				'message_detail as md',
				'md.msg_id = m.message_id'
			);
		}
		if($last_id){
			$cond['where'] .= ' AND m.id < '.$last_id.' ';
		}
		$pagerInfo = array(
				'perpage' => $perpage,
				'page' => $page
		);
		return $this->get_all($cond,$pagerInfo);
	}
	
	/**
	 * 添加消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 上午10:53:42
	 */
	function add_message($data,$type='sys'){
		// message表
		$msg = array(
			'message_type' => $type,
		);
		
		// message_detail表
		$msg_detail = array(
				'create_time' => TIME_NOW,
		);
		
		switch ($type){
			case 'sys':
				break;
			case 'group':
				$msg_detail_note['group_id'] = $data['group_id'];
				$msg_detail_note['group_name'] = $data['group_name'];
				$msg_detail_note['nick_name'] = $data['nick_name'];
				$msg_detail_note['user_name'] = $data['user_name'];
				$msg_detail_note['user_id'] = $data['user_id'];
				$msg_detail_note['waiting'] = $data['waiting'];
				$msg_detail_note['set_user_name'] = $data['set_user_name'];
				$msg_detail_note['member_id'] = $data['member_id'];
				$msg_detail['member_id'] = $data['member_id'];
				break;
			case 'forum':
				$msg_detail_note['forum_id'] = $data['forum_id'];
				$msg_detail_note['forum_name'] = $data['forum_name'];
				$msg_detail_note['post_detail'] = $data['post_detail'];
				$msg_detail_note['content'] = empty($data['content']) ? '' : $data['content'];
				$msg_detail_note['user_name'] = $data['user_name'];
				$msg_detail_note['nick_name'] = $data['nick_name'];
				$msg_detail_note['user_id'] = $data['user_id'];
				$msg_detail_note['type'] = $data['type'];
				break;
			default:
				break;
		}
		
		$msg_detail['note'] = serialize($msg_detail_note);
		$msg['message_id'] = $this->insert($msg_detail,'message_detail');
		if($msg['message_id'] && $type == 'group'){	// 写入 message_detail表
			$admins = explode(',', $data['admins']);
			foreach ($admins as $value){
				$msg['receiver'] = $value;
				$this->insert($msg,'messages'); // 写入 message表
			}
		}elseif($msg['message_id'] && $type == 'forum'){
			$msg['receiver'] = $data['receiver'];
			$this->insert($msg,'messages'); // 写入 message表
		}
		return TRUE;
	}
	
	/**
	 * 删除消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 上午10:53:10
	 */
	function del_message($id)
	{
		$cond = array(
			'table' => 'messages',
			'primaryKey' => 'id',
			'data' => array(
				'id' => $id,
				'receiver_del' => '1'
			)
		);
		if($this->update($cond))
		{
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * 是否有未读消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-1 下午6:10:28
	 */
	function is_has_message_unread($user_id){
		$cond = array(
				'table' => 'messages',
				'fields' => '*',
				'where' => array(
						'receiver' => $user_id,
						'receiver_read' => 0,
				)
		);
		$msg = $this->get_one($cond);
		if($msg){
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	 * 消息是否已删除
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-4 下午2:54:41
	 */
	function is_message_deled($id,$user_id){
		$cond = array(
				'table' => 'messages',
				'fields' => '*',
				'where' => array(
						'id' => $id,
				)
		);
		$msg = $this->get_one($cond);
		if(empty($msg)){
			return 1; 	// 已删除
		}else{
			if($msg['receiver'] != $user_id){
				return 0;	// 无权限
			}elseif($msg['receiver_del'] == 0){
				return 2;	// 未删除
			}elseif($msg['receiver_del'] == 1){
				return 1; // 已删除
			}
		}
	}
	
	/**
	 * 更新部落申请消息详情
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-4 下午3:58:20
	 */
	public function update_message_detail($data){
		$cond = array(
				'table' => 'message_detail',
				'fields' => '*',
				'where' => array(
						'member_id' => $data['member_id'],
				)
		);
		$detail = $this->get_one($cond);
		if(empty($detail)){
			return FALSE;
		}else{
			$detail_note = unserialize($detail['note']);
			$detail_note['waiting'] = $data['waiting'];
			$detail_note['set_user_name'] = $data['set_user_name'];
			$msg_detail_note = serialize($detail_note);
			$sql = "update message_detail set note='$msg_detail_note' where member_id =".$data['member_id'];
			$this->db->query($sql);
			return TRUE;
		}
	}
	
   /**
	* 获取消息 可选是否获取详情
	* param int $message_id, bool $detail
	* return array()
	*/
	public function get_message_detail($message_id,$detail=TRUE)
	{
		$cond = array(
			'table' => 'messages',
			'fields' => '*',
			'where' => array(
				'id' => $message_id
			)
		);
		$message = $this->get_one($cond);
		if($message && $detail)
		{
			if($message['message_type'] != 'sys')
			{
				$cond = array(
					'table' => 'message_detail',
					'fields' => '*',
					'where' => array(
						'msg_id' => $message['message_id']
					)
				);
				$message_detail = $this->get_one($cond);
			}
			else
			{
				$cond = array(
					'table' => 'sys_message',
					'fields' => '*',
					'where' => array(
						'id' => $message['message_id']
					)
				);
				$message_detail = $this->get_one($cond);
			}
			if(!$message_detail)
			{
				return array();
			}
			$message = array_merge($message,$message_detail);
		}
		return $message;
	}
	
	/**
	 * 标记已读消息
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-4 下午3:58:20
	 */
	public function updateMessage($ids){
		$sql = "update messages set receiver_read = 1 where id in ($ids)";
	}
}