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
						$cond = array(
							'table' => 'forums as f',
							'fields' => 'f.forum_id,f.create_time,f.create_user,f.city,f.lon,f.lat,ui.headimg,ui.nick_name,ui.sex,ui.birthday',
							'where' => 'f.forum_id IN ('.implode(',',$r).') AND f.is_delete = 0',
							'join' => array(
								'user_info as ui',
								'ui.user_id = f.create_user'
							)
						);
						$rs = $this->get_all($cond);
						if($rs)
						{
							foreach($rs as $key => $row)
							{
								$row['age'] = getAge($row['birthday']);
								unset($row['birthday']);
								$jianren[$row['forum_id']] = $row;
							}
							foreach($r as $line)
							{
								if(!isset($jianren[$line]))
								{
									continue;
								}
								$home['jianren'][] = $jianren[$line];	
							}
						}
						else
						{
							$home['jianren'] = array();
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