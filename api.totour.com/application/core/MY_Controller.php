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
    public $pageTitle = '且游商城';					//页面title

	public $token = array();						//类似session机制
	public $tokenMemcache;

	public $modelPrefix		= '';
	public $model			= NULL;
	public $modelName		= '';
    function __construct() {  //构造函数
        parent::__construct();
		$this->_init();
    }

	public function _init()
	{
		if($this->autoLoadModel)
		{
			$this->_LoadModel();
		}
        $this->load->helper('functions');
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

   /**
    * 公用方法 token
	*/
	public function check_token()
	{
		if($this->token)
		{
			return $this->token;
		}
		$token = $this->input->get('token');
		if(empty($token)||strlen($token)!=32)
		{
			return FALSE;
		}
		$this->load_token_memcache();
		$this->token = $this->tokenMemcache->get('token_'.$token);
		if(empty($this->token))
		{
			return FALSE;
		}
		if($this->token['create_time'] < TIME_NOW - 600)
		{
			$this->tokenMemcache->delete('token_'.$token);
			$this->token = array();
			return FALSE;
		}
		return TRUE;
	}

	public function load_token_memcache()
	{
		if($this->tokenMemcache)
			return ;
		$this->tokenMemcache = new Memcache;
		$this->tokenMemcache->connect($this->config->item('tokenMemcache_ip'),$this->config->item('tokenMemcache_port'));
	}
   /**
    * 获取当前用户innid
    */
	public function get_user_inn_id($exit = FALSE)
	{
		if(empty($this->token['inn_id']))
		{
			if($this->check_token())
			{
				return empty($this->token['inn_id'])?'':$this->token['inn_id'];
			}

			if($exit)
			{
				response_code('6005');
			}
			else
			{
				return FALSE;
			}
		}
		return $this->token['inn_id'];
	}

   /**
    * 获取当前用户id
	*/
	public function get_user_id($exit = FALSE)
	{
		if(empty($this->token['user_id']))
		{
			if($this->check_token())
			{
				return $this->token['user_id'];
			}

			if($exit)
			{
				response_code('4004');
			}
			else
			{
				return FALSE;
			}
		}
		return $this->token['user_id'];
	}

   /**
    * 获取当前用户innid
	*/
	public function get_user_name()
	{
		$user_name = $this->web_user->get_user_name();
        return $user_name ? $user_name:NULL;
	}

	public function set_current_data($data)
	{
		$this->web_user->set_userdata($data);
	}

	public function get_current_data($data)
	{
		return $this->web_user->get_userdata($data);
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
                    if ($this->viewFile) { //如果模板存在,则使用指定的模板
                        $this->_setViewData($data);
                        $this->load->view($this->viewFile, $this->viewData, $return);
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
        $this->viewData['attachUrl'] = $this->config->item('attach_url');
        $this->viewData['moduleTag'] = $this->moduleTag;
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
}