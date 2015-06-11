<?php

class upload_image {
 
	public $thumbConfig = array( //用于图片上传的缩略图配置参数
    	'image_library' => 'gd2',
    	'source_image' => '', //原图，此为上传后保存的路径
    	'create_thumb' => TRUE,
		'thumb_marker' => 's', //缩略图后缀
		'maintain_ratio' => FALSE	//是否保持宽高比
    	//'width' => 100, //宽度
    	//'height' => 100, //高度
    	//'new_image' => '' //产品的4个小图需要备份原图，这个部分操作比较麻烦一些
    );

   /**
	* 得取文件的扩展名
	* 
	* @param string $fileName
	* @return string 
	*/
    public function getFileExt($fileName) {
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
    public function getUploadName($dir, $ext) {
        return $dir . '/' . date('Y/m/d/') . date('His') . mt_rand(100000, 999999) . '.' . $ext;
    }


   /**
	* 检测文件年月日路径是否存在，如果不存在则创建
	* 
	* @param string $root 上传的文件夹，取以根目录为始的相对路径
	*/
    public function checkUploadDir($root) {
        $root = $this->config->item('uploaded_img_path') . $root; //由config 里设定要上传的文件目录
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
	* 处理图片
	* 
	* @param array $sourceArr 要上传的文件信息数组
	* @param string $to 上传文件存放的文件夹，相对
	* @param array $data 要插入或修改的数据数组信息
	* @param array $thumb 缩略图信息，只需要设置宽度和高度即可
	* @return bool 成否与否 
	*/
    public function uploadFile($thumb = array(),$thumb_marker='') {
		$ret = $this->getUploadedNames();
        if($ret['code'] != '1'){
			 return $ret;
		}
        if($thumb) {
        	$thumb = array_merge($this->thumbConfig, $thumb);
        	if($thumb_marker) { $thumb['thumb_marker'] = $thumb_marker; } //如果没有设置后缀,则自动添加相应的后缀

        	//$cla = 'image'.$k;
        	$thumb['source_image'] = $this->config->item('uploaded_img_path').$ret['msg'];
        	$this->load->library('image_lib', $thumb);
        	$this->image_lib->resize();
        }
        return $ret;
    }

   /**
	* 普通图片上传 无需压缩处理
	* @param $FILES
	* @param string $imgtype
	* @return array
	*/
	public function getUploadedNames($imgtype = 'imgFile') 
	{
		if(empty($_FILES)||empty($_FILES[$imgtype])||empty($_FILES[$imgtype]['name']))
		{
			return array('code' => '-1','msg' => '文件上传失败！');
		}
		$gallery = '';
		$save_path = $this->config->item('uploaded_img_path');
		$time = $_SERVER['REQUEST_TIME'];
		
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$max_size = 2097152;								//最大文件大小 2M
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

		$this->checkUploadDir('/');							//检测并创建相应文件夹
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
}