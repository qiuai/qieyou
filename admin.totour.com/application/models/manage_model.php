<?php

class manage_model extends MY_Model {

	public function get_dest_list()
	{
		$cond = array(
			'table' => 'destination',
			'fields' => 'dest_id,dest_name',
		);
		return $this->get_all($cond);
	}

	public function get_article_total($dest_id)
	{
		$cond = array(
			'table' => 'article',
			'fields' => 'article_id',
			'where' => 'dest_id = '.$dest_id.' AND state != 3'
		);
		if($dest_id == 0)
		{
			$cond['where'] = 'state != 3';
		}
		return $this->get_total($cond);
	}

	public function get_article_list($dest_id,$page,$perpage,$order_by = 'create_time DESC')
	{
		$cond = array(
			'table' => 'article',
			'fields' => 'article.article_id,article.article_title,article.dest_id,article.create_time,article.update_time,article.article_likes,article.user_id,article.state,users.user_name',
			'where' => 'article.dest_id = '.$dest_id.' AND article.state != 3',
			'order_by' => 'article.'.$order_by,
			'join' => array(
				'users',
				'users.user_id = article.user_id'
			)
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		if($dest_id == 0)
		{
			$cond['where'] = 'article.state != 3';
		}
		return $this->get_all($cond,$pagerInfo);
	}

	public function get_article_detail($article_id)
	{
		$cond = array(
			'table' => 'article',
			'fields' => '*',
			'where' => array(
				'article.article_id' => $article_id
			),
			'join' => array(
				'article_detail',
				'article_detail.article_id = article.article_id'
			)
		);
		return $this->get_one($cond);
	}

	public function get_total_homepage_records($action)
	{
		$cond = array(
			'table' => 'home_config',
			'fields' => 'id',
			'where' => array(
				'class' => $action,
				'delete' => 'N'
			)
		);
		return $this->get_total($cond);
	}

	public function get_homepage_records($action,$page,$perpage,$order_by)
	{
		$cond = array(
			'table' => 'home_config',
			'fields' => '*',
			'where' => array(
				'class' => $action,
				'delete' => 'N'
			),
			'order_by' => $order_by.' is_show ASC'
		);
		$pagerInfo = array(
            'cur_page' => $page,
            'per_page' => $perpage
        );
		return $this->get_all($cond,$pagerInfo);
	}

	public function create_home_config($p)
	{
		$cond = array(
			'table' => 'home_config',
			'fields' => '*',
			'data' => array(
				'' => '',
				'class' => $action,
				'is_show' => 'Y'
			)
		);
		return $this->get_all($cond);
	}

	/*
	public function get_data($action,$id)
	{
		switch($action)
		{
			case 'inn':
				$cond = array(
					'table' => 'inns,inns_shopfront,inns_manager',
					'fields' => 'inns.inn_id as id,inns.inn_name as name,inns.inns_url as url,inns_shopfront.inns_summary as content,inns_shopfront.inns_thumb as img,inns_manager.manager_name as add_content',
					'where' => 'inns.inn_id = '.$id.' AND inns_shopfront.inn_id = inns.inn_id AND inns_manager.inn_id = inns.inn_id'
				);
				break;
			case 'article':
				$cond = array(
					'table' => 'article',
					'fields' => 'article.article_id as id,article.article_title as name,article.article_banner as img,article_detail.front as content',
					'where' => array(
						'article.article_id' => $id,
					),
					'join' => array(
						'article_detail',
						'article_detail.article_id = article.article_id'
					)
				);
				break;
			case 'activity':
				$cond = array(
					'table' => 'products',
					'fields' => 'product_id as id,product_name as name,thumb as img,content',
					'where' => array(
						'product_id' => $id,
						'category' => 'game'
					)
				);
				break;
		}
		return $this->get_one($cond);
	}*/

	public function process_catch_data($class,$p)
	{
		$data['class'] = $class;
		$front_url = $this->config->item('front_base_url');
		switch($class)
		{
			case 'inn':
				$data['url'] = $front_url.$p['url'];	
				break;
			case 'article':
				$data['url'] = $front_url.'perfectday/view/'.$p['id'].'.html';
				break;
			case 'activity':
				$data['url'] = $front_url.'activities/view/'.$p['id'].'.html';	
				break;
		}
		$data['classid'] = $p['id'];
		$data['img'] = $p['img'];
		$data['name'] = $p['name'];
		$data['content'] = $p['content'];
		$data['add_content'] = isset($p['add_content'])?$p['add_content']:'';
		$data['is_show'] = 'Y';
		$data['seq'] = $this->get_max_seq($class)+1;
		return $data;
	}

	public function modify_home_recommend($action,$class,$p)
	{
		$rs = 0;
		switch($action)
		{
			case 'create':
				$rs = $this->create_home_recommend($class,$p);
				break;
			case 'update':
				$rs = $this->modify_recommend($class,$p);
				break;
			case 'delete':
				$data['is_show'] = 'N';
				$id = input_num($p['id'],'id不正确！','POST',0);
				$cond = array(
					'table' => 'home_config',
					'primaryKey' => 'id',
					'data' => array(
						'id' => $id,
						'delete' => 'Y'
					)
				);
				$rs = $this->update($cond);
				break;
		}
		if($rs)
		{
			return array('code' => '1','msg'=> '修改成功！');
		}
		return array('code' => '0','msg'=> '操作失败，请重试！');
	}
	
	private function modify_recommend($class,$p)
	{
		$check['class'] = $class;
		$check['id'] = check_empty($p['id'],'id不能为空！');
		$check['classid'] = check_empty($p['classid'],'项目id不能为空！');
		$check['url'] = check_empty($p['url'],'url地址不能为空！');
		$check['img'] = check_empty($p['img'],'缩略图不能为空！');
		$check['name'] = check_empty($p['name'],'名称不能为空！');
		$check['content'] = check_empty($p['content'],'简介不能为空！');
		$check['add_content'] = isset($p['add_content'])?$p['add_content']:'';
		$check['is_show'] = check_empty($p['is_show'],'是否显示必须选择一个值！');
		$check['seq'] = check_empty($p['seq'],'排序值必须为大于0的整数！');

		$cond = array(
			'table' => 'home_config',
			'fields' => '*',
			'where' => array(
				'id' => $check['id'],
				'delete' => 'N'
			)
		);
		$rs = $this->get_one($cond);
		if(!$rs)
		{
			return FALSE;
		}
		$changed = array_diff_assoc($check,$rs);
	//	print_r($changed);exit;
		if($changed)
		{
			$data = array(
				'table' => 'home_config',
				'primaryKey' => 'id',
				'data' => $changed
			);
			$data['data']['id'] = $check['id'];
			return $this->update($data);
		}
		return TRUE;
	}

	private function create_home_recommend($class,$p)
	{
		$data['class'] = $class;
		$data['classid'] = check_empty($p['classid'],'项目id不能为空！');
		$data['url'] = check_empty($p['url'],'url地址不能为空！');
		$data['img'] = check_empty($p['img'],'缩略图不能为空！');
		$data['name'] = check_empty($p['name'],'名称不能为空！');
		$data['content'] = check_empty($p['content'],'简介不能为空！');
		$data['add_content'] = isset($p['add_content'])?$p['add_content']:'';
		$data['is_show'] = check_empty($p['is_show'],'是否显示必须选择一个值！');
		$data['seq'] = check_empty($p['seq'],'排序值必须为大于0的整数！');
		if($this->get_class_info($class,$data['classid']))
		{
			_ajaxJson('-1','存在相同记录');
		}
		return $this->insert($data,'home_config');
	}

	private function get_class_info($class,$classid)
	{
		$cond = array(
			'table' => 'home_config',
			'fields' => '*',
			'where' => array(
				'class' => $class,
				'classid' => $classid,
				'delete' => 'N'
			)
		);
		return $this->get_one($cond);
	}

   /**
    * 获得所需分类最大排序
	* @prarm string $class
	* @return int
	*/
	public function get_max_seq($class)
	{
		$sql = "SELECT max(seq) as maxseq FROM `home_config` WHERE `class` = '".$class."' AND `delete` = 'N'";
		return $this->db->query($sql)->row()->maxseq;
	}

	public function save_seq($p)
	{
		$key = 1;$data='';	
		foreach($p['id'] as $row)
		{
			$row_seq = $p['seq'][$key-1];
			input_num($row,'第 '.$key.' 行，id必须为正整数！','POST',1);
			input_num($row_seq,'第 '.$key.' 行，排序必须为自然数！','POST',0);
			$data .= '('.$row.','.$row_seq.'),';
            $key++;
		}
		$data = rtrim($data,',');
		$sql = "insert into home_config (id,seq) values ".$data." on duplicate key update seq=values(seq)";
		if($this->db->query($sql))
		{
			return array('code' => '1','msg' => '修改成功！');
		}
		else
		{
			return array('code' => '-2','msg' => '修改失败！');
		}
	}

	public function search_article_by_keyword($keyword,$search_type,$dest_id,$page,$perpage)
	{
		$total = 0;
		$rs = array();
		if($search_type == 'uname')
		{
			$cond = array(
				'table' => 'article,users',
				'fields' => 'article.article_id,article.article_title,article.dest_id,article.create_time,article.update_time,article.article_likes,article.user_id,article.state,users.user_name',
				'where' => 'article.user_id = users.user_id AND article.state != 3 AND users.user_name = "'.$keyword.'"'
			);
			if($dest_id)
			{
				$cond['where'] .= 'AND article.dest_id = '.$dest_id;
			}
			$pagerInfo = array(
				'cur_page' => $page,
				'per_page' => $perpage
			);
			$rs = $this->get_all($cond,$pagerInfo);
		}
		elseif($search_type == 'title')
		{
			$cond = array(
				'table' => 'article,users',
				'fields' => 'article.article_id,article.article_title,article.dest_id,article.create_time,article.update_time,article.article_likes,article.user_id,article.state,users.user_name',
				'where' => 'article.article_title LIKE "%'.$keyword.'%" AND article.user_id = users.user_id AND article.state != 3 '
			);
			if($dest_id)
			{
				$cond['where'] .= 'AND article.dest_id = '.$dest_id;
			}
			$pagerInfo = array(
				'cur_page' => $page,
				'per_page' => $perpage
			);
			$rs = $this->get_all($cond,$pagerInfo);
		}
		if($rs)
		{
			$total = count($rs);
			if($page==1)
			{
				if($total=$perpage)
				{
					$total = $this->get_total($cond);
				}
			}
			else
			{
				$total = $this->get_total($cond);
			}
		}
		else
		{
			$total = 0;
			$rs = array();
		}
		return array('total'=> $total,'result' => $rs);
	}

	public function modify_article($action,$p)
	{
		$cond = array(
			'table' => 'article',
			'primaryKey' => 'article_id',
			'data' => array(
				'article_id' => $p['aid'],
				'state' => 'deleted'
			)
		);		
		if($action == 'reback')
		{
			$cond['data']['state'] = 'draft';
		}
		$rs = $this->update($cond);
		if($rs)
		{
			return array('code' => '1' , 'msg' => '修改成功！');
		}
		else
		{
			return array('code' => '-1' , 'msg' => '修改失败，请重试！');
		}
	}
}