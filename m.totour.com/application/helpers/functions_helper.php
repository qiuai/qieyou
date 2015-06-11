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

function is_ajax()
{
	if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
	{
		return TRUE;
	}

	return FALSE;
}

function is_post()
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		return TRUE;
	}
	return FALSE;
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

function input_float_response($float,$msg,$default='')
{
	if(empty($float)||!is_numeric($float)||$float<0)
	{
		if($default !== '')
		{
			return $default;
		} 
		response_code($msg);
	}
	return round($float,2);
}

/*
* 前端字符串简单合法校验 未读懂本函数情况下请勿使用
* $string 前端传来字符串 '_'代表空格 输出时将转化	$use_replace参数
* $code 当msg不可用时 返回默认值$default
* $method 返回$msg调用方法
* $scope 数组，用此数组校验输入值是否在数组内
* $default 默认值
*/
function input_string($string,$scope,$default=FALSE,$code='')
{
	if($string&&in_array($string,$scope)) //存在且在预设参数中 则返回正确结果
	{
		return $string;
	}
	if($default!==FALSE)	return $default;   //判定输入值无效后  优先返回$default
	if(!$code)				show_404();		   //不存在错误信息时  出现404
	response_code($code);						   //判断请求类型返回相应结果
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
	if((empty($num)&&$num !== '0'))		//存在0的情况 HTTP传值类型为string
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
			response_code($msg);
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
			response_code($code);
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
function response_row($data)
{
	header("Content-type: application/json;charset=utf-8");
	echo json_encode($data);
	exit;
}

function response_json($code,$msg='')
{
	header("Content-type: application/json;charset=utf-8");
	echo json_encode(array('code'=>$code,'msg'=>$msg));
	exit;
}

function response_data($data)
{
	if(is_post())
	{
		response_json($data['code'],$data['msg']);
	}
	_jsBack(isset($data['msg'])?$data['msg']:$data);
}

function check_empty($data,$default = FALSE,$msg = '参数错误！')
{
	if(empty($data)&&$data !== '0')
	{
		if($default !== FALSE)
		{
			return $default;
		}
		response_code($msg);
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
	if(is_post()||is_ajax())
	{
		response_json($code,$msg);
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
		response_code($code);
	}
}

function make_mobile_identify_code()
{
	$strPol = "0217459683";
	$str = '';
	for($i=0;$i<4;$i++)
	{
		$str.=$strPol[rand(0,9)];//rand($min,$max) $max = strlen($strPol)-1; 61生成介于min和max两个数之间的一个随机整数
	}
	return $str;
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

/*验证luhn算法的数*/
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
	response_code($msg);
}

/**
 * 计算两个坐标之间的距离(米)
 * @param float $fP1Lat 起点(纬度)
 * @param float $fP1Lon 起点(经度)
 * @param float $fP2Lat 终点(纬度)
 * @param float $fP2Lon 终点(经度)
 * @return int
 */
function distanceBetween($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon)
{
    $fEARTH_RADIUS = 6378137;
    //角度换算成弧度
    $fRadLon1 = deg2rad($fP1Lon);
    $fRadLon2 = deg2rad($fP2Lon);
    $fRadLat1 = deg2rad($fP1Lat);
    $fRadLat2 = deg2rad($fP2Lat);
    //计算经纬度的差值
    $fD1 = abs($fRadLat1 - $fRadLat2);
    $fD2 = abs($fRadLon1 - $fRadLon2);
    //距离计算
    $fP = pow(sin($fD1/2), 2) +
          cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2/2), 2);
    return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
}

/**
 * 计算两个坐标之间的距离(米)
 * @param float $fP1Lat 起点(纬度)
 * @param float $fP1Lon 起点(经度)
 * @param float $fP2Lat 终点(纬度)
 * @param float $fP2Lon 终点(经度)
 * @return int
 */
function echoDistance($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon)
{	
	if($fP1Lat === ''|| $fP1Lon === ''|| $fP2Lat === ''|| $fP2Lon === '')
	{
		return '';
	}
	$distance = distanceBetween($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon);
	if($distance>1000)
	{
		$distance = round($distance/1000).'km';
	}
	else
	{
		$distance .= 'M';
	}
	return $distance;
}

function checkUserName($userName,$code = '1002')
{
	if(!$userName||!preg_match('/^[A-Za-z0-9_]+$/',$userName))
	{
		response_code($code);
	}
	$len = strlen($userName);
	if($len<6||$len>16)
	{
		response_code($code);
	}
	if(preg_match("/^\d*$/",$userName)&&!preg_match("/^1[3-9]{1}[0-9]{9}$/",$userName))
	{
		response_code($code);
	}
	return $userName;
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

/**
 * 百度坐标系转换成标准GPS坐系
 * @param float $lnglat 坐标(如:106.426, 29.553404)
 * @return string 转换后的标准GPS值:
 */
function BD09LLtoWGS84($fLng, $fLat){ // 经度,纬度
    $lnglat = explode(',', $lnglat);
    list($x,$y) = $lnglat;
    $Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x={$x}&y={$y}";
    $result = @file_get_contents($Baidu_Server);
    $json = json_decode($result);
    if($json->error == 0){
        $bx = base64_decode($json->x);
        $by = base64_decode($json->y);
        $GPS_x = 2 * $x - $bx;
        $GPS_y = 2 * $y - $by;
        return $GPS_x.','.$GPS_y;//经度,纬度
    }else
        return $lnglat;
}

function input_identity_number($idcard,$msg) 
{ 
	$id_cardlen = strlen($idcard);
	if($id_cardlen == 15) 
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
	if($id_cardlen == 18)
	{
		$idcard_base = substr($idcard, 0, 17);
		if (idcard_verify_number($idcard_base) == strtoupper(substr($idcard, 17, 1)))
		{
			return $idcard; 
		}
	}
	response_code($msg);
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

function getIpCity()
{
  $getIp=$_SERVER["REMOTE_ADDR"];
  echo 'IP:',$getIp;
  echo '<br/>';
  $content = file_get_contents("http://api.map.baidu.com/location/ip?ak=7IZ6fgGEGohCrRKUE9Rj4TSQ&ip={$getIp}&coor=bd09ll");
  $json = json_decode($content);
 
  echo 'log:',$json->{'content'}->{'point'}->{'x'};//按层级关系提取经度数据
  echo '<br/>';
  echo 'lat:',$json->{'content'}->{'point'}->{'y'};//按层级关系提取纬度数据
  echo '<br/>';
  print $json->{'content'}->{'address'};//按层级关系提取address数据
}

function showTime($time)
{
	$last = TIME_NOW - $time;
	if($last < 60)
	{
		return '< 1分钟';
	}
	if($last >= 60 && $last < 3600)
	{
		return round($last/60).'分钟';
	}
	if($last >= 3600 && $last < 86400)
	{
		return round($last/3600).'小时'; 
	}
	if($last >=86400 && $last < 2592000)
	{
		return round($last/86400).'天';
	}
	if($last >= 2592000 && $last < 31536000)
	{
		return round($last/259200).'月';
	}
	if($last >= 31536000)
	{
		return round($last/31536000).'年';
	}
}

function check_birthday($birthday)
{
	if(empty($birthday))
	{
		return FALSE;
	}
	if(substr_count($birthday,'-') != 2)
	{
		return FALSE;
	}
	list($year, $month, $day) = explode('-', $birthday);
	if(!$year||!$month||!$day||$year<1850)
	{
		return FALSE;
	}
	if(date('Y') == $year&&date('m') == $month)
	{
		return FALSE;
	}
	if(checkdate($month,$day,$year))
	{
		return $year.'-'.$month.'-'.$day;
	}
	return FALSE;
}

function getAge($birthday)
{
	if(!check_birthday($birthday))
	{
		return '';
	}
	list($year, $month, $day) = explode('-', $birthday);
	$year = (int)$year;
	$month = (int)$month;
	$day = (int)$day;
	$age = date('Y') - $year;
	if (date('m') < $month || (date('m') == $month && date('d') < $day))
		$age--;
	return $age;
}