!function(n) {
	function e(n, e, r) {
		var t = c[n] || (c[n] = []);
		t.push(e);
		var i = o[n] || {},
			p = i.pkg ? a[i.pkg].url : i.url || n;
		if (!(p in u)) {
			u[p] = !0;
			var s = document.createElement("script");
			s.type = "text/javascript", s.src = p, s.onerror = function() {
				r && r()
			}, f.appendChild(s)
		}
	}
	function r(n, e, r) {
		var t = document.createElement("script");
		t.type = "text/javascript", t.src = n, t.onerror = function() {
			r && r()
		}, t.onload = function() {
			e && e()
		}, f.appendChild(t)
	}
	if (!window.require || !window.define) {
		var t, i, o, a, f = document.getElementsByTagName("head")[0],
			c = {},
			p = {},
			s = {},
			u = {};
		i = function(n, e) {
			p[n] = e;
			var r = c[n];
			if (r) {
				for (var t = r.length - 1; t >= 0; --t) r[t]();
				delete c[n]
			}
		}, t = function(n) {
			n = t.alias(n);
			var e = s[n];
			if (e) return e.exports;
			var r = p[n];
			if (!r) throw Error("Cannot find module `" + n + "`");
			e = s[n] = {
				exports: {}
			};
			try{
				var i = "function" == typeof r ? r.apply(e, [t, e.exports, e]) : r;
			} catch(error){
				console.log(error.stack);
			}
			return i && (e.exports = i), e.exports
		}, t.async = function(i, a, f) {
			function c(n) {
				for (var r = n.length - 1; r >= 0; --r) {
					var t = n[r];
					if (!(t in p || t in v)) {
						v[t] = !0, h++, e(t, s, f);
						var i = o[t];
						i && "deps" in i && c(i.deps)
					}
				}
			}
			function s() {
				if (0 == h--) {
					var e, r, i = [];
					for (e = 0, r = u.length; r > e; ++e) i[e] = t(u[e]);
					a && a.apply(n, i)
				}
			}
			var u;
			u = "string" == typeof i ? [i] : i;
			for (var l = [], d = u.length - 1; d >= 0; --d) u[d].match(/^src\:/) ? (l.push(u[d].substring(4)), u.splice(d, 1)) : u[d] = t.alias(u[d]);
			if (l.length > 1) {
				for (var d = l.length - 1; d >= 1; --d) r(l[d], void 0, f);
				0 == u.length ? r(l[0], a, f) : r(l[0], void 0, f)
			} else 1 == l.length && (0 == u.length ? r(l[0], a, f) : r(l[0], void 0, f));
			if (0 !== u.length) {
				var v = {},
					h = 0;
				c(u), s()
			}
		}, t.resourceMap = function(n) {
			o = n.res || {}, a = n.pkg || {}
		}, t.alias = function(n) {
			return n
		}, i.amd = {
			jQuery: !0,
			version: "1.0.0"
		}, window.require = t, window.define = i
	}
}(this);