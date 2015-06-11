<?php

class Destination extends MY_Controller {

    public $controllerTag = 'inn';
    public $moduleTag = '';
    
	function __construct() {
		parent::__construct();
		$this->cklogin();
	}
	
	public function index()
	{
		show_404();
	}

	//search the destination by criteria
	public function destlist() {
		$this->moduleTag = 'destinationList';
		$cityName = $this->input->get('city');
		$provinceName = $this->input->get('province');
		$page = input_int($this->input->get('page'),1,FALSE,1);
        $perpage = DEFAULT_PERPAGE;
		if(!preg_match("/^\d*$/",$page)){
			$page = 1;
		}
		$destInfo = $this -> model -> searchDestInfo($cityName,$provinceName,build_limit($page, $perpage));
		$destInfo['provinceName'] = $provinceName == null ? "" : $provinceName;
		$destInfo['cityName'] = $cityName == null ? "" : $cityName;
		$pageInfo = array(
			'total' => $destInfo['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $destInfo['total']/$perpage,
			'url' => makePageUrl($page)
		);
		$this->viewData = array(
			'destInfo' => $destInfo,
			'pageInfo' => $pageInfo
		);
	}
	
    /*get the destination by city and province*/
	public function getDestinations() {
		$parent_id = is_positive_number($this->input->post('city_id'));
		$destList = $this->model->get_china_dest_by_parent_id($parent_id);
		$this->_echoJson($destList);
	}

    /*get the localtion by dest*/
	public function getLocations() {
		$parent_id = is_positive_number($this->input->post('dest_id'));
		$destList = $this->model->get_china_dest_local_by_parent_id($parent_id);
		$this->_echoJson($destList);
	}

    /*get the destination by city and province*/
	public function getLocalInn() {
		$parent_id = is_positive_number($this->input->post('city_id'));
		$destList = $this->model->get_china_dest_by_parent_id($parent_id);
		$this->_echoJson($destList);
	}

    /*get the destination by city and province*/
	public function getDestinationLocal() {
		$dest_id = is_positive_number($this->input->post('dest_id'));
		$destList = $this->model->get_china_dest_local_by_dest_id($dest_id);
		$this->_echoJson($destList);
	}

	public function getDestinationsByUser() {
		$cityName = $this->input->post('city');
		$provinceName = $this->input->post('province');
		if (empty($cityName)) {
			$this-> errorMsg(0,"目的地所属城市不能为空！");
		}
		if (empty($provinceName)) {
			$this-> errorMsg(0,"目的地所属省份不能为空！");
		}
		$destList = array();
		$userRole = $this->model->getUserRole();
		$userId = $this->web_user->get_id();
		if ($userRole == ROLE_ADMIN || $userRole == ROLE_TREASURER ||
				$userRole == ROLE_CLIENT_SERVICE) {
			$destList = $this-> model -> getDestByCityAndProvince($cityName,$provinceName);
		} else if ($userRole == ROLE_REGION_MANAGER ) {
			$destList = $this-> model -> getDestByUserId($cityName,$provinceName,$userId);
		}
		$this->_echoJson($destList);
	}
	
	public function searchDestInns() {
		$this->moduleTag = 'searchDestInns';
		$dest_id = input_int($this->input->get('tid'),1,FALSE,0);
		$local_id = input_int($this->input->get('lid'),1,FALSE,0);
		$page = input_int($this->input->get('page'),1,FALSE,1);
        $perpage = DEFAULT_PERPAGE;

		$arr = $this->model->get_localArr(0,$local_id,$dest_id);
		$destInfo = $arr['destInfo'];
		$localArr = $arr['localArr'];

		$destInnsInfo = $this->model->searchInnsBy_dest_Id(build_limit($page, $perpage),$dest_id,$local_id);
		
		$pageInfo = array(
			'total' => $destInnsInfo['total'],
			'perpage' => $perpage,
			'curpage' => $page,
			'totalpage' => $destInnsInfo['total']/$perpage,
			'url' => makePageUrl($page)
		);
		$this->viewData = array(
			'destInnsInfo' => $destInnsInfo,
			'destInfo' => $destInfo,
			'localArr' => $localArr,
			'pageInfo' => $pageInfo
		);
	}
	
	public function edit() {
        $this->moduleTag = 'destinationList';
		$dest_id = $this->input->get('dest_id');
		if (empty($dest_id)) {
			$this-> errorMsg(0,"目的地名称不能为空！");
		}
		if(is_post()) {
			$destInfo = $this-> checkEditDestinationInfo();
            $dest = $this -> model -> get($dest_id);
            if (empty($dest)) {
                $this-> errorMsg(0,"目的地不存在！");
            }
			$this-> model -> update($dest_id, $destInfo);
			$this->_echoJson(array('code' => '1', 'msg' => 'Success !'));
		} else if (!empty($dest_id)){
            $destInfo = $this -> model -> get($dest_id);
			$this->viewData['destInfo'] = $destInfo;
            $this->viewData['key_auth'] = $this->model->get_create_user_authcode();
		}
	}
	
	public function getInnsInfo() {
		$inn_id = $this -> input-> get('innsid');
		if(empty($inn_id) ||!preg_match('/^\d*$/',$inn_id))
		{
			$this-> errorMsg(0,"参数不正确！");
		}
        $this->useLayout = FALSE;
		$this->load->model('inns_model');
		$innsInfo = $this->inns_model->get_innsDetailInfo_by_inn_id($inn_id,TRUE);
		if(!$innsInfo){
			$this-> errorMsg(0,"驿栈不存在！");
		}
        $this->viewData['innsInfo'] = $innsInfo;
	}
	
	public function create() {
        $this->moduleTag = 'destinationList';
		if(is_post()) {
			$destInfo = $this-> checkDestinationInfo();
			if($this-> model -> create($destInfo))
			{
				$this->_echoJson(array('code' => '1', 'msg' => 'Success !'));
			}
			else
			{
				$this-> errorMsg('-1',"创建目的地失败！");
			}
		}
        else{
            $this->viewData['key_auth'] = $this->model->get_create_user_authcode();
        }
	}
	
	private function checkDestinationInfo() {
		$destInfo = array();
		$destInfo['dest_name'] = $this->input->post('dest_name');
		$destInfo['city'] = $this->input->post('city');
		$destInfo['province'] = $this->input->post('province');
		$destInfo['is_display'] = $this->input->post('is_display');
		$destInfo['summary'] = $this->input->post('summary');
		$destInfo['banner_list'] = $this->input->post('banner_list');
		if (empty($destInfo['banner_list'])) {
			$this-> errorMsg(0,"目的地图片不能为空");
		}
        $destInfo['banner_list'] = json_encode($destInfo['banner_list']);
		if (empty($destInfo['dest_name'])) {
			$this-> errorMsg(0,"目的地名称不能为空！");
		}
		if (empty($destInfo['city'])) {
			$this-> errorMsg(0,"目的地所属城市不能为空！");
		}
		if (empty($destInfo['province'])) {
			$this-> errorMsg(0,"目的地所属省份不能为空！");
		}
		if (empty($destInfo['is_display'])) {
			$this-> errorMsg(0,"目的地是否显示不能为空！");
		}
        if (empty($destInfo['summary'])) {
            $this-> errorMsg(0,"目的地简介不能为空！");
        }
		return $destInfo;
	}

    private function checkEditDestinationInfo() {
        $destInfo = array();
        $destInfo['dest_name'] = $this->input->post('dest_name');
        $destInfo['is_display'] = $this->input->post('is_display');
        $destInfo['summary'] = $this->input->post('summary');
        $destInfo['banner_list'] = $this->input->post('banner_list');
        $destInfo['banner_list'] = json_encode($destInfo['banner_list']);
        $dest_id = $this->input->post('dest_id');
        if (empty($dest_id)) {
            $this-> errorMsg(0,"目的地ID不能为空！");
        }
        if (empty($destInfo['dest_name'])) {
            $this-> errorMsg(0,"目的地名称不能为空！");
        }
        if (empty($destInfo['is_display'])) {
            $this-> errorMsg(0,"目的地是否显示不能为空！");
        }
        if (empty($destInfo['summary'])) {
            $this-> errorMsg(0,"目的地简介不能为空！");
        }
        return $destInfo;
    }
}