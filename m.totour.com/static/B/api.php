<?php

new Api();

class Api{

	public function __construct(){
		$mod = empty($_GET['mod']) ? 'index' : trim($_GET['mod']);
		if( method_exists($this, $mod) ){
			$this->{$mod}();
			return;
		}
	}


	public function order(){
		$data = '{"code":"1","msg":[{"order_num":"2611003","state":"A","contact":"a33aaef","telephone":"13266630952","create_time":"1430906269","total":"628.00","price":"628.00","quantity":"1","product_name":"<\u53e4\u57ce\u5c81\u6708\u9a7f\u6808>\u884c\u653f\u5957\u623f","category":"1","product_thumb":"uploads\/2015\/01\/10\/015940344929.jpg"},{"order_num":"2601003","state":"A","contact":"a33aaef","telephone":"13266630952","create_time":"1430906216","total":"628.00","price":"628.00","quantity":"1","product_name":"<\u53e4\u57ce\u5c81\u6708\u9a7f\u6808>\u884c\u653f\u5957\u623f","category":"1","product_thumb":"uploads\/2015\/01\/10\/015940344929.jpg"},{"order_num":"2591003","state":"A","contact":"a33aaef","telephone":"13266630952","create_time":"1430793891","total":"628.00","price":"628.00","quantity":"1","product_name":"<\u53e4\u57ce\u5c81\u6708\u9a7f\u6808>\u884c\u653f\u5957\u623f","category":"1","product_thumb":"uploads\/2015\/01\/10\/015940344929.jpg"},{"order_num":"2581003","state":"A","contact":"a33aaef","telephone":"13266630952","create_time":"1430793799","total":"628.00","price":"628.00","quantity":"1","product_name":"<\u53e4\u57ce\u5c81\u6708\u9a7f\u6808>\u884c\u653f\u5957\u623f","category":"1","product_thumb":"uploads\/2015\/01\/10\/015940344929.jpg"},{"order_num":"2571003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430723997","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"},{"order_num":"2551003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430282551","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"},{"order_num":"2531003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430207647","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"},{"order_num":"2521003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430207551","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"},{"order_num":"2511003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430207330","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"},{"order_num":"2501003","state":"A","contact":"\u4fde\u521a\u94883","telephone":"18612540330","create_time":"1430207118","total":"220.50","price":"220.50","quantity":"1","product_name":"<\u62c9\u78e8\u65e0\u5fe7>\u65c5\u884c\u9669\u5168\u5e74\u8ba1\u5212\u4e00","category":"7","product_thumb":"uploads\/2015\/01\/10\/021758225928s.jpg"}]}';

		$type = empty($_GET['type']) ? 'O' : $_GET['type'];
		$data = json_decode($data, true);
		foreach($data['msg'] as $k => & $v){
			$v['product_name'] = $type . $v['product_name'] . $k;
		}
		sleep(1);
		echo json_encode($data);
	}


	public function orderDetail(){
		sleep(1);
	}
}