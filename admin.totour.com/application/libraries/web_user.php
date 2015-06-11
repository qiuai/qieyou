<?php

class web_user {

    private $_access = array();

    function __construct($param = array()) 
	{
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    function __get($name) 
	{
        $CI = & get_instance();
        return $CI->$name;
    }

    /**
     *
     * @param type $identity
     * @param type $duration  持续时间,0为永远
     */
    function login($identity, $duration = 7200) 
	{
        if ($duration !== 7200) {
            //CI 已经是使用 Cookie|Database 放 Session 了..
            $this->session->sess_expire_on_close = false;
        } else {
            $this->session->sess_expire_on_close = true;
        }

        $this->session->sess_expiration = $duration;
      /*  $states = $identity->get_persistent_states();
        foreach ($states as $state_name => $state_value) {
            $this->set_state($state_name, $state_value);
        }*/
		$data = array(
			'id' => $identity['user_id'],
		    'username' => $identity['user_name'],
		    'role' => $identity['role'],
			'realname' => $identity['real_name'],
			'lastlogintime' => $identity['last_login_time'],
			'lastloginip' => $identity['last_login_ip'],
			'inn_id' => $identity['inn_id'],
        );
		if(isset($identity['city_id']))
		{
			$data['city_id'] = $identity['city_id'];
			$data['city_name'] = $identity['city_name'];
		}
		$this->session->set_userdata($data);
    }

    function logout() 
	{
        $this->session->sess_destroy();
    }

    public function check_access($operation, $params = NULL, $allow_caching = true) 
	{
        if ($allow_caching && $params === NULL && isset($this->_access[$operation])) {
            return $this->_access[$operation];
        } else {
            return $this->_access[$operation] = $this->auth->check_access($operation, $this->get_id(), $params); 
        }
    }

    function get_authitems() 
	{
        return $this->auth->get_all_user_auth_items($this->get_id());
    }

    function get_operates() 
	{
        return $this->auth->get_all_user_auth_items($this->get_id(), db_auth_manager::TYPE_OPERATE);
    }

    function get_roles()
	{
        return $this->auth->get_all_user_auth_items($this->get_id(), db_auth_manager::TYPE_ROLE);
    }

    function get_groups()
	{
        return $this->auth->get_all_user_auth_items($this->get_id(), db_auth_manager::TYPE_GROUP);
    }

    function get_department() 
	{
        
    }

	public function get_user_inn_id()
	{
        $inn_id = $this->session->userdata('inn_id');
        return $inn_id ? $inn_id : NULL;
	}

    public function get_id()
	{
        $id = $this->session->userdata('id');
        return $id ? $id : NULL;
    }

    public function get_city_id()
	{
        $city_id = $this->session->userdata('city_id');
        return $city_id ? $city_id : NULL;
    }

    public function get_name()
	{
        $name = $this->session->userdata('username');
        return $name ? $name : NULL;
    }

    public function is_guest()
	{
        return!(bool) $this->get_id();
    }

    public function set_state($name, $value)
	{
        $this->session->set_userdata($name, $value);
    }

    public function get_state($name, $default = null)
	{
        $value = $this->session->userdata($name);
        return $value ? $value : $default;
    }

	public function get_role()
	{
		$role = $this->session->userdata('role');
		return $role;
	}
}