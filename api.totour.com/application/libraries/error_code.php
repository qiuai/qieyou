<?php

//api全局错误码：
$code_explain = array(
'-1' => '执行失败',							//false

'1'	 => '执行成功',							//success
'1001' => '您尚未登录',						//user no login
'1002' => 'invalid username',				//用户名称字符串异常/或为空
'1003' => 'invalid userpass',				//用户密码格式错误/或为空
'1004' => 'invalid user or userpass',		//账号或密码错误
'1005' => '登录超时，请重试',				//invalid user or userpass
'1006' => '访问出错，请重新登录',			//invalid user inn id
'1007' => '此号码已注册，请登录',			//reged user mobile
'1010' => '您已登录',						//logined user
'1011' => '请选择要修改的地址',				//invalid address_id
'1012' => '不合法的坐标',					//invalid lat lnt
'1013' => '地址记录不存在',					//exist address
'1014' => '姓名不能为空',					//exist real_name
'1015' => '您尚未选择区域',					//invalid local_id
'1016' => '您尚未填写详细地址',				//invalid address
'1017' => '您最多只能添加20个收货地址',		//maximum address
'1018' => '您最多只能添加20个身份信息',		//maximum identity
'1019' => '身份证号码错误',					//invalid idcardNum
'1020' => '身份记录不存在',					//exist identity
'1021' => 'invalid sex',					//性别选择错误
'1022' => '生日输入有误',					//invalid birthday
'1023' => '您必须输入一个昵称',				//invalid nickname
'1024' => '请上传一张头像',					//invalid headimg
'1025' => '个性签名不能为空',				//invalid signature
'1026' => '消息已删除',					//the message is deleted
'1027' => '无操作权限',					//no permission

'2001' => '产品不存在',						//exist product
'2002' => '产品不存在',						//invalid pid
'2003' => '评论不能为空',					//invalid comment_content
'2004' => '评论不存在',						//invalid comment_id
'2005' => 'liked comment',					//您已经赞过了
'2006' => 'unlike comment',					//您尚未赞过此评论
'2007' => '您尚未选择回复对象',				//invalid reply_user
'2008' => '此商品当前无法购买',				//invalid product
'2009' => '下单失败，商品库存不足',			//error product quantity
'2010' => '商户不存在',						//exist inn
'2011' => '您尚未收藏此店铺',				//unlike inn
'2012' => '您已经收藏此店铺',				//liked inn
'2013' => '您尚未收藏此商品',				//unlike product
'2014' => '您已经收藏此商品',				//liked product
'2015' => '您购买的商品数量有误',			//invalid count

'3001' => '订单编号错误',					//invalid ordernum
'3002' => '请选择一个收货地址',				//invalid address_id
'3003' => '请选择一个投保人',				//invalid identify_id
'3004' => '订单无法支付',					//invalid order state
'3005' => '优惠券不存在',					//invalid quan id
'3006' => '优惠券已经领完了',				//empty quan quantity
'3007' => '提交订单失败',					//network 
'3008' => '优惠券领取失败，您的积分不足',	//not enough points
'3009' => '订单不存在',						//exist order
'3010' => '订单不能取消',					//invalid order state
'3011' => '评分只能为1-5星',				//invalid order stars
'3012' => '评价不能为空',					//empty order comment
'3013' => '订单已经评价过了',				//order have been evaluated


'4000' => 'network error',					//未知错误，请重试...
'4001' => '参数错误',						//不合法的参数
'4002' => '请选择需要的操作',				//invalid action
'4003' => 'empty parm',						//缺少参数
'4004' => 'invalid accesstoken',			//不合法的access_token
'4005' => 'exist user',						//不存在的用户
'4006' => 'invalid dest',					//缺少目的地
'4007' => 'invalid lat',					//纬度错误
'4008' => 'invalid lon',					//经度错误
'4009' => '您没有相关权限',					//permission denied

'5001' => '手机号错误',						//invalid mobile
'5002' => '验证码错误',						//invalid mobile_identify
'5003' => '信息不能为空',					//invalid note
'5004' => '新密码不正确',					//invalid new password
'5005' => '两次密码输入不一致',				//invalid repeat password 
'5006' => '密码错误',						//invalid password
'5007' => '您尚未绑定手机号',				//exist user mobile
'5008' => '短信发送间隔过短',				//permission denied
'5009' => '此手机号已与您的账号绑定',		//is user mobile
'5010' => '此手机号已被其他账号绑定',		//existed mobile
'5011' => '您尚未验证手机号',				//permission denied
'5012' => '验证码超时，请重新验证',			//overtime
'5013' => '页面超时',						//overtime

'6001' => '圈子不存在',						//invalid group_id
'6002' => '圈子不存在或被关闭',				//invalid group
'6003' => '贴子不存在',						//invalid fourm_id
'6004' => '贴子不存在或被删除',				//invalid fourm
'6005' => '您没有相关操作权限',				//permission denied
'6006' => '未找到此用户',					//exist member
'6007' => '用户已经通过审核',				//already join
'6008' => '您已经加入了此部落',				//already join
'6009' => '您的申请正在审核',				//waiting verify
'6010' => '您是群主无法退出部落',			//permission denied
'6011' => '您尚未加入此部落',				//not join
'6012' => '部落不允许任何人加入',			//no join permission
'6013' => '您必须上传一张照片',				//no picture
'6014' => '您尚未填写标题',					//no title
'6015' => '天数输入不正确',					//invalid day
'6016' => '线路不能为空',					//invalid line
'6017' => '您尚未选择出发',					//invalid starttime
'6018' => '您尚未收藏此贴子',				//unlike forum
'6019' => '您已经收藏此贴子',				//liked forum
'6020' => '圈子名称不正确',					//invalid groupname
'6021' => '必须上传一张图片',				//invalid groupimg
'6022' => '圈子描述不正确',					//invalid note
'6023' => '圈子加入方式错误',				//invalid joinmethod
'6024' => '出发时间不正确',					//invalid starttime
'6025' => '评论不存在',						//invaild post id
'6026' => '评论不能为空',					//invaild post note
'6027' => '您的积分不足',					//unenough point
'6028' => '您不能打赏自己',					//unenough point
'6029' => '问答贴不能打赏',					//invalid forum type
'6030' => '您没有创建部落权限',				//permission denied
'6031' => '您已经创建了3个部落',			//max group num
'6032' => '最多设置3个标签',				//error tags
'6033' => '标签字数不正确',				    //error tags lenght
);
?>