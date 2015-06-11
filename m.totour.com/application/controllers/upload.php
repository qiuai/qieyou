<?php

class Upload extends MY_Controller {

	public $directView = FALSE;

    public function __construct() 
	{
        parent::__construct();
    }

   /**
	* 首页
	*/
	public function index() 
	{
		$user_id = $this->get_user_id(TRUE);
		$type = $this->input->get('type');
		$thumbs = array();
		switch($type)
		{
			case 'userhead':
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'user/headimg/';
				break;
			case 'topic':
			case 'forum':
				$thumbs[] = array(
					'width' => 150,
					'height' => 150,
					'thumb_marker' => 's',
					'maintain_ratio' => TRUE,
					'master_dim' => 'width'
				);
				$thumbs[] = array(
					'width' => 640,
					'height' => 640,
					'thumb_marker' => 'm',
					'maintain_ratio' => TRUE,
					'master_dim' => 'width'
				);
				$link = 'forum/';
				break;
			case 'grouphead':
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'forum/group/';
				break;
			case 'comments':
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => TRUE,
					'master_dim' => 'width'
				);
				$link = 'uploads/comment/';
				break;
			case 'feedback':
				$link = 'uploads/feedback/';
				break;
			default:
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'uploads/';
				break;
		}
    	$rs = $this->model->getUploadedNames($link); //保存图片 得到图片链接
		if($rs['code'] == '1' && $thumbs)	//生成缩略图
		{	
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->model->thumbConfig, $thumb);
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$rs['msg'];
				$this->image_lib->initialize($thumb);  
				$this->image_lib->resize();
			}
			
		}
		response_row($rs);
	}
	
	public function downloadFormImg($url,$type){
		$thumbs = array();
		switch($type)
		{
			case 'userhead':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'user/headimg/';
				break;
			case 'topic':
			case 'forum':
				$thumbs[] = array(
				'width' => 150,
				'height' => 150,
				'thumb_marker' => 's',
				'maintain_ratio' => TRUE,
				'master_dim' => 'width'
						);
						$thumbs[] = array(
								'width' => 640,
								'height' => 640,
								'thumb_marker' => 'm',
								'maintain_ratio' => TRUE,
								'master_dim' => 'width'
						);
						$link = 'forum/';
						break;
			case 'grouphead':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'forum/group/';
				break;
			case 'comments':
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => TRUE,
				'master_dim' => 'width'
						);
						$link = 'uploads/comment/';
						break;
			case 'feedback':
				$link = 'uploads/feedback/';
				break;
			default:
				$thumbs[] = array(
				'width' => 160,
				'height' => 160,
				'thumb_marker' => 's',
				'maintain_ratio' => FALSE
				);
				$link = 'uploads/';
				break;
		}
		$rs = $this->model->getUrlImgNames($url,$link); //保存图片 得到图片链接
		
		if($rs['code'] == '1' && $thumbs)	//生成缩略图
		{
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->model->thumbConfig, $thumb);
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$rs['msg'];
				$this->image_lib->initialize($thumb);
				$this->image_lib->resize();
			}
		}
		response_row($rs);
	}

	public function wap()
	{
		$user_id = $this->get_user_id(TRUE);
		$type = $this->input->get('type');
		$thumbs = array();
		switch($type)
		{
			case 'userhead':
				$thumbs[] = array(
					'width' => 160,
					'height' => 160,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'user/headimg/';
				break;
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
    	$rs = $this->model->getWapUploadedNames($link); //保存图片 得到图片链接
		if($rs['code'] == '1' && $thumbs)	//生成缩略图
		{	
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->model->thumbConfig, $thumb);
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$rs['msg'];
				$this->image_lib->initialize($thumb);  
				$this->image_lib->resize();
			}
			
		}
		response_row($rs);

	}
}