<?php

class Homepage_model extends MY_Model {

	public function home_config()
	{
		$cond = array(
			'table' => 'recommend_config',
			'fields' => 'type,type_id',
			'where' => array(
				'is_show' => 'Y',
				'is_delete' => '0'
			),
			'order_by' => 'sort ASC'
		);
		$rs = $this->get_all($cond);
		$home = array();
		if($rs)
		{
			$type = array();
			foreach($rs as $key => $row)
			{
				$type[$row['type']][] = $row['type_id'];
			}
			foreach($type as $k => $r)
			{
				switch($k)
				{
					case 'group':
						$cond = array(
							'table' => 'groups',
							'fields' => 'group_id,group_name,group_img',
							'where' => 'group_id IN ('.implode(',',$r).')'
						);
						$rs = $this->get_all($cond);
						if($rs)
						{
							foreach($rs as $key => $row)
							{
								$group[$row['group_id']] = $row;
							}
							foreach($r as $line)
							{
								if(!isset($group[$line]))
								{
									continue;
								}
								$home['groups'][] = $group[$line];	
							}
						}
						break;
					case 'product':
						$cond = array(
							'table' => 'products',
							'fields' => 'product_id,product_name,quantity,price,old_price,tuan_end_time,gallery',
							'where' => 'product_id IN ('.implode(',',$r).') AND state = "T"'
						);
						$rs = $this->get_all($cond);
						if($rs)
						{
							foreach($rs as $key => $row)
							{
								$products[$row['product_id']] = $row;
							}
							foreach($r as $line)
							{
								if(!isset($products[$line]))
								{
									continue;
								}
								if(!$products[$line]['quantity'])
								{
									continue;
								}
								$home['products'][] = $products[$line];	
							}
						}
						break;
					case 'jianren':
						$query = 'SELECT f.*,gs.group_name,fm.*,ui.nick_name,ui.headimg,ui.sex,ui.birthday,ui.local ';
						$query .= 'FROM forums as f JOIN groups as gs ON gs.group_id = f.group_id JOIN forum_jianren as fm ON fm.forum_id = f.forum_id JOIN user_info as ui ON ui.user_id = f.create_user ';
						$query .= 'WHERE f.is_delete = 0 AND f.forum_id IN ('.implode(',',$r).')';
						$rs = $this->db->query($query)->result_array();
						if($rs)
						{
							$forum = array();
							foreach($rs as $key => $row)
							{
								$row['create_time'] = showTime($row['create_time']);
								$row['age'] = getAge($row['birthday']);
								unset($row['birthday']);
								$forum[$row['forum_id']] = $row;
							}
							foreach($r as $line)
							{
								if(!isset($forum[$line]))
								{
									continue;
								}
								$home['jianren'][] = $forum[$line];	
							}
						}
						break;
					default:
						break;
				}
			}
		}
		return $home;
	}
}