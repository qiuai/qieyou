/**
 * 省二级联动
 */

$._cityInfo = [{"n":"客栈酒店","nid":"1","c":["精品客栈","客栈","青年旅社","家庭旅馆","度假酒店","独家公寓"],"pid":["1","2","3","4","5","6"]},
{"n":"美食饕餮","nid":"2","c":["高端美食","当地美食","西餐","中餐","咖啡馆","小吃快餐","饮品"],"pid":["7","8","9","10","11","12","13"]},
{"n":"娱乐休闲","nid":"3","c":["酒吧","茶楼","SPA水疗","足疗按摩","KTV","电影院"],"pid":["14","15","16","17","18","19"]},
{"n":"当地行","nid":"4","c":["高端房车","私家车","代驾","接送机","拼车","火车票","摩托车","自行车"],"pid":["20","21","22","23","24","25","26","27"]},
{"n":"当地游","nid":"5","c":["当地参团","主题摄影","个性玩法","户外探险","半日体验","景点门票","团队定制","私人定制","拓展培训"],"pid":["28","29","30","31","32","33","34","35","36"]},
{"n":"当地购","nid":"6","c":["手造文化","工艺品","土特产","纪念品"],"pid":["37","38","39","40"]},
{"n":"旅游险","nid":"7","c":["户外险","境内险","境外险","领队险","团队险"],"pid":["41","42","43","44","45"]}];

var cidSelect = $('#cid');
var ccidSelect = $('#ccid');

$.initLocalSelect = function(defaultProv,defaultCity) {
//	var show_default = false;
//	if(!defaultProv&&!defaultCity)
//	{
		var show_default = true;
//	}
	defaultProv = defaultProv?defaultProv:'1';
	defaultCity = defaultCity?defaultCity:'1';
    var ProvRow = getProvRow(defaultProv);
    var provHtml = '';
 //   provHtml += '<option ref="-1" value="">请选择</option>';
    for(var i = 0; i < $._cityInfo.length; i++) {
        provHtml += '<option ref="' + i + '" value="' + $._cityInfo[i].nid + '"' + ( $._cityInfo[i].nid == defaultProv ? ' selected="selected"' : '') + '>' + $._cityInfo[i].n + '</option>';
		
    }
    cidSelect.html(provHtml);
    $.initCities(ProvRow, defaultCity);
	if(show_default)
	{
		ccidSelect.change();
	}
    cidSelect.change(function() {
        var currId = parseInt(cidSelect.find('option:selected').attr("ref"));
        $.initCities(currId);
    });
};

$.initCities = function(provId, defaultCity) {
    var hasDefaultCity = (typeof(defaultCity) != 'undefined');
    if(cidSelect.val() != '' && provId >= 0) {
        var cities = $._cityInfo[provId].c;
        var citieIds = $._cityInfo[provId].pid;
        var cityHtml = '';

  //      cityHtml += '<option value="">请选择</option>';
		if(cities.length>0)
		{
			for(var i = 0; i < cities.length; i++) {
				cityHtml += '<option value="' + citieIds[i] + '"' + ((hasDefaultCity && citieIds[i] == defaultCity) ? ' selected="selected"' : '') + '>' + cities[i] + '</option>';
			}
			ccidSelect.html(cityHtml);
		}
		else{
			ccidSelect.html('<option value="">尚未新建</option>');
		}
    } else {
        ccidSelect.html('<option value="">请先选择</option>');
    }
};

function getProvRow(province){
    var provinceId = '';
    for(var i = 0; i < $._cityInfo.length; i++){
        if($._cityInfo[i].nid == province ){
            provinceId = i;
            break;
        }
    }
    return provinceId;
}
