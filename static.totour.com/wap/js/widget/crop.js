/**
 * Date:   2015-04-19 15:13
 * Author: ‏‏Sanonz‏‏ <sanonz@126.com>
 */
define('widget/crop', function(){
	var Crop = function(options){
		var self = this;
		this.options = extend({
			url: '/upload',
			formData: {},
			cropContainer: 'crop_container',
			cropWrapper: 'crop_wrapper',
			cropDark: 'crop_dark',
			cropLight: 'crop_light',
			cropBottom: 'crop_bottom',
			cropConfirm: 'crop_confirm',
			cropCancel: 'crop_cancel'
		}, options);
		this.data = {
			cache: {},
			original: {},
			point: {
				h: 0,
				ch: 0,
				one: {x: 0, y: 0},
				two: {x: 0, y: 0},
				move: {x: 0, y: 0},
				changed: {x: 0, y: 0}
			}
		};
		this.elem = {
			image: new Image(),
			cropContainer: document.getElementById(this.options.cropContainer),
			cropWrapper: document.getElementById(this.options.cropWrapper),
			cropDark: document.getElementById(this.options.cropDark),
			cropLight: document.getElementById(this.options.cropLight),
			cropBottom: document.getElementById(this.options.cropBottom),
			cropConfirm: document.getElementById(this.options.cropConfirm),
			cropCancel: document.getElementById(this.options.cropCancel)
		};
		this.data.ctx = {
			cropDark: this.elem.cropDark.getContext('2d'),
			cropLight: this.elem.cropLight.getContext('2d')
		};

		this.elem.image.onload = function(){
			self.init();
		};

		this.data.delta = {
			ww: window.innerWidth,
			wh: window.innerHeight - parseInt(getComputedStyle(this.elem.cropBottom).height) || 0
		};
		this.data.delta.wz = this.data.delta.ww > this.data.delta.wh ? this.data.delta.wh : this.data.delta.ww
		this.elem.cropDark.width = this.data.delta.ww;
		this.elem.cropDark.height = this.data.delta.wh;
		this.elem.cropLight.width = this.elem.cropLight.height = this.data.delta.wz;

		setStyle(this.elem.cropWrapper, {height: this.data.delta.wh + 'px'});
		this.elem.cropWrapper.addEventListener('touchstart', this.listener(), false);
		this.elem.cropWrapper.addEventListener('touchmove', this.listener(), false);
		this.elem.cropWrapper.addEventListener('touchend', this.listener(), false);
		this.elem.cropCancel.addEventListener('click', this.listener('handlerCancel'), false);
		this.elem.cropConfirm.addEventListener('click', this.listener('handlerConfirm'), false);

		/*window.addEventListener('resize', function(event){
			if( !self.elem.image.src ) return;
			extend(self.data.delta, {
				ww: window.innerWidth,
				wh: window.innerHeight - parseInt(getComputedStyle(self.elem.cropBottom).height) || 0
			});
			self.data.delta.wz = self.data.delta.ww > self.data.delta.wh ? self.data.delta.wh : self.data.delta.ww;
			self.elem.cropDark.width = self.data.delta.ww;
			self.elem.cropDark.height = self.data.delta.wh;
			self.elem.cropLight.width = self.elem.cropLight.height = self.data.delta.wz;
			self.init();
		});*/
	};

	Crop.prototype = {
		init: function(){
			var t,
				e = this.elem,
				d = this.data.delta;

			extend(d, {
				iw: this.elem.image.width,
				ih: this.elem.image.height
			});

			t = getScaleValue(e.image.width, e.image.height, d.wz, d.wz);
			extend(t, {
				sw: (d.ww - t.w) / 2,
				sh: (d.wh - t.h) / 2,
				dw: (d.wz - t.w) / 2,
				dh: (d.wz - t.h) / 2
			});
			extend(d, t);
			extend(this.data.original, t);
			this.drawImage();
			t = null;
		},
		handlerDispatch: function(event){
			var touches = event.targetTouches,
				one = touches[0],
				two = touches[1],
				p = this.data.point,
				o = {x: one ? one.clientX : 0, y: one ? one.clientY : 0};
			event.preventDefault();
			switch(event.type){
				case 'touchstart':
					extend(p, {one: o, changed: o});
					two && (extend(p.two, {x: two.clientX, y: two.clientY}), p.h = p.ch = getLayoutScale(one.clientX, one.clientY, two.clientX, two.clientY));
					break;
				case 'touchmove' :
					two ? this.layoutScale(one.clientX, one.clientY, two.clientX, two.clientY) : this.layoutMove(one.clientX, one.clientY, 0, 0);
					break;
				case 'touchend'  :
					p.h = p.ch = 0;
					this.limitScope();
					break;
			}
		},
		listener: function(name){
			var self = this, fn = function(){
				self[name || 'handlerDispatch'].apply(self, arguments);
			};
			return fn;
		},
		render: function(file){
			var self = this, fr = new FileReader();
			fr.readAsDataURL(file);
			fr.onload = function(frEvent){
				setStyle(self.elem.cropContainer, {display: 'block'});
				self.elem.image.src = frEvent.target.result;
			};
		},
		drawImage: function(){
			var c = this.data.ctx, d = this.data.delta;
			c.cropDark.clearRect(0, 0, d.ww, d.wh);
			c.cropDark.drawImage(this.elem.image, d.sw, d.sh, d.w, d.h);
			c.cropLight.clearRect(0, 0, d.wz, d.wz);
			c.cropLight.drawImage(this.elem.image, d.dw, d.dh, d.w, d.h);
			c.cropLight.lineWidth = 1;
			c.cropLight.strokeStyle = '#FFF';
			c.cropLight.strokeRect(0, 0, d.wz, d.wz);
		},
		layoutMove: function(ox, oy, tx, ty){
			var d = this.data.delta, p = this.data.point;
			extend(p, {
				changed: {x: ox, y: oy},
				move: {x: ox - p.changed.x, y: oy - p.changed.y}
			});
			extend(d, {
				sw: d.sw + p.move.x,
				sh: d.sh + p.move.y,
				dw: d.dw + p.move.x,
				dh: d.dh + p.move.y
			});
			this.drawImage();
		},
		layoutScale: function(ox, oy, tx, ty){
			var d = this.data.delta, p = this.data.point, h, s, o;
			p.h = getLayoutScale(ox, oy, tx, ty);
			h = p.h - p.ch;
			s = getScale({
				w: d.w,
				h: d.h,
				sw: d.w + h
			});
			o = {
				w: (d.w - s.w) / 2,
				h: (d.h - s.h) / 2
			};
			extend(d, {
				sw: d.sw + o.w,
				sh: d.sh + o.h,
				dw: d.dw + o.w,
				dh: d.dh + o.h
			});
			extend(d, {
				w: s.w,
				h: s.h
			});
			this.layoutMove(ox, oy, tx, ty);
			p.ch = p.h;
		},
		limitScope: function(){
			var d = this.data.delta,
				n = {w: d.w - d.wz + d.dw, h: d.h - d.wz + d.dh};
			// 限制活动
			if( d.dw > 0 ){
				d.sw = d.dw = 0;
			}
			if( d.dh > 0 ){
				extend(d, {
					sh: (d.wh - d.wz) / 2,
					dh: 0,
				});
			}
			if( n.w < 0 ){
				d.sw = d.dw = d.wz - d.w;
			}
			if( n.h < 0 ){
				extend(d, {
					sh: d.wz + (d.wh - d.wz) / 2 - d.h,
					dh: d.wz - d.h
				});
			}
			// 限制缩放
			if( d.w < d.wz || d.h < d.wz ){
				extend(d, this.data.original);
			}
			this.drawImage();
		},
		handlerConfirm: function(){
			var imageData, formData = '';
			if( this.data.cache.isSend ) return;
			this.trigger('confirm');
			imageData = this.elem.cropLight.toDataURL('image/png').substring(22);
			for(var i in this.options.formData){
				formData += i + '=' + this.options.formData[i] + '&';
			}
			formData += 'image=' + encodeURIComponent(imageData);
			this.request(formData);
		},
		handlerCancel: function(){
			this.trigger('cancel');
			this.elem.cropConfirm.innerHTML = '选取';
			this.elem.cropConfirm.setAttribute('disabled', '');
			setStyle(this.elem.cropContainer, {display: 'none'});
		},
		request: function(formData){
			var self = this, xhr = new XMLHttpRequest();
			self.data.cache.isSend = true;
			self.elem.cropConfirm.innerHTML = '选取中...';
			xhr.open('POST', self.options.url);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onload = function(){
				self.data.requestText = JSON.parse(xhr.responseText);
				self.trigger('success');
				self.data.cache.isSend = false;
				self.elem.cropConfirm.innerHTML = '选取';
				self.elem.cropConfirm.setAttribute('disabled', '');
			};
			xhr.onerror = function(){
				self.trigger('error');
				self.data.cache.isSend = false;
				self.elem.cropConfirm.innerHTML = '选取';
				self.elem.cropConfirm.removeAttribute('disabled');
			};
			xhr.send(formData);
		},
		on: function(event, handler){
			if( typeof handler === 'function' ){
				event = 'on' + ucfirst(event);
				Array.isArray(this[event]) || (this[event] = []);
				this[event].push(handler);
			}
			return this;
		},
		off: function(event, id){
			var event = 'on' + ucfirst(event);
			if( Array.isArray(this[event]) ){
				delete this[event][id];
			}
			return this;
		},
		trigger: function(event){
			var event = 'on' + ucfirst(event);
			if( Array.isArray(this[event]) ){
				for(var i = 0, len = this[event].length; i < len; i++){
					this[event][i].call(this, this.data.requestText);
				}
			} else if( typeof this[event] === 'function' ){
				this[event].call(this, this.data.requestText);
			}
			return this;
		}
	};

	function getScale(options){
		var scale = {w: 0, h: 0};
		for(var i in options){
			if( !options.hasOwnProperty(i) ) continue;
			options[i] = parseFloat(options[i]);
		}

		if( !options.w || !options.h ) return scale;

		if( options.sw ){
			scale.w = options.sw;
			scale.h = options.h * options.sw / options.w;
			return scale;
		}
		if( options.sh ){
			scale.h = options.sh;
			scale.w = options.w * options.sh / options.h;
			return scale;
		}

		return scale;
	}

	function getScaleValue(w, h, x, y){
		if( w > h ){
			return getScale({
				w: w,
				h: h,
				sh: y
			});
		} else {
			return getScale({
				w: w,
				h: h,
				sw: x
			});
		}
	}

	function hypotenuse(w, h){
		return Math.sqrt(w * w + h * h);
	}

	function getLayoutScale(ox, oy, tx, ty){
		if( ox == tx ) return Math.abs(oy - ty);
		if( oy === ty ) return Math.abs(ox - tx);
		return hypotenuse(Math.abs(ox - tx), Math.abs(oy - ty));
	}

	function extend(target, source){
        target = isObject(target) ? target : {};
        source = isObject(source) ? source : {};
        for(var i in source){
            source.hasOwnProperty(i) && (isObject(source[i]) ? extend(target[i], source[i]) : target[i] = source[i]);
        }
        return target;
    }

    function isElement(element){
		return typeof element === 'object' && (element.nodeType === 1 || element.nodeType === 9);
	}

    function setStyle(element, style){
		if( isElement(element) && typeof style === 'object' ){
			for(var i in style){
				element.style[i] = style[i];
			}
		}
		return element;
	}

    function ucfirst(word){
    	return word = String(word), word.charAt(0).toUpperCase() + word.substr(1);
    }
	
	function isObject(obj){
		return typeof obj === 'object';
	}

	return Crop;
});