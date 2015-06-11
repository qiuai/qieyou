<?php

//api全局错误码：
$code_explain = array(
'-1' => '执行失败',							//false

'1'	 => '执行成功',							//success
'1001' => 'no user',						//用户名不能为空
'1002' => 'error user password',			//密码错误
'1003' => 'no found user',					//用户不存在
'1004' => '手机号格式不正确',				//error user name
'1005' => 'registered username',			//用户已注册
'1006' => 'locked account',					//账户已锁定
'1007' => '错误的昵称',						//invalid nick name
'1008' => 'exist cashout',					//提现申请不存在
'1009' => 'invalid classid',				//不合法的收藏classid
'1010' => '错误的商户名',					//invalid inn name
'1011' => '请选择一个区域',					//invalid dest id
'1012' => '请选择一个街道',					//invalid local id
'1013' => '商户利润分成错误',				//invalid profit
'1014' => '未填写商户联系人',				//invalid inn contacts
'1015' => '错误的商户联系手机号',			//invalid inn moblie number
'1016' => '坐标错误',						//invalid lat lnt
'1017' => '银行名称错误',					//invalid bank_info
'1018' => '银行账户不正确',					//invalid bank account no
'1019' => '银行开户人错误',					//invalid bank account owner
'1020' => '商户地址错误',					//invalid inn address
'1021' => '原密码错误',						//invalid inn oldpassword
'1022' => '新密码错误',						//invalid inn newpassword
'1023' => '重复密码错误',					//invalid inn oldpassword
'1024' => '两次密码输入不一致',				//invalid inn repassword
'1025' => '请上传图片',						//invalid update img
'1026' => '该部落不存在',					//invalid groups
'1027' => '该商品不存在',					//invalid product
'1028' => '该捡人不存在',					//invalid jianren
'1029' => '用户手机号不存在',				//invalid jianren
'1030' => '部落已推荐',						
'1031' => '商品已推荐',
'1032' => '捡人已推荐',
'1033' => '重复了管理员',
'1034' => '部落不存在',


'2001' => 'invalid sid',					//不合法的商户id
'2002' => 'invalid sort',					//不合法的排序类型
'2003' => 'invalid nid',					//不合法的区域id
'2004' => 'invalid cid',					//不合法的一级分类id
'2005' => 'invalid page',					//不合法的分页数字
'2006' => 'invalid ccid',					//不合法的二级分类id
'2007' => 'exist inn',						//商户不存在
'2008' => 'invalid pid',					//不合法的商品id
'2009' => 'removed product',				//商品被删除
'2010' => 'invalid inn_name',				//不合法的商户名
'2011' => 'invalid profit',					//不合法的分佣比例
'2012' => 'invalid mapval',					//不合法的坐标
'2013' => 'invalid bank_account_no',		//银行帐号格式错误
'2014' => 'invalid bank_account_owner',		//银行开户人错误
'2015' => 'invalid inn_address',			//不合法的商户地址
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
'4006' => '手机号已注册为商户',				//regged innholder

'5001' => 'network error',					//网络错误，传输数据失败等
'5002' => 'invalid type',					//不合法的参数类型

'6032' => '最多设置3个标签',					//error tags
'6033' => '标签字数不正确',				    //error tags lenght

);
?>