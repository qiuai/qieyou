<?php

class MY_Model extends CI_Model {

	public $modelMemcache;
	public $loadmemcache = FALSE;
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
		if($this->loadmemcache)
		{
			$this->load_memcache();
		}
        $this->load->database();
		$this->load_localmemcache();
    }
	public function load_localmemcache()
	{
		$this->localMemcache = new Memcache;
		$this->localMemcache->connect($this->config->item('localMemcache_ip'),$this->config->item('localMemcache_port'));
	}
	public function load_memcache()
	{
		if($this->modelMemcache)
		{
			return TRUE;
		}
		$this->modelMemcache = new Memcache;
		$this->modelMemcache->connect($this->config->item('mainMemcache_ip'),$this->config->item('mainMemcache_port'));
	}
    public function get_query_count($query) {
		$count_sql = "SELECT count(1) as total FROM ".$query."";
    	$row = $this->db->query($count_sql) -> row_array(); 
		return $row['total'];
    }
    
	public function get_sum_by_column($query, $columnName) {
		$sum_sql = "SELECT sum($columnName) as sum FROM (".$query.") as records ";
    	$sum = 0;
		$row = $this->db->query($sum_sql) -> row_array(); 
		return $sum = $row['sum'];
	}
    
    /**
     * 获取所有的数据
     * 
     * @param array $cond 查询条件
     * @param array $pagerInfo 分页信息
     * @return array 结果
     */
    public function get_all($cond = array(), &$pagerInfo = NULL)
	{
        $this->_pre_conditions($cond, $pagerInfo);
        $this->_pre_query($cond);
        $this->_pre_query_fields($cond);
        
        if (isset($cond['limit']) && isset($cond['offset'])) {
            $rows = $this->db->get($cond['table'], $cond['limit'], $cond['offset'])->result_array();
        } else {
            $rows = $this->db->get($cond['table'])->result_array();
        }
		return $rows;
    }

    /**
     * 取得一条数据
     * 
     * @param array $cond 条件数组
     * @return array 
     */
    public function get_one($cond) {
        $this->_pre_conditions($cond);
        $this->_pre_query($cond);
        $this->_pre_query_fields($cond);
		
		$this->db->limit(1);
        $rs = $this->db->get($cond['table'])->row_array();
        return $rs ? !empty($cond['key'])? $rs[$cond['key']] :$rs : array();
    }

    /**
     * 对于连接数据库查询的预处理
     * 
     * @param array $cond 查询条件
     */
    public function _pre_query($cond) {
        if (isset($cond['where'])) {
            $this->db->where($cond['where']);
        }
    }

    /**
     * 对于查询中,字段项的预处理
     * 
     * @param array $cond 
     */
    public function _pre_query_fields($cond) {
        if (isset($cond['fields'])) {
            $arr = array();
            $farr = explode(',', $cond['fields']);
            foreach ($farr as $f) {
                $arr[] = $this->_pre_query_field($f, $cond);
            }

            $cond['fields'] = implode(',', $arr);
            $this->db->select($cond['fields']);
        }
    }

    /**
     * 对于查询前每个字段的处理
     * 
     * @param string $field
     * @param array $cond
     * @return string 
     */
    public function _pre_query_field($field, $cond = NULL) {
        return $field;
    }

   /**
	* 对于查询条件的预处理
	* 
	* @param array $cond 查询条件
	* @param array $pagerInfo 分页信息
	*/
    public function _pre_conditions(&$cond, $pagerInfo = NULL) 
	{
    	if (isset($cond['join']))	//join 至少需要 2项, (表名, 关系, left/inner/right)
		{
			if(is_array($cond['join'][0]))	//多表join
			{
				foreach($cond['join'] as $k => $v)
				{
					if(isset($v[2]))
					{
						$this->db->join($v[0], $v[1], $v[2]);
					}
					else
					{
						$this->db->join($v[0], $v[1]); //否则 有2 个参数
					}
				}
			}
			else
			{
				if(isset($cond['join'][2]))
				{
					$this->db->join($cond['join'][0], $cond['join'][1], $cond['join'][2]);
				}
				else
				{
					$this->db->join($cond['join'][0], $cond['join'][1]); //否则 有2 个参数
				}
			}
        }   
        if (isset($cond['order_by']))
		{
			$order_by = explode(' ', preg_replace('/\s+/i', ' ', $cond['order_by']));
            $this->db->order_by($order_by[0], $order_by[1]);
			if(isset($order_by[3]))
			{
				$this->db->order_by($order_by[2], $order_by[3]);
			}
        }
		if (isset($pagerInfo['per_page']) && isset($pagerInfo['cur_page']))
		{
			$cond['limit'] = $pagerInfo['per_page'];
			$cond['offset'] = ($pagerInfo['cur_page'] - 1) * $pagerInfo['per_page'];
		}
    }

    /*
     * 获取记录数量
     * 
     * @param array $cond 条件数组
     */

    function get_total($cond) 
	{
        $this->_pre_conditions($cond);

        if (!empty($cond['where'])) //忽略limit,offset
		{
            $this->db->where($cond['where']);
        } 
        $result = $this->db->count_all_results($cond['table']);
        return $result;
    }

    /**
     * 删除一条数据
     * 
     * @param int/array $cond 条件,或是编号
     * @param string $field_name 字段名称
     * @return TRUE/FALSE 
     */
    function delete($cond)
	{
		if (empty($cond['where'])) 
		{
			return FALSE;
		}
		
		$this->_pre_conditions($cond);
		$this->_pre_query($cond);

		if (isset($cond['limit']))
		{
			$this->db->limit($cond['limit'])->delete($cond['table']);
		} 
		else 
		{
			$this->db->delete($cond['table']);
		}
        return $this->db->affected_rows() > 0;
    }

    /**
     * 更新数据
     * 
     * @param array $cond 修改数据记录的条件数组
     * @return bool 是否成功 
     */
    function update($cond) {
        if (!isset($cond['data'])||!isset($cond['primaryKey'])||!isset($cond['table'])) {
            return FALSE;
        }
        $primaryKey = $cond['primaryKey'];
        if (!isset($cond[$primaryKey])) {
            if (!isset($cond['data'][$primaryKey])) {
                return FALSE;
            }
            $cond[$primaryKey] = $cond['data'][$primaryKey];
            unset($cond['data'][$primaryKey]);
        }
        $this->_pre_conditions($cond);
   //     $this->_before_update($cond['data']);
        if (isset($cond['where'])) {
            $cond['where'][$primaryKey] = $cond[$primaryKey];
            $this->db->where($cond['where']);
        } else {
            $this->db->where($primaryKey, $cond[$primaryKey]);
        }
        $this->db->update($cond['table'], $cond['data']);

        $ret = $this->db->affected_rows() > 0;
        return $ret;
    }

    /**
     * 插入一条数据
     * 
     * @param array $data 要插入的记录数组
     * @param array $tableName 可以指定的表名
     * @return int/bool 插入的自动id/失败 
     */
    function insert($data, $tableName = NULL) {
    //    $this->_before_insert($data);
        $ret = $this->db->insert($tableName, $data);
        if ($ret) {
			return $insert_id = $this->db->insert_id();
        }
        return FALSE;
    }

    function insert_id() {
        return $this->db->insert_id();
    }

    function _before_insert(&$data) {
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->_before_insert_field($k, $v, $data);
            }
        }
    }

    function _before_insert_field($key, $val, $record) {
        return $val;
    }

    function _before_update(&$data) {
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->_before_update_field($k, $v, $data);
            }
        }
    }

    function _before_update_field($key, $val, $record) {
        return $val;
    }

    /**
     * 对于 db->query() 的替换
     * 所有子类将不再出现 $this->db->query() 字样，
     * $this->query() 作为代替
     * 
     * @param string $query 
     */
    public function query($query) {
        return $this->db->query($query);
    }

   /**
	* 获取当前登录的用户id
	* 
	* @return int 
	*/
    public function getUserId() {
        $userId = $this->web_user->get_id();
        return $userId ? $userId : NULL;
    }
   /**
	* 获取当前登录的用户用户名
	* 
	* @return int 
	*/
    public function getUserName() {
        $userId = $this->web_user->get_name();
        return $userId ? $userId : NULL;
    }

   /**
	* 获取当前登录的用户驿栈ID
	* 只有店长/驿栈老板才有驿栈ID
	* @return int 
	*/
    public function getUserInnId() {
        $userId = $this->web_user->get_id();
        $userRole = $this->web_user->get_role();
        $UserInnId = null;
        if ($userRole == ROLE_INNHOLDER) {
			$cond = array(
				'table' => 'inns',
				'fields' => 'inn_id',
				'where' => array(
					'innholder_id' => $userId
				)
			);
			$UserInnId = $this->get_one($cond);
        } else if ($userRole == ROLE_SHOP_MANAGER) {
			$cond = array(
				'table' => 'r_users_inns',
				'fields' => 'inn_id',
				'where' => array(
					'user_id' => $userId
				)
			);
			$UserInnId = $this->get_one($cond);
		}
        return $UserInnId ? $UserInnId['inn_id'] : '0';
    }

   /**
	* 写用户操作日志
	*
	* @param string $action 动作名称
	* @param string $note 备注
	* @param string $level 事件级别：C: 普通 I: 敏感 D: 危险 U: 高危险
	* @param string $state 状态
	* @return bool 是否成功
	*/
    public function wLog($action, $note, $level = 'C', $state = 'S',$user_id='',$url='') {
        $data = array(
            'user_id' => $user_id?$user_id:$this->web_user->get_user_id(),
            'action' => $action,
            'note' => $note,
            'state' => $state,
            'create_time' => $_SERVER['REQUEST_TIME'],
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
            'url' => $url?$url:'/' . $this->uri->uri_string,
            'event_level' => $level
        );
        return $this->insert($data, 'sys_logs');
    }

	public function get_create_user_authcode() 
	{
		$user_id = $this->web_user->get_user_id();
		return $this->authcode('yzauthuser'.$user_id,'ENCODE','yz_img_k'.$user_id);
	}
 
   /** 字符串加密函数
	* @param string $string 
	* return key or string 
	*/
	public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {
		$ckey_length = 4;   
		// 随机密钥长度 取值 0-32;
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		// 当此值为 0 时，则不产生随机密钥

		$key = md5($key ? $key : 'yzaut_key'); //这里可以填写默认key值
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				 return substr($result, 26);
			} 
			else {
				 return '';
			}
		} 
		else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}

    /**
     * 如果是单条的详情输出，则有可能会进行特殊处理
     *
     * @param array $rs 单条记录数据
     * @return array
     */
    public function _proDetailRecord($rs) { return $rs; }
	
	public function	get_users_info_by_ids($ids)
	{
		$user_info = array();
		$cond = array(
			'table' => 'user_info',
			'fields' => 'user_id,nick_name,sex,headimg,birthday',
			'where' => 'user_id IN ('.$ids.')'
		);
		$rs = $this->get_all($cond);
		if($rs)
		{
			foreach($rs as $key => $row)
			{
				$row['age'] = getAge($row['birthday']);
				unset($row['birthday']);
				$user_info[$row['user_id']] = $row;
			}
		}
		return $user_info;
	}

	public function get_inn_info_by_ids($ids)
	{
		$inn_info = array();
		$search_sql_user = array();
		$return_arr = array();		//返回的数组
		if(!$ids)
		{
			return array();
		}
		if(!$this->modelMemcache)
		{
			$this->load_memcache();
		}
		$id_arr = explode(',',$ids);
		foreach($id_arr as $key => $id)
		{
			if(!$id) continue;
			$inn = $this->modelMemcache->get('inn_info'.$id);
			if($inn)
			{
				$inn_info[] = $inn;
			}
			else
			{
				$search_sql_user[] = $id;
			}
		}
		if($search_sql_user)
		{
			$cond = array(
				'table' => 'inns as inn',
				'fields' => 'inn.inn_id,inn.dest_id,inn.local_id,inn.inn_name,inn.lon,inn.lat,inn.bdgps,inn.create_time,
			it.inn_head,it.features,it.inn_summary,it.inn_address,it.inner_moblie_number,it.inner_telephone',
				'where' => 'inn.inn_id IN ('.implode(',',$search_sql_user).')',
				'join' => array(
					'inn_shopfront as it',
					'it.inn_id = inn.inn_id'
				)
			);
			$rs = $this->get_all($cond);
			foreach($rs as $key => $inn)
			{
				$new[$inn['inn_id']] = $inn;
				$this->modelMemcache->set('inn_info'.$inn['inn_id'],$inn,FALSE,1800);
			}
		}
		if($inn_info)
		{
			foreach($inn_info as $key => $row)
			{
				$return_arr[$row['inn_id']] = $row;
			}
			unset($inn_info);
		}

		if(isset($new))
		{
			foreach($new as $key =>$val)
			{
				$return_arr[$key] = $val;
			}
		}
		return $return_arr;
	}

	public function get_user_info_by_id($ids)
	{
		$user_info = array();
		$search_sql_user = array();
		$return_arr = array();
		/*if($all)
		{*/
			$id_arr = explode(',',$ids);
			foreach($id_arr as $key => $id)
			{
				if(!$id) continue;
				$user = $this->modelMemcache->get('user'.$id);
				if($user)
				{
					$user_info[] = $user;
				}
				else
				{
					$search_sql_user[] = $id;
				}
			}
	/*	}
		else
		{
			$user = $this->modelMemcache->get('user'.$ids);
			if(!$user)
			{
				$search_sql_user[] = $id;
			}
			else
			{
				$user_info[] = $user;
			}
		}*/

		if($search_sql_user)
		{
			$cond = array(
				'table' => 'users as u',
				'fields' => '*',
				'where' => 'u.user_id IN ('.implode(',',$search_sql_user).')',
				'join' => array(
					'user_info as ui',
					'ui.user_id = u.user_id'
				)
			);
			$rs = $this->get_all($cond);
			foreach($rs as $key => $user)
			{
				$new[$user['user_id']] = $user;
				$this->modelMemcache->set('user'.$user['user_id'],$user,FALSE,1800);
			}
		}
		if($user_info)
		{
			foreach($user_info as $key => $row)
			{
				$return_arr[$row['user_id']] = $row;
			}
			unset($user_info);
		}

		if(isset($new))
		{
			foreach($new as $key =>$val)
			{
				$return_arr[$key] = $val;
			}
		}
		return $return_arr;
	}

	public function get_dest_info_by_local_id($local_id,$pro = FALSE)
	{
		$cond = array(
			'table' => 'china_dest_local as l',
			'fields' => '*',
			'where' => array(
				'l.local_id' => $local_id,
				'l.is_display' => 'Y'
			),
			'join' => array(
				'china_dest as d',
				'd.dest_id = l.dest_id' 
			)
		);
		$local =  $this->get_one($cond);
		if($local && $pro)
		{
			$extends = explode(',',$local['extends']);
			$local['province'] = $extends[0];
			$local['city'] = empty($extends[1])?$extends[0]:$extends[1];
		}
		return $local;
	}

   /**
    * 获取商户详细资料
	* @param int inn_id
	* @param bool $detail
	* @return array()
	**/
	public function get_inn_info_by_inn_id($inn_id,$detail=TRUE)
	{
		$cond = array(
			'table' => 'inns as i',
			'fields' => '*',
			'where' => array(
				'i.inn_id' => $inn_id
			)
		);
		if($detail)
		{
			$cond['join'] = array(
				'inn_shopfront as sf',
				'sf.inn_id = i.inn_id'
			);
		}
		return $this->get_one($cond);
	}

	public function get_user_local($local)
	{
		$url = 'http://api.map.baidu.com/geocoder/v2/?ak='.$this->config->item('baidu_map_key').'&location='.$local['lat'].','.$local['lon'].'&output=json
		&pois='.(empty($local['pois'])?'0':'1').'&coordtype='.(empty($local['coordtype'])?'wgs84ll':'bd09ll').'';
		echo $url;exit;
		$cl = curl_init();
		//设置选项，包括URL
		curl_setopt($cl, CURLOPT_URL, $url);
		curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cl, CURLOPT_HEADER, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($cl);
		//释放curl句柄
		curl_close($cl);
		//打印获得的数据
		print_r($output);
		exit;
	}

	public function get_user_detail($user_id)
	{
		$user = $this->modelMemcache->get('user_detail'.$user_id);
		if(!$user)
		{
			$cond = array(
				'table' => 'users',
				'fields' => '*',
				'where' => array(
					'users.user_id' => $user_id
				),
				'join' => array(
					'user_info',
					'user_info.user_id = users.user_id'
				)
			);
			$user = $this->get_one($cond);
			$this->modelMemcache->set('user_detail'.$user_id,$user,FALSE,300);;
		}
		return $user;
	}

	public function update_user_point($user_id,$point,$content,$act = 1,$plus = TRUE)
	{
		if($this->db->query('UPDATE users SET point = point + '.$point.' WHERE user_id = '.$user_id))
		{
			$point = array(
				'user_id' => $user_id,
				'action' => $act,
				'content' => $content,
				'point' => $point,
				'create_time' => TIME_NOW
			);
			$this->insert($point,'user_point');
			$this->modelMemcache->delete('user_detail'.$user_id);
			return TRUE;
		}
		return FALSE;
	}
	
	public function check_mobile_send($mobile)
	{
		return $this->modelMemcache->get('mobileSMS_'.$mobile);
	}
	
	public function save_mobile_identify($mobile,$data)
	{
		return $this->modelMemcache->set('mobileSMS_'.$mobile,$data,FALSE,300);
	}

	public function delete_mobile_identify($mobile)
	{
		return $this->modelMemcache->delete('mobileSMS_'.$mobile);
	}
}