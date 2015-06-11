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

function base_url()
{
	if( !defined('BASEURL'))
	{
        $CI = &get_instance();
		define('BASEURL',$CI->config->item('base_url'));
	}
	return BASEURL;
}
/**
 * 格式化时间
 *
 * @param integer $time 时间戳
 *
 * @return string
 */
function format_time($time)
{
	return date("Y-m-d H:i:s", $time);
}

/**
 * 格式化日期
 *
 * @param integer $time 时间戳
 *
 * @return string
 */
function format_date($time)
{
	return date("Y-m-d", $time);
}

function is_post()
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		return true;
	}

	return false;
}

function makePageUrl()
{
	$baseUrl = rtrim(base_url(),'/');
	$requestUrl = $_SERVER['REQUEST_URI'];
	if(!strpos($requestUrl,'?'))			return $baseUrl.$requestUrl.'?page=';
	if(strpos($requestUrl,'?page='))		return $baseUrl.str_replace('page='.$_GET['page'],'',$requestUrl).'page=';
	if(strpos($requestUrl,'&page='))		return $baseUrl.str_replace('&page='.$_GET['page'],'',$requestUrl).'&page=';
	return $baseUrl.$requestUrl.'&page=';
}

function build_limit($page, $perpage)
{
	$page = $page <= 0 ? 1 : $page;
	$perpage = empty($perpage) ? DEFAULT_PERPAGE : $perpage;
	return " LIMIT " . ($page - 1) * $perpage . ", $perpage";
}

function input_num_response($num,$msg,$method = 'GET',$default='')
{
	if(empty($num)||!is_numeric($num)||$num<0)
	{
		if($default !== '')
		{
			return $default;
		}
		response_msg($msg);
	}
	return intval($num);
}

function input_float_response($float,$msg,$default='')
{
	if(empty($float)||!is_numeric($float)||$float<0)
	{
		if($default !== '')
		{
			return $default;
		} 
		response_msg($msg);
	}
	return round($float,2);
}

function response_json($code,$msg)
{
	header("Content-type: application/json;charset=utf-8");
	echo json_encode(array('code'=>$code,'msg'=>$msg));
	exit;
}

/*
* 前端字符串简单合法校验 未读懂本函数情况下请勿使用
* $string 前端传来字符串 '_'代表空格 输出时将转化	$use_replace参数
* $msg 当msg不可用时 返回默认值$default
* $method 返回$msg调用方法
* $scope 数组，用此数组校验输入值是否在数组内
* $default 默认值
*/
function input_string($string,$scope,$default=FALSE,$msg='')
{
	if($string&&in_array($string,$scope)) //存在且在预设参数中 则返回正确结果
	{
		return $string;
	}
	if($default!==FALSE)	return $default;   //判定输入值无效后  优先返回$default
	if(!$msg)				show_404();		   //不存在错误信息时  出现404
	response_msg($msg);						   //判断请求类型返回相应结果
}

/*
* 前端数字简单合法校验 未读懂本函数情况下请勿使用
* $num 前端传来数字
* $min_limit 最小界限值
* $max_limit 最大界限值
* $default 默认值
* $code 错误代码
*/
function input_int($num,$min_limit = FALSE,$max_limit = FALSE,$default = FALSE,$msg='')
{
	$error = FALSE;
	if((empty($num)&&$num !== '0'))		//存在0的情况
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
		elseif($msg)
		{
			response_msg($msg);
		}
		else
		{
			show_404();
		}
	}
	return $num;
}

/*
* 前端数字简单合法校验 未读懂本函数情况下请勿使用
* $num 前端传来数字
* $min_limit 最小值界限
* $max_limit 最大值界限
* $default 默认值
* $msg 当msg不可用时 返回默认值$default
*/
function input_num($num,$min_limit = FALSE ,$max_limit = FALSE,$default='',$code = '参数错误')
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
function _jsGo($url, $msg = '')
{
	echo _getJsHeader() . ($msg == '' ? '' : 'alert("' . $msg . '");') . 'location.href = "' . $url . '";' . _getJsFooter(); 
	exit; 
}
function _ajaxJson($code,$msg)
{
	header("Content-type: application/json;charset=utf-8");
	echo json_encode(array('code'=>$code,'msg'=>$msg));
	exit;
}

/**
* 校验参数$positive_number是否为正整数
*/
function is_positive_number($positive_number,$msg="参数不正确")
{
	if(!empty($positive_number)&&preg_match("/^\d*$/",$positive_number))
	{
		return $positive_number;
	}
	response_msg($msg);
}

function response_msg($msg)
{
	if(is_post())
	{
		_ajaxJson('-1',$msg);
	}
	_jsBack($msg);
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

function check_empty($data,$default = FALSE,$msg = '参数错误！')
{
	if(empty($data)&&$data !== '0')
	{
		if($default !== FALSE)
		{
			return $data;
		}
		if(is_numeric($msg))
		{
			response_code($msg);
		}
		response_msg($msg);
	}
	return $data;
}

function response_code($code = '-1',$msg = 'error !')
{
	log_message('error','code:'.$code.';msg:'.$msg);
	if(is_numeric($code))
	{
		include(APPPATH.'libraries/error_code.php');
		$msg =$code_explain[$code];
	}
	else
	{
		$msg = $code;
		$code = '-1';
	}
	if(is_post())
	{
		_ajaxJson($code,$msg);
	}
	_jsBack($msg);
}

function input_mobilenum($num,$code,$default = FALSE)
{
	if(preg_match("/^1[3-9]{1}[0-9]{9}$/",$num))
	{
		return $num;
	}
	else
	{
		if($default !== FALSE)
		{
			return $default;
		}
		response_msg($code);
	}
}

function getRandChar($length)
{
	$str = '';
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	
	for($i=0;$i<$length;$i++)
	{
		$str.=$strPol[rand(0,61)];//rand($min,$max) $max = strlen($strPol)-1; 61生成介于min和max两个数之间的一个随机整数
	}
	return $str;
}

function trimall($str)	//删除空格
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str); 
}

function check_luhn($num,$msg)
{
    $card_len = strlen($num);
    $i = 0;
    $num_i = array();
    do{
        if(!$i)
		{
            $num_x = $card_len % 2 ? 1 : 2;
        }
		else
		{
            $num_x = $num_x == 1 ? 2 : 1;
        }
        $num_i[$i] = $num[$i] * $num_x;
        $num_i[$i] = $num_i[$i] > 9 ? $num_i[$i] - 9 : $num_i[$i];
    }while(isset($num[++$i]));
    $sum = array_sum($num_i);
    if($sum % 10 == 0)
	{
		return $num;
	}
	response_msg($msg);
}

/**
 * 百度坐标系转换成标准GPS坐系
 * @param float $lnglat 坐标(如:106.426, 29.553404)
 * @return string 转换后的标准GPS值:
 */
function BD09LLtoWGS84($flon,$fLat){ // 经度,纬度
    $lnglat = array($flon,$fLat);
    $Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x={$flon}&y={$fLat}";
    $result = @file_get_contents($Baidu_Server);
    $json = json_decode($result);
    if($json->error == 0){
        $bx = base64_decode($json->x);
        $by = base64_decode($json->y);
        $GPS_x = 2 * $flon - $bx;
        $GPS_y = 2 * $fLat - $by;
        return array(number_format($GPS_x,7),number_format($GPS_y,7));//经度,纬度
    }else
        return $lnglat;
}

function checkLocationPoint($val,$type='lat',$default=FALSE)
{
	if(empty($val)&&$val !== '0')		//有输入变量
	{
		if($default !== FALSE)
		{
			return $default;
		}
		response_code($type=='lat'?'4007':'4008');
	}
	if(!is_float($val)&& !is_numeric($val))		//不是浮点数也不是整数
	{
		if($default !== FALSE)
		{
			return $default;
		}
		response_code($type=='lat'?'4007':'4008');
	}
	$val = (float)$val;
	if($type == 'lat')
	{
		if(abs($val) > 90)
		{
			if($default !== FALSE)
			{
				return $default;
			}
			response_code('4007');
		}	
	}
	else
	{
		if(abs($val) > 180)
		{
			if($default !== FALSE)
			{
				return $default;
			}
			response_code('4008');
		}
	}
	return number_format($val,7,'.','');
}