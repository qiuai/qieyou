<?php
/*
 * --------------------------------------------------------------------
 * 团购中商品虚拟推荐信息模型
 * --------------------------------------------------------------------
 *
 */
class Product_option_model extends MY_Model {

	// 信息列表
	public function optList(){
		$cond = array(
			'table' => 'product_opinion',
			'fields' => '*',
			'order_by' => 'id desc'
		);

		$pagerInfo = array(
				'perpage' => $perpage,
				'page' => $page
		);

		$data['list'] = $this->get_all($cond,$pagerInfo);
		$data['total'] = $this->get_total($cond);
		return $data;
	}

	// 信息添加
	public function optAdd($data){
		if(!empty($data) && $this->insert($data,'product_opinion');){
			return TRUE;
		}
		return FALSE;
	}

	// 信息删除
	public function optDel($id){
		$cond = array(
			'table' => 'product_opinion',
			'primaryKey' => 'id',
			'data' => array(
				'id' => $id,
			)
		);
		if($this->update($cond))
		{
			return TRUE;
		}
		return FALSE;
	}

	// 信息编辑
	public function optEdit($data){
		$cond = array(
			'table' => 'product_opinion',
			'primaryKey' => 'id',
			'data' => $data
		);
		if($this->update($cond)){
			return TRUE;
		}
		return FALSE;
	}
}