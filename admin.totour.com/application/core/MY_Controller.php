<?php

class MY_Controller extends CI_Controller {

	public $autoLoadModel = TRUE; 
    public $viewFile = NULL;						//视图文件
    public $useViewFile = NULL;						//是否使用视图文件
    public $useLayout = TRUE;						//是否使用布局
    public $viewData = array();						//要渲染的数据
    public $directView = TRUE;						//是否自动渲染视图
    public $viewAbsolutePath = FALSE;				//使用绝对路径
    public $_viewData = array();					//特殊处理的数据
    public $viewPrefix = '';						//视图前缀，如果 admin 可能会用到
	public $templateDir = '';						//模板文件夹
    public $theme = '';								//主题
	public $layout = 'default';						//默认布局
    public $moduleTag = '';
    public $controllerTag = '';

    public $pageTitle = '且游管理后台';				//页面title
	public $userRole = array('admin','cservice','treasurer');
	public $modelPrefix		= '';
	public $model			= NULL;
	public $modelName		= '';
    function __construct() {  //构造函数
        parent::__construct();
		$this->_init();
    }

	public function _getModelName($modelName = NULL) {
        if ($modelName) {
            if ($this->modelPrefix){
                $modelName = (strpos($modelName, $this->modelPrefix) === FALSE ? $this->modelPrefix . $modelName : $modelName); //自动加前缀
            }
            return strpos($modelName,'_model') ? $modelName : $modelName.'_model'; //自动加 _model
        } 
		else
		{
            return $this->modelPrefix.$this->router->class.'_model';
        }
    }

	public function _LoadModel($models = NULL) 
	{
        if ($models) 
		{
			$modelNames = explode(',',$models);
            foreach($modelNames as $modelName)
			{
				$modelName = $this->_getModelName($modelName);
				$this->load->model($modelName);
            }
        } 
		else 
		{
			$modelName = $this->_getModelName();
			$this->load->model($modelName);
			$_model = $this->router->class.'_model';
			$this->model = $this->$_model;
        }
    }

	public function cklogin()
	{
		if(!$this->web_user->get_id()||!in_array($this->web_user->get_role(),$this->userRole))	
		{
			$this->setUserLogout();
		}
	}

	public function get_user_city_id()
	{
		$city_id = $this->web_user->get_city_id();
		if(!$city_id|| $this->web_user->get_role() != ROLE_ADMIN)
		{
			return '530700';
		}
		return $city_id;
	}

	public function _init()
	{
		if($this->autoLoadModel)
		{
			$this->_LoadModel();
		}
		define('BASEURL',$this->config->item('base_url'));
		$this->load->library('web_user','','web_user');			//用户模块
        $this->load->helper('functions');
	}

   /**
    * 获取当前用户innid
	*/
	public function get_user_inn_id()
	{
		$inn_id = $this->web_user->get_user_inn_id();
		return $inn_id?$inn_id:$this->get_user_role()== ROLE_ADMIN?'100':$this->setUserLogout();
	}
   
   /**
    * 获取当前用户innid
	*/
	public function get_user_id()
	{
		$user_id = $this->web_user->get_id();
        return $user_id ? $user_id:NULL;
	}

   /**
    * 获取当前用户innid
	*/
	public function get_user_name()
	{
		$user_name = $this->web_user->get_name();
        return $user_name ? $user_name:NULL;
	}

   /**
	* 获取当前登录的用户角色
	* @return int 
	*/
    public function get_user_role() 
	{
        $userRole = $this->web_user->get_role();
        return $userRole ? $userRole:NULL;
    }

   /**
    * 将当前用户踢下线
	*/
	public function setUserLogout()
	{
		$this->web_user->logout();
		header("Location: ".base_url()."login"); 
		exit;
	}

   /**
	* 渲染视图文件
	* 
	* @param type $data
	* @param type $templateName
	* @param type $return 
	*/
    public function _view($templateName = NULL, $data = NULL, $return = FALSE) {
        //$this->load->_ci_view_paths = array_merge($this->load->_ci_view_paths ,array(FCPATH . 'application/views' ));
        $_viewLayout = $this->templateDir . $this->theme . $this->viewPrefix;
        
        header('Expires:'. gmdate('l d F Y H:i ', strtotime('-1 day')) . ' GMT'); //1天前过期
        header('Cache-Control:private, no-cache, must-revalidate');
        header('Pragma: no-cache');

        //if(extension_loaded('zlib')) {  ob_start('ob_gzhandler'); }
		
        //$cacheMinutes = $this->config->get_item('cache_time', 5); //默认缓存5分钟
        //if($cacheMinutes > 0) {
        //	$this->output->cache($cacheMinutes);
        //}

        if ($this->useViewFile === FALSE) {
            if ($this->useLayout == TRUE && $this->directView == TRUE) { //如查使用布局
                $this->_setViewData($data);
                $this->load->view('_layouts' . '/' . $this->layout, $this->viewData, $return);
            } else { //如果不使用布局,则立即停止
                exit;
            }
        } else {
            if ($this->useLayout == TRUE) { //如果使用布局
                if ($this->directView == TRUE) {
                    if ($this->viewFile == NULL || !is_file($this->viewFile)) {
                        $this->_setViewFile($templateName); //如果没有设置视图文件,则设置
                    }
                    $this->_setViewData($data);
                    $this->viewData['layout_for_content'] = $this->viewFile; //视图
                    $this->load->view($_viewLayout . '_layouts' . '/' . $this->layout, $this->viewData, $return);
                }
            } else {
                if ($this->directView == TRUE) { //如果自动渲染
                    $_viewFile = $this->templateDir . '/' . $this->theme . '/' . $this->viewPrefix . $this->router->class . '/' . $this->router->method;
                    $this->_setViewData($data);
                    $this->load->view($_viewFile, $this->viewData);
                } else { //否则的话,使用用户指定的模板
                    if ($templateName != NULL) { //如果模板存在,则使用指定的模板
                        $this->_setViewData($data);
                        $this->load->view($templateName, $this->viewData, $return);
                    }
                }
            }
        }
    }

   /**
	* 设置渲染的数据
	* 
	* @param array $data 为模板提供的数据
	*/
    protected function _setViewData($data = NULL) {
        if ($data != NULL) {
            $this->viewData = array_merge($this->viewData, $data);
        }
		$this->viewData['layout_for_title'] = $this->pageTitle; //标题
        $this->viewData['layout_for_content'] = NULL;
        $this->viewData['baseUrl'] = base_url();
		$this->viewData['staticVer'] = $this->config->item('version');
        $this->viewData['staticUrl'] = $this->config->item('static_url');
        $this->viewData['session'] = $this->session->all_userdata();

		$locations = array('controllerTag' => $this->controllerTag, 'moduleTag' => $this->moduleTag);
		$dbInfo = array();
		if(isset($this->viewData['session']['username'])&&$this->viewData['session']['username'] == 'develop')
		{
			$queries = $this->db->queries;
			$query_times = $this->db->query_times;
			foreach($queries as $key => $val)
			{
				$dbInfo[] = $val.' casttime:  '.$query_times[$key];
			}
		}

        $this->viewData['locations'] = $locations;
		$this->viewData['dbInfo'] = $dbInfo;

    }

   /**
	* 设置视图文件
	* 
	* @param string $viewFile 文件名称
	*/
    protected function _setViewFile($viewFile = NULL) {
        $_viewFile =$this->viewPrefix;
        if ($this->viewAbsolutePath == TRUE) {
            $this->viewFile = (APPPATH . $this->router->class . '/' . ($viewFile ? $viewFile : $this->router->method) . '.php');
        } else {
            $this->viewFile = $_viewFile . ($this->viewFile == NULL ? $this->router->class . '/' . $this->router->method : $this->viewFile);
        }
    }

   /**
	* 对于 _remap 方法的修改,使 $controller->action 能自动 _view()
	* 如果有手工调用 _view() 则不自动渲染
	* 
	*/
    public function _remap() {
        $args = func_get_args(); //第一个参数是方法名称
        if (isset($args[0])) { //对于非 _view() 方法,执行 view() 达到自动渲染的效果
            $method = $args[0];
            if (!method_exists($this, $method)) {
                show_404();
            }
            call_user_func_array(array($this, $method), isset($args[1]) ? $args[1] : array()); //执行其原有方法

            if ($method != '_view') {
                $this->_view();
            }
        }
    }
	
   /**
	* 以 json 形式输出
	* 
	* @param array $arr
	*/
    public function jsonAjax($code,$msg) {
    	$this->_echoJson(array('code'=>$code,'msg'=>$msg));
    } 

   /**
	* 输出 json 头部文件
	*/
    public function _printJsonHeader() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
		header("Content-type: application/json;charset=utf-8");
    }

	public function errormsg($code,$msg)
	{
		$this->_printJsonHeader();
	    $this->_echoJson(array('code' => $code,'msg' => $msg));
	}

	public function _echoJson($arr)
	{
		$this->_printJsonHeader();
        exit (json_encode($arr));
	}
}