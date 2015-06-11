<?php

class MY_Model extends CI_Model {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
        $this->load->database();
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

    public function _get_order_by($order_by) {
        $arr = explode(' ', preg_replace('/\s+/i', ' ', $order_by));
        return array($this->_pre_query_field($arr[0]), $arr[1]);
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
    public function _pre_conditions(&$cond, $pagerInfo = NULL) {
    	if (isset($cond['join'])) { //如果是join， 则至少需要 2项, (表名, 关系, left/inner/right)
        	$tblA = $cond['join'][0];
        	$rel = $cond['join'][1];
        	if(isset($cond['join'][2])){
        		$this->db->join($tblA, $rel, $cond['join'][2]);
        	}
        	else {
        		$this->db->join($tblA, $rel); //否则 有2 个参数
        	}
        }   
        if (isset($cond['order_by'])) {
            $order_by = $this->_get_order_by($cond['order_by']);
            $this->db->order_by($order_by[0], $order_by[1]);
        }
        if (isset($cond['limit']) && !isset($cond['offset'])) {
            $cond['offset'] = isset($pagerInfo['cur_page']) ? ($pagerInfo['cur_page'] - 1) * $cond['limit'] : 0; //如果设置分页,则自动 offset
        } else if (!isset($cond['limit']) && isset($cond['offset'])) {
            $cond['limit'] = $this->config->get_item('per_page', 20);
        } else if (!isset($cond['limit']) && !isset($cond['offset'])) {
            if (isset($pagerInfo['per_page']) && isset($pagerInfo['cur_page'])) {
                $cond['limit'] = $pagerInfo['per_page'];
                $cond['offset'] = ($pagerInfo['cur_page'] - 1) * $pagerInfo['per_page'];
            } else if (isset($pagerInfo['per_page'])) {
                $cond['limit'] = $pagerInfo['per_page'];
                $cond['offset'] = 0;
            } else if (isset($pagerInfo['cur_page'])) {
                $cond['limit'] = $this->config->get_item('per_page', 20);
                $cond['offset'] = ($pagerInfo['cur_page'] - 1) * $cond['limit'];
            }
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
		if (!isset($cond['where'])) 
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
            'user_id' => $user_id?$user_id:$this->web_user->get_id(),
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
		$user_id = $this->web_user->get_id();
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

	public function get_user_info_in_ids($ids,$parm = '',$info = TRUE,$all = FALSE)
	{
		$cond = array(
			'table' => 'user_info as ui',
			'fields' => $parm?$parm:'*',
			'where' => 'ui.user_id IN ('.$ids.')'
		);
		if($info)
		{
			$cond['join'] = array(
				'users as u',
				'u.user_id = ui.user_id'
			);
		}
		return $all?$this->get_all($cond):$this->get_one($cond);
	}

	public function get_fulldest_by_local_parent_id($dest_id,$parent_id = FALSE)
	{
		$cond = array(
			'table' => 'china_dest_local',
			'fields' => '*',
			'where' => array(
				'dest_id' => $dest_id,
			),
			'order_by' => 'local_id ASC'
		);
		$arr['local'] = $this->get_all($cond);

		$cond = array(
			'table' => 'china_dest',
			'fields' => '*',
			'where' => 'parent_id = (SELECT parent_id FROM china_dest WHERE dest_id = '.$dest_id.')'
		);
		if($parent_id)
		{
			$cond['where'] = array(
				'parent_id' => $parent_id
			);
		}
		$arr['dest'] = $this->get_all($cond);

		return $arr;
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

   /**
    * 获取一个目的地下的所有驿栈
	* @param dest_id
	* @param array
	*/
	public function get_innlist_by_local_id($local_id)
	{
		$cond = array(
			'table' => 'inns',
			'fields' => 'inn_id,inn_name',
			'where' => array(
				'local_id' => $local_id,
				'is_qieyou' => 0
			)
		);
		return $this->get_all($cond);
	}

   /**
    * 获取目的地详情
	* @param dest_id
	* @param array
	*/
	public function get_dest_info_by_dest_id($dest_id)
	{
		$cond = array(
			'table' => 'china_dest',
			'fields' => 'dest_id,dest_name,extends,parent_id',
			'where' => array(
				'dest_id' => $dest_id,
				'is_display' => 'Y'
			)
		);
		$dest = $this->get_one($cond);
		if($dest)
		{
			$extends = explode(',',$dest['extends']);
			$dest['province'] = $extends[0];
			$dest['city'] = empty($extends[1])?$extends[0]:$extends[1];
		}
		return $dest;
	}

	public function get_localArr($inn_id=0,$local_id=0,$dest_id=0)
	{
		$destInfo = array('province'=>'530000','city'=>'530700','dest_id'=>'0','local_id' => '0','inn_id' => '0');//所有id组合数组
		$localArr = array();
		$Innlist = array();
		if($inn_id)		//查看单个商户
		{
			$inn_info = $this->get_inn_info_by_inn_id($inn_id,FALSE);
			if(empty($inn_info))
			{
				_jsBack('商户不存在！');
			}
			$dest_info = $this->get_dest_info_by_dest_id($inn_info['dest_id']);
			$destInfo['province'] = $dest_info['province'];
			$destInfo['city'] = $dest_info['city'];
			$destInfo['dest_id'] = $inn_info['dest_id'];
			$destInfo['local_id'] = $inn_info['local_id'];
			$destInfo['inn_id'] = $inn_info['inn_id'];
			$localArr = $this->get_fulldest_by_local_parent_id($destInfo['dest_id'],$dest_info['parent_id']);
			$Innlist = $this->model->get_innlist_by_local_id($destInfo['local_id']);
		}
		else if($local_id)	//查看街道商户
		{
			$local_info = $this->model->get_dest_info_by_local_id($local_id,TRUE);
			if(empty($local_info))
			{
				_jsBack('街道不存在！');
			}
			$destInfo['province'] = $local_info['province'];
			$destInfo['city'] = $local_info['city'];
			$destInfo['dest_id'] = $local_info['dest_id'];
			$destInfo['local_id'] = $local_info['local_id'];
			$localArr = $this->get_fulldest_by_local_parent_id($destInfo['dest_id'],$local_info['parent_id']);
			$Innlist = $this->model->get_innlist_by_local_id($destInfo['local_id']);
		}
		else if($dest_id)
		{
			$dest_info = $this->get_dest_info_by_dest_id($dest_id);
			if(empty($dest_info))
			{
				_jsBack('区域不存在！');
			}
			$destInfo['province'] = $dest_info['province'];
			$destInfo['city'] = $dest_info['city'];
			$destInfo['dest_id'] = $dest_info['dest_id'];
			$localArr = $this->get_fulldest_by_local_parent_id($destInfo['dest_id'],$dest_info['parent_id']);
		}
		else	//未指定位置使用默认值 0
		{
			$localArr = array('dest' =>array(),'local'=>array());
			$destInfo = array('province'=>'','city'=>'','dest_id'=>'0','local_id' => '0','inn_id' => '0');
		}
		return array('destInfo' => $destInfo,'localArr' => $localArr,'Innlist' => $Innlist);
	}
}