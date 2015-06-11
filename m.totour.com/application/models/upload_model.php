<?php

class Upload_model extends MY_Model {
 
	public $thumbConfig = array( //用于图片上传的缩略图配置参数
    	'image_library' => 'gd2',
    	'source_image' => '', //原图，此为上传后保存的路径
    	'create_thumb' => TRUE,
		'thumb_marker' => 's', //缩略图后缀
		//'maintain_ratio' => FALSE	是否保持宽高比
    	//'width' => 100, //宽度
    	//'height' => 100, //高度
    );

   /**
	* 得取文件的扩展名
	**/
    public function getFileExt($fileName) {
        $info = explode('.', strrev($fileName));
        return strrev($info[0]);
    }

   /**
	* 得取要上传的文件的全路径，相对路径 生成文件名
	**/
    public function getUploadName($dir, $ext) {
        return $dir . '/' . date('Y/m/d/') . date('His') . mt_rand(100000, 999999) . '.' . $ext;
    }


   /**
	* 检测文件年月日路径是否存在，如果不存在则创建
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
	* 普通图片上传 无需压缩处理
	* @param $FILES
	* @param string $imgtype
	* @return array
	*/
	public function getUploadedNames($link,$imgtype='imgFile') 
	{
		if(empty($_FILES[$imgtype])||empty($_FILES[$imgtype]['name']))
		{
			return array('code' => '-1','msg' => '文件上传失败！');
		}
		$gallery = '';
		$save_path = $this->config->item('uploaded_img_path').$link;
		$time = TIME_NOW;
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

		$this->checkUploadDir($link);							//检测并创建相应文件夹
		$new_file_path = $this->getUploadName($save_path, $file_ext); //自动生成新的文件路径
		$new_file_arr = explode('/', strrev($new_file_path));
		$new_file_name = strrev($new_file_arr[0]); 

		//返回已保存的图片路径
		$file_url = $link.strrev($new_file_arr[3]) . '/' . strrev($new_file_arr[2]) . '/' . strrev($new_file_arr[1]) . '/' . $new_file_name;
		if (move_uploaded_file($tmp_name, $new_file_path) === false)
		{	
			return array('code' => '-3','msg' => '上传文件失败！');
		}
		else{
			@chmod($new_file_path, 0644);						//尝试修改图片的权限为644
			return array('code' => '1','msg' => $file_url);
		}
	}
	
	/**
	 * 下载远程图片
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-6 下午6:03:41
	 * @param unknown $link
	 * @param string $imgtype
	 * @return multitype:string
	 */
	public function getUrlImgNames($url,$link)
	{ 	
		if(!fopen($url,"r")){
			return array('code' => '-1','msg' => '文件获取失败！');
		}
		$save_path = $this->config->item('uploaded_img_path').$link;
		$time = TIME_NOW;
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	
		if (@is_dir($save_path) === false)					//检查目录
		{
			return array('code' => '-1','msg' => '上传目录不存在！');
		}
		if (@is_writable($save_path) === false)				//检查目录写权限
		{
			return array('code' => '-1','msg' => '上传目录没有写入权限！');
		}
	
		$temp_arr = explode(".", $url);
		$file_ext = strtolower(trim(array_pop($temp_arr)));
		$file_ext = in_array($file_ext, $ext_arr) ? $file_ext : 'jpg';

		$this->checkUploadDir($link);							//检测并创建相应文件夹
		$new_file_path = $this->getUploadName($save_path, $file_ext); //自动生成新的文件路径
		$new_file_arr = explode('/', strrev($new_file_path));
		$new_file_name = strrev($new_file_arr[0]); 
		
		$file_url = $link.strrev($new_file_arr[3]) . '/' . strrev($new_file_arr[2]) . '/' . strrev($new_file_arr[1]) . '/' . $new_file_name;
		
		// 保存图片
		file_put_contents($new_file_path, file_get_contents($url));

		@chmod($new_file_path, 0644);						//尝试修改图片的权限为644
		return array('code' => '1','msg' => $file_url);
	}
	
   /**
	* 普通图片上传 无需压缩处理
	* @param $FILES
	* @param string $imgtype
	* @return array
	*/
	public function getWapUploadedNames($link,$imgtype='imgFile') 
	{
		$data = $this->input->post('image');
		if(empty($data))
		{
			return array('code' => '-1','msg' => '文件上传失败！');
		}
		$image = base64_decode($this->input->post('image'));
		$gallery = '';
		$save_path = $this->config->item('uploaded_img_path').$link;
		$time = TIME_NOW;
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$max_size = 4194304;								//最大文件大小 4M
	//	$file_name = $_FILES[$imgtype]['name'];				//原文件名
	//	$tmp_name = $_FILES[$imgtype]['tmp_name'];			//服务器上临时文件名
		$file_size = strlen($image);						//文件大小

		if (@is_dir($save_path) === false)					//检查目录
		{
			return array('code' => '-1','msg' => '上传目录不存在！'); 
		} 
		if (@is_writable($save_path) === false)				//检查目录写权限
		{
			return array('code' => '-1','msg' => '上传目录没有写入权限！');
		}
	/*	if (@is_uploaded_file($tmp_name) === false)			//检查是否已上传
		{ 
			return array('code' => '-1','msg' => '临时文件路径错误！');
		}*/ 
		if ($file_size > $max_size)
		{ 
			return array('code' => '-2','msg' => '上传文件大小超过限制！');
		} 
		
		//获得文件扩展名
		/*$temp_arr = explode(".", $file_name);
		$file_ext = trim(array_pop($temp_arr));
		$file_ext = strtolower($file_ext);
		if (!in_array($file_ext, $ext_arr))
		{
			return array('code' => '-2','msg' => '文件格式错误！只允许'. implode(",", $ext_arr) .'格式。');
		}*/

		$this->checkUploadDir($link);							//检测并创建相应文件夹
		$new_file_path = $this->getUploadName($save_path, 'jpg'); //自动生成新的文件路径
		$new_file_arr = explode('/', strrev($new_file_path));
		$new_file_name = strrev($new_file_arr[0]); 

		//返回已保存的图片路径
		$file_url = $link.strrev($new_file_arr[3]) . '/' . strrev($new_file_arr[2]) . '/' . strrev($new_file_arr[1]) . '/' . $new_file_name;
		if (file_put_contents($new_file_path, $image) === false)
		{	
			return array('code' => '-3','msg' => '上传文件失败！');
		}
		else{
			@chmod($new_file_path, 0644);						//尝试修改图片的权限为644
			return array('code' => '1','msg' => $file_url);
		}
	}	
}

