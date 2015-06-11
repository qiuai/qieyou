<?php

class web_user {
	private $sess_encrypt_cookie		= FALSE;
	private $sess_expiration			= 7200;
	private $sess_expire_on_close		= FALSE;
	private $sess_match_useragent		= TRUE;
	private $sess_cookie_name			= 'ci_session';
//	private $sess_table_name			= 'wap_session';
	private $cookie_prefix				= '';
	private $cookie_path				= '';
	private $cookie_domain				= '';
	private $cookie_secure				= FALSE;
	private $sess_time_to_update		= 300;
	private $encryption_key				= '';
	private $userdata					= array();
	private $CI;
	private $sessMemcache;

    function __construct($params = array()) 
	{
		// Set the super object to a local variable for use throughout the class
		$this->CI =& get_instance(); 

		log_message('debug', "web_user Class Initialized");
		$this->load->helper('cookie');
		
		$this->sessMemcache = new Memcache;
		$this->sessMemcache->connect($this->CI->config->item('sessMemcache_ip'),$this->CI->config->item('sessMemcache_port'));
		if(!$this->sessMemcache)
		{
			log_message('error', "load Memcache faild !");
			return FALSE;
		}
		

		//创建全局变量
		foreach (array('sess_encrypt_cookie', 'sess_expiration', 'sess_expire_on_close', 'sess_match_useragent', 'sess_cookie_name',/* 'sess_table_name', */'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'cookie_prefix', 'encryption_key') as $key)
		{
			$this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
		}
		// Load the string helper so we can use the strip_slashes() function
		$this->CI->load->helper('string');

		// 是否载入加密类
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$this->CI->load->library('encrypt');
		}

		// 设置过期时间 默认1天
		if ($this->sess_expiration == 0)
		{
			$this->sess_expiration = 86400;	
		}

		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;

		// Run the Session routine. If a session doesn't exist we'll create a new one.  If it does, we'll update it.
		if ( ! $this->sess_read())
		{
			$this->sess_create();
		}
		else
		{
			$this->sess_update();
		}
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
        if ($duration) {
            $this->sess_expire_on_close = false;
			$this->sess_expiration = $duration;
        } else {
            $this->sess_expire_on_close = true;
        }
		$data = array(
			'user_id' => $identity['user_id'],
		    'user_name' => $identity['user_name'],
		    'nick_name' => $identity['nick_name'],
			'band_mobile' => $identity['user_mobile'],
		    'headimg' => $identity['headimg'],
			'unread_msg' => $identity['unread_msg']
        );
		if(isset($identity['inn_id']))
		{
			$data['inn_id'] = $identity['inn_id'];
		}
		setcookie(
			'logined',
			'1',
			$this->sess_expiration + TIME_NOW,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
		$this->set_userdata($data);
    }

	public function logout() 
	{
        return $this->sess_destroy();
    }

    public function get_user_id()
	{
		return ( ! isset($this->userdata['user_id'])) ? FALSE : $this->userdata['user_id'];
    }

    public function get_user_name()
	{
		return ( ! isset($this->userdata['user_name'])) ? FALSE : $this->userdata['user_name'];
    }

	public function get_userdata($item)
	{
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}

	public function get_all_userdata()
	{
		return $this->userdata;
	}

   /**
	* Fetch the current session data if it exists
	* @return bool
	*/
	private function sess_read()
	{
		// Fetch the cookie
		$session = $this->CI->input->cookie($this->sess_cookie_name);

		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE)
		{
			log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}

		// HMAC authentication
		$len = strlen($session) - 40;
		if ($len <= 0)
		{
			log_message('error', 'Session: The session cookie was not signed.');
			return FALSE;
		}

		// Check cookie authentication
		$hmac = substr($session, $len);
		$session = substr($session, 0, $len);

		// Time-attack-safe comparison
		$hmac_check = hash_hmac('sha1', $session, $this->encryption_key);
		$diff = 0;

		for ($i = 0; $i < 40; $i++)
		{
			$xor = ord($hmac[$i]) ^ ord($hmac_check[$i]);
			$diff |= $xor;
		}

		if ($diff)
		{
			log_message('error', 'Session: HMAC mismatch. The session cookie data did not match what was expected.');
			$this->sess_destroy();
			return FALSE;
		}

		// Decrypt the cookie data
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->CI->encrypt->decode($session);
		}

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['Sessid']) OR ! isset($session['Agent']) OR ! isset($session['LastVisit']))
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Is the session current?
		if (($session['LastVisit'] + $this->sess_expiration) < TIME_NOW)
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		/*if ($this->sess_match_useragent == TRUE AND trim($session['Agent']) != trim(substr($this->CI->input->user_agent(), 0, 60)))
		{
			$this->sess_destroy();
			return FALSE;
		}*/
		
		//memcache取session
		$db_session = $this->sessMemcache->get($session['Sessid']);
		if($db_session)
		{
			//用户伪造user_agent
			if($db_session['Agent'] !== $session['Agent'])	
			{
				$this->sess_destroy();
				return FALSE;
			}
		}
		/*else	//取db
		{
			$this->CI->db->where('session_id', $session['session_id']);

			if ($this->sess_match_useragent == TRUE)
			{
				$this->CI->db->where('user_agent', $session['user_agent']);
			}
			
			$query = $this->CI->db->get($this->sess_table_name);
			
			if ($query->num_rows() == 0)
			{
				$this->sess_destroy();
				return FALSE;
			}

			// Is there custom data?  If so, add it to the main session array
			$row = $query->row();

			if (!empty($row->user_data))
			{
				$db_session['user_data'] = $this->_unserialize($row->user_data);
			}
		}*/

		//解析session数据
		if (!empty($db_session['user_data']))
		{
			$db_session['user_data'] = $this->_unserialize($db_session['user_data']);
			if (is_array($db_session['user_data']))
			{
				foreach ($db_session['user_data'] as $key => $val)
				{
					$session[$key] = $val;
				}
			}
		}
		// Session is valid!
		$this->userdata = $session;
		return TRUE;
	}

	// --------------------------------------------------------------------

   /**
	* Write the session data
	*/
	private function sess_write()
	{
		$cookie_userdata = array();

		$custom_userdata = $this->userdata;

		// 必备信息写入cookie
		foreach (array('Sessid','Agent','LastVisit') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}
		
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			$custom_userdata = $this->_serialize($custom_userdata);
		}
		$session_data = $cookie_userdata;
		$session_data['user_data'] = $custom_userdata;

		// 更新session信息
		$this->sessMemcache->set($this->userdata['Sessid'],$session_data,FALSE,$this->sess_expiration);
		
	/*	
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			$custom_userdata = $this->_serialize($custom_userdata);
		}
		//$userdata = $this->_serialize($this->userdata);
		$this->CI->db->where('Sessid', $this->userdata['Sessid']);
		$this->CI->db->update($this->sess_table_name, array('LastVisit' => $this->userdata['LastVisit'], 'user_data' => $custom_userdata));
		*/
		// 更新cookie
		$this->_set_cookie($cookie_userdata);
	}

   /**
	* Create a new session
	*/
	function sess_create()
	{
		//创建新的session_id
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		$this->userdata = array(
			'Sessid'	=> md5(uniqid($sessid, TRUE)),
			'Agent'	=> substr($this->CI->input->user_agent(), 0, 60),
			'LastVisit'	=> TIME_NOW
		);

		$this->sessMemcache->add($this->userdata['Sessid'],$this->userdata,FALSE,$this->sess_expiration);
		// 更新cookie
		$this->_set_cookie();
	}

	// --------------------------------------------------------------------

   /**
	* Update an existing session
	*/
	private function sess_update()
	{
		// We only update the session every five minutes by default
		if ($this->CI->input->is_ajax_request() OR ($this->userdata['LastVisit'] + $this->sess_time_to_update) >= TIME_NOW)
		{
			return;
		}

		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = $this->userdata['Sessid'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)//产生新的id
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		// Turn it into a hash
		$new_sessid = md5(uniqid($new_sessid, TRUE));

		// Update the session data in the session data array
		$this->userdata['Sessid'] = $new_sessid;
		$this->userdata['LastVisit'] = TIME_NOW;

		
		$custom_userdata = $this->userdata;

		// _set_cookie() will handle this for us if we aren't using database sessions by pushing all userdata to the cookie.


		$cookie_data = array();
		foreach (array('Sessid','Agent','LastVisit') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_data[$val] = $this->userdata[$val];
		}

		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			$custom_userdata = $this->_serialize($custom_userdata);
		}
		
		$session_data = $cookie_data;
		$session_data['user_data'] = $custom_userdata;
		// Update the session ID and last_activity field in the DB if needed
		$this->sessMemcache->delete($old_sessid);
		$this->sessMemcache->set($new_sessid,$session_data,FALSE,$this->sess_expiration);
		
		//	$this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('LastVisit' => TIME_NOW, 'Sessid' => $new_sessid), array('session_id' => $old_sessid)));

		// Write the cookie
		$this->_set_cookie($cookie_data);
	}

	// --------------------------------------------------------------------

   /**
	* Destroy the current session
	*/
	function sess_destroy()
	{
		// Kill the session DB row
		if (!empty($this->userdata['Sessid']))
		{
			$this->sessMemcache->delete($this->userdata['Sessid']);
		}

		// Kill the cookie
		setcookie(
			$this->sess_cookie_name,
			addslashes(serialize(array())),
			(TIME_NOW - 31500000),
			$this->cookie_path,
			$this->cookie_domain,
			0
		);

		setcookie(
			'logined',
			0,
			(TIME_NOW - 31500000),
			$this->cookie_path,
			$this->cookie_domain,
			0
		);

		// Kill session data
		$this->userdata = array();
	}

   /**
	* Add or change data in the "userdata" array
	*/
	function set_userdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}
		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$this->userdata[$key] = $val;
			}
		}
		$this->sess_write();
	}

   /**
	* Delete a session variable from the "userdata" array
	*/
	function unset_userdata($newdata = array())
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => '');
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				unset($this->userdata[$key]);
			}
		}
		$this->sess_write();
	}

   /**
	* Write the session cookie
	*/
	public function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}
		
		$cookie_data = array(
			'Sessid' =>	$this->userdata['Sessid'],
			'Agent' => $this->userdata['Agent'],
			'LastVisit' => $this->userdata['LastVisit']
		);
		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}

		$cookie_data .= hash_hmac('sha1', $cookie_data, $this->encryption_key);

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + TIME_NOW;

		// Set the cookie
		setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
	}

   /**
	* Serialize an array
	* This function first converts any slashes found in the array to a temporary marker, so when it gets unserialized the slashes will be preserved
	*/
	private function _serialize($data)
	{
		if(!$data)
		{
			return '';
		}
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}
		return serialize($data);
	}

   /**
	* Unserialize
	* This function unserializes a data string, then converts any temporary slash markers back to actual slashes
	*/
	private function _unserialize($data)
	{
		$data = @unserialize(strip_slashes($data));

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
}