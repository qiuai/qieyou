$(function(){var c=$(".changeProductBtn");c.click(function(){var d=$(this).attr("ref");var g=$(this).attr("change");var f="";switch(g){case"N":f="下架";break;case"Y":case"T":f="上架";break;case"D":f="删除";break}var e=$.layer({title:[""+f+""],shade:[0.4,"#000",true],area:["auto","auto"],dialog:{msg:"您确定要"+f+"此商品？"+(g=='D'?'<font color="red">（删除之后无法恢复）</font>':''),btns:2,type:4,btn:["确定","取消"],yes:function(){$.ajax({url:baseUrl+"product/changeState",type:"POST",data:{state:g,pid:d},success:function(h){if(h.code==1){layer.msg("操作成功！",1,1);window.location.reload()}else{layer.alert(h.msg,8,f+"失败")}}})},no:function(){layer.close(e)}}})});var b=$(".viewInnsInfo");b.click(function(){var d=$(this).attr("ref");$.ajax({url:baseUrl+"destination/getInnsInfo",data:{innsid:d},cache:false,success:function(e){if(e.code==1){layer.alert(e.msg,8,"查看失败")}else{$.layer({shade:[0.4,"#000",true],type:1,area:["auto","auto"],title:false,page:{html:'<div class="viewInnsInfoDom">'+e+"</div>"},close:function(f){layer.close(f)}})}}})});var a=$(".viewUserInfo");a.click(function(){var d=$(this).attr("ref");$.ajax({url:baseUrl+"user/getUserInfo",data:{user_id:d},cache:false,success:function(e){if(e.code==1){layer.alert(e.msg,8,"查看失败")}else{$.layer({shade:[0.4,"#000",true],type:1,area:["auto","auto"],title:false,page:{html:'<div class="viewUserInfoDom">'+e+"</div>"},close:function(f){layer.close(f)}})}}})});$(".addProductBtn").click(function(){var d=$(this).attr("ref");window.open(baseUrl+"product/add?sid="+d)});$(document).on("click",".close",function(){var d=layer.getIndex(this);layer.close(d)})});function WdataOnPick(d){var c=datetimeToUnix($("#startTime").val()+" 00:00:00");var b=datetimeToUnix($("#endTime").val()+" 00:00:00");var a=$("#timeTips");if(c>b){a.show().addClass("tips-warn");if(d=="st"){a.html("<i class='tips-ico'></i><p>开始日期须小于结束日期</p>")}else{a.html("<i class='tips-ico'></i><p>结束日期须大于开始日期</p>")}a.fadeOut(5000)}else{a.hide()}}function closeWindow(){window.open("","_parent","");window.close()}function datetimeToUnix(d){var c=d.replace(/:/g,"-");c=c.replace(/ /g,"-");var a=c.split("-");var b=new Date(Date.UTC(a[0],a[1]-1,a[2],a[3]-8,a[4],a[5]));return parseInt(b.getTime()/1000)}function unixToDatetime(a){var b=new Date(parseInt(a)*1000);return b.toLocaleString().replace(/年|月/g,"-").replace(/日/g," ")}function getMoreDate(f,e){if(e=="0"){var c=new Date()}else{c=new Date(getNewDate(e))}var g=c.getDate();var b=new Date(c);b.setDate(g+f);var d=b.getMonth()+1;d=d<10?"0"+d:d;var a=b.getDate();a=a<10?"0"+a:a;b=b.getFullYear()+"-"+d+"-"+a;return b}function getNewDate(b){b=b.split("-");var a=new Date();a.setUTCFullYear(b[0],b[1]-1,b[2]);a.setUTCHours(0,0,0,0);return a}var creatPageObject=function(c,e,b,a,d,f){this.currpage=parseInt(c);if(e==0){this.total=1}else{this.total=Math.ceil(e)}this.url=b;this.template=d||'<li><a href="{url}">{page}</a></li>';this.template_ellipsis=f||'<li{currpage}><a href="javascript:void(0);">{page}</a></li>';this.options=a||{};this.options.first=this.options.first||2;this.options.last=this.options.last||2;this.options.left=this.options.left||3;this.options.right=this.options.right||2;this.options.allShowNum=this.options.allShowNum||0;this.error=false;this.msg="初始化过程中发生了以下错误：\n";this.init()};creatPageObject.prototype.init=function(){if(!this.isInteger(this.currpage)){this.error=true;this.msg+="当前页必须是正整数\n"}if(!this.isInteger(this.total)){this.error=true;this.msg+="总页数必须是正整数\n"}if(!this.isInteger(this.options.left)){this.error=true;this.msg+="options参数中的left必须是正整数\n"}if(!this.isInteger(this.options.right)){this.error=true;this.msg+="options参数中的right必须是正整数\n"}if(!this.isInteger(this.options.allShowNum)){this.error=true;this.msg+="options参数中的allShowNum必须是正整数\n"}if(!this.isInteger(this.options.first)){this.error=true;this.msg+="options参数中的first必须是正整数\n"}if(!this.isInteger(this.options.last)){this.error=true;this.msg+="options参数中的last必须是正整数\n"}if(this.error){this.show(this.msg);return}if(this.currpage>this.total){this.error=true;this.msg+="当前页数不能大于总页数\n"}if(this.error){this.show(this.msg);return}};creatPageObject.prototype.isInteger=function(b){if(isNaN(b)){return false}else{var a=new RegExp("^\\d+$","g");if(!a.test(b)){return false}}return true};creatPageObject.prototype.show=function(a){alert(a)};creatPageObject.prototype.createPage=function(){if(this.error){return 0}var b="";var a={url:"",page:"",currpage:""};if(this.currpage>1){a.url=this.url.replace(/{.*}/g,this.currpage-1);a.page="« Prev";b+=this.replaceTemplate(this.template,a)}if(this.options.allShowNum){if(this.total<=this.options.allShowNum){b+=this.showPage1()}else{b+=this.showPage2()}}else{b+=this.showPage2()}if(this.currpage<this.total){a.url=this.url.replace(/{.*}/g,this.currpage+1);a.page="Next »";b+=this.replaceTemplate(this.template,a)}b="<ul>"+b+"</ul>";return b};creatPageObject.prototype.showPage1=function(){var c="";var b={url:"",page:"",currpage:""};for(var a=1;a<=this.total;a++){b.url=this.url.replace(/{.*}/g,a);b.page=a;if(this.currpage==a){b.currpage=' class="current"'}else{b.currpage=""}c+=this.replaceTemplate(this.template,b)
}return c};creatPageObject.prototype.showPage2=function(){var e="";var d={url:"",page:"",currpage:""};for(var a=1;a<=this.options.first;a++){if(a<=this.total){d.url=this.url.replace(/{.*}/g,a);d.page=a;if(this.currpage==a){d.currpage=' class="current"';e+=this.replaceTemplate(this.template_ellipsis,d)}else{d.currpage=" ";e+=this.replaceTemplate(this.template,d)}}}if(this.currpage-this.options.left-this.options.first>1){if(this.currpage-this.options.left-this.options.first>2){d.url="";d.page="...";d.currpage="";e+=this.replaceTemplate(this.template_ellipsis,d)}else{d.url=this.url.replace(/{.*}/g,this.options.first+1);d.page=this.options.first+1;d.currpage="";e+=this.replaceTemplate(this.template,d)}}if(this.total-this.options.last>this.options.first){var c=(this.currpage-this.options.left)>this.options.first?(this.currpage-this.options.left):(this.options.first+1);var b=(this.currpage+this.options.right)<(this.total-this.options.last)?(this.currpage+this.options.right):(this.total-this.options.last);for(c;c<=b;c++){d.url=this.url.replace(/{.*}/g,c);d.page=c;if(this.currpage==c){d.currpage=' class="current"';e+=this.replaceTemplate(this.template_ellipsis,d)}else{d.currpage="";e+=this.replaceTemplate(this.template,d)}}}if(this.total-this.options.last>this.currpage+this.options.right){if(this.total-this.options.last>this.currpage+this.options.right+1){d.url="";d.page="...";d.currpage="";e+=this.replaceTemplate(this.template_ellipsis,d)}else{d.url=this.url.replace(/{.*}/g,this.currpage+this.options.right+1);d.page=this.currpage+this.options.right+1;d.currpage="";e+=this.replaceTemplate(this.template,d)}}if(this.options.first<this.total){var f=(this.total-this.options.last)>this.options.first?(this.total-this.options.last+1):(this.options.first+1);for(f;f<=this.total;f++){if(f>this.options.first){d.url=this.url.replace(/{.*}/g,f);d.page=f;if(this.currpage==f){d.currpage=' class="current"';e+=this.replaceTemplate(this.template_ellipsis,d)}else{d.currpage="";e+=this.replaceTemplate(this.template,d)}}}}return e};creatPageObject.prototype.replaceTemplate=function(d,e){var f="";d=decodeURI(d);var a=d;var c=new RegExp("{(.*?)}","g");while((result=c.exec(d))!=null){var b=new RegExp(result[0],"g");if(result[1].indexOf("url:")!=-1){result[1]=result[1].replace("url:","");a=a.replace(b,encodeURI(e[result[1]]))}else{a=a.replace(b,e[result[1]])}}f+=a;f=f.replace(/undefined/g,"");return f};