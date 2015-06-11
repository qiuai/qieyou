<?php

class Home extends MY_Controller {

	public $layout_for_title = '我的且游';
	public $layout = 'simple';
	public $moduleTag = '我的且游';
	
    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{
		$user_id = $this->get_user_id();
		$user = array();
		if($user_id)
		{
			$user = $this->model->get_user_detail($user_id,FALSE);
		}
		$this->layout = 'homepage';
		$this->viewData = array(
			'user' => $user,
			'shouye' => 'home'
		);
    }

	public function finance()
	{
		$inn_id = $this->get_user_inn_id(TRUE);
		$inn = $this->model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn)
		{
			response_code('1006');
		}
		$this->moduleTag = '我的余额';
		$this->viewData = array(
			'inn' => $inn,
			'backUrl' => base_url().'home'
		);
	}
	
	public function tranflow()
	{
		$inn_id = $this->get_user_inn_id(TRUE);
		$inn = $this->model->get_user_inn_by_inn_id($inn_id,FALSE);
		if(!$inn)
		{
			response_code('1006');
		}
		$last_id = input_int($this->input->get('lastid'),0,FALSE,0);
		$limit = input_int($this->input->get('limit'),1,50,20);

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
	
	public function quan()
	{
		$this->moduleTag = '我的抵用券';
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
		$this->viewData = array(
			'able' => $able,
			'quan' => $quan
		);
	}

	public function order()
	{
		$this->moduleTag = '我的订单';
		$user_id = $this->get_user_id(TRUE);
		$this->viewData = array(
			'backUrl' => base_url().'home'	
		);
	}

	public function editavatar()
	{
		$this->moduleTag = '修改头像';
	}

	public function editmobile()
	{
		$this->moduleTag = '修改手机号码';
		$this->viewData = array(
			'step' => empty($_GET['step'])?'verify':$_GET['step']
		);
	}

	public function mobileAuth()//用户绑定新手机  手机号授权  修改绑定手机 需要校验手机号绑定情况
	{
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->post('type'),array('auth','band','modify'),FALSE,'4001');
		$user = $this->model->get_user_detail($user_id);
		if(!$user)
		{
			response_code('4005');
		}
		if($type == 'auth')				//更换手机时 原手机号授权
		{
			if(!$user['user_mobile'])
			{
				response_code('5007');
			}
			$mobile = $user['user_mobile'];
			if($this->model->check_mobile_send($mobile))	//防止短信轰炸
			{
				response_code('5008');
			}
		}
		else if($type == 'band')		//无手机号首次绑定情况
		{
			$mobile = input_mobilenum($this->input->post('mobile'),'5001');
			$rs = $this->model->search_user_mobile($mobile);
			if($rs)
			{
				if($rs['user_id'] != $user_id)
				{
					response_code('5009');
				}
				else
				{
					response_code('5010');
				}
			}
		}
		else if($type = 'modify')	//修改已经绑定手机  验证是否久手机已经发短信
		{
			$rs = $this->model->check_mobile_send($user['user_mobile']);
			print_r($rs);exit;
			if(!$rs)	//超时
			{
				response_code('5012');
			}
			$mobile = input_mobilenum($this->input->post('mobile'),'5001');
			if($mobile == $user['user_mobile'])	
			{
				response_code('5008');
			}
			$rs = $this->model->search_user_mobile($mobile);
			if($rs)
			{
				if($rs['user_mobile'] == $mobile)
				{
					response_code('5009');
				}
				else
				{
					response_code('5010');
				}
			}
		}
		$mobile_identify = make_mobile_identify_code();
		$str = array('check_mobile'=>$mobile,'mobile_identify' => $mobile_identify,'sms_sendtime' => TIME_NOW);
		$this->set_current_data($str);

		$str['user_id'] = $user_id;
		$message = array(
			'type' => 'bondMobile',
			'mobile' => $mobile,
			'param' => array(
				$mobile_identify,'5'
			)
		);
		$this->model->save_mobile_identify($str);
		$this->sendSMS($str);
		response_code('1');
	}

	private function sendSMS($message)
	{
		$options['accountsid'] = $this->config->item('sms_ucpaas_sid');
		$options['token'] = $this->config->item('sms_ucpaas_token');
		$this->load->library('sms_ucpaas',$options);

		$sms_ucpaas_sid = $this->config->item('sms_ucpaas_appid');
		switch($message['type'])
		{
			case 'regUser':		//注册提醒
				$templateId = '5171';
				break;
			case 'forgotUser':	//忘记密码
				$templateId = '5199';
				break;
			case 'bondMobile':	//绑定手机
				$templateId = '5200';
				break;
			default:
				exit;
		}
		$param = implode(',',$message['param']);
		return $this->sms_ucpaas->templateSMS($sms_ucpaas_sid,$message['mobile'],$templateId,$param);
	}

	public function orderList()
	{
		$user_id = $this->get_user_id(TRUE);
		$page = input_int($this->input->get('page'),1,FALSE,1);					//分页
		$perpage = input_int($this->input->get('perpage'),1,20,10);				//分页
		$state = input_string($this->input->get('state'),array('O','A','U','P','S','R','C'),'O'); //排序方法 默认创建时间最新
		$orders = $this->model->get_orders_by_user_id($user_id,$page, $perpage,$state);
		response_json('1',$orders);
	}
	
	public function password()
	{
		$this->moduleTag = '修改密码';
	}
	
	public function shoucang()
	{
		$this->moduleTag = '我的收藏';
		$user_id = $this->get_user_id(TRUE);
		$type = input_string($this->input->get('type'),array('item','inn'),'item');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$this->viewData = array(
			'type' => $type,
			'page' => $page,
			'perpage' => $perpage,
			'shoucang' => TRUE
		);
	}

	public function shoucang_ajax()
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

	public function point()
	{
		$this->moduleTag = '我的积分';
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
		$this->viewData = array(
			'user' => $user,
			'quan' => $quan
		);
	}

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

	public function pointlist()
	{
		$this->moduleTag = '积分明细';	
		$user_id = $this->get_user_id(TRUE);
		$user = $this->model->get_user_detail($user_id);
		$this->viewData = array(
			'user' => $user,
			'pointlist' => ''//$this->model->get_user_pointlist_by_user_id($user_id,1,100)
		);
	}

	public function userPointList()
	{
		$user_id = $this->get_user_id(TRUE);
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$data = $this->model->get_user_pointlist_by_user_id($user_id,$page,$perpage);
		response_json('1',$data);
	}
	
	public function address()
	{
		$this->moduleTag = '管理收货地址';
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
		$this->viewData = array(
			'type' => empty($_GET['type'])?0:1,
			'address' => $address,
			'backUrl' => '/home'
		);
	}

	public function editaddress()
	{	
		$address_id = input_int($this->input->get('addressId'),1,FALSE,0);
		$this->moduleTag = ($address_id ? '编辑' : '新增') . '收货地址';
		$address = array();
		$user_id = $this->get_user_id(TRUE);
		if($address_id)
		{
			$address = $this->model->get_user_address_by_id($user_id,$address_id);
			if(!$address)
			{
				response_code('1013');
			}
			else
			{
				$address['local_array'] = $this->model->get_local_info($address['location_id']);
			}
		}
		$this->viewData = array(
			'type' => empty($_GET['type'])?0:1,
			'address' => $address
		);
	}

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
			$default = FALSE;
			if($this->input->post('submit_order'))
			{
				$default = TRUE;
			}
			if($this->model->create_user_data($type,$new_data,$default))
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
						response_code('4000');
					}
				}
			}
		}
		response_code('1');
	}

	public function edituser()
	{
		$user_id = $this->get_user_id(TRUE);
		$this->moduleTag = '修改个人资料';
		$user = $this->model->get_user_detail($user_id);
		$this->viewData = array(
			'user' => $user	
		);
		
	}

	public function identify()
	{
		$this->moduleTag = '常用证件信息';
		$user_id = $this->get_user_id(TRUE);
		$identify = array();
		$identify = $this->model->get_user_identify($user_id);
		$this->viewData = array(
			'type' => empty($_GET['type'])?0:1,
			'identify' => $identify,
			'backUrl' => '/home'
		);
	}

	public function editidentify()
	{
		$identify_id = input_int($this->input->get('identifyId'),1,FALSE,0);
		$this->moduleTag = ($identify_id ? '编辑' : '新增') . '证件信息';
		$identify = array();
		$user_id = $this->get_user_id(TRUE);
		if($identify_id)
		{
			$identify = $this->model->get_user_identify_by_id($user_id,$identify_id);
			if(!$identify)
			{
				response_code('1020');
			}
		}
		$this->viewData = array(
			'type' => empty($_GET['type'])?0:1,
			'identify' => $identify
		);
	}
	
	public function getLocallist()
	{
		$city_id = input_int($this->input->get('pid'),100000,1000000,FALSE,'4001');
		response_json('1',$this->model->get_local_list($city_id));
	}

	private function check_user_data($type)
	{
		if($type == 'address')
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
		else if($type == 'identify')
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

	public function search()
	{
		$this->moduleTag = '搜索';
		$this->layout = 'item';
	}
	public function feedback()
	{
		$this->get_user_id(TRUE);
		$this->moduleTag = '意见反馈';
	}
	
   /**
    * 用户反馈
	*/
	public function userFeedback()
	{
		$feedback['user_id'] = $this->get_user_id(TRUE);
		$feedback['note'] = check_empty(strip_tags($this->input->post('note',TRUE)),FALSE,'5003');
		$feedback['imgs'] = trimall(strip_tags($this->input->post('imgs',TRUE)));
		$feedback['client'] = 'WAP';
		$feedback['version'] = $this->config->item('version');
		$feedback['device'] = $_SERVER['HTTP_USER_AGENT'];
		$feedback['ip'] =  $_SERVER['REMOTE_ADDR'];
		$feedback['create_time'] =  TIME_NOW;
		if($this->model->insert($feedback,'feedbacks'))
		{
			response_code('1');
		}
		response_code('4000');
	}

	public function crop(){
		$this->moduleTag = '头像裁剪';
	}
	
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
									$this->set_current_data(array('headimg' => $changedkeys['headimg']));
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
}