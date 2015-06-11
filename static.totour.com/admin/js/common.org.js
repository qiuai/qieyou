$(function(){

	/*
    var $SliderDl = $(".slider dl");
    var $SliderDt = $(".slider dt");
    var $SliderDd = $(".slider dd");

    $SliderDd.click(function(){
        $SliderDd.removeClass("current");
        $(this).addClass("current");
    });*/
	
	var changeProductBtn = $(".changeProductBtn");
	changeProductBtn.click(function(){
		var pid = $(this).attr('ref');
		var change = $(this).attr('change');
		var word = '';
		switch(change){case 'N': word = '下架';break;case 'Y': case 'T': word = '上架';break;case 'D': word = '删除';break;}
		var layershow = $.layer({
			title:[''+word+''],
			shade : [0.4 , "#000" , true],
			area: ['auto','auto'],
			dialog: {
				msg: '您确定要'+word+'此商品？'+(change=='D'?'<font color="red">（删除之后无法恢复）</font>':''),
				btns: 2,                    
				type: 4,
				btn: ['确定','取消'],
				yes: function(){
					$.ajax({
						url: baseUrl+"product/changeState",
						type: "POST",
						data:{state:change,pid:pid},
						success: function(data){
							if(data.code == 1){
								layer.msg('操作成功！' ,1,1);
								window.location.reload();
							}
							else {
								layer.alert(data.msg ,8,word+"失败");
							}
						}
					});
				}, no: function(){
					layer.close(layershow);
				}
			}
		});
	});
	/*
    //tab切换
    var $tabBox = $(".tabBox");
    var $tabLi = $(".tabBox .tab").find("li");
    var $tabContent = $(".tabBox .tabContent");
    $tabLi.click(function(){
        var index = $(this).index();
        $tabLi.removeClass("current");
        $(this).addClass("current");
        $tabContent.hide();
        $tabContent.eq(index).show();
    });*/

	/**查看驿栈老板信息**/
	var viewInnsInfo = $('.viewInnsInfo');
	viewInnsInfo.click(function(){
		var innsId = $(this).attr('ref');
		$.ajax({
			url: baseUrl+"destination/getInnsInfo",
			data:{innsid:innsId},
			cache: false,
			success: function(data){
				if(data.code == 1){
					layer.alert(data.msg ,8,"查看失败");
				}
				else {
					$.layer({
						shade : [0.4 , "#000" , true],
						type : 1,
						area : ['auto','auto'],
						title : false,
						page : { html : '<div class="viewInnsInfoDom">'+data+'</div>'},
						close : function(index){
							layer.close(index);
						}
					});
				}
			}
		});
	});
	/**查看用户信息**/
	var viewUserInfo = $('.viewUserInfo');
	viewUserInfo.click(function(){
		var userId = $(this).attr('ref');
		
		$.ajax({
			url: baseUrl+"user/getUserInfo",
			data:{user_id:userId},
			cache: false,
			success: function(data){
				if(data.code == 1){
					layer.alert(data.msg ,8,"查看失败");
				}
				else {
					$.layer({
						shade : [0.4 , "#000" , true],
						type : 1,
						area : ['auto','auto'],
						title : false,
						page : { html : '<div class="viewUserInfoDom">'+data+'</div>'},
						close : function(index){
							layer.close(index);
						}
					});
				}
			}
		});
	});

    $('.addProductBtn').click(function(){
		var sid = $(this).attr('ref');
		window.open(baseUrl + "product/add?sid="+sid);
      //  window.location.href = baseUrl + "product/add?sid="+sid;
    });

    $(document).on('click','.close',function(){
        var index = layer.getIndex(this);
        layer.close(index);
    });
	
});

/**开始时间与结束时间选择判断**/
function WdataOnPick(data)
{
	var startTime = datetimeToUnix($('#startTime').val()+" 00:00:00");
	var endTime = datetimeToUnix($('#endTime').val()+" 00:00:00");
	var timeTips = $("#timeTips");
//	timeTips.attr('style','');
//	timeTips.stop(true);
	if(startTime>endTime){
		timeTips.show().addClass("tips-warn");
		if (data=="st"){
			timeTips.html("<i class='tips-ico'></i><p>开始日期须小于结束日期</p>");
		//	$('#endTime').val($("#startTime").val());
		}
		else{
			timeTips.html("<i class='tips-ico'></i><p>结束日期须大于开始日期</p>");
		//    $('#startTime').val(getMoreDate(-1,$("#endTime").val()));
		//	$('#startTime').val($("#endTime").val());
		}
		timeTips.fadeOut(5000);
	}
	else{
		timeTips.hide();
	}

};

/********************* Select all Checkbox *******************
function setChecked(obj)
{
    var check = document.getElementsByName("id[]");
    for (var i=0; i<check.length; i++)
    {
        check[i].checked = obj.checked;
    }
}**/

//********************* close Window *********************/
function closeWindow(){
    window.open('','_parent','');
    window.close();
}

/********************* 替换br成\r\n ******************
function replaceBrToEnter(str){
    var reg=new RegExp("<br/>","g");
    str = str.replace(reg,"\r\n");
    return str;
}***/

/********************* 替换\r\n成br ********************
function replaceEnterToBr(str){
    var reg=new RegExp("\n","g");
    var reg1=new RegExp("\r\n","g");

    str = str.replace(reg,"<br/>");
    str = str.replace(reg1,"<br/>");

    return str;
}*/


/**日期转时间戳**/
function datetimeToUnix(datetime){
    var tmp_datetime = datetime.replace(/:/g,'-');
    tmp_datetime = tmp_datetime.replace(/ /g,'-');
    var arr = tmp_datetime.split("-");
    var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
    return parseInt(now.getTime()/1000);
}

/**时间戳转日期**/
function unixToDatetime(unix) {
    var now = new Date(parseInt(unix) * 1000);
    return now.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
}

/** 获取当前日期的前几天或者后几天
 * 入参：value为天数，可以有正负
 * date为日期：格式2013,01,10
 * 出参：currMoreTime，yyyy-MM-dd**/

function getMoreDate(value,date){
    if(date=='0'){
        var currTime = new Date();
    }
    else{
        currTime = new Date(getNewDate(date));
    }
    var currDay = currTime.getDate();
    var currMoreTime = new Date(currTime);
    currMoreTime.setDate(currDay+value);
    var currMoreMonth = currMoreTime.getMonth()+1;
    currMoreMonth = currMoreMonth<10?'0'+currMoreMonth : currMoreMonth;
    var currMoreDay = currMoreTime.getDate();
    currMoreDay = currMoreDay<10?'0'+currMoreDay : currMoreDay;
    currMoreTime = currMoreTime.getFullYear()+'-'+currMoreMonth+'-'+currMoreDay;
    return currMoreTime;
}

/**解决IE6，7下new Date方法返回NaN的问题
 * @param str 日期字符串
 * @return 2013-01-17
 * **/
function getNewDate(str) {
    str = str.split('-');
    var date = new Date();
    date.setUTCFullYear(str[0], str[1] - 1, str[2]);
    date.setUTCHours(0, 0, 0, 0);
    return date;
}

//********************* Page Function *********************//
 /*构造分页*/
//3个基本参数,1个模板参数(也就是html代码)，1个配置参数
//当前页currpage
//总页数total
//url地址url
//html标签template
//配置参数说明{ left: 3, right: 2, allShow : false, allShowNum: 10 }
//left表示当前页左边的个数、right表示当前页右边的个数、allShow表示总页数在某个数字以内全部显示不用省率号、allShowNum表示某个数字，默认是10

/**使用方法
//获取当前页
 var temp = window.location.href;
 temp = temp.substr(temp.indexOf('currpage='),temp.length);
 temp = temp.substr(temp.indexOf('=')+1,temp.length)
 var currpage = isNaN(temp) ? 1 : temp;
 //var currpage = 1;
 //总页数
 var total = 100;
 //分页的url地址
 var url = 'jquery分页构造器.html?currpage={1}';
 //模板样式
 var template = '<li{currpage}><a href="{url}">{page}</a></li>';
 //省率号模板样式
 var template_ellipsis = '<li>{page}</li>';
 //分页对象的参数设置,各个参数的作用可以查看对象注释
 var options = {};
 options.first = 1;
 options.last = 1;
 options.left = 3;
 options.right =2;
 options.allShowNum = 0;//开启10以内全显示,0表示不开启
 //$("#fenye").html(createPage(currpage,total,url,options));
 //创建一个分页对象实例
 var testObj = new creatPageObject(currpage,total,url,options,template,template_ellipsis);
 //在页面上显示分页
 $("#fenye").html(testObj.createPage());
 **/
/*构造分页对象*/
var creatPageObject = function(currpage,total,url,options,template,template_ellipsis){
    this.currpage = parseInt(currpage);
    if(total ==0){
        this.total = 1;
    }
    else{
        this.total = Math.ceil(total); //向上取整
    }
    this.url = url;
    this.template = template || '<li><a href="{url}">{page}</a></li>';
    this.template_ellipsis = template_ellipsis || '<li{currpage}><a href="javascript:void(0);">{page}</a></li>';
    this.options = options || {};
    this.options.first = this.options.first || 2;
    this.options.last = this.options.last || 2;
    this.options.left = this.options.left || 3;
    this.options.right = this.options.right || 2;
    this.options.allShowNum = this.options.allShowNum || 0;
    this.error = false;
    this.msg = '初始化过程中发生了以下错误：\n';
    this.init();
};
creatPageObject.prototype.init = function(){
    if(!this.isInteger(this.currpage)){
        this.error = true;
        this.msg += '当前页必须是正整数\n';
    }
    if(!this.isInteger(this.total)){
        this.error = true;
        this.msg += '总页数必须是正整数\n';
    }
    if(!this.isInteger(this.options.left)){
        this.error = true;
        this.msg += 'options参数中的left必须是正整数\n';
    }
    if(!this.isInteger(this.options.right)){
        this.error = true;
        this.msg += 'options参数中的right必须是正整数\n';
    }
    if(!this.isInteger(this.options.allShowNum)){
        this.error = true;
        this.msg += 'options参数中的allShowNum必须是正整数\n';
    }
    if(!this.isInteger(this.options.first)){
        this.error = true;
        this.msg += 'options参数中的first必须是正整数\n';
    }
    if(!this.isInteger(this.options.last)){
        this.error = true;
        this.msg += 'options参数中的last必须是正整数\n';
    }
    if(this.error){
        this.show(this.msg);
        return;
    }
    if(this.currpage>this.total){
        this.error = true;
        this.msg += '当前页数不能大于总页数\n';
    }
    if(this.error){
        this.show(this.msg);
        return;
    }
};
creatPageObject.prototype.isInteger = function(num){
    if(isNaN(num)) return false;
    else{
        var regExp = new RegExp('^\\d+$','g');
        if(!regExp.test(num)){
            return false;
        }
    }
    return true;
};
creatPageObject.prototype.show = function(msg){
    alert(msg);
};
creatPageObject.prototype.createPage = function(){
    if(this.error){
        //this.show(this.msg);
        return 0;
    }
    var htmlstring = '';
    var data = { url:'', page: '', currpage: '' };
    //添加上一页
    //if(this.currpage != 1) htmlstring += '<li><a href="'+this.url.replace(/{.*}/g,this.currpage-1)+'">上一页</a></li>';
    if(this.currpage > 1) {
        data.url = this.url.replace(/{.*}/g,this.currpage-1);
        data.page = '« Prev';
        htmlstring += this.replaceTemplate(this.template,data);
    }
    if(this.options.allShowNum){
        if(this.total <= this.options.allShowNum) htmlstring += this.showPage1();
        else htmlstring += this.showPage2();
    }
    else {
        htmlstring += this.showPage2();
    }
    //添加下一页
    //if(this.currpage < this.total) htmlstring += '<li><a href="'+this.url.replace(/{.*}/g,this.currpage+1)+'">下一页</a></li>';
    if(this.currpage < this.total) {
        data.url = this.url.replace(/{.*}/g,this.currpage+1);
        data.page = 'Next »';
        htmlstring += this.replaceTemplate(this.template,data);
    }
    htmlstring = '<ul>'+ htmlstring + '</ul>';
    return htmlstring;
};
creatPageObject.prototype.showPage1 = function(){
    var htmlstring = '';
    var data = { url:'', page: '', currpage: '' };
    for (var i=1; i<=this.total; i++){
        //if(this.currpage==i) htmlstring += '<li class="hby_curr"><a href="'+this.url.replace(/{.*}/g,i)+'">'+ i +'</a></li>';
        //else htmlstring += '<li><a href="'+this.url.replace(/{.*}/g,i)+'">'+ i +'</a></li>';
        data.url = this.url.replace(/{.*}/g,i);
        data.page = i;
        if(this.currpage==i) data.currpage = ' class="current"';
        else data.currpage = '';
        htmlstring += this.replaceTemplate(this.template,data);
    }
    return htmlstring;
};
creatPageObject.prototype.showPage2 = function(){
    var htmlstring = '';
    var data = { url:'', page: '', currpage: '' };
    //开始部分
    for(var m=1; m<=this.options.first; m++){
        if(m<=this.total){
            //if(m==this.currpage) htmlstring += '<li class="hby_curr"><a href="'+this.url.replace(/{.*}/g,m)+'">'+ m +'</a></li>';
            //else htmlstring += '<li><a href="'+this.url.replace(/{.*}/g,m)+'">'+ m +'</a></li>';
            data.url = this.url.replace(/{.*}/g,m);
            data.page = m;
            if(this.currpage==m){
                data.currpage = ' class="current"';
                htmlstring += this.replaceTemplate(this.template_ellipsis,data);
            }
            else {
                data.currpage = ' ';
                htmlstring += this.replaceTemplate(this.template,data);
            }
        }
    }
    //出现省率号
    if(this.currpage-this.options.left-this.options.first>1){
        if(this.currpage-this.options.left-this.options.first>2){
            data.url = '';
            data.page = '...';
            data.currpage = '';
            htmlstring += this.replaceTemplate(this.template_ellipsis,data);
        }
        else{
            data.url = this.url.replace(/{.*}/g,this.options.first+1);
            data.page = this.options.first+1;
            data.currpage = '';
            htmlstring += this.replaceTemplate(this.template,data);
        }
    }
    //当前页附近循环
    if(this.total-this.options.last>this.options.first){
        var i = (this.currpage-this.options.left) > this.options.first ? (this.currpage-this.options.left) : (this.options.first+1);
        var i_big = (this.currpage+this.options.right) < (this.total - this.options.last) ? (this.currpage+this.options.right) : (this.total - this.options.last);
        for( i; i<=i_big; i++ ){
            data.url = this.url.replace(/{.*}/g,i);
            data.page = i;
            if(this.currpage==i) {
                data.currpage = ' class="current"';
                htmlstring += this.replaceTemplate(this.template_ellipsis,data);
            }
            else {
                data.currpage = '';
                htmlstring += this.replaceTemplate(this.template,data);
            }

        }
    }
    //出现省率号
    if(this.total-this.options.last>this.currpage+this.options.right){
        if(this.total-this.options.last>this.currpage+this.options.right+1){
            data.url = '';
            data.page = '...';
            data.currpage = '';
            htmlstring += this.replaceTemplate(this.template_ellipsis,data);
        }
        else{
            data.url = this.url.replace(/{.*}/g,this.currpage+this.options.right+1);
            data.page = this.currpage+this.options.right+1;
            data.currpage = '';
            htmlstring += this.replaceTemplate(this.template,data);
        }
    }
    //结尾部分
    if(this.options.first < this.total){
        var n = (this.total-this.options.last) > this.options.first ? (this.total-this.options.last+1) : (this.options.first + 1);
        for(n; n<=this.total; n++){
            if(n>this.options.first){
                //if(n==this.currpage) htmlstring += '<li class="hby_curr"><a href="'+this.url.replace(/{.*}/g,n)+'">'+ n +'</a></li>';
                //else htmlstring += '<li><a href="'+this.url.replace(/{.*}/g,n)+'">'+ n +'</a></li>';
                data.url = this.url.replace(/{.*}/g,n);
                data.page = n;
                if(this.currpage==n) {
                    data.currpage = ' class="current"';
                    htmlstring += this.replaceTemplate(this.template_ellipsis,data);
                }
                else {
                    data.currpage = '';
                    htmlstring += this.replaceTemplate(this.template,data);
                }

            }
        }
    }
    return htmlstring;
};
creatPageObject.prototype.replaceTemplate = function(template,data){
    var htmlstring = '';
    template = decodeURI(template);
    var temp = template;
    var reg = new RegExp('\{(.*?)\}','g');
    while((result = reg.exec(template)) != null){
        var reg1 = new RegExp(result[0],'g');
        if(result[1].indexOf('url:') != -1){
            result[1] = result[1].replace('url:','');
            temp = temp.replace(reg1,encodeURI(data[result[1]]));
        }
        else{
            temp = temp.replace(reg1,data[result[1]]);
        }
    }
    htmlstring += temp;
    htmlstring = htmlstring.replace(/undefined/g,'');
    return htmlstring;
};