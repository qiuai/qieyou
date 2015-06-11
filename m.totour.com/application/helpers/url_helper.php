<?php

/*前端填写URL 参数首字母需小写*/
function getUrl($name)
{
	if(!$name)
	{
		return base_url();
	}

	$url_arr = array(
		'group'					=>	'group',					//圈子首页
		'groupDetail'			=>	'group/',					//部落详情
		'groupUserHomepage'		=>	'group/user/',				//用户个人首页

		'forumSendTour'			=>	'forum/sendtour',			//发送游记
		'forumSendWen'			=>	'forum/sendwen',			//发送问答
		'forumSendJian'			=>	'forum/sendjian',			//发送捡人

		'groupSearch'			=>	'user/search',				//圈子搜索
		'groupSearchList'		=>	'user/searchlist',			//圈子搜索列表
		'my'					=>	'user',						//社区个人中心
		'itemView'				=>	'item/',					//商品信息
		'itemDetail'			=>	'item/detail',				//商品详情
		'itemLike'				=>	'item/itemlike',			//收藏商品
		'itemCommentList'		=>	'item/commentlist',			//商品评论列表
		'itemCommentDetail'		=>	'item/comment',				//商品评论详情
		'itemCommentLike'		=>	'item/commentlike',			//商品评论点赞
		'itemCommentReply'		=>	'item/commentreplylist',	//商品评论回复列表
		'itemCommentReplyPost'	=>	'item/commentReply',		//商品评论回复POST

		'login'					=>	'login',					//登录
		'loginPost'				=>	'login/userLogin',			//登录POST
		'loginIdentifyCode'		=>	'login/identifyCode',		//验证码
		'logout'				=>	'login/logout',				//登录注销

		'orderConfirm'			=>	'order/confirm',			//提交订单
		'orderSubmit'			=>	'order/submit',				//提交订单POST
		'orderView'				=>	'order/view',				//订单详情
		'orderPayment'			=>	'order/payment',			//订单支付页面
		'orderPay'				=>	'order/pay',				//跳转支付
		'orderCancel'			=>	'order/cancel',				//订单取消
		'user'					=>	'home',						//我的商城
		'userReg'				=>	'login/userreg',			//注册页
		'userRegPost'			=>	'login/regpost',			//用户注册POST
		'userJifen'				=>	'home/point',				//我的积分
		'userJifenList'			=>	'home/pointlist',			//我的积分流水
		'userAddress'			=>	'home/address',				//收货地址
		'userAddressEdit'		=>	'home/editaddress',			//收货地址添加/修改
		'userAddressEditPost'	=>	'home/modifyUserData',		//收货地址添加/修改POST
		'userQuan'				=>	'home/quan',				//我的抵用券
		'userLike'				=>	'home/shoucang',			//我的收藏
		'userIdcard'			=>	'home/identify',			//身份信息
		'userIdcardEdit'		=>	'home/editidentify',		//身份信息添加/修改
		'userIdcardEditPost'	=>	'home/modifyUserData',		//身份信息添加/修改POST
		'userOrder'				=>	'home/order',				//我的订单
		'userFinance'			=>	'home/finance',				//我的账户余额
		'userTransflow'			=>	'home/tranflow',			//余额流水数据获取
		'userEdit'				=>	'home/edituser',			//我的个人资料
		'userEditPost'			=>	'home/editUserinfo',		//修改我的个人资料POST

		'userGroup'				=>	'user/group',				//社区-我的部落
		'userWenda'				=>	'user/wenda',				//社区-我的问答
		'userJianren'			=>	'user/jianren',				//社区-我的捡人
		'userTour'				=>	'user/tour',				//社区-我的游记
		'userMsg'				=>	'user/message',				//社区-我的消息
		'userForumList'			=>	'user/getMyForum',			//社区-我的圈子贴子列表
		'userOtherForumList'	=>	'user/getUserForum',		//社区-他人的圈子贴子列表
		'userFeedback'			=>	'user/feedback',			//社区-意见反馈

		'special'				=>	'special',					//特卖
		'search'				=>	'special/search',			//特卖搜索
		'innView'				=>	'special/inn',				//店铺详情
		'aboutQieyou'			=>	'help/qieyou',				//关于且游
		'aboutJifen'			=>	'help/jifen',				//积分规则
		'downloadApp'			=>	'help/download',			//APP下载
	);

	if(isset($url_arr[$name]))		//业务拆分后可配置模块的 basu_url('model');
	{
		echo base_url().$url_arr[$name];
		return ;
	}
	log_message('error','no fount parm'.$name);
	echo base_url();
	return;
}