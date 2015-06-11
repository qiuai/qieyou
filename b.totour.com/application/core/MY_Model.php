<?php

class MY_Model extends CI_Model {

    public $table_prefix = '';		//表前缀
    public $table_name = '';		//表名 
    public $load_db = FALSE;		//载入数据库
    protected $ci = null;

    /**
     * 构造函数
     */
    function __construct()
	{
        parent::__construct();
		$this->_init();
    }
	
	private function _init()
	{
		if($this->load_db)
		{
			$this->load->database();
		}
	}

    public function get_query_count($query)
	{
		$count_sql = "SELECT count(1) as total FROM ".$query."";
    	$row = $this->db->query($count_sql) -> row_array(); 
		return $row['total'];
    }
    
	public function get_sum_by_column($query, $columnName)
	{
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
    public function get_one($cond)
	{
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
    public function _pre_query($cond)
	{
        if (isset($cond['where'])) {
            $this->db->where($cond['where']);
        }
    }

    public function _get_order_by($order_by)
	{
        $arr = explode(' ', preg_replace('/\s+/i', ' ', $order_by));
        return array($this->_pre_query_field($arr[0]), $arr[1]);
    }

   /**
	* 对于查询中,字段项的预处理
	* 
	* @param array $cond 
	*/
    public function _pre_query_fields($cond)
	{
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
    public function _pre_query_field($field, $cond = NULL)
	{
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
						$this->db->join($v[0], $v[1]);
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
					$this->db->join($cond['join'][0], $cond['join'][1]);
				}
			}
        }
        if (isset($cond['order_by']))
		{
            $order_by = $this->_get_order_by($cond['order_by']);
            $this->db->order_by($order_by[0], $order_by[1]);
        }
        if (isset($cond['limit']) && !isset($cond['offset']))
		{
            $cond['offset'] = isset($pagerInfo['cur_page']) ? ($pagerInfo['cur_page'] - 1) * $cond['limit'] : 0; //如果设置分页,则自动 offset
        } 
		else if(!isset($cond['limit']) && isset($cond['offset']))
		{
            $cond['limit'] = $this->config->get_item('per_page', 20);
        } 
		else if(!isset($cond['limit']) && !isset($cond['offset']))
		{
            if (isset($pagerInfo['per_page']) && isset($pagerInfo['cur_page']))
			{
                $cond['limit'] = $pagerInfo['per_page'];
                $cond['offset'] = ($pagerInfo['cur_page'] - 1) * $pagerInfo['per_page'];
            }
			else if(isset($pagerInfo['per_page']))
			{
                $cond['limit'] = $pagerInfo['per_page'];
                $cond['offset'] = 0;
            }
			else if(isset($pagerInfo['cur_page']))
			{
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
        if (isset($cond['where'])) {
            $this->db->where($cond['where']);
        } //忽略limit,offset
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
    function update($cond)
	{
        if (!isset($cond['data'])||!isset($cond['primaryKey'])||!isset($cond['table'])) 
		{
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
        if(isset($cond['where']))
		{
            $cond['where'][$primaryKey] = $cond[$primaryKey];
            $this->db->where($cond['where']);
        }
		else
		{
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
    function insert($data, $tableName = NULL)
	{
        $ret = $this->db->insert($tableName, $data);
        if($ret)
		{
			return $insert_id = $this->db->insert_id();
        }
        return FALSE;
    }

    function insert_id()
	{
        return $this->db->insert_id();
    }

   /**
	* 对于 db->query() 的替换
	* 所有子类将不再出现 $this->db->query() 字样
	* 
	* @param string $query 
	*/
    public function query($query)
	{
        return $this->db->query($query);
    }

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

}