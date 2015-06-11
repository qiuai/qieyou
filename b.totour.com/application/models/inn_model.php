<?php

class Inn_model extends MY_Model {

	public function get_inn_subs_by_inn_id($inn_id)
	{
		$cond = array(
			'table' => 'inn_subs',
			'field' => '*',
			'where' => array(
				'inn_subs.inn_id' => $inn_id,
				'inn_subs.state' => 1,
			),
			'join' => array(
				'user_info',
				'user_info.user_id = inn_subs.user_id'
			),
			'order_by' => 'user_info.created_time DESC'
		);
		return $this->get_all($cond);
	}	
	
	public function get_sub_detail_by_user_id($inn_id,$user_id)
	{
		$cond = array(
			'table' => 'inn_subs',
			'field' => '*',
			'where' => array(
				'inn_subs.inn_id' => $inn_id,
				'inn_subs.user_id' => $user_id
			),
			'join' => array(
				'user_info',
				'user_info.user_id = inn_subs.user_id'
			),
			'order_by' => 'user_info.created_time DESC'
		);
		return $this->get_one($cond);
	}

   /**
	* 未完成
	*/
	public function modify_inn_sub_by_user_id($action,$inn_id,$user_id)
	{
		return TRUE;
	}

	public function get_inns_by_userid($innholder_id) {
		$this->db->from('inns i');
		$this->db->where('i.innholder_id', $innholder_id);
		return $this->db->get()->row_array();
	}
	
	public function update_inn_shopfront($inns_id,$innsInfo) {
		$this->db->where('inns_id', $inns_id);
		$this->db->update('inn_shopfront', $innsInfo);
		return $inns_id;
	}
	
	public function get_innsDetailInfo_by_inns_id($inns_id)
	{
		$sql = "select u.user_id,u.real_name,u.user_sex,u.email, u.mobile_phone, d.dest_name, d.city, d.province,
				 i.inns_name, i.inns_id, i.inns_url ,i.state,i.front_show, sf.inns_address, sf.inner_telephone from inns i 
				inner join users u on u.user_id = i.innholder_id
				left join inn_shopfront sf on sf.inns_id  = i.inns_id
				inner join destination d on d.dest_id = i.dest_id
				where i.inns_id = $inns_id ";
		return $this->db->query($sql)->row_array();
	}
	
   /**
	* 得到驿栈基本信息
	* @param array $p
	* @param array $innsInfo
	* @return array
	*/
	public function update_inninfo_by_inns_id($p,$innsInfo)
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

				$data['inns_id'] = $innsInfo['inns_id'];
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
					'primaryKey' => 'inns_id',
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
				$data['inns_id'] = $innsInfo['inns_id'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inns_id';
				$cond['table'] = 'inn_shopfront';
				break;
			case 'manager':	
				if(empty($p['manager_name']))		return array('code' => '-3','msg' => '您尚未填写掌柜名称！');
				if(empty($p['manager_native']))		return array('code' => '-3','msg' => '您尚未填写掌柜籍贯！');
				if(empty($p['manager_face']))		return array('code' => '-3','msg' => '您尚未上传掌柜头像！');

				$data['inns_id'] = $innsInfo['inns_id'];
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
				$cond['primaryKey'] = 'inns_id';
				$cond['table'] = 'inns_manager';
				break;
			case 'story':
				if(empty($p['inns_summary']))		return array('code' => '-3','msg' => '您尚未填写驿栈简介！');
				if(empty($p['content']))			return array('code' => '-3','msg' => '您尚未填写驿栈故事！');
				$data['inns_id'] = $innsInfo['inns_id'];
				$data['inns_story'] = $p['content'];
				$data['inns_summary'] = $p['inns_summary'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inns_id';
				$cond['table'] = 'inn_shopfront';
				break;
			case 'booking':
				$data['inns_id'] = $innsInfo['inns_id'];
				$data['booking_info_1'] = $p['booking_info_1'];
				$data['booking_info_2'] = $p['booking_info_2'];
				$data['booking_info_3'] = $p['booking_info_3'];
				$data['booking_info_4'] = $p['booking_info_4'];
				$data['booking_info_5'] = $p['booking_info_5'];
				$cond['data'] = $data;
				$cond['primaryKey'] = 'inns_id';
				$cond['table'] = 'inn_shopfront';
				break;
			case 'front_show':
				$role = $this->model->getUserRole();
				if(in_array($role,array(ROLE_ADMIN,ROLE_CLIENT_SERVICE)))
				{
					$data['front_show'] = $p['front_show']=='N'?'N':'Y';
					$data['inns_id'] = $innsInfo['inns_id'];
					$cond['data'] = $data;
					$cond['primaryKey'] = 'inns_id';
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
			$this->wlog('Update Inninfo','修改了 “ '.$innsInfo['inns_name'].' ” 的信息');	
		}
		return array('code' => '1', 'msg' => '修改成功！');
	}

   /**
	* 获取用于支付的信息
	* @param array $arr
	* @return string
	*/
	public function getPayInfo($Info) 
	{
		$encrypted = '';
		$front_baseUrl = $this->config->item('front_base_url');
		if($Info) 
		{
			$trans = array(
				'Debug' => '0', //是否调试模式
				'Source' => 'yizhan', //源标志
				'SourceID' => $Info['order_num'], //订单编号
				'SourceDesc' => $Info['name'], 
				'TransTotal' => $Info['total'] * 100, //总价
				'Method' => array('express', 'alipay'),
				'Return' => base_url().'inns/cashin',
				'Notify' => $front_baseUrl.'rpc/dyhtranscallback' // 此处提交url，必须带 http:// 前繈指定域名, 下同 **************************************************>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			);
			require_once (APPPATH . 'libraries/class_aes.php');
			$aes = new AES('doyouhikerocks!!');
			$encrypted = base64_encode($aes->encrypt(serialize($trans)));
		}
		return $encrypted;
	}
}
