<?php
class User extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
		$this->check_token();
	}

   /**
	* 我的基本信息
	*/
	public function info()
	{
		$parm = 'ui.nick_name,ui.mobile_phone,ui.headimg';
		$userinfo = $this->model->get_user_info_in_ids($this->token['user_id'],$parm,FALSE);
		if($userinfo)
		{
			$user_info['NickName'] = $userinfo['nick_name'];
			$user_info['Mobile'] = $userinfo['mobile_phone'];
			$user_info['HeadImg'] = $userinfo['headimg'];

			if(is_post())
			{
				$check_info = $this->check_user_info_value();
				$changedkeys = array_diff_assoc($check_info,$userinfo);
				if($changedkeys)
				{
					$changedkeys['user_id'] = $this->token['user_id'];
					$this->model->update_user_info($changedkeys);
				}
				response_msg('1');
			}
			else
			{
				$inn_info = $this->model->get_inn_info_by_inn_id($this->token['inn_id']);
				$user_info['BankName'] = $inn_info['bank_info'];
				$user_info['BankAccountHolder'] = $inn_info['bank_account_name'];
				$user_info['BankAccount'] = substr_replace($inn_info['bank_account_no'],'****',8,4);
			}
		}
		else
		{
			response_msg('4005');
		}
		response_data($user_info);
	}

   /**
	* 我的铺子
	*/
	public function inninfo()
	{
		$inn_info = $this->model->get_inn_info_by_inn_id($this->token['inn_id']);
		if($inn_info)
		{
			if(is_post())
			{
				$check_info = $this->check_inn_info_value();
				$changedkeys = array_diff_assoc($check_info,$inn_info);
				if($changedkeys)
				{
					$changedkeys['inn_id'] = $this->token['inn_id'];
					$changedkeys['update_by'] = $this->token['user_id'];
					$this->model->update_inn_info($changedkeys);
				}
				response_msg('1');
			}
		}
		else
		{
			response_msg('2007');
		}
		response_data($inn_info);
	}

   /**
	* 查看我的收藏
	*/
	public function favorites()
	{
		$class = input_string($this->input->get('class'),array('product','inn'),'product');
		$page = input_int($this->input->get('page'),1,FALSE,FALSE,'1015');
		$perpage = input_int($this->input->get('perpage'),1,FALSE,FALSE,'1016');
		if($class == 'inn')
		{
			$rs = $this->model->get_user_inn_fav($this->token['user_id'],$page,$perpage);
		}
		else
		{
			$cate_id = input_int($this->input->get('cid'),0,FALSE,0);
			$rs = $this->model->get_user_product_fav($this->token['user_id'],$cate_id,$page,$perpage);
		}
		response_data($rs);
	}
	
   /**
	* 对店铺收藏的操作 
	* 添加删除
	*/
	public function favor_inn()
	{
		$act = input_string($this->input->get('act'),array('add','del'),FALSE,FALSE,'1014');
		$class_id = input_int($this->input->get('classid'),1,FALSE,FALSE,'1009');
		$inn = $this->model->get_inn_info_by_inn_id($class_id,FALSE);
		if(!$inn)
		{
			response_msg('2007');
		}
		$is_fav = $this->model->check_fav('inn',$class_id,$this->token['user_id']);
		if($act == 'add')
		{
			if($is_fav)
			{
				response_msg('1010');
			}
		}
		else
		{
			if(!$is_fav)
			{		
				response_msg('1011');
			}
			$inn['del_id'] = $is_fav;
		}
		$this->model->update_fav($act,$class_id);
		$this->model->modify_user_fav($act,'inn',$this->token['user_id'],$inn);
		response_msg('1');
	}

   /**
	* 对商品收藏的操作 
	* 添加删除
	*/
	public function favor_product()
	{
		$act = input_string($this->input->get('act'),array('add','del'),FALSE,FALSE,'1014');
		$product_id = input_int($this->input->get('classid'),1,FALSE,FALSE,'1009');
		$is_fav = $this->model->check_fav('product',$product_id,$this->token['user_id']);
		$this->_LoadModel('product');
		if($act == 'add')
		{
			$product = $this->product_model->get_product_by_id($product_id);
			if(!$product)
			{
				response_msg('2009');
			}
			if($is_fav)
			{
				response_msg('1012');
			}
		}
		else
		{ 
			if(!$is_fav)
			{		
				response_msg('1013');
			}
			$product['del_id'] = $is_fav;
		}
		
		$this->product_model->update_fav($act,$product_id);
		$this->model->modify_user_fav($act,'product',$this->token['user_id'],$product);
		response_msg('1');
	}

	public function logout()
	{
		$rs = $this->model->user_logout($this->token['token']);
		if($rs)
		{
			response_msg('1');
		}
		response_msg('4000');
	}
	
   /**
	* 修改密码
	*/
	public function changepwd()
	{
		$this->model->updatePassWord($this->input->post());
	}

	public function checkusername() 
	{
		$name = input_mobilenum($this->input->post('name'),'1004');
		$user = $this->model->get_user_by_name($name);
		if ($user)
		{
			response_msg('1005');
		} 
		response_msg('1');
	}

	public function partners() 
	{
		$partners = $this->model->get_user_partner_by_user_id($this->token['user_id']);
		response_data($partners);
	}

	public function removepartners() 
	{
		$ids = $this->input->get('ids');
		if(!$ids)
		{
			response_msg('4000');
		}
		$partner_ids = explode(',',$ids);
		if(!$partner_ids)
		{
			response_msg('1021');
		}
		$rows = implode(',',$partner_ids);
		$partners = $this->model->edit_user_partner('rm',$this->token['user_id'],$rows);
		if($partners == count($partner_ids))
		{
			response_msg('1');
		}
		response_msg('4000');
	}
	
	private function check_user_info_value()
	{
		$nick_name = $this->input->post('NickName',TRUE);
		$headimg = $this->input->post('HeadImg',TRUE);
		$user = array();
		if($nick_name)
		{
			$user['nick_name'] = $nick_name;
		}
		if($headimg)
		{
			$user['headimg'] = $headimg;
		}
		return $user;
	}
	
	private function check_inn_info_value()
	{
		$inn_head = $this->input->post('inn_head',TRUE);
		$features = $this->input->post('inn_features',TRUE);
		$inner_contacts = $this->input->post('inn_contacts',TRUE);
		$inner_moblie_number = $this->input->post('inn_contacts_mobile',TRUE);
		$inner_telephone = $this->input->post('inn_telephone',TRUE);
		$inn_summary = $this->input->post('inn_summary',TRUE);
		$inn_address = $this->input->post('inn_address',TRUE);
		$inn_info = array();
		if($inn_head)
		{
			$inn_info['inn_head'] = $inn_head;
		}
		if($features)
		{
			$inn_info['features'] = $features;
		}
		if($inner_contacts)
		{
			$inn_info['inner_contacts'] = $inner_contacts;
		}
		if($inner_moblie_number)
		{
			$inn_info['inner_moblie_number'] = input_mobilenum($inner_moblie_number,'3006');
		}	
		if($inner_telephone)
		{
			$inn_info['inner_telephone'] = $inner_telephone;
		}
		if($inn_summary)
		{
			$inn_info['inn_summary'] = $inn_summary;
		}
		if($inn_address)
		{
			$inn_info['inn_address'] = $inn_address;
		}
		return $inn_info;
	}
}