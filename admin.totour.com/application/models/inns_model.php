<?php

class Inns_model extends MY_Model {
  
	public function update_inns($inn_id, $innsInfo) {
		$this->db->where('inn_id', $inn_id);
		$this->db->update('inns', $innsInfo);
		return $inn_id;
	}
	
	public function get_inns_account_balance($inn_id,$allinfo = FALSE) {
		$this->db->where('inn_id', $inn_id);
		$row = $this->db->get('inns')-> row_array();
		if($allinfo)
		{
			return $row;
		}
		return $row['account_balance'];
	}

	public function get_innsDetailInfo_by_inn_id($inn_id)
	{
		$sql = "select u.user_id,u.user_name,ui.real_name,ui.sex, ui.mobile_phone , i.create_time,
				 i.inn_name, i.inn_id,i.state,i.front_show,sf.inner_contacts, sf.inn_address, sf.inner_telephone ,sf.inner_moblie_number , d.dest_name , l.local_name 
				from inns i 
				join inn_shopfront  AS sf on sf.inn_id  = i.inn_id 
				join users AS u on u.user_id = i.innholder_id 
				join user_info AS ui on ui.user_id = i.innholder_id
				join china_dest AS d on d.dest_id = i.dest_id
				join china_dest_local AS l on l.local_id = i.local_id
				where i.inn_id = $inn_id ";
		return $this->db->query($sql)->row_array();
	}

	public function search_cash_apply_by_inns($limit, $innsId = '') {
		$records = array();
		$select = "SELECT ca.*, u.user_name as applyUserName, i.inn_name as innsName,
				u2.user_name as cashier
			   FROM cashout ca 
			   LEFT JOIN users u on ca.apply_user_id = u.user_id 
			   LEFT JOIN users u2 on ca.cashier_id = u2.user_id 
			   LEFT JOIN inns i on i.inn_id = ca.inn_id ";
        $orderby = "  order by ca.create_time desc ";
		$where = "WHERE ca.state != 'rejected' ";
		if (!empty($innsId)) {
			$where = $where. "AND i.inn_id = $innsId";
		}
		$sql = $select.$where.$orderby.$limit;
		$result = $this->db->query($sql) -> result_array();
		$total = $this-> get_query_count($select.$where);
		$amountWhere = "WHERE ca.state = 'settled' ";
		if (!empty($innsId)) {
			$amountWhere = $amountWhere." AND i.inn_id = $innsId";
		}
		$totalAmount = $this-> get_sum_by_column($select.$amountWhere,'amount');
		foreach ($result as $row)
		{
			$row['create_time'] = format_time($row['create_time']);
			array_push($records, $row);
		}
		return array ('data' => $records, 'total' => $total,'totalAmount' => $totalAmount);
	}

	public function create_r_inns_user($user_id, $inn_id) {
		$r_users_inns = array(
			'user_id' => $user_id,
			'inn_id' => $inn_id
		);
		$this->db->insert('r_users_inns', $r_users_inns);
		return $user_id = $this->db->insert_id();
	}

   /**
	* 获取商户管家
	* @param int $inn_id
	* @return array
	*/
	public function get_smanagers_by_inn_id($inn_id)
	{
		$cond = array(
			'table' => 'r_users_inns',
			'field' => '*',
			'where' => array(
				'r_users_inns.inn_id' => $inn_id
			),
			'join' => array(
				'users',
				'users.user_id = r_users_inns.user_id'
			)
		);
		return $this->get_all($cond);
	}

   /**
	* 得到驿栈掌柜信息
	* @param int $inn_id
	* @return array
	*/
	public function get_inn_manager_by_inn_id($inn_id)
	{
		$cond = array(
			'table' => 'inns_manager',
			'fields' => '*',
			'where' => array(
				'inn_id' => $inn_id
			)
		);
		$rs = $this->get_one($cond);
		if($rs)
		{
			$rs['manager_homepage'] = json_decode($rs['manager_homepage'],true);
			return $rs;
		}
		else
		{
			$data['inn_id'] = $inn_id;
			$this->insert($data,'inns_manager');
			return array();
		}
	}
	
   /**
	* 得到驿栈基本信息
	* @param array $p
	* @param array $innsInfo
	* @return array
	*/
	public function update_inninfo_by_inn_id($p,$innsInfo)
	{
		$action = empty($p['act'])?'error':$p['act'];
		switch($action)
		{
			case 'info':
				if(empty($p['thumb']))			return array('code' => '-3','msg' => '您尚未上传驿栈首页推荐图片！');
				if(empty($p['inns_pic']))		return array('code' => '-3','msg' => '您尚未上传驿栈图片！');
				if(empty($p['price_section']))	return array('code' => '-3','msg' => '您尚未填写价格区间！');
				if(empty($p['inns_map_pic']))	return array('code' => '-3','msg' => '您尚未上传驿栈地图！');
				if(empty($p['business_circle']))return array('code' => '-3','msg' => '您尚未填写周边商圈！');
				if(empty($p['attraction']))		return array('code' => '-3','msg' => '您尚未填写附近景点！');
				if(empty($p['entertainment']))	return array('code' => '-3','msg' => '您尚未填写附近娱乐场所！');
				if(empty($p['traffic_info']))	return array('code' => '-3','msg' => '您尚未填写到店方式！');

				$data['inn_id'] = $innsInfo['inn_id'];
				$data['inns_thumb'] = $p['thumb'];
				$data['inns_pic_list'] = $p['inns_pic'];
				$data['price_range']=$p['price_section'];
				$data['inns_map_pic'] = $p['inns_map_pic'];
				$data['facilities'] = implode(',',$p['facilities']);
				$data['business_circle']=$p['business_circle'];
				$data['attraction']=$p['attraction'];
				$data['entertainment']=$p['entertainment'];
				$data['facilities_more']=$p['facilities_more'];
				$data['traffic_info']=$p['traffic_info'];
				$cond = array(
					'data' => $data,
					'primaryKey' => 'inn_id',
					'table' => 'inn_shopfront'
				);
				break;
			case 'pics':
				$image = array();
				foreach($p['imgUrl'] as $key => $url)
				{
					$image[$key]['image'] = $url;
					$image[$key]['desc'] = isset($p['imgTxt'][$key])?$p['imgTxt'][$key]:'';
				}
				$data['banner_pic_list'] = json_encode($image);
				$data['inn_id'] = $innsInfo['inn_id'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inn_id';
				$cond['table'] = 'inn_shopfront';
				break;
		/*	case 'manager':	
				if(empty($p['manager_name']))		return array('code' => '-3','msg' => '您尚未填写掌柜名称！');
				if(empty($p['manager_native']))		return array('code' => '-3','msg' => '您尚未填写掌柜籍贯！');
				if(empty($p['manager_face']))		return array('code' => '-3','msg' => '您尚未上传掌柜头像！');

				$data['inn_id'] = $innsInfo['inn_id'];
				$data['manager_name'] = $p['manager_name'];
				$data['manager_native'] = $p['manager_native'];
				$data['manager_face'] = $p['manager_face'];
				$data['manager_word'] = $p['manager_word'];
				$data['manager_homepage'] = json_encode(array(
					'homepage_name1' => $p['homepage_name1'],'homepage_desc1' => $p['homepage_desc1'],'homepage_url1' => $p['homepage_url1'],
					'homepage_name2' => $p['homepage_name2'],'homepage_desc2' => $p['homepage_desc2'],'homepage_url2' => $p['homepage_url2'],
					'homepage_name3' => $p['homepage_name3'],'homepage_desc3' => $p['homepage_desc3'],'homepage_url3' => $p['homepage_url3']
				));
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inn_id';
				$cond['table'] = 'inns_manager';
				break; */
			case 'story':
				if(empty($p['inns_summary']))		return array('code' => '-3','msg' => '您尚未填写驿栈简介！');
				if(empty($p['content']))			return array('code' => '-3','msg' => '您尚未填写驿栈故事！');
				$data['inn_id'] = $innsInfo['inn_id'];
				$data['inns_story'] = $p['content'];
				$data['inns_summary'] = $p['inns_summary'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inn_id';
				$cond['table'] = 'inn_shopfront';
				break;
			case 'booking':
				$data['inn_id'] = $innsInfo['inn_id'];
				$data['booking_info_1'] = $p['booking_info_1'];
				$data['booking_info_2'] = $p['booking_info_2'];
				$data['booking_info_3'] = $p['booking_info_3'];
				$data['booking_info_4'] = $p['booking_info_4'];
				$data['booking_info_5'] = $p['booking_info_5'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inn_id';
				$cond['table'] = 'inn_shopfront';
				break;
			case 'front_show':
				$role = $this->model->getUserRole();
				if(in_array($role,array(ROLE_ADMIN,ROLE_CLIENT_SERVICE)))
				{
					$data['front_show'] = $p['front_show']=='N'?'N':'Y';
					$data['inn_id'] = $innsInfo['inn_id'];
					$cond['data'] = $data;
					$cond['primaryKey'] = 'inn_id';
					$cond['table'] = 'inns';
				}
				else
				{
					return array('code' => '-1','msg' => '您没有先关权限，请联系管理员');
				}
				break;
			case 'error':
			default:
				return array('code' => '-1','msg' => '参数错误！');
		}
		if($this->update($cond))
		{
			$this->wlog('Update Inninfo','修改了 “ '.$innsInfo['inn_name'].' ” 的信息');	
		}
		return array('code' => '1', 'msg' => '修改成功！');
	}

	public function update_inn_info($info,$done)
	{
		$inn = array();
		$inninfo = array();
		$rs = 0;
		foreach($info as $key => $value)
		{
			switch($key)
			{
				case 'profit':
				case 'dest_id':
				case 'local_id':
				case 'lon':
				case 'lat':
				case 'bdgps':
					$inn[$key] = $value;
					break;
				case 'inn_name':
					$inn[$key] = $value;
					$inninfo[$key] = $value;
					break;
				case 'bank_info':
				case 'bank_account_no':
				case 'bank_account_name':
				case 'inner_telephone':
				case 'inn_address':
				case 'inner_contacts':
				case 'inner_moblie_number':
					$inninfo[$key] = $value;
					break;
			}
		}
		if($inn)
		{
			$cond = array(
				'table' => 'inns',
				'primaryKey' => 'inn_id',
				'data' => array(
					'inn_id' => $done['inn_id'],
				)
			);
			$cond['data'] = array_merge($cond['data'],$inn);
			$rs = $this->update($cond);
		}
		if($inninfo)
		{
			$cond = array(
				'table' => 'inn_shopfront',
				'primaryKey' => 'inn_id',
				'data' => array(
					'inn_id' => $done['inn_id'],
					'update_time' => $_SERVER['REQUEST_TIME'],
					'update_by' => $done['user_id']
				)
			);
			$cond['data'] = array_merge($cond['data'],$inninfo);
			$rs = $this->update($cond);
		}
		if($rs)
		{
			$this->wLog('edit inn', '商户：<a href="javascript:void(0);" class="viewInnsInfo" ref="'.$done['inn_id'].'">'.$done['inn_name'].'</a>信息被<a  href="'.base_url().'inns/editinfo?sid='.$done['inn_id'].'" target="_blank">修改</a>', 'I', $state = 'S',$done['user_id'],'inn/editinfo');
		}
		return TRUE;
	}
}