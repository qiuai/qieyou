<?php
/**
 * Excel表格处理类
 * 
 * @copyright (C) 2015, totour.com, Inc.
 * @project qieyou
 * @author Vonwey <vonwey@163.com>
 * @CreateDate: 2015-6-2 下午2:45:59
 * @version 1.4
 */
// die();
class Excel extends MY_Controller {
	
	private $page;
	private $limit;
	private $totalpage;
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 导入数据
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-2 下午2:47:21
	 */
	public function index(){
		
		// 每次读取条数
		$this->limit = 100;
		
		// 分页读取
		$this->getPage();
		
		// 读取数据
		$data = $this->readData();
		
		// 写入数据
		if(!empty($data)){
			foreach ($data as $key=>$value){
				$value = $this->formatData($value,$key);
				$this->wirteData($value,$key);
			}
			$this->getUrl();	// 下一页
		}else{
			$this->getUrl(); 	// 下一页
			//die("第【".$this->page."】页写入失败");
		}
	}
	
	public function scandir($dir){
		
		if (!is_dir($dir)) {
			return false;
		}
		//打开目录
		$handle = opendir($dir);
		while (($file = readdir($handle)) !== false) {
			//排除掉当前目录和上一个目录
			if ($file == "." || $file == "..") {
				continue;
			}
			$file = $dir . DIRECTORY_SEPARATOR . $file;
			//如果是文件就打印出来，否则递归调用
			if (is_file($file)) {
				$files[] = $file;
			} elseif (is_dir($file)) {
				$files[] = $this->scandir($file);
			}
		}
		
		return $files;
	}
	
	/**
	 * 读取数据
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-2 下午4:10:52
	 */
	private function readData(){
		// 文件路径
		$file = BASEPATH.'../excel/users.xls';
		
		// 载入PHPExcel类
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$objPHPExcel = new IOFactory();
		
		// 读取文件
		$readerType = '.xls';
		$readerType = ($readerType == ".xlsx") ? "Excel2007" : "Excel5";
		
		$objReader = $objPHPExcel::createReader($readerType)->load($file);
		$sheet = $objReader->getSheet(0);	//获取当前活动sheet
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumn = $sheet->getHighestColumn(); // 取得总列数
		$colspan = range( 'A', $highestColumn );
		
		// 获取图片
// 		$headimgs= $sheet->getDrawingCollection();
		
		$out = 6;
		$this->totalpage = ceil($highestRow / $this->limit);
		$startNum = ($this->page - 1) * $this->limit + $out;
		$endNum = $this->page * $this->limit + $out;
		$data = array();
		for ($row = $startNum; $row <= $endNum; $row++) {
			foreach ($colspan as $value){
				$data[$row][] = (string)$objReader->getActiveSheet()->getCell($value . $row)->getValue();
			}
			
// 			// 获取头像
// 			$codata = $headimgs[$row]->getCoordinates(); //得到单元数据 比如G2单元
// 			ob_start();
// 			call_user_func(
// 			$headimgs[$row]->getRenderingFunction(),
// 			$headimgs[$row]->getImageResource()
// 			);
// 			$imageContents = ob_get_contents();
			
// 			// 新文件名
// 			$headdir = BASEPATH.'../../'.'static.totour.com/user/headimg/';
// 			if (!is_dir($headdir.'rand')) {
// 				@mkdir($headdir.'rand');
// 				@chmod($headdir.'rand', 0755);
// 			}
// 			if (!is_dir($headdir.'rand/'.$this->page)) {
// 				@mkdir($headdir.'rand/'.$this->page);
// 				@chmod($headdir.'rand/'.$this->page, 0755);
// 			}
// 			$filename = time().rand(1000,10000).'.jpg';
				
// 			file_put_contents($headdir.'rand/'.$this->page.'/'.$filename, $imageContents); //把文件保存到本地
// 			ob_end_clean();
			
// 			// 下载并返回头像地址
// 			$data[$row]['headimg'] = 'user/headimg/rand/'.$this->page.'/'.$filename;
		}
		
		return $data;
	}
	
	/**
	 * 写入数据
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-2 下午3:46:39
	 */
	private function wirteData($data,$key){
		if(empty($data) || !$data['user_name']){
			return FALSE;	// 读取数据出错
		}else{
			$time = time();
			
			// 写入users表
			$user['user_name'] = $data['user_name'];
			$salt = getRandChar(4);
			$user['user_pass'] = md5(md5($data['user_pass']).$salt);
			$user['salt'] = $salt;
			$user['create_time'] = $time;
			
			// 写入user_info表
			$user_info['user_name'] = $data['user_name'];
			$user_info['nick_name'] = $data['nick_name'];
			$user_info['headimg'] = $data['headimg'];
			$user_info['sex'] = 'U';
			$user_info['create_time'] = $time;
			$user_info['last_login_time'] = $time;
			$user_info['update_time'] = $time;
			
			if($this->isExsitRecord($user['user_name'])){
				// 已存在  更新
				$sql = "update user_info set nick_name=\"".$user_info['nick_name']."\",headimg='".$user_info['headimg']."',sex='".$user_info['sex']."' where user_name='".$user_info['user_name']."'";
				$this->model->query($sql);
				return TRUE;
			}else{
				$this->db->trans_start();
				$inser_id = $this->model->insert($user,'users');
				
				// 写入user_info表
				$user_info['user_id'] = $inser_id;
				$user_info['create_by'] = $inser_id;
				$user_info['update_by'] = $inser_id;
				
				$this->model->insert($user_info,'user_info');
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					return FALSE;
				}
				return TRUE;
			}
		}
	}
	
	private function formatData($data,$key){
		if(empty($data)){
			return $data;
		}else{
			$user_info['user_name'] = $data[1];
			$user_info['user_pass'] = $data[2];
			$user_info['nick_name'] = $data[4];
			//$user_info['headimg'] = $data['headimg'];
			$user_info['headimg'] = $this->dealImg($key);
			return $user_info;
		}
	}
	
	/**
	 * 判断记录是否存在
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-2 下午6:05:38
	 */
	private function isExsitRecord($user_name){
		$cond = array(
				'table' => 'users',
				'fields' => '*',
				'where' => array(
						'user_name' => $user_name
				)
		);
		if($this->model->get_one($cond)){
			return TRUE; // 存在
		}else{
			return FALSE; // 不存在
		}
	}
	
	/**
	 * 处理头像
	 *
	 * @author Vonwey <vonwey@163.com>
	 * @CreateDate: 2015-6-2 下午6:20:32
	 */
	public function dealImg($keys){
		$rootdir = 'D:/server/src/trunk/';
		$picsdir = $rootdir.'m.totour.com/excel/head_img/';
		$headdir = $rootdir.'static.totour.com/user/headimg/';
		
		// 当前记录ID
// 		$keys = ($this->page - 1) * $this->limit + intval($keys) +　1;
		
		// 读取文件夹
		$files = $this->scandir($picsdir);
		foreach ($files as $file){
			foreach ($file as $v){
				$filesname[] = $v;
			}
				
		}
		
		// 新文件名
		if (!is_dir($headdir.'rand')) {
			@mkdir($headdir.'rand');
			@chmod($headdir.'rand', 0755);
		}
		if (!is_dir($headdir.'rand/'.$this->page)) {
			@mkdir($headdir.'rand/'.$this->page);
			@chmod($headdir.'rand/'.$this->page, 0755);
		}
		
		$temp_arr = explode(".", $filesname[$keys]);
		$file_ext = trim(array_pop($temp_arr));
		$file_ext = strtolower($file_ext) ? strtolower($file_ext) : 'jpg';
		
		$filename = time().rand(1000,10000).".".$file_ext;
		
		// 下载头像
		$content = file_get_contents($filesname[$keys]);
		file_put_contents($headdir.'rand/'.$this->page.'/'.$filename, $content);
		
		// 生成缩略图
		$thumb = array(
			'width' => 160,
			'height' => 160,
			'thumb_marker' => 's', ////缩略图后缀
			'maintain_ratio' => FALSE, //是否保持宽高比
			'image_library' => 'gd2',
			'source_image' => $headdir.'rand/'.$this->page.'/'.$filename, //原图，此为上传后保存的路径
			'create_thumb' => TRUE,
		);
		$this->load->library('image_lib');
		$this->image_lib->initialize($thumb);
		$this->image_lib->resize();
		
		// 下载并返回头像地址
		return 'user/headimg/rand/'.$this->page.'/'.$filename;
	}
	
	// 分页处理
	private function getPage(){
		if(intval($_REQUEST['totalpage'])){
			$this->totalpage = intval($_REQUEST['totalpage']);
		}
		$this->page = intval($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	}
	
	// 分页跳转
	private function getUrl(){
		if($this->page <= $this->totalpage && $this->page < 101){
			$url = "http://".$_SERVER['HTTP_HOST']."/excel?page=".($this->page + 1)."&totalpage=".$this->totalpage;
			echo "<script>window.location.href='$url';</script>";
		}else{
			die('OVER');
		}
	}
	
}
// END Controller class