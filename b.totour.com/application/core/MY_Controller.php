<?php

class MY_Controller extends CI_Controller {

	public $autoLoadModel = TRUE;
	public $token = array();
	public $model			= NULL;

    public function __construct() 
	{
        parent::__construct();
		$this->_init();
    }

	private function _init()
	{
		if($this->autoLoadModel)
		{
			$this->_LoadModel();
		}
        $this->load->helper('functions');
	}

	private function _getModelName($modelName = NULL)
	{
		return $this->router->class.'_model';
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

	public function check_token()
	{
		if($this->token)
		{
			return $this->token;
		}
		if($this->input->get('token'))
		{
			$token = $this->input->get('token');
		}
		else
		{
			response_msg('4004');
		}
		$sql = 'SELECT * FROM access_token WHERE token = '.$this->db->escape($token).'';
		$access_token = $this->db->query($sql)->row_array();
		if(!$access_token)
		{
			response_msg('4004');
		}
		$this->token = $access_token;
		return $this->token;
	}
	
	public function get_user_id()
	{
		if(!$this->token)
		{	
			if($this->input->get('token'))
			{
				$this->check_token();
			}
			else
			{
				return FALSE;
			}
		}
		return $this->token['user_id'];
	}
}