<?php

class Upload extends MY_Controller {

	public $autoLoadModel = FALSE ;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public $thumbConfig = array( //用于图片上传的缩略图配置参数
    	'image_library' => 'gd2',
    	'source_image' => '', //原图，此为上传后保存的路径
    	'create_thumb' => TRUE,
		//'thumb_marker' => 's', //缩略图后缀
    );

   /**
	* 处理上传
	* 
	* @param array $sourceArr 要上传的文件信息数组
	* @param string $to 上传文件存放的文件夹，相对
	* @param array $data 要插入或修改的数据数组信息
	* @param array $thumb 缩略图信息，只需要设置宽度和高度即可
	* @return bool 成否与否 
	*/
    public function index() 
	{
		$this->check_token();
		$type = input_string($this->input->get('type'),array('userheadimg','innheadimg','product'),FALSE,'5002');
		$thumbs = array();
		switch($type)
		{
			case 'innheadimg':
				$thumbs[] = array(
					'width' => 100,
					'height' => 100,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'inn/header/';
				break;
			case 'userheadimg':
				$thumbs[] = array(
					'width' => 100,
					'height' => 100,
					'thumb_marker' => 's',
					'maintain_ratio' => FALSE
				);
				$link = 'user/headimg/';
				break;
			case 'product':
				$thumbs[] = array(
					'width' => 640,
					'height' => 440,
					'thumb_marker' => 'm',
					'maintain_ratio' => TRUE
				);
				$thumbs[] = array(
					'width' => 160,
					'height' => 110,
					'thumb_marker' => 's',
					'maintain_ratio' => TRUE
				);
				$link = 'uploads/';
				break;
		}
		$rs = $this->getUploadedNames($link);
        if($rs['code'] != '1')
		{
			response_msg('4000');
		}
        if($thumbs) 
		{
			$this->load->library('image_lib');
			foreach($thumbs as $key => $thumb)
			{
				$thumb = array_merge($this->thumbConfig, $thumb);
				
				log_message('error',json_encode($thumb));
				$thumb['source_image'] = $this->config->item('uploaded_img_path').$link.$rs['msg'];
				log_message('error',$thumb['source_image']);
				$this->image_lib->initialize($thumb);  
				$this->image_lib->resize();
			}
        }
		response_data($link.$rs['msg']);
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
	* 得取文件的扩展名
	* 
	* @param string $fileName
	* @return string 
	*/
    private function getFileExt($fileName) {
        $info = explode('.', strrev($fileName));
        return strrev($info[0]);
    }

   /**
	* 得取要上传的文件的全路径，相对的
	* 
	* @param string $dir 要上传的文件夹相对路径
	* @param string $ext 扩展名
	* @return string 
	*/
    private function getUploadName($dir, $ext) {
        return $dir . date('Y/m/d/') . date('His') . mt_rand(100000, 999999) . '.' . $ext;
    }

   /**
	* 检测文件年月日路径是否存在，如果不存在则创建
	* 
	* @param string $root 上传的文件夹，取以根目录为始的相对路径
	*/
    private function checkUploadDir($root) { //由config 里设定要上传的文件目录
        if (is_dir($root)) {
            $ydir = $root . '/' . date('Y');
            if (!is_dir($ydir)) {
                mkdir($ydir);
            }

            $mdir = $ydir . '/' . date('m');
            if (!is_dir($mdir)) {
                mkdir($mdir);
            }

            $ddir = $mdir . '/' . date('d');
            if (!is_dir($ddir)) {
                mkdir($ddir);
            }
        }
    }

   /**
	* 图片上传 无需压缩处理 返回url相对路径
	* @param $FILES
	* @param string $imgtype
	* @return array
	*/

	public function getUploadedNames($link,$imgtype='imgFile') 
	{
		if(empty($_FILES)||empty($_FILES[$imgtype])||empty($_FILES[$imgtype]['name']))
		{
			return array('code' => '-1','msg' => '文件上传失败！');
		}
		$gallery = '';
		$save_path = $this->config->item('uploaded_img_path').$link;

		$time = $_SERVER['REQUEST_TIME'];
		
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$max_size = 4194304;								//最大文件大小 2M
		$file_name = $_FILES[$imgtype]['name'];				//原文件名
		$tmp_name = $_FILES[$imgtype]['tmp_name'];			//服务器上临时文件名
		$file_size = $_FILES[$imgtype]['size'];				//文件大小
		if (@is_dir($save_path) === false)					//检查目录
		{
			return array('code' => '-1','msg' => '上传目录不存在！'); 
		} 
		if (@is_writable($save_path) === false)				//检查目录写权限
		{
			return array('code' => '-1','msg' => '上传目录没有写入权限！');
		} 
		if (@is_uploaded_file($tmp_name) === false)			//检查是否已上传
		{ 
			return array('code' => '-1','msg' => '临时文件路径错误！');
		} 
		if ($file_size > $max_size)
		{ 
			return array('code' => '-2','msg' => '上传文件大小超过限制！');
		} 
		
		//获得文件扩展名
		$temp_arr = explode(".", $file_name);
		$file_ext = trim(array_pop($temp_arr));
		$file_ext = strtolower($file_ext);
		if (!in_array($file_ext, $ext_arr))
		{
			return array('code' => '-2','msg' => '文件格式错误！只允许'. implode(",", $ext_arr) .'格式。');
		}

		$this->checkUploadDir($save_path);							//检测并创建相应文件夹
		$new_file_path = $this->getUploadName($save_path, $file_ext); //自动生成新的文件路径
		$new_file_arr = explode('/', strrev($new_file_path));
		$new_file_name = strrev($new_file_arr[0]); 

		//返回已保存的图片路径
		$file_url = strrev($new_file_arr[3]) . '/' . strrev($new_file_arr[2]) . '/' . strrev($new_file_arr[1]) . '/' . $new_file_name;
		if (move_uploaded_file($tmp_name, $new_file_path) === false)
		{	
			return array('code' => '-3','msg' => '上传文件失败！');
		}
		else{
			@chmod($new_file_path, 0644);						//尝试修改图片的权限为644
			return array('code' => '1','msg' => $file_url);
		}
	}

   /* 图片上传类共有4个公用接口 2个批量上传 uploadFromEditor处理压缩图片  2个单张图片上传 editorUploadImage用于需要返回完整地址的编辑器 */
   /**
	* 编辑器批量上传图片 需生成缩略图
	* param FILE imgFile
	* return json_array
	*/

   /**
	* 普通POST方法 ajax上传图片 返回相对地址
	* param FILE imgFile
	* return json_array
	*/
	public function uploadImage()
	{
		$this->cklogin();
    	$rs = $this->model->getUploadedNames();
		$class = $this->input->get('image');
		if($rs['code'] == '1')
		{
			$thumb_img = $this->model->imageFix($rs['msg'],$class);
			$this->returnJson(array('error' => 0, 'url' => $thumb_img));
		}
		else
		{
			$this->returnJson(array('error' => $rs['code'], 'message' => $rs['msg']));
		}
    }
}
