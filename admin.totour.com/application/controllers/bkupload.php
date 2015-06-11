<?php

class Bkupload extends MY_Controller {

/* 图片上传类共有4个公用接口 2个批量上传 uploadFromEditor处理压缩图片  2个单张图片上传 editorUploadImage用于需要返回完整地址的编辑器 */
   /**
	* 编辑器批量上传图片 需生成缩略图
	* param FILE imgFile
	* return json_array
	*/
	public function uploadFromEditor()
	{
		if(!$this->authcheck())
		{
			echo '网络错误，请刷新页面后再试！';
			exit;
		}
		$type = $this->input->get('type');
		$thumbs = array();
		switch($type)
		{
			case 'room':
				$thumbs[] = array(
					'width' => 138,
					'height' => 97,
					'thumb_marker' => 's'
				);	
				break;
			case 'inn_banner':
				$thumbs[] = array(
					'width' => 996,
					'height' => 332,
					'create_thumb' => FALSE,
					'thumb_marker' => ''
				);
				$thumbs[] = array(
					'width' => 642,
					'height' => 214,
					'thumb_marker' => 'm'
				);
				break;
			default:
				$this->returnJson(array('error' => '1', 'message' => '参数不正确'));
			exit;
		}
		$rs = $this->model->uploadFile($thumbs,$link);
		if($rs['code'] == '1')
		{	
			$this->returnJson(array('error' => 0, 'url' => $rs['msg']));
		}
		else
		{
			$this->returnJson(array('error' => $rs['code'], 'message' => $rs['msg']));
		}
    }

   /**
	* 普通POST方法 ajax上传图片 返回相对地址
	* param FILE imgFile
	* return json_array
	*/
	public function uploadImage()
	{
		$this->cklogin();
		$type = $this->input->get('type');
		$link = 'uploads/';
		switch($type)
		{
			case 'grouphead':
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'forum/group/';
				break;
		}
		$rs = $this->model->uploadFile($thumbs,'',$link);
    	//$rs = $this->model->getUploadedNames('uploads/');
	//	$class = $this->input->get('image');
		if($rs['code'] == '1')
		{
			$this->returnJson(array('error' => 0, 'url' => $rs['msg']));
		}
		else
		{
			$this->returnJson(array('error' => $rs['code'], 'message' => $rs['msg']));
		}
    }

   /**
	* 编辑器ajax上传图片 返回完整地址
	* param FILE imgFile
	* return json_array
	*/
	public function editorUploadImage()
	{
		$this->cklogin();
    	$rs = $this->model->getUploadedNames();
		if($rs['code'] == '1')
		{	
			$this->returnJson(array('error' => 0, 'url' => $this->config->item('static_url').$rs['msg']));
		}
		else
		{
			$this->returnJson(array('error' => $rs['code'], 'message' => $rs['msg']));
		}
    }

   /**
	* flash编辑器批量ajax上传 返回相对地址 需要检测get值
	* param FILE imgFile
	* return json_array
	*/
	public function swfImageUpload()
	{
	/*	if(!$this->authcheck())
		{
			echo '网络错误，请刷新页面后再试！';
			exit;
		}*/
		$type = $this->input->get('type');
		switch($type)
		{
			case 'product':
				$thumbs = array();
				$thumbs[] = array(
					'width' => 640,
					'height' => 440,
					'thumb_marker' => 'm',
					'maintain_ratio' => TRUE,
					'master_dim' => 'width'
				);
				$thumbs[] = array(
					'width' => 160,
					'height' => 110,
					'thumb_marker' => 's',
					'maintain_ratio' => TRUE,
					'master_dim' => 'width'
				);
				$link = 'uploads/';
				break;
			default:
				break;
		}

    	$rs = $this->model->getUploadedNames($link);
		if($rs['code'] == '1')
		{	
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->model->thumbConfig, $thumb);
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$rs['msg'];
				$this->image_lib->initialize($thumb);  
				$this->image_lib->resize();
			}

			$this->returnJson(array('error' => 0, 'url' => $rs['msg']));
		}
		else
		{
			$this->returnJson(array('error' => $rs['code'], 'message' => $rs['msg']));
		}
    }

   /**
	* 编辑器删除图片
	* 现阶段不做服务器删除图片处理
	* param FILE imgFile
	* return json_array
	*/
	public function deletePicFromEditor()
	{
		$this->cklogin();
		$url = $this->input->post('url');
		$inns_id = $this->input->post('sid');
		$product_id = $this->input->post('pid');
		//$userRole
    	$rs = $this->model->deletePicture($url,$inns_id,$product_id);
		return $rs;
    }
	
   /**
    * 用户批量上传 swf 文件丢失cookies session的处理 获取之前session
	* 请求需带上程序生成的key	此处存在被攻击风险 直接拷贝在线session值
	* return bool
	*/
	public function authcheck()
	{
		$auth = $this->input->get('auth');
		$user_id = $this->input->get('uid');
		$session_id = $this->input->get('sid');
		if(empty($auth)||empty($user_id)||empty($session_id))
		{
			return FALSE;
		}
		$key = $this->model->authcode(str_ireplace(' ','+',$auth),'DECODE','yz_img_k'.$user_id);
		if($key == 'yzauthuser'.$user_id)			//校验auth值是否有效 无效则超时，伪造
		{
			$current_session_id = $this->session->userdata('session_id');
			if($session_id == $current_session_id)	//校验当前session_id 是否与发来的一致
			{
				return TRUE;
			}
			if(isset($_COOKIE[$this->config->item('sess_cookie_name')])){		//可能是ie浏览器
				$old_cookie = $_COOKIE[$this->config->item('sess_cookie_name')];	//session_id 不一致，可能为flash bug 或伪造 cookie
				$hash	 = substr($old_cookie, strlen($old_cookie)-32);
				$session = substr($old_cookie, 0, strlen($old_cookie)-32);
				if($hash = md5($session.$this->config->item('encryption_key')))		//说明原始cookie有效
				{
					$session_data = $this->session->_unserialize($session);
					if($session_data['session_id'] == $session_id)					//获取cookie里的session_id 是否等于当前发送session_id
					{
						$this->set_user_cookie($old_cookie);						//重新将原始cookie 写入
					}
					return TRUE;										//cookie里的session_id不等于当前发送session_id	flash/超时
				}
				else	//伪造cookie
				{
					echo '校验失败，请尝试刷新页面，重新上传！';exit;
				}
			}
			else	//cookie 不存在 可能是火狐浏览器等 上传插件
			{
				return TRUE;
			}
		}
		return FALSE;
	}
	/*function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}*/
	function set_user_cookie($cookie_data,$expire='')
	{
		if(!$expire)
		{
			$expire = ($this->config->item('sess_expire_on_close') === TRUE) ? 0 : time()+$this->config->item('sess_expiration');;
		}
		setcookie($this->config->item('sess_cookie_name'),$cookie_data,$expire,'/',$this->config->item('cookie_domain'),FALSE);
	}

	function returnJson($arr)
	{
		include_once APPPATH . 'libraries/Services_JSON.php'; 
		$json = new Services_JSON();
		header('Content-type: text/html; charset=UTF-8');
		echo $json->encode($arr);
		exit;
	}
	
	public function cklogin()
	{
		if(!$this->web_user->get_id()||!in_array($this->web_user->get_role(),$this->userRole))	
		{
			$this->setUserLogout();
		}
	}
}