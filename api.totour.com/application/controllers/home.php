<?php

class Home extends MY_Controller {

    public function __construct() 
	{
        parent::__construct();
    }

   /**
    * 用户信息
    **/		
	public function userinfo()
	{
		$user_id = $this->get_user_id(TRUE);
		$user_info = $this->model->get_user_detail($user_id,TRUE);
		if($user_info)
		{
			$user = array(
				'user_id' => $user_info['user_id'],
				'user_name' => $user_info['user_name'],
				'state' => $user_info['state'],
				'nick_name' => $user_info['nick_name'],
				'headimg' => $user_info['headimg'],
				'signature' => $user_info['signature'],
				'birthday' => $user_info['birthday'],
				'age' => getAge($user_info['birthday']),
				'sex' => $user_info['sex'],
				'role' => $user_info['role'],
				'mobile_phone' => $user_info['user_mobile'],
			);
			response_json('1',$user);
		}
		response_code('4005');
	}

   /**
    * 我的资产
    **/	
	public function finance()
	{
		$inn_id = $this->get_user_inn_id(TRUE);
		$inn = $this->model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn)
		{
			response_code('1006');
		}
		$data = array(
			'state' => $inn['state'],
			'account' => $inn['account'],
			'withdrawing' => $inn['withdrawing'],
		);
		response_json('1',$data);
	}

   /**
    * 交易流水列表
    **/	
	public function tranflow()
	{
		$inn_id = $this->get_user_inn_id(TRUE);
		$inn = $this->model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn)
		{
			response_code('1006');
		}
		$last_id = input_int($this->input->get('lastid'),0,FALSE,0);
		$limit = input_int($this->input->get('limit'),1,50,15);

		$res = array();
		$data = $this->model->get_account_records_by_inn_id($inn_id,$last_id,$limit);
		if($data)
		{
			$month = $data[0]['create_time'];
			$first_month = date('Ym',$month); 
			$month_start = strtotime($first_month.'01');
			$month_end = strtotime('+1 month',$month_start) -1 ;
			$res['title'][0]['month_start'] = $month_start;
			$res['title'][0]['month_end'] = $month_end;
			$i = 0;
			foreach($data as $key => $row)
			{
				if($row['create_time'] >= $month_start)
				{
					$res['title'][$i]['lastid'] = $row['record_id'];
				}
				else
				{
					$month_start = date('Ym',$row['create_time']);
					$month_start = strtotime($month_start.'01');
					$month_end = strtotime('+1 month',$month_start) -1 ;
					$i ++;
					$res['title'][$i]['month_start'] = $month_start;
					$res['title'][$i]['month_end'] = $month_end;
					$res['title'][$i]['lastid'] = $row['record_id'];
				}
				$res['list'][] = $row;
			}
			foreach($res['title'] as $key => $row)
			{
				$rs = $this->model->get_mouth_transflow($inn_id,$row['month_start'],$row['month_end']);
		
				$res['title'][$key]['cashin'] = $rs['cashin'];
				$res['title'][$key]['cashout'] = $rs['cashout'];
			}
		}
		else
		{
			$res = array(
				'title' => array( array('month' =>strtotime(date('Ym').'01'),'month_end'=>$_SERVER['REQUEST_TIME'],'cashin'=>'0','cashout'=>'0','lastid'=>'0')),
				'list' => array()
			);
		}
		response_json('1',$res);
	}

   /**
    * 全部优惠券列表
    **/
	public function quan()
	{
		$user_id = $this->get_user_id(TRUE);
		$quan = $this->model->get_user_quan($user_id);
		$able = array();
		if($quan)
		{
			foreach($quan as $key => $row)
			{
				if(!$row['overdue'])
				{
					$able[] = $row;
				}
			}
		}
		$data = array(
			'able' => $able,
			'quan' => $quan
		);
		response_json('1',$data);
	}

   /**
    * 订单列表
    **/
	public function orderList()
	{
		$user_id = $this->get_user_id(TRUE);
		$page = input_int($this->input->get('page'),1,FALSE,1);					//分页
		$perpage = input_int($this->input->get('perpage'),1,20,10);				//分页
		$state = input_string($this->input->get('state'),array('O','A','U','P','S','R','C'),'O'); //排序方法 默认创建时间最新
		$orders = $this->model->get_orders_by_user_id($user_id,$page, $perpage,$state);
		response_json('1',$orders);
	}

   /**
    * 收藏列表
    **/
	public function shoucang()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->get('type'),array('item','inn'),'item');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$data = array();
		if($type == 'item')
		{
			$data = $this->model->get_user_product_fav($user_id,$page,$perpage);
		}
		else
		{
			$data = $this->model->get_user_inn_fav($user_id,$page,$perpage);
		}
		response_json('1',$data);
	}

   /**
    * 优惠券兑换页
    **/
	public function point()
	{
		$user_id = $this->get_user_id(TRUE);
		$user = $this->model->get_user_detail($user_id);
		$quan = $this->model->get_sys_quan();
		$user_quan = $this->model->get_user_quan($user_id,TRUE);
		if($user_quan)
		{
			foreach($quan as $k => $row)
			{
				if(isset($user_quan[$row['quan_id']]))
				{
					$quan[$k]['is_get'] = 1;
				}
			}
		}
		$data = array(
			'point' => $user['point'],
			'quan' => $quan
		);
		response_json('1',$data);
	}

   /**
    * 优惠券兑换
    **/
	public function getCoupons()
	{
		$user_id = $this->get_user_id(TRUE);
		$coupon_id = input_int($this->input->post('quan'),0,FALSE,FALSE,'3005');
		$coupon = $this->model->get_cash_coupon_by_id($coupon_id);
		if(!$coupon||$coupon['end_time']< TIME_NOW)
		{
			response_code('3005');
		}
		if($coupon['total'] <= $coupon['quantity'])
		{
			response_code('3006');
		}
		$user = $this->model->get_user_detail($user_id);
		if($user['point'] < $coupon['require'])
		{
			response_code('3008');
		}
		if($this->model->bond_user_quan($coupon,$user_id))
		{
			response_code('1');
		}
		response_code('4000');
	}

   /**
    * 积分列表
    **/
	public function pointlist()
	{
		$user_id = $this->get_user_id(TRUE);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$data = $this->model->get_user_pointlist_by_user_id($user_id,$page,$perpage);
		response_json('1',$data);
	}

   /**
    * 收货地址列表
    **/
	public function address()
	{
		$user_id = $this->get_user_id(TRUE);
		$address = array();
		$rs = $this->model->get_user_address($user_id);
		if($rs)
		{
			foreach($rs as $key => $row)
			{
				$row['local_array'] = $this->model->get_local_info($row['location_id']);
				$address[] = $row;
			}
		}
		response_json('1',$address);
	}

   /**
    * 修改收货地址 修改身份信息  添加 & 修改 & 设为默认 & 删除 
    **/
	public function modifyUserData()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->post('type'),array('address','identify'),FALSE,'4001');
		$action = input_string($this->input->post('act'),array('add','setdefault','del','edit'),FALSE,'4002');
		if($action == 'add')
		{
			$new_data = $this->check_user_data($type);
			if($type == 'address')
			{
				$rs = $this->model->get_user_address($user_id);
			}
			else
			{
				$rs = $this->model->get_user_identify($user_id);
			}
			if($rs&&count($rs)>19)
			{
				response_code('1013');
			}
			else if(!$rs)	//没有保存任何数据时设置为默认
			{
				$new_data['is_default'] = 1;
			}
			$new_data['user_id'] = $user_id;

			if($this->model->create_user_data($type,$new_data))
			{
				response_code('1');
			}
			response_code('4000');
		}

		$class_id = input_int($this->input->post('classid'),1,FALSE,'1011');
		if($action == 'edit')
		{
			$new_data = $this->check_user_data($type);
			if($type == 'address')
			{
				$dataInfo = $this->model->get_user_address_by_id($user_id,$class_id);
				if(!$dataInfo)
				{
					response_code('1013');
				}
			}
			else
			{
				$dataInfo = $this->model->get_user_identify_by_id($user_id,$class_id);
				if(!$dataInfo)
				{
					response_code('1020');
				}
			}
			$changedkeys = array_diff_assoc($new_data,$dataInfo);
			if($changedkeys)
			{
				if(!$this->model->update_user_data($type,$changedkeys,$class_id))
				{
					response_code('4000');
				}
			}
		}
		else
		{
			if($type == 'address')
			{
				$dataInfo = $this->model->get_user_address_by_id($user_id,$class_id);
				if(!$dataInfo)
				{
					response_code('1013');
				}
			}
			else
			{
				$dataInfo = $this->model->get_user_identify_by_id($user_id,$class_id);
				if(!$dataInfo)
				{
					response_code('1020');
				}
			}

			if($action == 'del')
			{
				if(!$this->model->delete_user_data($type,$class_id))
				{
					response_code('4000');
				}
			}
			else if($action == 'setdefault')
			{
				if($dataInfo['is_default'])
				{
					response_code('1');
				}
				else
				{
					if(!$this->model->set_user_default($type,$class_id,$user_id))
					{
						log_message('error','success');
						response_code('4000');
					}
				}
			}
		}
		response_code('1');
	}

   /**
    * 身份信息列表
    **/
	public function identify()
	{
		$user_id = $this->get_user_id(TRUE);
		$identify = $this->model->get_user_identify($user_id);
		response_json('1',$identify);
	}

   /**
    * 获取目的地信息
    **/
	public function getLocallist()
	{
		$city_id = input_int($this->input->get('pid'),100000,1000000,FALSE,'4001');
		response_json('1',$this->model->get_local_list($city_id));
	}

   /**
    * 修改用户信息
    **/
	public function editUserinfo()
	{
		$check_info = $this->check_user_info_value();
		$user_id = $this->get_user_id(TRUE);
		if(!$check_info)
		{
			response_code('1');
		}
		$userinfo = $this->model->get_user_detail($user_id);
		if(!$userinfo)
		{
			response_code('4005');
		}
		$changedkeys = array_diff_assoc($check_info,$userinfo);
		if($changedkeys)
		{
			$changedkeys['user_id'] = $user_id;
			$rs = $this->model->update_user_info($changedkeys);
			if($rs)
			{
				$rs = array_keys($changedkeys);
				if($rs)
				{
					foreach($rs as $key => $row)
					{
						if(empty($userinfo[$row])&&in_array($row,array('headimg','nick_name','signature','birthday')))
						{
							switch($row)
							{
								case 'headimg':
								//	$this->set_current_data('headimg',$changedkeys['headimg']);
									$act = 12;$contnet = '首次上传头像';break;
								case 'nick_name':
									$act = 13;$contnet = '首次填写昵称';break;
								case 'signature':
									$act = 14;$contnet = '首次填写签名';break;
								case 'birthday':
									$act = 15;$contnet = '首次填写生日';break;
							}
							$this->model->update_user_point($user_id,20,$contnet,$act);
						}
						if($row == 'sex' && $userinfo['sex']=='U')
						{
							$this->model->update_user_point($user_id,20,'首次填写性别',11);
						}
					}
				}
				//添加首次修改送积分  判断原先的key是否为空  my_model 公用积分函数 读取liber中配置文件 返回相应分数 更新用户
			}
			else
			{
				response_code('4000');
			}
		}
		response_code('1');
	}

   /**
    * 用户反馈
	*/
	public function userFeedback()
	{
		$feedback['user_id'] = $this->get_user_id(TRUE);
		$feedback['note'] = check_empty(strip_tags($this->input->post('note',TRUE)),FALSE,'5003');
		$feedback['imgs'] = trimall(strip_tags($this->input->post('imgs',TRUE)));
		$feedback['client'] = trimall(strip_tags($this->input->post('client',TRUE)));
		$feedback['version'] = $feedback['client'].' V'.trimall(strip_tags($this->input->post('version',TRUE))).';Server V'.$this->config->item('version');
		$feedback['device'] = trimall(strip_tags($this->input->post('device',TRUE)));
		$feedback['ip'] =  $_SERVER['REMOTE_ADDR'];
		$feedback['create_time'] =  TIME_NOW;
		$rs = $this->model->insert($feedback,'feedbacks');
		if($rs)
		{
			response_code('1');
		}
		response_code('4000');
	}

	private function check_user_info_value()
	{
		$nickname = $this->input->post('nickname',TRUE);
		$headimg = $this->input->post('headimg',TRUE);
		$signature = $this->input->post('signature',TRUE);
		$birthday = $this->input->post('birthday',TRUE);
		$sex = $this->input->post('sex',TRUE);
		$user = array();
		if($nickname)
		{
			$user['nick_name'] = check_empty(trimall(strip_tags($nickname)),FALSE,'1023');
		}
		if($headimg)
		{
			$user['headimg'] = check_empty(trimall(strip_tags($headimg)),FALSE,'1024');
		}
		if($signature)
		{
			$user['signature'] = check_empty(trimall(strip_tags($signature)),FALSE,'1025');	
		}
		if($birthday)
		{
			$birthday = check_birthday($birthday);
			if(!$birthday)
			{
				response_code('1022');
			}
			$user['birthday'] = $birthday;
		}
		if($sex)
		{
			$user['sex'] = input_string($sex,array('F','M'),FALSE,'1021');
		}
		return $user;
	}

	private function check_user_data($class)
	{
		if($class == 'address')
		{
			$address = array();
			$address['real_name'] = check_empty(trimall(strip_tags($this->input->post('real_name'))),FALSE,'1014');
			$address['mobile'] = input_mobilenum($this->input->post('mobile'),'5001');
			$address['location_id'] = input_int($this->input->post('local_id'),100000,1000000,FALSE,'1015');
			$address['address'] = check_empty(trimall(strip_tags($this->input->post('address'))),FALSE,'1016');
			$local = check_empty($this->model->get_local_info($address['location_id']),FALSE,'1015');
			$address['location'] = $local['sheng']['name'].$local['shi']['name'].$local['city']['name'];
			return $address;
		}
		else if($class == 'identify')
		{
			$identify = array();
			$identify['real_name'] = check_empty(trimall(strip_tags($this->input->post('real_name'))),FALSE,'1014');
			$identify['idcard'] = input_identity_number($this->input->post('idcard'),'1019');
			return $identify;
		}
		else
		{
			response_code('4001');
		}
	}
}