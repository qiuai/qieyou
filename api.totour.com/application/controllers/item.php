<?php

class Item extends MY_Controller {

   /**
	* 商品详情页
	**/
	public function index() 
	{
		$product_id = input_int($this->input->get('pid'),0,FALSE,FALSE,'2001');		//商品id	
		$product = $this->model->get_product_by_product_id($product_id,TRUE);
		if(!$product)
		{
			response_code('2001');
		}
		$user_id = $this->get_user_id();
		if($user_id)
		{
			$is_fav = $this->model->check_product_fav($product_id,$user_id);
			$product['is_fav'] =$is_fav?1:0;
		}
		else
		{
			$product['is_fav'] = 0;
		}	
		$local_info = $this->model->get_dest_info_by_local_id($product['local_id'],TRUE);
		$product['local']=$local_info['local_name'].$local_info['dest_name'];
		
		$tuan = $this->model->get_rand_product_by_inn_category($product['inn_id'],'tuan');
		$product['tuan']=$tuan;
		if($product['comments'])
		{
			$product['comment_score'] = $this->model->get_comment_score_by_product_id($product_id);
		}
		else
		{
			$product['comment_score'] = array(
				'product_id' => $product_id,
				'one' => '0',
				'two' => '0',
				'three' => '0',
				'four' => '0',
				'five' => '0',
				'picture' => '0'
			);
		}
		response_data(array('product'=>$product));
    }

   /**
    * 评论详情
	**/	
	public function comment()
	{
		$comment_id = input_int($this->input->get('comment_id'),1,FALSE,FALSE,'2004');
		$comment = $this->model->get_comment_detail($comment_id);
		if(!$comment)
		{
			response_code('2004');
		}
		$user_id = $this->get_user_id();
		$comment['is_like'] = '0';
		if($user_id)
		{
			$comment['is_like'] = $this->model->check_comment_fav($comment_id,$user_id);
		}
		response_data(array('comment'=>$comment));
	}

   /**
    * 评论回复列表
	**/
	public function commentreplylist()
	{
		$comment_id = input_int($this->input->get('comment_id'),1,FALSE,FALSE,'2004');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);
		$comment = $this->model->get_comment_detail($comment_id);
		if(!$comment)
		{
			response_code('2004');
		}
		$comment_reply = array();
		$user_info = array();
		if($comment['replyNum'] && ($comment['replyNum']> ($page-1)*$perpage))
		{
			$rs = $this->model->get_comment_reply($comment_id,$page,$perpage);
			$get_user = array();
			foreach($rs as $key => $row)
			{
				$get_user[] = $row['create_user_id'];
				$get_user[] = $row['reply_user_id'];
			}
			$get_user = array_unique($get_user);
			$user_info = $this->model->get_users_info_by_ids(implode(',',$get_user));
			foreach($rs as $key => $row)
			{
				$row['create_nick_name'] = $user_info[$row['create_user_id']]['nick_name'];
				$row['reply_nick_name'] = $user_info[$row['reply_user_id']]['nick_name'];
				$comment_reply[] = $row;
			}
		}
		response_json('1',$comment_reply);
	}
	
   /**
    * 评论点赞
	**/
	public function commentlike()
	{
		$user_id = $this->get_user_id(TRUE);
		$comment_id = input_int($this->input->get('comment_id'),1,FALSE,FALSE,'2004');
		$act = input_string($this->input->get('act'),array('like','unlike'),FALSE,'4001');
		$comment = $this->model->get_comment_detail($comment_id);
		if(!$comment)
		{
			response_code('2004');
		}
		if($act == 'like')
		{
			$is_like = $this->model->check_comment_fav($comment_id,$user_id);
			if($is_like)
			{
				response_code('2005');
			}
		}
		else
		{
			$is_like = $this->model->check_comment_fav($comment_id,$user_id);
			if(!$is_like)
			{
				response_code('2006');
			}
		}
		if($this->model->comment_fav($act,$comment_id,$user_id))
		{
			response_code('1');
		}
		response_code('-1');
	}

   /**
	* 获取评论列表
	*/
	public function commentlist()
	{
		$item_id = input_int($this->input->get('item_id'),1,FALSE,FALSE,'2001');
		$act = input_string($this->input->get('type'),array('all','good','between','bad','pic'),'all');
		$page = input_int($this->input->get('page'),1,FALSE,1);
		$perpage = input_int($this->input->get('perpage'),1,20,10);

		$comment_score = $this->model->get_comment_score_by_product_id($item_id);
		$limit = ($page-1)*$perpage;
		$data = array();
		$search = FALSE;
		if($comment_score)
		{
			switch($act)
			{
				case 'all':
					$total = $comment_score['one']+$comment_score['two']+$comment_score['three']+$comment_score['four']+$comment_score['five'];
					if($total > $limit)  $search = TRUE;
					break;
				case 'good':
					$total = $comment_score['four']+$comment_score['five'];
					if($total > $limit)  $search = TRUE;
					break;
				case 'between':
					$total = $comment_score['two']+$comment_score['three'];
					if($total > $limit)  $search = TRUE;
					break;
				case 'bad':
					$total = $comment_score['one'];
					if($total > $limit)  $search = TRUE;
					break;
				case 'pic':
					$total = $comment_score['picture'];
					if($total > $limit)  $search = TRUE;
					break;
			}
		}
		if($search)
		{
			$data = $this->model->get_comment_by_product_id($item_id,$act,$page,$perpage);
		}
		response_json('1', $data);
	}

   /**
	* 评论回复
	**/
	public function commentReply()
	{
		$user_id = $this->get_user_id(TRUE);
		$comment_id = input_int($this->input->post('comment_id'),1,FALSE,FALSE,'2004');
		$reply_user = input_int($this->input->post('reply_user'),1,FALSE,FALSE,'2007');
		$content = check_empty(trimall(strip_tags($this->input->post('content'))),FALSE,'2003');
		$comment = $this->model->get_comment_detail($comment_id);
		if(!$comment)
		{
			response_code('2004');
		}
		if($this->model->comment_reply($comment_id,$content,$user_id,$reply_user))
		{
			response_code('1');
		}
		response_code('-1');
	}

   /**
    * 收藏商品
	**/
	public function itemlike()
	{
		$user_id = $this->get_user_id(TRUE);
		$product_id = input_int($this->input->get('item_id'),1,FALSE,FALSE,'2001');
		$act = input_string($this->input->get('act'),array('like','unlike'),FALSE,'4001');

		if($act == 'like')
		{
			$is_like = $this->model->check_product_fav($product_id,$user_id);
			if($is_like)
			{
				response_code('2014');
			}
		}
		else
		{
			$is_like = $this->model->check_product_fav($product_id,$user_id);
			if(!$is_like)
			{
				response_code('2013');
			}
		}
		if($this->model->product_fav($act,$product_id,$user_id))
		{
			response_code('1');
		}
		response_code('-1');
	}
}