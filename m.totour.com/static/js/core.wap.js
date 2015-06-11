/**
 * 2014-03-19 code by Sanonz <sanonz@126.com>
 */


// init
(function(){
	Array.isArray = Array.isArray || function(arr){
		return Object.prototype.toString.call(arr) === '[object Array]';
	};

	Object.isObject = function(obj){
		return Object.prototype.toString.call(obj) === '[object Object]';
	};

	Date.prototype.format = function(format) {
		/* 
		 * eg:format="yyyy-mm-dd hh:ii:ss";
		 */
		var o = {
			"m+": this.getMonth() + 1,
			"d+": this.getDate(),
			"h+": this.getHours(),
			"i+": this.getMinutes(),
			"s+": this.getSeconds(),
			"q+": Math.floor((this.getMonth() + 3) / 3),
			"S": this.getMilliseconds()
		}
		if(/(y+)/.test(format)) {
			format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
		}
		for(var k in o) {
			if (new RegExp("(" + k + ")").test(format)) {
				format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
			}
		}
		return format;
	}
})();

window.QY = window.QY || {};
window.QY.UI = window.QY.UI || {};
window.QY.util = window.QY.util || {};

// QY.UI
(function(UI){
	var elem = {},
		data = {};
	UI.share = function(options){
		elem.share = $('<div class="ui-share">' +
		    '<a target="_blank" class="li-item" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + options.url + '&title=' + options.title + '&pics=' + options.pic + '"><img alt="" src="' + QY.domain.resource + 'images/zone.png"/>QQ空间</a>' +
		    '<a target="_blank" class="li-item" href="http://v.t.sina.com.cn/share/share.php?url=' + options.url + '&amp;title=' + options.title + '&amp;pic=' + options.pic + '"><img alt="" src="' + QY.domain.resource + 'images/sina.png"/>新浪微博</a>' +
		    '<a target="_blank" class="li-item" href="http://share.v.t.qq.com/index.php?c=share&amp;a=index&amp;url=' + options.url + '&amp;title=' + options.title + '&amp;pic=' + options.pic + '"><img alt="" src="' + QY.domain.resource + 'images/qqweibo.png"/>腾讯微博</a>' +
		'</div>').appendTo('body');
		elem.shareBg = $('<div class="ui-share-bg"></div>').appendTo('body');
		elem.shareBg.click(function(){
			elem.share.remove();
			elem.shareBg.remove();
		});
	};

	UI.scrollTop = function(){
		var h = document.body.scrollTop, t = 1, timer;
		timer = setInterval(function(){
			h == 0 && clearInterval(timer);
			window.scrollTo(0, h);
			t += 1;
			h -= 5 * t;
			h = 0 > h ? 0 : h;
		}, 20);
	};
})(QY.UI);

// QY.util.base
(function(util){
	util.url = function(action, param){
		if( Object.isObject(action) ){
			param = action.param;
			action = action.action;
		}
		action = action || '';
		param = param ? '?' + param : '';
		return ('http://' + location.host + '/' + action + param);
	};

	util.getParam = function(n, t) {
	    var i = new RegExp("(?:^|\\?|#|&)" + n + "=([^&#]*)(?:$|&|#)", "i"), o = i.exec(t || location.href);
	    return o ? decodeURIComponent(o[1]) : ""
	}

	util.jumpLogin = function(backurl){
		window.location.href = QY.domain.base + 'login?url=' + encodeURIComponent((backurl || window.location.href));
	};

	util.checkLogin = function(code){
		if( code == '1001' ){
			util.jumpLogin();
			return false;
		}
		return true;
	};

	util.isLogin = function(){
		return !!+util.cookie.get('logined');
	};

	util.redirect = function(url, com){
		com = com === undefined ? true : false;
		window.location.href = com ? QY.domain.base + url : url;
	};

	util.each = function(obj, iterator, fn){
        var flag, isFn = typeof fn === 'function';
        if( typeof obj !== 'object' ){
            isFn && fn();
        } else {
            for(var k in obj){
                flag = true;
                if( obj.hasOwnProperty(k) && iterator(obj[k], k) === false )
                    break;
            }
        }
        if( isFn ) flag || fn();
    };

    util.getGreatCircleDistance = function(lat1, lng1, lat2, lng2){
    	if( lat1 === undefined || lng1 === undefined || lat2 === undefined || lng2 === undefined ) return '';
    	var PI = Math.PI, radLat1 = lat1*PI/180, radLat2 = lat2*PI/180, a = radLat1 - radLat2, b = lng1*PI/180 - lng2*PI/180,
			s = 2*Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) + Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
        s = Math.round(s*6378137);
        s = s > 1000 ? Math.round(s/1000) + 'km' : s + 'm';
        return s;
    };

    util.formatBytes = function(size, type){
	    var rs, units = ['B', 'KB', 'MB', 'GB', 'TB'];
	    for(var i = 0, len = units.length; i < len && size >= 1024; i++){
	      size /= 1024;
	    }
	    rs = [+size.toFixed(2), units[i]];
	    return type ? rs : rs.join('');
	}

	util.changeImageSize = function(src, size){
		src = String(src);
		return size ? src.replace(/^(.*?)s?m?(\.\w+)$/, '$1' + size + '$2') : src.replace(/(.*?)(s|m)(\.\w+)/, '$1$3');
	}

	util.extendElement = function(source, elem){
		if( !elem ){
			elem = source;
			source = {};
		}
		util.each(elem, function(v){
			source[String(v).substr(1).replace(/\_(\w)/g, function(e1, e2){
				return e2.toUpperCase();
			})] = $(v);
		});

		return source;
	};

	util.extendValue = function(source, elem){
		if( !elem ){
			elem = source;
			source = {};
		}
		util.each(elem, function(v, k){
			source[k] = $.trim(v.val());
		});

		return source;
	};

	util.setImageMiddles = function(container){
		container.find('img[data-src]').each(function(){
			var img = $(this), p = img.parent(), src = img.attr('data-src'), c = new Image(), ww = p.width(), wh = p.height();
			c.onload = function(){
				var iw = this.width, ih = this.height;
				img.css({width: 'auto', height: 'auto'});
				if( Math.abs(iw - ww) < Math.abs(ih - wh) ){
					img.css({width: ww});
				} else {
					img.css({height: wh});
				}
				img.attr('src', src);
				img.css({
					marginTop: (wh - img.height()) / 2,
					marginLeft: (ww - img.width()) / 2
				});
			};
			c.src = src;
		});
	};

	util.setImageMiddle = function(img){
		var img = $(img), p = img.parent(), ww = p.width(), wh = p.height(), src = img.attr('src'), c = new Image();
		c.onload = function(){
			var iw = this.width, ih = this.height;
			img.css({width: 'auto', height: 'auto'});
			if( Math.abs(iw - ww) < Math.abs(ih - wh) ){
				img.css({width: ww});
			} else {
				img.css({height: wh});
			}
			img.attr('src', src);
			img.css({
				marginTop: (wh - img.height()) / 2,
				marginLeft: (ww - img.width()) / 2
			});
		};
		c.src = src;
	};
})(this.QY.util);

// QY.util.popup
(function(util){
	util.popup = {
		show: function(msg, type, duration){
			var node = $('<div class="popup popup-' + type + '"><div class="popup-inner">' + msg + '</div></div>').appendTo('body');
			setTimeout(function(){
				node.addClass('fadeIn');
			}, 10);
			setTimeout(function(){
				node.addClass('fadeOut');
				setTimeout(function(){
					node.remove();
				}, 500);
			}, !duration && duration !== 0 ? 3000 : duration);
		},
		success: function(msg){
			this.show(msg, 'success');
		},
		error: function(msg){
			this.show(msg, 'error');
		}
	};
})(this.QY.util);

// QY.util.request
(function(util){
	util.request = function(options){
		options = $.extend({
			type: 'POST',
			dataType: 'json',
			error: function(){
				QY.util.popup.error('网络出错');
			}
		}, options);

		return $.ajax(options);
	};
})(this.QY.util);

// QY.util.validate
QY.util.validate = QY.util.validate || {};
(function(validate){
	validate.regExp = {
		mobile: /^1\d{10}$/,
		email: /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/,
		idcard: /^\d{17}(\d|X)$/
	};
	validate.mobile = function(mobile){
		return validate.regExp.mobile.test(mobile);
	};
	validate.email = function(email){
		return validate.regExp.email.test(email);
	};
	validate.idcard = function(idcard){
		return validate.regExp.idcard.test(idcard);
	};
})(this.QY.util.validate);

// QY.util
(function(util){
	util.cookie = {
        get: function(n){
            var m = document.cookie.match(new RegExp( "(^| )"+n+"=([^;]*)(;|$)"));
            return !m ? "" : decodeURIComponent(m[2]);
        },
        set: function(name, value, domain, path, hour){
            if(!name || !value){
                return false;
            }
            var expire = new Date();
            expire.setTime(expire.getTime() + (hour?3600000 * hour:30*24*60*60*1000));
            document.cookie = name + "=" + value + "; " + "expires=" + expire.toGMTString()+"; path="+ (path ? path :"/")+ "; " + (domain ? ("domain=" + domain + ";") : "");
            return true;
        },
        remove: function(name, domain, path){
            document.cookie = name + "=; expires=Mon, 26 Jul 1997 05:00:00 GMT; path="+ (path ? path :"/")+ "; " + (domain ? ("domain=" + domain + ";") : "");
        },
        clear: function(){
            var rs = document.cookie.match(new RegExp("([^ ;][^;]*)(?=(=[^;]*)(;|$))", "gi"));
            for (var i in rs){
                document.cookie = rs[i] + "=;expires=Mon, 26 Jul 1997 05:00:00 GMT; path=/; ";
            }
        }
    };

    util.localStorage = {
    	localStorage: window.localStorage,
    	check: function(){
    		return this.localStorage ? true: false;
    	},
    	set: function(key, value){
    		return this.check() ? this.localStorage.setItem(key, value) : null;
    	},
    	get: function(key){
    		return this.check() ? this.localStorage.getItem(key) : null;
    	},
    	remove: function(key){
    		return this.check() ? this.localStorage.removeItem(key) : null;
    	}
    };

    util.sessionStorage = {
    	sessionStorage: window.sessionStorage,
    	check: function(){
    		return this.sessionStorage ? true: false;
    	},
    	set: function(key, value){
    		return this.check() ? this.sessionStorage.setItem(key, value) : null;
    	},
    	get: function(key){
    		return this.check() ? this.sessionStorage.getItem(key) : null;
    	},
    	remove: function(key){
    		return this.check() ? this.sessionStorage.removeItem(key) : null;
    	}
    };
})(this.QY.util);

// QY.util.template & author: Sanonz <sanonz@126.com>
(function(util){
    var cache = {};

    function compileTpl(id, tpl){
        var elem, start = 0, tmp = "", t;
        if( tpl ){
            tpl = String(tpl);
        } else {
            elem = document.getElementById(id);
            if( !elem ) return;
            tpl = elem.tagName == "INPUT" || elem.tagName == "TEXTAREA" ? elem.value : elem.innerHTML;
            elem.parentNode.removeChild(elem);
        }
        tpl = tpl.replace(/[\n\t\r]/g, "");
        tpl.replace(/<%(.+?)%>/g, function(e1, e2, e3, e4){
            t = e4.substr(start, e3 - start).replace(/(^\s+|\s+$)/g, '');
            t && (tmp += "_.push('" + t + "');");
            tmp += parseTpl(e2);
            start = e3 + e1.length;
        });
        t = tpl.substr(start);
        t && (tmp += "_.push('" + t + "');");
        return tmp;
    }

    function parseTpl(str){
    	var match;
        if( match = str.match(/^=\s*(.+?)\s*$/) ) return "_.push(" + match[1] + ");";
        if( match = str.match(/^\s*(if|else\s*if)\s+(.+?)$/) ) return (match[1] == "if" ? "if" : "}else if") + "(" + match[2] + "){";
        if( match = str.match(/^\s*switch\s+(.+?)$/) ) return "switch(" + match[1] + "){";
        if( match = str.match(/^\s*case\s+(.+?)$/) ) return "case " + match[1] + ":";
        if( match = str.match(/^\s*include\s+(.+?)$/) ) return loadTemplate(match[1], null, true);
        if( match = str.match(/^\s*each\s+(\S+)\s+(\S+)(\s+(\S+))?\s*/) ) return "QY.util.each(" + match[1] + ",function(" + (match[4] ? match[4] + "," : "") + match[2] + "){";
        if( /^\s*else\s*$/.test(str) ) return "}else{";
        if( /^\s*eachElse\s*$/.test(str) ) return "},function(){";
        if( /^\s*\/each\s*$/.test(str) ) return "});";
        if( /^\s*break\s*$/.test(str) ) return "return false;";
        if( /^\s*\/case\s*$/.test(str) ) return "break;";
        if( /^\s*continue\s*$/.test(str) ) return "return true;";
        if( /^\s*\/(if|switch)\s*$/.test(str) ) return "}";
        return str + ";";
    }

    function loadTemplate(id, tpl, wrap){
    	var tmp = wrap ? "" : "var _=[];with(vars){";
    	tmp += cache[id] || (cache[id] = compileTpl(id, tpl));
    	wrap || (tmp += "};return _.join('');");
        return tmp;
    }

    util.template = function(id, args, tpl){
        return Function("vars", loadTemplate(id, tpl))(args);
    };
})(this.QY.util);

// Zepto Fn
(function($){
	$.fn.slideUp = function(setting, duration){
		slide(this, 'Up', setting, duration);
	};
	$.fn.slideRight = function(setting, duration){
		slide(this, 'Right', setting, duration);
	};
	$.fn.slideDown = function(setting, duration){
		slide(this, 'Down', setting, duration);
	};
	$.fn.slideLeft = function(setting, duration){
		slide(this, 'Left', setting, duration);
	};
	function slide(self, orientation, setting, duration){
		var timer = {}, map = {Up: 'Down', Right: 'Left', Down: 'Up', Left: 'Right'};
		duration = parseInt(duration) || 499;
		if( setting ){
			clearTimeout(timer.c);
			self.removeClass('slide' + map[orientation]).addClass('slide' + orientation).show();
			timer.o = setTimeout(function(){
				self.removeClass('slide' + orientation);
			}, duration);
		} else {
			clearTimeout(timer.o);
			self.removeClass('slide' + map[orientation]).addClass('slide' + orientation);
			timer.c = setTimeout(function(){
				self.removeClass('slide' + orientation).hide();
			}, duration);
		}
	}
})(Zepto);