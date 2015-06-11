<?php

class Partners_model extends MY_Model {

	// 获取客户列表
	public function get_list($user_id,$page, $perpage,$search=''){
		$cond = array(
				'table' => 'partners',
				'fileds' => '*',
				'where' => 'user_id = ' . $user_id ,
				'order_by' => 'create_time DESC'
		);
		if($search){
			$cond['where'] .= ' AND (real_name = "'.$search.'" or mobile_phone = "'.$search.'")';
		}
		$pagerInfo = array(
				'cur_page' => $page,
				'per_page' => $perpage
		);
		return $this->get_all($cond,$pagerInfo);
	}
	
	// 备注操作
	public function saveNote($user_id,$partner_id,$note){
		$sql = "UPDATE partners SET `note` = '$note' WHERE partner_id = $partner_id and user_id = $user_id";
		$rs = $this->db->query($sql);
		return $rs;
	}
}