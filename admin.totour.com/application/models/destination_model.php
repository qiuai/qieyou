<?php
class Destination_model extends MY_Model {
	
	public function get_china_dest_by_parent_id($parent_id)
	{
		$memcache = new memcache;
		$memcache->connect($this->config->item('localMemcache_ip'),$this->config->item('localMemcache_port'));
		$dest = $memcache->get('china_dest'.$parent_id);
		if($dest !== array())
		{
			$cond = array(
				'table' => 'china_dest',
				'fields' => '*',
				'where' => array(
					'parent_id' => $parent_id
				)
			);
			$dest = $this->get_all($cond);
			$memcache->set('china_dest'.$parent_id,$dest,FALSE,1800);
		}
		return $this->get_all($cond);
	}

	public function get_china_dest_local_by_parent_id($dest_id)
	{
		$cond = array(
			'table' => 'china_dest_local',
			'fields' => '*',
			'where' => array(
				'dest_id' => $dest_id
			)
		);
		return $this->get_all($cond);
	}

	public function get($dest_id) {
		$this->db->where('dest_id', $dest_id);
		return $this->db->get('destination')->row_array();
	}
	
	public function update($dest_id,$destInfo) {
		$this->db->where('dest_id', $dest_id);
		$this->db->update('destination', $destInfo);
		return $dest_id;
	}
	
	public function create($destInfo) {
		$this->db->insert('destination', $destInfo);
		return $this->db->insert_id();
	}
	
	public function getDestByCityAndProvince($cityName, $provinceName) {
		$this->db->select('dest_id,dest_name');
		$this->db->where('city', $cityName);
		$this->db->where('province', $provinceName);
		$this->db->where('is_display', 'Y');
		return $result = $this->db->get('destination')->result_array();
	}

	public function getDestByUserId($cityName, $provinceName,$userId) {
		$this->db->from('destination d');
		$this->db->select('d.dest_id,d.dest_name');
		$this->db->join('r_users_dest ud', 'ud.dest_id = d.dest_id');
		$this->db->where('city', $cityName);
		$this->db->where('province', $provinceName);
		$this->db->where('ud.user_id', $userId);
		return $result = $this->db->get()->result_array();
	}
	
	public function searchDestInfo($cityName, $provinceName, $limit) {
		$provinceName = $this-> db -> escape($provinceName);
		$cityName = $this-> db -> escape($cityName);
		$select = "SELECT d.*, count(i.inn_id) as count from ";
		$selectjoin = "destination d 
				left join inns i on i.dest_id = d.dest_id";
		$where = " WHERE 1=1 ";
		if ($cityName) {
			$where = $where." AND d.city = ".$cityName." ";
		}
		if ($provinceName) {
			$where = $where." AND d.province = ".$provinceName." ";
		};
		$groupby = "group by d.dest_id ";
		$sql = $select.$selectjoin.$where.$groupby.$limit;
		$totalsql = $selectjoin.$where;
		$total = $this-> get_query_count($totalsql);
		$result = $this->db->query($sql) -> result_array();
		return array ('data' => $result, 'total' => $total);
	}
	
	public function searchInnsBy_dest_Id($limit, $dest_id, $local_id) 
	{
		$select = " SELECT i.inn_id,i.inn_name,i.create_time,i.state,d.dest_name,l.local_name,u.user_name FROM ";
		$selectjoin = " inns as i ";
		$selectjoin .= " JOIN users u ON u.user_id = i.innholder_id ";
		$selectjoin .= " JOIN china_dest as d ON d.dest_id = i.dest_id ";
		$selectjoin .= " JOIN china_dest_local as l ON l.local_id = i.local_id ";
		if($dest_id)
		{
			$where = " WHERE i.dest_id = ".$dest_id."";
		}
		else if($local_id)
		{
			$where = " WHERE i.local_id = ".$local_id."";
		}
		else{
			$where = " WHERE 1 ";
		}
		$where .= ' AND i.is_qieyou = 0';
		$order_by = " ORDER BY i.create_time DESC ";
		$sql = $select.$selectjoin.$where.$order_by.$limit;
		$totalsql = $selectjoin.$where;
		$result = $this->db->query($sql) -> result_array();
		$total = $this-> get_query_count($totalsql);
		return array ('data' => $result, 'total' => $total);
	}
}