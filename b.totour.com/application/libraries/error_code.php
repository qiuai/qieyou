<?php

//api全局错误码：
$code_explain = array(
'-1' => 'check token fail',					//session 过期

'1'	 => 'success',							//执行成功
'1001' => 'no user',						//用户名不能为空
'1002' => 'error user password',			//密码错误
'1003' => 'no found user',					//用户不存在
'1004' => 'error user name',				//用户名格式不正确
'1005' => 'registered username',			//用户已注册
'1006' => 'locked account',					//账户已锁定
'1007' => 'invalid applyid',				//不合法的提现id
'1008' => 'exist cashout',					//提现申请不存在
'1009' => 'invalid classid',				//不合法的收藏classid
'1010' => 'inn has been collecting',		//店铺已收藏
'1011' => 'you has not collecting inn',		//店铺尚未收藏
'1012' => 'product has been collecting',	//商品已收藏
'1013' => 'you has not collecting product',	//商品尚未收藏
'1014' => 'missing action',					//缺少参数
'1015' => 'invalid page',					//不合法的页面参数
'1016' => 'invalid perpage',				//不合法的分页参数
'1017' => 'invalid lastid',					//不合法的lastid
'1018' => 'no permission',					//没有商户主权限
'1019' => 'invalid uid',					//不合法的用户id
'1020' => 'exist innsub',					//子账户不存在
'1021' => 'missing ids',					//缺少参数
'1022' => 'insufficient balance',			//余额不足

'2001' => 'invalid sid',					//不合法的商户id
'2002' => 'invalid sort',					//不合法的排序类型
'2003' => 'invalid nid',					//不合法的区域id
'2004' => 'invalid cid',					//不合法的一级分类id
'2005' => 'invalid page',					//不合法的分页数字
'2006' => 'invalid ccid',					//不合法的二级分类id
'2007' => 'exist inn',						//商户不存在
'2008' => 'invalid pid',					//不合法的商品id
'2009' => 'removed product',				//商品被删除
'2010' => 'invalid local',					//不合法的区域id
'2011' => 'invalid seq',					//排序参数错误
'2012' => 'invalid mapval',					//不合法的坐标
'2013' => 'exist product',					//商品不属于此商家
'2014' => 'no permission',					//团购商品无法修改
'2015' => 'invalid quantity',				//不合法的商品数量
'2016' => 'invalid price',					//不合法的商品价格
'2017' => 'invalid oldprice',				//不合法的商品原价
'2018' => 'exist thumb',					//缺少商品封面缩略图
'2019' => 'exist productimage',				//缺少商品图片
'2020' => 'invalid end_time',				//不合法的商品有效期
'2021' => 'invalid agent',					//不合法的商品代售佣金

'3001' => 'invalid ordernum',				//不合法的订单编号
'3002' => 'exist order',					//订单不存在
'3003' => 'invalid orderstate',				//不合法的订单状态
'3004' => 'invalid order',					//订单不可退款
'3005' => 'exist pnname',					//缺少订单联系人
'3006' => 'invalid pnmobile',				//不合法的联系人手机
'3007' => 'unknown error',					//内部错误下单失败
'3008' => 'invalid count',					//购买数错误
'3009' => 'no inventory',					//库存不够或已经下架
'3010' => 'exceed product',					//产品过期
'3011' => 'exist order coupon',				//不存在的消费码
'3012' => 'coupon has exceed',				//消费码已过期
'3013' => 'invalid identity_no',			//错误的身份证

'4000' => 'unknown error',					//未知错误
'4001' => 'invalid parm',					//不合法的参数
'4003' => 'empty parm',						//缺少参数
'4004' => 'invalid accesstoken',			//不合法的access_token
'4005' => 'exist user',						//不存在的用户
'4006' => 'invalid city',					//不合法的城市

'5001' => 'network error',					//网络错误，传输数据失败等
'5002' => 'invalid type',					//不合法的参数类型
);
?>