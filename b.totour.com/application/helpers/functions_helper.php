<?php

function getRequestIp() {
	// ip address
	$ip = "unknown";
	if (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != '')	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}

	if (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != '')	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != '')	{
		$ips = explode(",", trim($_SERVER['HTTP_X_FORWARDED_FOR']));

		$ip = trim($ips[sizeof($ips) - 1]);
	}
	return $ip;
}

function makePageUrl($page)
{
	$baseUrl = rtrim(base_url(),'/');
	$requestUrl = $_SERVER['REQUEST_URI'];
	if(!strpos($requestUrl,'?'))	{echo'a';exit;}	//	return $baseUrl.$requestUrl.'?page=';
	if(strpos($requestUrl,'?page='))		{echo'b';exit;}//return $baseUrl.str_replace('page='.$_GET['page'].'&','',$requestUrl).'&page=';
	if(strpos($requestUrl,'&page='))		{echo'c';exit;}//return $baseUrl.str_replace('&page='.$_GET['page'],'',$requestUrl).'&page=';
	{echo'd';exit;}
	return $baseUrl.$requestUrl.'&page=';
}

function build_limit($page, $perpage)
{
	$page = $page <= 0 ? 1 : $page;
	$perpage = empty($perpage) ? DEFAULT_PERPAGE : $perpage;
	return " LIMIT " . ($page - 1) * $perpage . ", $perpage";
}

function is_post()
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		return TRUE;
	}
	return FALSE;
}

/*
* 前端字符串简单合法校验 未读懂本函数情况下请勿使用
* $string 前端传来字符串 '_'代表空格 输出时将转化	$use_replace参数
* $msg 当msg不可用时 返回默认值$default
* $method 返回$msg调用方法
* $scope 数组，用此数组校验输入值是否在数组内
* $default 默认值
*/
function input_string($string,$scope,$default=FALSE,$code='4000')
{
	if($string&&in_array($string,$scope))
	{
		return $string;
	}
	if($default !== FALSE)			return $default;	//判定输入值无效后  优先返回$default
	response_msg($code);						//判断请求类型返回相应结果
}

/*
* 前端数字简单合法校验 未读懂本函数情况下请勿使用
* $num 前端传来数字
* $min_limit 最小界限值
* $max_limit 最大界限值
* $default 默认值
* $code 错误代码
*/
function input_int($num,$min_limit = FALSE,$max_limit = FALSE,$default = FALSE,$code='4000')
{
	$error = FALSE;
	if((empty($num)&&$num !== '0'))		//传入空值  且不为0时 
	{
		$error = TRUE;
	}
	else
	{
		$num = intval($num);
	}
	if(!$error&&$min_limit !== FALSE && $num<$min_limit)
	{
		$error = TRUE;
	}
	if(!$error&&$max_limit !== FALSE && $num>$max_limit)
	{
		$error = TRUE;
	}
	if($error)
	{
		if($default !== FALSE)
			return $default;
		else
		{
			response_msg($code);
		}
	}
	return $num;
}

/*
* 前端数字正则校验  用于订单号 验证码等非零整数
* $num 前端传来数字
* $min_limit 最小界限值
* $max_limit 最大界限值
* $default 默认值
* $code 错误代码
*/
function input_num($num,$min_limit = FALSE ,$max_limit = FALSE,$default='',$code = '4000')
{
	$error = FALSE;
	if(empty($num)||!preg_match("/^\d*$/",$num))
	{
		$error = TRUE;
	}
	if(!$error&&$min_limit !== FALSE && $num<$min_limit)
	{
		$error = TRUE;
	}
	if(!$error&&$max_limit !== FALSE && $num>$max_limit)
	{
		$error = TRUE;
	}
	if($error)
	{
		if($default !== FALSE)
			return $default;
		else
		{
			response_msg($code);
		}
	}
	return $num;
}

function input_empty($data,$default = FALSE,$msg = '4000')
{
	if(empty($data)&&$data !== '0' )
	{
		if($default !== FALSE)
		{
			return $default;
		}
		response_msg($msg);
	}
	return $data;
}

function input_mobilenum($num,$code)
{
	if(preg_match("/^1[3-9]{1}[0-9]{9}$/",$num)){    
		return $num;
	}else{    
		response_msg($code);
	}
}

function _getJsHeader()
{
	header('Content-Type:text/html; charset=UTF-8'); 
	return '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script type="text/javascript">';
}

function _jsBack($msg = '') 
{ 
	echo _getJsHeader() . ($msg == '' ? '' : 'alert("' . $msg . '");') . 'history.back();' . _getJsFooter(); 
	exit;
}
function _getJsFooter() 
{
	return '</script></head><body></body></html>';
}

function response_data($data)
{
	header("Content-type: application/json;charset=utf-8");
	echo json_encode(array('code' => '1','data'=>$data));
		log_message('error',json_encode(array('code' => '1','data'=>$data)));
	exit;
}

function response_msg($code)
{
	header("Content-type: application/json;charset=utf-8");
	if($code == '1')
	{
		echo json_encode(array('code' => '1','msg'=> 'success'));
		exit;
	}
	include(APPPATH.'libraries/error_code.php');
	echo json_encode(array('code' => $code,'msg'=>$code_explain[$code]));
	exit;
}

function input_identity_number($id_card,$msg) 
{ 
	if(strlen($id_card) == 15) 
	{
		return idcard_15to18($id_card); 
	}
	if(idcard_checksum18($id_card))
	{
		return $id_card;
	}
	response_msg($msg);
}

function idcard_checksum18($idcard)
{ 
	if(strlen($idcard) == 18) 
	{
		$idcard_base = substr($idcard, 0, 17);
		if (idcard_verify_number($idcard_base) == strtoupper(substr($idcard, 17, 1)))
		{
			return $idcard; 
		}
	}
	return FALSE;
} 

function idcard_verify_number($idcard_base) // 计算身份证校验码，根据国家标准GB 11643-1999 
{
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	$checksum = 0;
	for ($i = 0; $i < strlen($idcard_base); $i++)
	{
		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	}
	$mod = $checksum % 11;
	$verify_number = $verify_number_list[$mod];
	return $verify_number;
}

function idcard_15to18($idcard)
{
	// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
	if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false)
	{
		$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
	}
	else
	{
		$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
	}
	$idcard = $idcard . idcard_verify_number($idcard);
	return $idcard;
}