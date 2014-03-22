!
function() {
	function extend(a, b) {
		var c;
		a || (a = {});
		for (c in b) a[c] = b[c];
		return a
	}
	function hash() {
		for (var a = 0,
		b = arguments,
		c = b.length,
		d = {}; c > a; a++) d[b[a++]] = b[a];
		return d
	}
	function pInt(a, b) {
		return parseInt(a, b || 10)
	}
	function isString(a) {
		return "string" == typeof a
	}
	function isObject(a) {
		return "object" == typeof a
	}
	function isArray(a) {
		return "[object Array]" === Object.prototype.toString.call(a)
	}
	function isNumber(a) {
		return "number" == typeof a
	}
	function log2lin(a) {
		return math.log(a) / math.LN10
	}
	function lin2log(a) {
		return math.pow(10, a)
	}
	function erase(a, b) {
		for (var c = a.length; c--;) if (a[c] === b) {
			a.splice(c, 1);
			break
		}
	}
	function defined(a) {
		return a !== UNDEFINED && null !== a
	}
	function attr(a, b, c) {
		var d, f, e = "setAttribute";
		if (isString(b)) defined(c) ? a[e](b, c) : a && a.getAttribute && (f = a.getAttribute(b));
		else if (defined(b) && isObject(b)) for (d in b) a[e](d, b[d]);
		return f
	}
	function splat(a) {
		return isArray(a) ? a: [a]
	}
	function pick() {
		var b, c, a = arguments,
		d = a.length;
		for (b = 0; d > b; b++) if (c = a[b], "undefined" != typeof c && null !== c) return c
	}
	function css(a, b) {
		isIE && b && b.opacity !== UNDEFINED && (b.filter = "alpha(opacity=" + 100 * b.opacity + ")"),
		extend(a.style, b)
	}
	function createElement(a, b, c, d, e) {
		var f = doc.createElement(a);
		return b && extend(f, b),
		e && css(f, {
			padding: 0,
			border: NONE,
			margin: 0
		}),
		c && css(f, c),
		d && d.appendChild(f),
		f
	}
	function extendClass(a, b) {
		var c = function() {};
		return c.prototype = new a,
		extend(c.prototype, b),
		c
	}
	function getDecimals(a) {
		return a = (a || 0).toString(),
		a.indexOf(".") > -1 ? a.split(".")[1].length: 0
	}
	function numberFormat(a, b, c, d) {
		var e = defaultOptions.lang,
		f = a,
		g = -1 === b ? getDecimals(a) : isNaN(b = mathAbs(b)) ? 2 : b,
		h = void 0 === c ? e.decimalPoint: c,
		i = void 0 === d ? e.thousandsSep: d,
		j = 0 > f ? "-": "",
		k = String(pInt(f = mathAbs( + f || 0).toFixed(g))),
		l = k.length > 3 ? k.length % 3 : 0;
		return j + (l ? k.substr(0, l) + i: "") + k.substr(l).replace(/(\d{3})(?=\d)/g, "$1" + i) + (g ? h + mathAbs(f - k).toFixed(g).slice(2) : "")
	}
	function pad(a, b) {
		return new Array((b || 2) + 1 - String(a).length).join(0) + a
	}
	function wrap(a, b, c) {
		var d = a[b];
		a[b] = function() {
			var a = Array.prototype.slice.call(arguments);
			return a.unshift(d),
			c.apply(this, a)
		}
	}
	function normalizeTickInterval(a, b, c, d) {
		var e, f;
		for (c = pick(c, 1), e = a / c, b || (b = [1, 2, 2.5, 5, 10], d && d.allowDecimals === !1 && (1 === c ? b = [1, 2, 5, 10] : .1 >= c && (b = [1 / c]))), f = 0; f < b.length && (a = b[f], !(e <= (b[f] + (b[f + 1] || b[f])) / 2)); f++);
		return a *= c
	}
	function normalizeTimeTickInterval(a, b) {
		var g, h, i, c = b || [[MILLISECOND, [1, 2, 5, 10, 20, 25, 50, 100, 200, 500]], [SECOND, [1, 2, 5, 10, 15, 30]], [MINUTE, [1, 2, 5, 10, 15, 30]], [HOUR, [1, 2, 3, 4, 6, 8, 12]], [DAY, [1, 2]], [WEEK, [1, 2]], [MONTH, [1, 2, 3, 4, 6]], [YEAR, null]],
		d = c[c.length - 1],
		e = timeUnits[d[0]],
		f = d[1];
		for (h = 0; h < c.length && (d = c[h], e = timeUnits[d[0]], f = d[1], !(c[h + 1] && (i = (e * f[f.length - 1] + timeUnits[c[h + 1][0]]) / 2, i >= a))); h++);
		return e === timeUnits[YEAR] && 5 * e > a && (f = [1, 2, 5]),
		e === timeUnits[YEAR] && 5 * e > a && (f = [1, 2, 5]),
		g = normalizeTickInterval(a / e, f),
		{
			unitRange: e,
			count: g,
			unitName: d[0]
		}
	}
	function getTimeTicks(a, b, c, d) {
		var f, i, m, n, o, p, e = [],
		g = {},
		h = defaultOptions.global.useUTC,
		j = new Date(b),
		k = a.unitRange,
		l = a.count;
		for (k >= timeUnits[SECOND] && (j.setMilliseconds(0), j.setSeconds(k >= timeUnits[MINUTE] ? 0 : l * mathFloor(j.getSeconds() / l))), k >= timeUnits[MINUTE] && j[setMinutes](k >= timeUnits[HOUR] ? 0 : l * mathFloor(j[getMinutes]() / l)), k >= timeUnits[HOUR] && j[setHours](k >= timeUnits[DAY] ? 0 : l * mathFloor(j[getHours]() / l)), k >= timeUnits[DAY] && j[setDate](k >= timeUnits[MONTH] ? 1 : l * mathFloor(j[getDate]() / l)), k >= timeUnits[MONTH] && (j[setMonth](k >= timeUnits[YEAR] ? 0 : l * mathFloor(j[getMonth]() / l)), i = j[getFullYear]()), k >= timeUnits[YEAR] && (i -= i % l, j[setFullYear](i)), k === timeUnits[WEEK] && j[setDate](j[getDate]() - j[getDay]() + pick(d, 1)), f = 1, i = j[getFullYear](), m = j.getTime(), n = j[getMonth](), o = j[getDate](), p = h ? 0 : (864e5 + 1e3 * 60 * j.getTimezoneOffset()) % 864e5; c > m;) e.push(m),
		k === timeUnits[YEAR] ? m = makeTime(i + f * l, 0) : k === timeUnits[MONTH] ? m = makeTime(i, n + f * l) : h || k !== timeUnits[DAY] && k !== timeUnits[WEEK] ? (m += k * l, k <= timeUnits[HOUR] && m % timeUnits[DAY] === p && (g[m] = DAY)) : m = makeTime(i, n, o + f * l * (k === timeUnits[DAY] ? 1 : 7)),
		f++;
		return e.push(m),
		e.info = extend(a, {
			higherRanks: g,
			totalRange: k * l
		}),
		e
	}
	function ChartCounters() {
		this.color = 0,
		this.symbol = 0
	}
	function stableSort(a, b) {
		var d, e, c = a.length;
		for (e = 0; c > e; e++) a[e].ss_i = e;
		for (a.sort(function(a, c) {
			return d = b(a, c),
			0 === d ? a.ss_i - c.ss_i: d
		}), e = 0; c > e; e++) delete a[e].ss_i
	}
	function arrayMin(a) {
		for (var b = a.length,
		c = a[0]; b--;) a[b] < c && (c = a[b]);
		return c
	}
	function arrayMax(a) {
		for (var b = a.length,
		c = a[0]; b--;) a[b] > c && (c = a[b]);
		return c
	}
	function destroyObjectProperties(a, b) {
		var c;
		for (c in a) a[c] && a[c] !== b && a[c].destroy && a[c].destroy(),
		delete a[c]
	}
	function discardElement(a) {
		garbageBin || (garbageBin = createElement(DIV)),
		a && garbageBin.appendChild(a),
		garbageBin.innerHTML = ""
	}
	function error(a, b) {
		var c = "Highcharts error #" + a + ": www.highcharts.com/errors/" + a;
		if (b) throw c;
		win.console && console.log(c)
	}
	function correctFloat(a) {
		return parseFloat(a.toPrecision(14))
	}
	function setAnimation(a, b) {
		globalAnimation = pick(a, b.animation)
	}
	function setTimeMethods() {
		var a = defaultOptions.global.useUTC,
		b = a ? "getUTC": "get",
		c = a ? "setUTC": "set";
		makeTime = a ? Date.UTC: function(a, b, c, d, e, f) {
			return new Date(a, b, pick(c, 1), pick(d, 0), pick(e, 0), pick(f, 0)).getTime()
		},
		getMinutes = b + "Minutes",
		getHours = b + "Hours",
		getDay = b + "Day",
		getDate = b + "Date",
		getMonth = b + "Month",
		getFullYear = b + "FullYear",
		setMinutes = c + "Minutes",
		setHours = c + "Hours",
		setDate = c + "Date",
		setMonth = c + "Month",
		setFullYear = c + "FullYear"
	}
	function setOptions(a) {
		return defaultOptions = merge(defaultOptions, a),
		setTimeMethods(),
		defaultOptions
	}
	function getOptions() {
		return defaultOptions
	}
	function SVGElement() {}
	function Tick(a, b, c) {
		this.axis = a,
		this.pos = b,
		this.type = c || "",
		this.isNew = !0,
		c || this.addLabel()
	}
	function PlotLineOrBand(a, b) {
		return this.axis = a,
		b && (this.options = b, this.id = b.id),
		this
	}
	function StackItem(a, b, c, d, e, f) {
		var g = a.chart.inverted;
		this.axis = a,
		this.isNegative = c,
		this.options = b,
		this.x = d,
		this.stack = e,
		this.percent = "percent" === f,
		this.alignOptions = {
			align: b.align || (g ? c ? "left": "right": "center"),
			verticalAlign: b.verticalAlign || (g ? "middle": c ? "bottom": "top"),
			y: pick(b.y, g ? 4 : c ? 14 : -6),
			x: pick(b.x, g ? c ? -6 : 6 : 0)
		},
		this.textAlign = b.textAlign || (g ? c ? "right": "left": "center")
	}
	function Axis() {
		this.init.apply(this, arguments)
	}
	function Tooltip(a, b) {
		var c = b.borderWidth,
		d = b.style,
		e = pInt(d.padding);
		this.chart = a,
		this.options = b,
		this.crosshairs = [],
		this.now = {
			x: 0,
			y: 0
		},
		this.isHidden = !0,
		this.label = a.renderer.label("", 0, 0, b.shape, null, null, b.useHTML, null, "tooltip").attr({
			padding: e,
			fill: b.backgroundColor,
			"stroke-width": c,
			r: b.borderRadius,
			zIndex: 8
		}).css(d).css({
			padding: 0
		}).hide().add(),
		useCanVG || this.label.shadow(b.shadow),
		this.shared = b.shared
	}
	function MouseTracker(a, b) {
		var c = useCanVG ? "": b.chart.zoomType;
		this.zoomX = /x/.test(c),
		this.zoomY = /y/.test(c),
		this.options = b,
		this.chart = a,
		this.init(a, b.tooltip)
	}
	function Legend(a) {
		this.init(a)
	}
	function Chart(a, b) {
		var c, e, f, g, h, d = a.series;
		a.series = null,
		c = merge(defaultOptions, a),
		c.series = a.series = d,
		e = c.chart,
		f = e.margin,
		g = isObject(f) ? f: [f, f, f, f],
		this.optionsMarginTop = pick(e.marginTop, g[0]),
		this.optionsMarginRight = pick(e.marginRight, g[1]),
		this.optionsMarginBottom = pick(e.marginBottom, g[2]),
		this.optionsMarginLeft = pick(e.marginLeft, g[3]),
		h = e.events,
		this.runChartClick = h && !!h.click,
		this.callback = b,
		this.isResizing = 0,
		this.options = c,
		this.axes = [],
		this.series = [],
		this.hasCartesianSeries = e.showAxes,
		this.init(h)
	}
	function Scroller(a) {
		var b = a.options,
		c = b.navigator,
		d = c.enabled,
		e = b.scrollbar,
		f = e.enabled,
		g = d ? c.height: 0,
		h = f ? e.height: 0,
		i = c.baseSeries;
		this.baseSeries = a.series[i] || "string" == typeof i && a.get(i) || a.series[0],
		this.handles = [],
		this.scrollbarButtons = [],
		this.elementsToDestroy = [],
		this.chart = a,
		this.height = g,
		this.scrollbarHeight = h,
		this.scrollbarEnabled = f,
		this.navigatorEnabled = d,
		this.navigatorOptions = c,
		this.scrollbarOptions = e,
		this.outlineHeight = g + h,
		this.init()
	}
	function RangeSelector(a) {
		var b = [{
			type: "month",
			count: 1,
			text: "1m"
		},
		{
			type: "month",
			count: 3,
			text: "3m"
		},
		{
			type: "month",
			count: 6,
			text: "6m"
		},
		{
			type: "ytd",
			text: "YTD"
		},
		{
			type: "year",
			count: 1,
			text: "1y"
		},
		{
			type: "all",
			text: "All"
		}];
		this.chart = a,
		this.buttons = [],
		this.boxSpanElements = {},
		this.init(b)
	}
	var UNDEFINED, Renderer, garbageBin, defaultOptions, dateFormat, globalAnimation, pathAnim, timeUnits, makeTime, getMinutes, getHours, getDay, getDate, getMonth, getFullYear, setMinutes, setHours, setDate, setMonth, setFullYear, globalAdapter, adapter, adapterRun, getScript, inArray, each, grep, offset, map, merge, addEvent, removeEvent, fireEvent, washMouseEvent, animate, stop, defaultLabelOptions, defaultPlotOptions, defaultSeriesOptions, Color, SVGRenderer, VMLRenderer, VMLElement, VMLRendererExtension, CanVGRenderer, CanVGController, Point, Series, LineSeries, AreaSeries, SplineSeries, areaProto, AreaSplineSeries, ColumnSeries, BarSeries, ScatterSeries, PiePoint, PieSeries, DATA_GROUPING, seriesProto, baseProcessData, baseGeneratePoints, baseDestroy, baseTooltipHeaderFormatter, NUMBER, commonOptions, specificOptions, defaultDataGroupingUnits, approximations, OHLCPoint, OHLCSeries, CandlestickSeries, symbols, MOUSEDOWN, MOUSEMOVE, MOUSEUP, buttonGradient, units, seriesInit, seriesProcessData, pointTooltipFormatter, doc = document,
	win = window,
	math = Math,
	mathRound = math.round,
	mathFloor = math.floor,
	mathCeil = math.ceil,
	mathMax = math.max,
	mathMin = math.min,
	mathAbs = math.abs,
	mathCos = math.cos,
	mathSin = math.sin,
	mathPI = math.PI,
	deg2rad = 2 * mathPI / 360,
	userAgent = navigator.userAgent,
	isOpera = win.opera,
	isIE = /msie/i.test(userAgent) && !isOpera,
	docMode8 = 8 === doc.documentMode,
	isWebKit = /AppleWebKit/.test(userAgent),
	isFirefox = /Firefox/.test(userAgent),
	SVG_NS = "http://www.w3.org/2000/svg",
	hasSVG = !!doc.createElementNS && !!doc.createElementNS(SVG_NS, "svg").createSVGRect,
	hasBidiBug = isFirefox && parseInt(userAgent.split("Firefox/")[1], 10) < 4,
	useCanVG = !hasSVG && !isIE && !!doc.createElement("canvas").getContext,
	hasTouch = doc.documentElement.ontouchstart !== UNDEFINED,
	symbolSizes = {},
	idCounter = 0,
	noop = function() {},
	DIV = "div",
	ABSOLUTE = "absolute",
	RELATIVE = "relative",
	HIDDEN = "hidden",
	PREFIX = "highcharts-",
	VISIBLE = "visible",
	PX = "px",
	NONE = "none",
	M = "M",
	L = "L",
	TRACKER_FILL = "rgba(192,192,192," + (hasSVG ? 1e-6: .002) + ")",
	NORMAL_STATE = "",
	HOVER_STATE = "hover",
	SELECT_STATE = "select",
	MILLISECOND = "millisecond",
	SECOND = "second",
	MINUTE = "minute",
	HOUR = "hour",
	DAY = "day",
	WEEK = "week",
	MONTH = "month",
	YEAR = "year",
	FILL = "fill",
	LINEAR_GRADIENT = "linearGradient",
	STOPS = "stops",
	STROKE = "stroke",
	STROKE_WIDTH = "stroke-width",
	seriesTypes = {};
	win.Highcharts = {},
	dateFormat = function(a, b, c) {
		if (!defined(b) || isNaN(b)) return "Invalid date";
		a = pick(a, "%Y-%m-%d %H:%M:%S");
		var e, d = new Date(b),
		f = d[getHours](),
		g = d[getDay](),
		h = d[getDate](),
		i = d[getMonth](),
		j = d[getFullYear](),
		k = defaultOptions.lang,
		l = k.weekdays,
		m = {
			a: l[g].substr(0, 3),
			A: l[g],
			d: pad(h),
			e: h,
			b: k.shortMonths[i],
			B: k.months[i],
			m: pad(i + 1),
			y: j.toString().substr(2, 2),
			Y: j,
			H: pad(f),
			I: pad(f % 12 || 12),
			l: f % 12 || 12,
			M: pad(d[getMinutes]()),
			p: 12 > f ? "AM": "PM",
			P: 12 > f ? "am": "pm",
			S: pad(d.getSeconds()),
			L: pad(mathRound(b % 1e3), 3)
		};
		for (e in m) a = a.replace("%" + e, m[e]);
		return c ? a.substr(0, 1).toUpperCase() + a.substr(1) : a
	},
	ChartCounters.prototype = {
		wrapColor: function(a) {
			this.color >= a && (this.color = 0)
		},
		wrapSymbol: function(a) {
			this.symbol >= a && (this.symbol = 0)
		}
	},
	timeUnits = hash(MILLISECOND, 1, SECOND, 1e3, MINUTE, 6e4, HOUR, 36e5, DAY, 864e5, WEEK, 6048e5, MONTH, 2592e6, YEAR, 31556952e3),
	pathAnim = {
		init: function(a, b, c) {
			b = b || "";
			var g, h, i, l, m, d = a.shift,
			e = b.indexOf("C") > -1,
			f = e ? 7 : 3,
			j = b.split(" "),
			k = [].concat(c),
			n = function(a) {
				for (i = a.length; i--;) a[i] === M && a.splice(i + 1, 0, a[i + 1], a[i + 2], a[i + 1], a[i + 2])
			};
			if (e && (n(j), n(k)), a.isArea && (l = j.splice(j.length - 6, 6), m = k.splice(k.length - 6, 6)), d <= k.length / f) for (; d--;) k = [].concat(k).splice(0, f).concat(k);
			if (a.shift = 0, j.length) for (g = k.length; j.length < g;) h = [].concat(j).splice(j.length - f, f),
			e && (h[f - 6] = h[f - 2], h[f - 5] = h[f - 1]),
			j = j.concat(h);
			return l && (j = j.concat(l), k = k.concat(m)),
			[j, k]
		},
		step: function(a, b, c, d) {
			var g, e = [],
			f = a.length;
			if (1 === c) e = d;
			else if (f === b.length && 1 > c) for (; f--;) g = parseFloat(a[f]),
			e[f] = isNaN(g) ? a[f] : c * parseFloat(b[f] - g) + g;
			else e = b;
			return e
		}
	},
	function(a) {
		win.HighchartsAdapter = win.HighchartsAdapter || a && {
			init: function(b) {
				var e, c = a.fx,
				d = c.step,
				f = a.Tween,
				g = f && f.propHooks;
				a.extend(a.easing, {
					easeOutQuad: function(a, b, c, d, e) {
						return - d * (b /= e) * (b - 2) + c
					}
				}),
				a.each(["cur", "_default", "width", "height"],
				function(a, b) {
					var h, i, e = d;
					"cur" === b ? e = c.prototype: "_default" === b && f && (e = g[b], b = "set"),
					h = e[b],
					h && (e[b] = function(c) {
						return c = a ? c: this,
						i = c.elem,
						i.attr ? i.attr(c.prop, "cur" === b ? UNDEFINED: c.now) : h.apply(this, arguments)
					})
				}),
				e = function(a) {
					var d, c = a.elem;
					a.started || (d = b.init(c, c.d, c.toD), a.start = d[0], a.end = d[1], a.started = !0),
					c.attr("d", b.step(a.start, a.end, a.pos, c.toD))
				},
				f ? g.d = {
					set: e
				}: d.d = e,
				this.each = Array.prototype.forEach ?
				function(a, b) {
					return Array.prototype.forEach.call(a, b)
				}: function(a, b) {
					for (var c = 0,
					d = a.length; d > c; c++) if (b.call(a[c], a[c], c, a) === !1) return c
				}
			},
			getScript: a.getScript,
			inArray: a.inArray,
			adapterRun: function(b, c) {
				return a(b)[c]()
			},
			grep: a.grep,
			map: function(a, b) {
				for (var c = [], d = 0, e = a.length; e > d; d++) c[d] = b.call(a[d], a[d], d, a);
				return c
			},
			merge: function() {
				var b = arguments;
				return a.extend(!0, null, b[0], b[1], b[2], b[3])
			},
			offset: function(b) {
				return a(b).offset()
			},
			addEvent: function(b, c, d) {
				a(b).bind(c, d)
			},
			removeEvent: function(b, c, d) {
				var e = doc.removeEventListener ? "removeEventListener": "detachEvent";
				doc[e] && !b[e] && (b[e] = function() {}),
				a(b).unbind(c, d)
			},
			fireEvent: function(b, c, d, e) {
				var h, f = a.Event(c),
				g = "detached" + c; ! isIE && d && (delete d.layerX, delete d.layerY),
				extend(f, d),
				b[c] && (b[g] = b[c], b[c] = null),
				a.each(["preventDefault", "stopPropagation"],
				function(a, b) {
					var c = f[b];
					f[b] = function() {
						try {
							c.call(f)
						} catch(a) {
							"preventDefault" === b && (h = !0)
						}
					}
				}),
				a(b).trigger(f),
				b[g] && (b[c] = b[g], b[g] = null),
				!e || f.isDefaultPrevented() || h || e(f)
			},
			washMouseEvent: function(a) {
				var b = a.originalEvent || a;
				return b.pageX === UNDEFINED && (b.pageX = a.pageX, b.pageY = a.pageY),
				b
			},
			animate: function(b, c, d) {
				var e = a(b);
				c.d && (b.toD = c.d, c.d = 1),
				e.stop(),
				e.animate(c, d)
			},
			stop: function(b) {
				a(b).stop()
			}
		}
	} (win.jQuery),
	globalAdapter = win.HighchartsAdapter,
	adapter = globalAdapter || {},
	globalAdapter && globalAdapter.init.call(globalAdapter, pathAnim),
	adapterRun = adapter.adapterRun,
	getScript = adapter.getScript,
	inArray = adapter.inArray,
	each = adapter.each,
	grep = adapter.grep,
	offset = adapter.offset,
	map = adapter.map,
	merge = adapter.merge,
	addEvent = adapter.addEvent,
	removeEvent = adapter.removeEvent,
	fireEvent = adapter.fireEvent,
	washMouseEvent = adapter.washMouseEvent,
	animate = adapter.animate,
	stop = adapter.stop,
	defaultLabelOptions = {
		enabled: !0,
		align: "center",
		x: 0,
		y: 15,
		style: {
			color: "#666",
			fontSize: "11px",
			lineHeight: "14px"
		}
	},
	defaultOptions = {
		colors: ["#4572A7", "#AA4643", "#89A54E", "#80699B", "#3D96AE", "#DB843D", "#92A8CD", "#A47D7C", "#B5CA92"],
		symbols: ["circle", "diamond", "square", "triangle", "triangle-down"],
		lang: {
			loading: "Loading...",
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			shortMonths: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			weekdays: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
			decimalPoint: ".",
			numericSymbols: ["k", "M", "G", "T", "P", "E"],
			resetZoom: "Reset zoom",
			resetZoomTitle: "Reset zoom level 1:1",
			thousandsSep: ","
		},
		global: {
			useUTC: !0,
			canvasToolsURL: "http://code.highcharts.com/stock/1.2.4/modules/canvas-tools.js",
			VMLRadialGradientURL: "http://code.highcharts.com/stock/1.2.4/gfx/vml-radial-gradient.png"
		},
		chart: {
			borderColor: "#4572A7",
			borderRadius: 5,
			defaultSeriesType: "line",
			ignoreHiddenSeries: !0,
			spacingTop: 10,
			spacingRight: 10,
			spacingBottom: 15,
			spacingLeft: 10,
			style: {
				fontFamily: '"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif',
				fontSize: "12px"
			},
			backgroundColor: "#FFFFFF",
			plotBorderColor: "#C0C0C0",
			resetZoomButton: {
				theme: {
					zIndex: 20
				},
				position: {
					align: "right",
					x: -10,
					y: 10
				}
			}
		},
		title: {
			text: "Chart title",
			align: "center",
			floating: !1,
			y: 15,
			style: {
				color: "#3E576F",
				fontSize: "16px"
			}
		},
		subtitle: {
			text: "",
			align: "center",
			y: 30,
			style: {
				color: "#6D869F"
			}
		},
		plotOptions: {
			line: {
				allowPointSelect: !1,
				showCheckbox: !1,
				animation: {
					duration: 1e3
				},
				events: {},
				lineWidth: 2,
				shadow: !0,
				marker: {
					enabled: !0,
					lineWidth: 0,
					radius: 4,
					lineColor: "#FFFFFF",
					states: {
						hover: {
							enabled: !0
						},
						select: {
							fillColor: "#FFFFFF",
							lineColor: "#000000",
							lineWidth: 2
						}
					}
				},
				point: {
					events: {}
				},
				dataLabels: merge(defaultLabelOptions, {
					enabled: !1,
					formatter: function() {
						return this.y
					},
					verticalAlign: "bottom",
					y: 0
				}),
				cropThreshold: 300,
				pointRange: 0,
				showInLegend: !0,
				states: {
					hover: {
						marker: {}
					},
					select: {
						marker: {}
					}
				},
				stickyTracking: !0
			}
		},
		labels: {
			style: {
				position: ABSOLUTE,
				color: "#3E576F"
			}
		},
		legend: {
			enabled: !0,
			align: "center",
			layout: "horizontal",
			labelFormatter: function() {
				return this.name
			},
			borderWidth: 1,
			borderColor: "#909090",
			borderRadius: 5,
			navigation: {
				activeColor: "#3E576F",
				inactiveColor: "#CCC"
			},
			shadow: !1,
			itemStyle: {
				cursor: "pointer",
				color: "#3E576F",
				fontSize: "12px"
			},
			itemHoverStyle: {
				color: "#000"
			},
			itemHiddenStyle: {
				color: "#CCC"
			},
			itemCheckboxStyle: {
				position: ABSOLUTE,
				width: "13px",
				height: "13px"
			},
			symbolWidth: 16,
			symbolPadding: 5,
			verticalAlign: "bottom",
			x: 0,
			y: 0
		},
		loading: {
			labelStyle: {
				fontWeight: "bold",
				position: RELATIVE,
				top: "1em"
			},
			style: {
				position: ABSOLUTE,
				backgroundColor: "white",
				opacity: .5,
				textAlign: "center"
			}
		},
		tooltip: {
			enabled: !0,
			backgroundColor: "rgba(255, 255, 255, .85)",
			borderWidth: 2,
			borderRadius: 5,
			dateTimeLabelFormats: {
				millisecond: "%A, %b %e, %H:%M:%S.%L",
				second: "%A, %b %e, %H:%M:%S",
				minute: "%A, %b %e, %H:%M",
				hour: "%A, %b %e, %H:%M",
				day: "%A, %b %e, %Y",
				week: "Week from %A, %b %e, %Y",
				month: "%B %Y",
				year: "%Y"
			},
			headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
			shadow: !0,
			shared: useCanVG,
			snap: hasTouch ? 25 : 10,
			style: {
				color: "#333333",
				fontSize: "12px",
				padding: "5px",
				whiteSpace: "nowrap"
			}
		},
		credits: {
			enabled: !0,
			text: "Highcharts.com",
			href: "http://www.highcharts.com",
			position: {
				align: "right",
				x: -10,
				verticalAlign: "bottom",
				y: -5
			},
			style: {
				cursor: "pointer",
				color: "#909090",
				fontSize: "10px"
			}
		}
	},
	defaultPlotOptions = defaultOptions.plotOptions,
	defaultSeriesOptions = defaultPlotOptions.line,
	setTimeMethods(),
	Color = function(a) {
		function d(a) {
			c = /rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]?(?:\.[0-9]+)?)\s*\)/.exec(a),
			c ? b = [pInt(c[1]), pInt(c[2]), pInt(c[3]), parseFloat(c[4], 10)] : (c = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(a), c && (b = [pInt(c[1], 16), pInt(c[2], 16), pInt(c[3], 16), 1]))
		}
		function e(c) {
			var d;
			return d = b && !isNaN(b[0]) ? "rgb" === c ? "rgb(" + b[0] + "," + b[1] + "," + b[2] + ")": "a" === c ? b[3] : "rgba(" + b.join(",") + ")": a
		}
		function f(a) {
			if (isNumber(a) && 0 !== a) {
				var c;
				for (c = 0; 3 > c; c++) b[c] += pInt(255 * a),
				b[c] < 0 && (b[c] = 0),
				b[c] > 255 && (b[c] = 255)
			}
			return this
		}
		function g(a) {
			return b[3] = a,
			this
		}
		var c, b = [];
		return d(a),
		{
			get: e,
			brighten: f,
			setOpacity: g
		}
	},
	SVGElement.prototype = {
		init: function(a, b) {
			var c = this;
			c.element = "span" === b ? createElement(b) : doc.createElementNS(SVG_NS, b),
			c.renderer = a,
			c.attrSetters = {}
		},
		animate: function(a, b, c) {
			var d = pick(b, globalAnimation, !0);
			stop(this),
			d ? (d = merge(d), c && (d.complete = c), animate(this, a, d)) : (this.attr(a), c && c())
		},
		attr: function(a, b) {
			var d, e, f, g, h, l, m, p, q, c = this,
			i = c.element,
			j = i.nodeName.toLowerCase(),
			k = c.renderer,
			n = c.attrSetters,
			o = c.shadows,
			r = c;
			if (isString(a) && defined(b) && (d = a, a = {},
			a[d] = b), isString(a)) d = a,
			"circle" === j ? d = {
				x: "cx",
				y: "cy"
			} [d] || d: "strokeWidth" === d && (d = "stroke-width"),
			r = attr(i, d) || c[d] || 0,
			"d" !== d && "visibility" !== d && (r = parseFloat(r));
			else for (d in a) if (l = !1, e = a[d], f = n[d] && n[d].call(c, e, d), f !== !1) {
				if (f !== UNDEFINED && (e = f), "d" === d) e && e.join && (e = e.join(" ")),
				/(NaN| {2}|^$)/.test(e) && (e = "M 0 0");
				else if ("x" === d && "text" === j) {
					for (g = 0; g < i.childNodes.length; g++) h = i.childNodes[g],
					attr(h, "x") === attr(i, "x") && attr(h, "x", e);
					c.rotation && attr(i, "transform", "rotate(" + c.rotation + " " + e + " " + pInt(a.y || attr(i, "y")) + ")")
				} else if ("fill" === d) e = k.color(e, i, d);
				else if ("circle" !== j || "x" !== d && "y" !== d) if ("rect" === j && "r" === d) attr(i, {
					rx: e,
					ry: e
				}),
				l = !0;
				else if ("translateX" === d || "translateY" === d || "rotation" === d || "verticalAlign" === d) q = !0,
				l = !0;
				else if ("stroke" === d) e = k.color(e, i, d);
				else if ("dashstyle" === d) {
					if (d = "stroke-dasharray", e = e && e.toLowerCase(), "solid" === e) e = NONE;
					else if (e) {
						for (e = e.replace("shortdashdotdot", "3,1,1,1,1,1,").replace("shortdashdot", "3,1,1,1").replace("shortdot", "1,1,").replace("shortdash", "3,1,").replace("longdash", "8,3,").replace(/dot/g, "1,3,").replace("dash", "4,3,").replace(/,$/, "").split(","), g = e.length; g--;) e[g] = pInt(e[g]) * a["stroke-width"];
						e = e.join(",")
					}
				} else "isTracker" === d ? c[d] = e: "width" === d ? e = pInt(e) : "align" === d ? (d = "text-anchor", e = {
					left: "start",
					center: "middle",
					right: "end"
				} [e]) : "title" === d && (m = i.getElementsByTagName("title")[0], m || (m = doc.createElementNS(SVG_NS, "title"), i.appendChild(m)), m.textContent = e);
				else d = {
					x: "cx",
					y: "cy"
				} [d] || d;
				if ("strokeWidth" === d && (d = "stroke-width"), isWebKit && "stroke-width" === d && 0 === e && (e = 1e-6), c.symbolName && /^(x|y|width|height|r|start|end|innerR|anchorX|anchorY)/.test(d) && (p || (c.symbolAttr(a), p = !0), l = !0), o && /^(width|height|visibility|x|y|d|transform)$/.test(d)) for (g = o.length; g--;) attr(o[g], d, "height" === d ? mathMax(e - (o[g].cutHeight || 0), 0) : e); ("width" === d || "height" === d) && "rect" === j && 0 > e && (e = 0),
				c[d] = e,
				q && c.updateTransform(),
				"text" === d ? (e !== c.textStr && delete c.bBox, c.textStr = e, c.added && k.buildText(c)) : l || attr(i, d, e)
			}
			return r
		},
		symbolAttr: function(a) {
			var b = this;
			each(["x", "y", "r", "start", "end", "width", "height", "innerR", "anchorX", "anchorY"],
			function(c) {
				b[c] = pick(a[c], b[c])
			}),
			b.attr({
				d: b.renderer.symbols[b.symbolName](b.x, b.y, b.width, b.height, b)
			})
		},
		clip: function(a) {
			return this.attr("clip-path", a ? "url(" + this.renderer.url + "#" + a.id + ")": NONE)
		},
		crisp: function(a, b, c, d, e) {
			var g, j, f = this,
			h = {},
			i = {};
			a = a || f.strokeWidth || f.attr && f.attr("stroke-width") || 0,
			j = mathRound(a) % 2 / 2,
			i.x = mathFloor(b || f.x || 0) + j,
			i.y = mathFloor(c || f.y || 0) + j,
			i.width = mathFloor((d || f.width || 0) - 2 * j),
			i.height = mathFloor((e || f.height || 0) - 2 * j),
			i.strokeWidth = a;
			for (g in i) f[g] !== i[g] && (f[g] = h[g] = i[g]);
			return h
		},
		css: function(a) {
			var e, b = this,
			c = b.element,
			d = a && a.width && "text" === c.nodeName.toLowerCase(),
			f = "",
			g = function(a, b) {
				return "-" + b.toLowerCase()
			};
			if (a && a.color && (a.fill = a.color), a = extend(b.styles, a), b.styles = a, useCanVG && d && delete a.width, isIE && !hasSVG) d && delete a.width,
			css(b.element, a);
			else {
				for (e in a) f += e.replace(/([A-Z])/g, g) + ":" + a[e] + ";";
				b.attr({
					style: f
				})
			}
			return d && b.added && b.renderer.buildText(b),
			b
		},
		on: function(a, b) {
			var c = b;
			return hasTouch && "click" === a && (a = "touchstart", c = function(a) {
				a.preventDefault(),
				b()
			}),
			this.element["on" + a] = c,
			this
		},
		setRadialReference: function(a) {
			return this.element.radialReference = a,
			this
		},
		translate: function(a, b) {
			return this.attr({
				translateX: a,
				translateY: b
			})
		},
		invert: function() {
			var a = this;
			return a.inverted = !0,
			a.updateTransform(),
			a
		},
		htmlCss: function(a) {
			var b = this,
			c = b.element,
			d = a && "SPAN" === c.tagName && a.width;
			return d && (delete a.width, b.textWidth = d, b.updateTransform()),
			b.styles = extend(b.styles, a),
			css(b.element, a),
			b
		},
		htmlGetBBox: function() {
			var a = this,
			b = a.element,
			c = a.bBox;
			return c || ("text" === b.nodeName && (b.style.position = ABSOLUTE), c = a.bBox = {
				x: b.offsetLeft,
				y: b.offsetTop,
				width: b.offsetWidth,
				height: b.offsetHeight
			}),
			c
		},
		htmlUpdateTransform: function() {
			var a, b, c, d, e, f, g, h, i, j, k, l, m, o, s, y, n, p, q, r, t, u, v, w, x;
			return this.added ? (a = this, b = a.renderer, c = a.element, d = a.translateX || 0, e = a.translateY || 0, f = a.x || 0, g = a.y || 0, h = a.textAlign || "left", i = {
				left: 0,
				center: .5,
				right: 1
			} [h], j = h && "left" !== h, k = a.shadows, (d || e) && (css(c, {
				marginLeft: d,
				marginTop: e
			}), k && each(k,
			function(a) {
				css(a, {
					marginLeft: d + 1,
					marginTop: e + 1
				})
			})), a.inverted && each(c.childNodes,
			function(a) {
				b.invertChild(a, c)
			}), "SPAN" === c.tagName && (n = a.rotation, p = 0, q = 1, r = 0, t = pInt(a.textWidth), u = a.xCorr || 0, v = a.yCorr || 0, w = [n, h, c.innerHTML, a.textWidth].join(","), x = {},
			w !== a.cTT && (defined(n) && (b.isSVG ? (y = isIE ? "-ms-transform": isWebKit ? "-webkit-transform": isFirefox ? "MozTransform": isOpera ? "-o-transform": "", x[y] = x.transform = "rotate(" + n + "deg)") : (p = n * deg2rad, q = mathCos(p), r = mathSin(p), x.filter = n ? ["progid:DXImageTransform.Microsoft.Matrix(M11=", q, ", M12=", -r, ", M21=", r, ", M22=", q, ", sizingMethod='auto expand')"].join("") : NONE), css(c, x)), l = pick(a.elemWidth, c.offsetWidth), m = pick(a.elemHeight, c.offsetHeight), l > t && /[ \-]/.test(c.innerText) && (css(c, {
				width: t + PX,
				display: "block",
				whiteSpace: "normal"
			}), l = t), o = b.fontMetrics(c.style.fontSize).b, u = 0 > q && -l, v = 0 > r && -m, s = 0 > q * r, u += r * o * (s ? 1 - i: i), v -= q * o * (n ? s ? i: 1 - i: 1), j && (u -= l * i * (0 > q ? -1 : 1), n && (v -= m * i * (0 > r ? -1 : 1)), css(c, {
				textAlign: h
			})), a.xCorr = u, a.yCorr = v), css(c, {
				left: f + u + PX,
				top: g + v + PX
			}), a.cTT = w), void 0) : (this.alignOnAdd = !0, void 0)
		},
		updateTransform: function() {
			var a = this,
			b = a.translateX || 0,
			c = a.translateY || 0,
			d = a.inverted,
			e = a.rotation,
			f = [];
			d && (b += a.attr("width"), c += a.attr("height")),
			(b || c) && f.push("translate(" + b + "," + c + ")"),
			d ? f.push("rotate(90) scale(-1,1)") : e && f.push("rotate(" + e + " " + (a.x || 0) + " " + (a.y || 0) + ")"),
			f.length && attr(a.element, "transform", f.join(" "))
		},
		toFront: function() {
			var a = this.element;
			return a.parentNode.appendChild(a),
			this
		},
		align: function(a, b, c) {
			var e, f, g, h, i, d = this;
			return a ? (d.alignOptions = a, d.alignByTranslate = b, c || d.renderer.alignedObjects.push(d)) : (a = d.alignOptions, b = d.alignByTranslate),
			c = pick(c, d.renderer),
			e = a.align,
			f = a.verticalAlign,
			g = (c.x || 0) + (a.x || 0),
			h = (c.y || 0) + (a.y || 0),
			i = {},
			("right" === e || "center" === e) && (g += (c.width - (a.width || 0)) / {
				right: 1,
				center: 2
			} [e]),
			i[b ? "translateX": "x"] = mathRound(g),
			("bottom" === f || "middle" === f) && (h += (c.height - (a.height || 0)) / ({
				bottom: 1,
				middle: 2
			} [f] || 1)),
			i[b ? "translateY": "y"] = mathRound(h),
			d[d.placed ? "animate": "attr"](i),
			d.placed = !0,
			d.alignAttr = i,
			d
		},
		getBBox: function() {
			var d, e, a = this,
			b = a.bBox,
			c = a.renderer,
			f = a.rotation,
			g = a.element,
			h = a.styles,
			i = f * deg2rad;
			if (!b) {
				if (g.namespaceURI === SVG_NS || c.forExport) {
					try {
						b = g.getBBox ? extend({},
						g.getBBox()) : {
							width: g.offsetWidth,
							height: g.offsetHeight
						}
					} catch(j) {} (!b || b.width < 0) && (b = {
						width: 0,
						height: 0
					})
				} else b = a.htmlGetBBox();
				c.isSVG && (d = b.width, e = b.height, f && (b.width = mathAbs(e * mathSin(i)) + mathAbs(d * mathCos(i)), b.height = mathAbs(e * mathCos(i)) + mathAbs(d * mathSin(i)))),
				isIE && h && "11px" === h.fontSize && 22.700000762939453 === e && (b.height = 14),
				a.bBox = b
			}
			return b
		},
		show: function() {
			return this.attr({
				visibility: VISIBLE
			})
		},
		hide: function() {
			return this.attr({
				visibility: HIDDEN
			})
		},
		add: function(a) {
			var h, i, j, k, b = this.renderer,
			c = a || b,
			d = c.element || b.box,
			e = d.childNodes,
			f = this.element,
			g = attr(f, "zIndex");
			if (a && (this.parentGroup = a), this.parentInverted = a && a.inverted, void 0 !== this.textStr && b.buildText(this), g && (c.handleZ = !0, g = pInt(g)), c.handleZ) for (j = 0; j < e.length; j++) if (h = e[j], i = attr(h, "zIndex"), h !== f && (pInt(i) > g || !defined(g) && defined(i))) {
				d.insertBefore(f, h),
				k = !0;
				break
			}
			return k || d.appendChild(f),
			this.added = !0,
			fireEvent(this, "add"),
			this
		},
		safeRemoveChild: function(a) {
			var b = a.parentNode;
			b && b.removeChild(a)
		},
		destroy: function() {
			var d, e, a = this,
			b = a.element || {},
			c = a.shadows;
			if (b.onclick = b.onmouseout = b.onmouseover = b.onmousemove = null, stop(a), a.clipPath && (a.clipPath = a.clipPath.destroy()), a.stops) {
				for (e = 0; e < a.stops.length; e++) a.stops[e] = a.stops[e].destroy();
				a.stops = null
			}
			a.safeRemoveChild(b),
			c && each(c,
			function(b) {
				a.safeRemoveChild(b)
			}),
			erase(a.renderer.alignedObjects, a);
			for (d in a) delete a[d];
			return null
		},
		empty: function() {
			for (var a = this.element,
			b = a.childNodes,
			c = b.length; c--;) a.removeChild(b[c])
		},
		shadow: function(a, b, c) {
			var e, f, h, i, j, k, d = [],
			g = this.element;
			if (a) {
				for (i = pick(a.width, 3), j = (a.opacity || .15) / i, k = this.parentInverted ? "(-1,-1)": "(" + pick(a.offsetX, 1) + ", " + pick(a.offsetY, 1) + ")", e = 1; i >= e; e++) f = g.cloneNode(0),
				h = 2 * i + 1 - 2 * e,
				attr(f, {
					isShadow: "true",
					stroke: a.color || "black",
					"stroke-opacity": j * e,
					"stroke-width": h,
					transform: "translate" + k,
					fill: NONE
				}),
				c && (attr(f, "height", mathMax(attr(f, "height") - h, 0)), f.cutHeight = h),
				b ? b.element.appendChild(f) : g.parentNode.insertBefore(f, g),
				d.push(f);
				this.shadows = d
			}
			return this
		}
	},
	SVGRenderer = function() {
		this.init.apply(this, arguments)
	},
	SVGRenderer.prototype = {
		Element: SVGElement,
		init: function(a, b, c, d) {
			var h, i, e = this,
			f = location,
			g = e.createElement("svg").attr({
				xmlns: SVG_NS,
				version: "1.1"
			});
			a.appendChild(g.element),
			e.isSVG = !0,
			e.box = g.element,
			e.boxWrapper = g,
			e.alignedObjects = [],
			e.url = (isFirefox || isWebKit) && doc.getElementsByTagName("base").length ? f.href.replace(/#.*?$/, "").replace(/([\('\)])/g, "\\$1").replace(/ /g, "%20") : "",
			e.defs = this.createElement("defs").add(),
			e.forExport = d,
			e.gradients = {},
			e.setSize(b, c, !1),
			isFirefox && a.getBoundingClientRect && (e.subPixelFix = h = function() {
				css(a, {
					left: 0,
					top: 0
				}),
				i = a.getBoundingClientRect(),
				css(a, {
					left: mathCeil(i.left) - i.left + PX,
					top: mathCeil(i.top) - i.top + PX
				})
			},
			h(), addEvent(win, "resize", h))
		},
		isHidden: function() {
			return ! this.boxWrapper.getBBox().width
		},
		destroy: function() {
			var a = this,
			b = a.defs;
			return a.box = null,
			a.boxWrapper = a.boxWrapper.destroy(),
			destroyObjectProperties(a.gradients || {}),
			a.gradients = null,
			b && (a.defs = b.destroy()),
			a.subPixelFix && removeEvent(win, "resize", a.subPixelFix),
			a.alignedObjects = null,
			null
		},
		createElement: function(a) {
			var b = new this.Element;
			return b.init(this, a),
			b
		},
		draw: function() {},
		buildText: function(a) {
			function o(c) {
				return n[c] = b.getBBox ? b.getBBox().height: a.renderer.fontMetrics(b.style.fontSize).h,
				mathRound(n[c] - (n[c - 1] || 0))
			}
			for (var k, b = a.element,
			c = pick(a.textStr, "").toString().replace(/<(b|strong)>/g, '<span style="font-weight:bold">').replace(/<(i|em)>/g, '<span style="font-style:italic">').replace(/<a/g, "<span").replace(/<\/(b|strong|i|em|a)>/g, "</span>").split(/<br.*?>/g), d = b.childNodes, e = /style="([^"]+)"/, f = /href="([^"]+)"/, g = attr(b, "x"), h = a.styles, i = h && h.width && pInt(h.width), j = h && h.lineHeight, l = "getComputedStyle", m = d.length, n = []; m--;) b.removeChild(d[m]);
			i && !a.added && this.box.appendChild(b),
			"" === c[c.length - 1] && c.pop(),
			each(c,
			function(c, d) {
				var h, n, m = 0;
				c = c.replace(/<span/g, "|||<span").replace(/<\/span>/g, "</span>|||"),
				h = c.split("|||"),
				each(h,
				function(c) {
					var r, p, q, t, u, s, v;
					if (("" !== c || 1 === h.length) && (p = {},
					q = doc.createElementNS(SVG_NS, "tspan"), e.test(c) && (r = c.match(e)[1].replace(/(;| |^)color([ :])/, "$1fill$2"), attr(q, "style", r)), f.test(c) && (attr(q, "onclick", 'location.href="' + c.match(f)[1] + '"'), css(q, {
						cursor: "pointer"
					})), c = (c.replace(/<(.|\n)*?>/g, "") || " ").replace(/&lt;/g, "<").replace(/&gt;/g, ">"), q.appendChild(doc.createTextNode(c)), m ? p.dx = 3 : p.x = g, m || (d && (!hasSVG && a.renderer.forExport && css(q, {
						display: "block"
					}), n = win[l] && pInt(win[l](k, null).getPropertyValue("line-height")), (!n || isNaN(n)) && (n = j || k.offsetHeight || o(d) || 18), attr(q, "dy", n)), k = q), attr(q, p), b.appendChild(q), m++, i)) for (s = c.replace(/([^\^])-/g, "$1- ").split(" "), v = []; s.length || v.length;) delete a.bBox,
					u = a.getBBox().width,
					t = u > i,
					t && 1 !== s.length ? (q.removeChild(q.firstChild), v.unshift(s.pop())) : (s = v, v = [], s.length && (q = doc.createElementNS(SVG_NS, "tspan"), attr(q, {
						dy: j || 16,
						x: g
					}), r && attr(q, "style", r), b.appendChild(q), u > i && (i = u))),
					s.length && q.appendChild(doc.createTextNode(s.join(" ").replace(/- /g, "-")))
				})
			})
		},
		button: function(a, b, c, d, e, f, g) {
			var j, k, l, m, n, h = this.label(a, b, c),
			i = 0,
			o = "style",
			p = {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
			};
			return e = merge(hash(STROKE_WIDTH, 1, STROKE, "#999", FILL, hash(LINEAR_GRADIENT, p, STOPS, [[0, "#FFF"], [1, "#DDD"]]), "r", 3, "padding", 3, o, hash("color", "black")), e),
			l = e[o],
			delete e[o],
			f = merge(e, hash(STROKE, "#68A", FILL, hash(LINEAR_GRADIENT, p, STOPS, [[0, "#FFF"], [1, "#ACF"]])), f),
			m = f[o],
			delete f[o],
			g = merge(e, hash(STROKE, "#68A", FILL, hash(LINEAR_GRADIENT, p, STOPS, [[0, "#9BD"], [1, "#CDF"]])), g),
			n = g[o],
			delete g[o],
			addEvent(h.element, "mouseenter",
			function() {
				h.attr(f).css(m)
			}),
			addEvent(h.element, "mouseleave",
			function() {
				j = [e, f, g][i],
				k = [l, m, n][i],
				h.attr(j).css(k)
			}),
			h.setState = function(a) {
				i = a,
				a ? 2 === a && h.attr(g).css(n) : h.attr(e).css(l)
			},
			h.on("click",
			function() {
				d.call(h)
			}).attr(e).css(extend({
				cursor: "default"
			},
			l))
		},
		crispLine: function(a, b) {
			return a[1] === a[4] && (a[1] = a[4] = mathRound(a[1]) - b % 2 / 2),
			a[2] === a[5] && (a[2] = a[5] = mathRound(a[2]) + b % 2 / 2),
			a
		},
		path: function(a) {
			var b = {
				fill: NONE
			};
			return isArray(a) ? b.d = a: isObject(a) && extend(b, a),
			this.createElement("path").attr(b)
		},
		circle: function(a, b, c) {
			var d = isObject(a) ? a: {
				x: a,
				y: b,
				r: c
			};
			return this.createElement("circle").attr(d)
		},
		arc: function(a, b, c, d, e, f) {
			return isObject(a) && (b = a.y, c = a.r, d = a.innerR, e = a.start, f = a.end, a = a.x),
			this.symbol("arc", a || 0, b || 0, c || 0, c || 0, {
				innerR: d || 0,
				start: e || 0,
				end: f || 0
			})
		},
		rect: function(a, b, c, d, e, f) {
			e = isObject(a) ? a.r: e;
			var g = this.createElement("rect").attr({
				rx: e,
				ry: e,
				fill: NONE
			});
			return g.attr(isObject(a) ? a: g.crisp(f, a, b, mathMax(c, 0), mathMax(d, 0)))
		},
		setSize: function(a, b, c) {
			var d = this,
			e = d.alignedObjects,
			f = e.length;
			for (d.width = a, d.height = b, d.boxWrapper[pick(c, !0) ? "animate": "attr"]({
				width: a,
				height: b
			}); f--;) e[f].align()
		},
		g: function(a) {
			var b = this.createElement("g");
			return defined(a) ? b.attr({
				"class": PREFIX + a
			}) : b
		},
		image: function(a, b, c, d, e) {
			var g, f = {
				preserveAspectRatio: NONE
			};
			return arguments.length > 1 && extend(f, {
				x: b,
				y: c,
				width: d,
				height: e
			}),
			g = this.createElement("image").attr(f),
			g.element.setAttributeNS ? g.element.setAttributeNS("http://www.w3.org/1999/xlink", "href", a) : g.element.setAttribute("hc-svg-href", a),
			g
		},
		symbol: function(a, b, c, d, e, f) {
			var g, k, l, m, h = this.symbols[a],
			i = h && h(mathRound(b), mathRound(c), d, e, f),
			j = /^url\((.*?)\)$/;
			return i ? (g = this.path(i), extend(g, {
				symbolName: a,
				x: b,
				y: c,
				width: d,
				height: e
			}), f && extend(g, f)) : j.test(a) && (m = function(a, b) {
				a.attr({
					width: b[0],
					height: b[1]
				}),
				a.alignByTranslate || a.translate( - mathRound(b[0] / 2), -mathRound(b[1] / 2))
			},
			k = a.match(j)[1], l = symbolSizes[k], g = this.image(k).attr({
				x: b,
				y: c
			}), l ? m(g, l) : (g.attr({
				width: 0,
				height: 0
			}), createElement("img", {
				onload: function() {
					var a = this;
					m(g, symbolSizes[k] = [a.width, a.height])
				},
				src: k
			}))),
			g
		},
		symbols: {
			circle: function(a, b, c, d) {
				var e = .166 * c;
				return [M, a + c / 2, b, "C", a + c + e, b, a + c + e, b + d, a + c / 2, b + d, "C", a - e, b + d, a - e, b, a + c / 2, b, "Z"]
			},
			square: function(a, b, c, d) {
				return [M, a, b, L, a + c, b, a + c, b + d, a, b + d, "Z"]
			},
			triangle: function(a, b, c, d) {
				return [M, a + c / 2, b, L, a + c, b + d, a, b + d, "Z"]
			},
			"triangle-down": function(a, b, c, d) {
				return [M, a, b, L, a + c, b, a + c / 2, b + d, "Z"]
			},
			diamond: function(a, b, c, d) {
				return [M, a + c / 2, b, L, a + c, b + d / 2, a + c / 2, b + d, a, b + d / 2, "Z"]
			},
			arc: function(a, b, c, d, e) {
				var f = e.start,
				g = e.r || c || d,
				h = e.end - 1e-6,
				i = e.innerR,
				j = e.open,
				k = mathCos(f),
				l = mathSin(f),
				m = mathCos(h),
				n = mathSin(h),

				o = e.end - f < mathPI ? 0 : 1;
				return [M, a + g * k, b + g * l, "A", g, g, 0, o, 1, a + g * m, b + g * n, j ? M: L, a + i * m, b + i * n, "A", i, i, 0, o, 0, a + i * k, b + i * l, j ? "": "Z"]
			}
		},
		clipRect: function(a, b, c, d) {
			var e, f = PREFIX + idCounter++,
			g = this.createElement("clipPath").attr({
				id: f
			}).add(this.defs);
			return e = this.rect(a, b, c, d, 0).add(g),
			e.id = f,
			e.clipPath = g,
			e
		},
		color: function(a, b, c) {
			var e, g, j, k, l, h, i, m, d = this,
			f = /^rgba/;
			return a && a.linearGradient ? g = "linearGradient": a && a.radialGradient && (g = "radialGradient"),
			g ? (h = a[g], i = d.gradients, m = b.radialReference, h.id && i[h.id] || (isArray(h) && (a[g] = h = {
				x1: h[0],
				y1: h[1],
				x2: h[2],
				y2: h[3],
				gradientUnits: "userSpaceOnUse"
			}), "radialGradient" === g && m && !defined(h.gradientUnits) && extend(h, {
				cx: m[0] - m[2] / 2 + h.cx * m[2],
				cy: m[1] - m[2] / 2 + h.cy * m[2],
				r: h.r * m[2],
				gradientUnits: "userSpaceOnUse"
			}), h.id = PREFIX + idCounter++, i[h.id] = j = d.createElement(g).attr(h).add(d.defs), j.stops = [], each(a.stops,
			function(a) {
				var b;
				f.test(a[1]) ? (e = Color(a[1]), k = e.get("rgb"), l = e.get("a")) : (k = a[1], l = 1),
				b = d.createElement("stop").attr({
					offset: a[0],
					"stop-color": k,
					"stop-opacity": l
				}).add(j),
				j.stops.push(b)
			})), "url(" + d.url + "#" + h.id + ")") : f.test(a) ? (e = Color(a), attr(b, c + "-opacity", e.get("a")), e.get("rgb")) : (b.removeAttribute(c + "-opacity"), a)
		},
		text: function(a, b, c, d) {
			var h, e = this,
			f = defaultOptions.chart.style,
			g = useCanVG || !hasSVG && e.forExport;
			return d && !e.forExport ? e.html(a, b, c) : (b = mathRound(pick(b, 0)), c = mathRound(pick(c, 0)), h = e.createElement("text").attr({
				x: b,
				y: c,
				text: a
			}).css({
				fontFamily: f.fontFamily,
				fontSize: f.fontSize
			}), g && h.css({
				position: ABSOLUTE
			}), h.x = b, h.y = c, h)
		},
		html: function(a, b, c) {
			var d = defaultOptions.chart.style,
			e = this.createElement("span"),
			f = e.attrSetters,
			g = e.element,
			h = e.renderer;
			return f.text = function(a) {
				return a !== g.innerHTML && delete this.bBox,
				g.innerHTML = a,
				!1
			},
			f.x = f.y = f.align = function(a, b) {
				return "align" === b && (b = "textAlign"),
				e[b] = a,
				e.htmlUpdateTransform(),
				!1
			},
			e.attr({
				text: a,
				x: mathRound(b),
				y: mathRound(c)
			}).css({
				position: ABSOLUTE,
				whiteSpace: "nowrap",
				fontFamily: d.fontFamily,
				fontSize: d.fontSize
			}),
			e.css = e.htmlCss,
			h.isSVG && (e.add = function(a) {
				var b, d, c = h.box.parentNode,
				f = [];
				if (a) {
					if (b = a.div, !b) {
						for (d = a; d;) f.push(d),
						d = d.parentGroup;
						each(f.reverse(),
						function(a) {
							var d;
							b = a.div = a.div || createElement(DIV, {
								className: attr(a.element, "class")
							},
							{
								position: ABSOLUTE,
								left: (a.translateX || 0) + PX,
								top: (a.translateY || 0) + PX
							},
							b || c),
							d = b.style,
							extend(a.attrSetters, {
								translateX: function(a) {
									d.left = a + PX
								},
								translateY: function(a) {
									d.top = a + PX
								},
								visibility: function(a, b) {
									d[b] = a
								}
							})
						})
					}
				} else b = c;
				return b.appendChild(g),
				e.added = !0,
				e.alignOnAdd && e.htmlUpdateTransform(),
				e
			}),
			e
		},
		fontMetrics: function(a) {
			a = pInt(a || 11);
			var b = 24 > a ? a + 4 : mathRound(1.2 * a),
			c = mathRound(.8 * b);
			return {
				h: b,
				b: c
			}
		},
		label: function(a, b, c, d, e, f, g, h, i) {
			function y() {
				var a, b = l.element.style;
				n = (void 0 === q || void 0 === r || k.styles.textAlign) && l.getBBox(),
				k.width = (q || n.width || 0) + 2 * p,
				k.height = (r || n.height || 0) + 2 * p,
				w = p + j.fontMetrics(b && b.fontSize).b,
				m || (a = h ? -w: 0, k.box = m = d ? j.symbol(d, -o * p, a, k.width, k.height) : j.rect( - o * p, a, k.width, k.height, 0, v[STROKE_WIDTH]), m.add(k)),
				m.attr(merge({
					width: k.width,
					height: k.height
				},
				v)),
				v = null
			}
			function z() {
				var d, a = k.styles,
				b = a && a.textAlign,
				c = p * (1 - o);
				d = h ? 0 : w,
				!defined(q) || "center" !== b && "right" !== b || (c += {
					center: .5,
					right: 1
				} [b] * (q - n.width)),
				(c !== l.x || d !== l.y) && l.attr({
					x: c,
					y: d
				}),
				l.x = c,
				l.y = d
			}
			function A(a, b) {
				m ? m.attr(a, b) : v[a] = b
			}
			function B() {
				l.add(k),
				k.attr({
					text: a,
					x: b,
					y: c
				}),
				defined(e) && k.attr({
					anchorX: e,
					anchorY: f
				})
			}
			var m, n, q, r, s, t, w, C, j = this,
			k = j.g(i),
			l = j.text("", 0, 0, g).attr({
				zIndex: 1
			}),
			o = 0,
			p = 3,
			u = 0,
			v = {},
			x = k.attrSetters;
			return addEvent(k, "add", B),
			x.width = function(a) {
				return q = a,
				!1
			},
			x.height = function(a) {
				return r = a,
				!1
			},
			x.padding = function(a) {
				return defined(a) && a !== p && (p = a, z()),
				!1
			},
			x.align = function(a) {
				return o = {
					left: 0,
					center: .5,
					right: 1
				} [a],
				!1
			},
			x.text = function(a, b) {
				return l.attr(b, a),
				y(),
				z(),
				!1
			},
			x[STROKE_WIDTH] = function(a, b) {
				return u = a % 2 / 2,
				A(b, a),
				!1
			},
			x.stroke = x.fill = x.r = function(a, b) {
				return A(b, a),
				!1
			},
			x.anchorX = function(a, b) {
				return e = a,
				A(b, a + u - s),
				!1
			},
			x.anchorY = function(a, b) {
				return f = a,
				A(b, a - t),
				!1
			},
			x.x = function(a) {
				return k.x = a,
				a -= o * ((q || n.width) + p),
				s = mathRound(a),
				k.attr("translateX", s),
				!1
			},
			x.y = function(a) {
				return t = k.y = mathRound(a),
				k.attr("translateY", a),
				!1
			},
			C = k.css,
			extend(k, {
				css: function(a) {
					if (a) {
						var b = {};
						a = merge({},
						a),
						each(["fontSize", "fontWeight", "fontFamily", "color", "lineHeight", "width"],
						function(c) {
							a[c] !== UNDEFINED && (b[c] = a[c], delete a[c])
						}),
						l.css(b)
					}
					return C.call(k, a)
				},
				getBBox: function() {
					return m.getBBox()
				},
				shadow: function(a) {
					return m.shadow(a),
					k
				},
				destroy: function() {
					removeEvent(k, "add", B),
					removeEvent(k.element, "mouseenter"),
					removeEvent(k.element, "mouseleave"),
					l && (l = l.destroy()),
					m && (m = m.destroy()),
					SVGElement.prototype.destroy.call(k)
				}
			})
		}
	},
	Renderer = SVGRenderer,
	hasSVG || useCanVG || (VMLElement = {
		init: function(a, b) {
			var c = this,
			d = ["<", b, ' filled="f" stroked="f"'],
			e = ["position: ", ABSOLUTE, ";"]; ("shape" === b || b === DIV) && e.push("left:0;top:0;width:1px;height:1px;"),
			docMode8 && e.push("visibility: ", b === DIV ? HIDDEN: VISIBLE),
			d.push(' style="', e.join(""), '"/>'),
			b && (d = b === DIV || "span" === b || "img" === b ? d.join("") : a.prepVML(d), c.element = createElement(d)),
			c.renderer = a,
			c.attrSetters = {}
		},
		add: function(a) {
			var b = this,
			c = b.renderer,
			d = b.element,
			e = c.box,
			f = a && a.inverted,
			g = a ? a.element || a: e;
			return f && c.invertChild(d, g),
			g.appendChild(d),
			b.added = !0,
			b.alignOnAdd && !b.deferUpdateTransform && b.updateTransform(),
			fireEvent(b, "add"),
			b
		},
		updateTransform: SVGElement.prototype.htmlUpdateTransform,
		attr: function(a, b) {
			var d, e, f, g, m, o, r, s, c = this,
			h = c.element || {},
			i = h.style,
			j = h.nodeName,
			k = c.renderer,
			l = c.symbolName,
			n = c.shadows,
			p = c.attrSetters,
			q = c;
			if (isString(a) && defined(b) && (d = a, a = {},
			a[d] = b), isString(a)) d = a,
			q = "strokeWidth" === d || "stroke-width" === d ? c.strokeweight: c[d];
			else for (d in a) if (e = a[d], o = !1, g = p[d] && p[d].call(c, e, d), g !== !1 && null !== e) {
				if (g !== UNDEFINED && (e = g), l && /^(x|y|r|start|end|width|height|innerR|anchorX|anchorY)/.test(d)) m || (c.symbolAttr(a), m = !0),
				o = !0;
				else if ("d" === d) {
					for (e = e || [], c.d = e.join(" "), f = e.length, r = []; f--;) r[f] = isNumber(e[f]) ? mathRound(10 * e[f]) - 5 : "Z" === e[f] ? "x": e[f];
					if (e = r.join(" ") || "x", h.path = e, n) for (f = n.length; f--;) n[f].path = n[f].cutOff ? this.cutOffPath(e, n[f].cutOff) : e;
					o = !0
				} else if ("visibility" === d) {
					if (n) for (f = n.length; f--;) n[f].style[d] = e;
					"DIV" === j && (e = e === HIDDEN ? "-999em": 0, d = "top"),
					i[d] = e,
					o = !0
				} else "zIndex" === d ? (e && (i[d] = e), o = !0) : "width" === d || "height" === d ? (e = mathMax(0, e), this[d] = e, c.updateClipping ? (c[d] = e, c.updateClipping()) : i[d] = e, o = !0) : "x" === d || "y" === d ? (c[d] = e, i[{
					x: "left",
					y: "top"
				} [d]] = e) : "class" === d ? h.className = e: "stroke" === d ? (e = k.color(e, h, d), d = "strokecolor") : "stroke-width" === d || "strokeWidth" === d ? (h.stroked = e ? !0 : !1, d = "strokeweight", c[d] = e, isNumber(e) && (e += PX)) : "dashstyle" === d ? (s = h.getElementsByTagName("stroke")[0] || createElement(k.prepVML(["<stroke/>"]), null, null, h), s[d] = e || "solid", c.dashstyle = e, o = !0) : "fill" === d ? "SPAN" === j ? i.color = e: (h.filled = e !== NONE ? !0 : !1, e = k.color(e, h, d, c), d = "fillcolor") : "shape" === j && "rotation" === d ? (c[d] = e, h.style.left = -mathRound(mathSin(e * deg2rad) + 1) + PX, h.style.top = mathRound(mathCos(e * deg2rad)) + PX) : "translateX" === d || "translateY" === d || "rotation" === d ? (c[d] = e, c.updateTransform(), o = !0) : "text" === d && (this.bBox = null, h.innerHTML = e, o = !0);
				o || (docMode8 ? h[d] = e: attr(h, d, e))
			}
			return q
		},
		clip: function(a) {
			var c, f, b = this,
			d = b.element,
			e = d.parentNode;
			return a ? (c = a.members, c.push(b), b.destroyClip = function() {
				erase(c, b)
			},
			e && "highcharts-tracker" === e.className && !docMode8 && css(d, {
				visibility: HIDDEN
			}), f = a.getCSS(b)) : (b.destroyClip && b.destroyClip(), f = {
				clip: docMode8 ? "inherit": "rect(auto)"
			}),
			b.css(f)
		},
		css: SVGElement.prototype.htmlCss,
		safeRemoveChild: function(a) {
			a.parentNode && discardElement(a)
		},
		destroy: function() {
			return this.destroyClip && this.destroyClip(),
			SVGElement.prototype.destroy.apply(this)
		},
		empty: function() {
			for (var d, a = this.element,
			b = a.childNodes,
			c = b.length; c--;) d = b[c],
			d.parentNode.removeChild(d)
		},
		on: function(a, b) {
			return this.element["on" + a] = function() {
				var a = win.event;
				a.target = a.srcElement,
				b(a)
			},
			this
		},
		cutOffPath: function(a, b) {
			var c;
			return a = a.split(/[ ,]/),
			c = a.length,
			(9 === c || 11 === c) && (a[c - 4] = a[c - 2] = pInt(a[c - 2]) - 10 * b),
			a.join(" ")
		},
		shadow: function(a, b, c) {
			var e, h, j, l, m, n, o, d = [],
			f = this.element,
			g = this.renderer,
			i = f.style,
			k = f.path;
			if (k && "string" != typeof k.value && (k = "x"), m = k, a) {
				for (n = pick(a.width, 3), o = (a.opacity || .15) / n, e = 1; 3 >= e; e++) l = 2 * n + 1 - 2 * e,
				c && (m = this.cutOffPath(k.value, l + .5)),
				j = ['<shape isShadow="true" strokeweight="', l, '" filled="false" path="', m, '" coordsize="10 10" style="', f.style.cssText, '" />'],
				h = createElement(g.prepVML(j), null, {
					left: pInt(i.left) + pick(a.offsetX, 1),
					top: pInt(i.top) + pick(a.offsetY, 1)
				}),
				c && (h.cutOff = l + 1),
				j = ['<stroke color="', a.color || "black", '" opacity="', o * e, '"/>'],
				createElement(g.prepVML(j), null, null, h),
				b ? b.element.appendChild(h) : f.parentNode.insertBefore(h, f),
				d.push(h);
				this.shadows = d
			}
			return this
		}
	},
	VMLElement = extendClass(SVGElement, VMLElement), VMLRendererExtension = {
		Element: VMLElement,
		isIE8: userAgent.indexOf("MSIE 8.0") > -1,
		init: function(a, b, c) {
			var e, f, d = this;
			d.alignedObjects = [],
			e = d.createElement(DIV),
			f = e.element,
			f.style.position = RELATIVE,
			a.appendChild(e.element),
			d.box = f,
			d.boxWrapper = e,
			d.setSize(b, c, !1),
			doc.namespaces.hcv || (doc.namespaces.add("hcv", "urn:schemas-microsoft-com:vml"), doc.createStyleSheet().cssText = "hcv\\:fill, hcv\\:path, hcv\\:shape, hcv\\:stroke{ behavior:url(#default#VML); display: inline-block; } ")
		},
		isHidden: function() {
			return ! this.box.offsetWidth
		},
		clipRect: function(a, b, c, d) {
			var e = this.createElement(),
			f = isObject(a);
			return extend(e, {
				members: [],
				left: f ? a.x: a,
				top: f ? a.y: b,
				width: f ? a.width: c,
				height: f ? a.height: d,
				getCSS: function(a) {
					var b = a.inverted,
					c = this,
					d = c.top,
					e = c.left,
					f = e + c.width,
					g = d + c.height,
					h = {
						clip: "rect(" + mathRound(b ? e: d) + "px," + mathRound(b ? g: f) + "px," + mathRound(b ? f: g) + "px," + mathRound(b ? d: e) + "px)"
					};
					return ! b && docMode8 && "IMG" !== a.element.nodeName && extend(h, {
						width: f + PX,
						height: g + PX
					}),
					h
				},
				updateClipping: function() {
					each(e.members,
					function(a) {
						a.css(e.getCSS(a))
					})
				}
			})
		},
		color: function(a, b, c, d) {
			var f, h, i, k, l, n, o, p, q, r, s, t, u, x, y, m, v, w, z, A, H, B, C, D, E, F, G, I, J, e = this,
			g = /^rgba/,
			j = NONE;
			return a && a.linearGradient ? i = "gradient": a && a.radialGradient && (i = "pattern"),
			i ? (m = a.linearGradient || a.radialGradient, v = "", w = a.stops, z = [], A = function() {
				h = ['<fill colors="' + z.join(",") + '" opacity="', s, '" o:opacity2="', r, '" type="', i, '" ', v, 'focus="100%" method="any" />'],
				createElement(e.prepVML(h), null, null, b)
			},
			x = w[0], y = w[w.length - 1], x[0] > 0 && w.unshift([0, x[1]]), y[0] < 1 && w.push([1, y[1]]), each(w,
			function(a, b) {
				g.test(a[1]) ? (f = Color(a[1]), k = f.get("rgb"), l = f.get("a")) : (k = a[1], l = 1),
				z.push(100 * a[0] + "% " + k),
				b ? (s = l, t = k) : (r = l, u = k)
			}), "fill" === c ? "gradient" === i ? (n = m.x1 || m[0] || 0, o = m.y1 || m[1] || 0, p = m.x2 || m[2] || 0, q = m.y2 || m[3] || 0, v = 'angle="' + (90 - 180 * math.atan((q - o) / (p - n)) / mathPI) + '"', A()) : (B = m.r, C = 2 * B, D = 2 * B, E = m.cx, F = m.cy, G = b.radialReference, I = function() {
				G && (H = d.getBBox(), E += (G[0] - H.x) / H.width - .5, F += (G[1] - H.y) / H.height - .5, C *= G[2] / H.width, D *= G[2] / H.height),
				v = 'src="' + defaultOptions.global.VMLRadialGradientURL + '" ' + 'size="' + C + "," + D + '" ' + 'origin="0.5,0.5" ' + 'position="' + E + "," + F + '" ' + 'color2="' + u + '" ',
				A()
			},
			d.added ? I() : addEvent(d, "add", I), j = t) : j = k) : g.test(a) && "IMG" !== b.tagName ? (f = Color(a), h = ["<", c, ' opacity="', f.get("a"), '"/>'], createElement(this.prepVML(h), null, null, b), j = f.get("rgb")) : (J = b.getElementsByTagName(c), J.length && (J[0].opacity = 1), j = a),
			j
		},
		prepVML: function(a) {
			var b = "display:inline-block;behavior:url(#default#VML);",
			c = this.isIE8;
			return a = a.join(""),
			c ? (a = a.replace("/>", ' xmlns="urn:schemas-microsoft-com:vml" />'), a = -1 === a.indexOf('style="') ? a.replace("/>", ' style="' + b + '" />') : a.replace('style="', 'style="' + b)) : a = a.replace("<", "<hcv:"),
			a
		},
		text: SVGRenderer.prototype.html,
		path: function(a) {
			var b = {
				coordsize: "10 10"
			};
			return isArray(a) ? b.d = a: isObject(a) && extend(b, a),
			this.createElement("shape").attr(b)
		},
		circle: function(a, b, c) {
			return this.symbol("circle").attr({
				x: a - c,
				y: b - c,
				width: 2 * c,
				height: 2 * c
			})
		},
		g: function(a) {
			var b, c;
			return a && (c = {
				className: PREFIX + a,
				"class": PREFIX + a
			}),
			b = this.createElement(DIV).attr(c)
		},
		image: function(a, b, c, d, e) {
			var f = this.createElement("img").attr({
				src: a
			});
			return arguments.length > 1 && f.attr({
				x: b,
				y: c,
				width: d,
				height: e
			}),
			f
		},
		rect: function(a, b, c, d, e, f) {
			isObject(a) && (b = a.y, c = a.width, d = a.height, f = a.strokeWidth, a = a.x);
			var g = this.symbol("rect");
			return g.r = e,
			g.attr(g.crisp(f, a, b, mathMax(c, 0), mathMax(d, 0)))
		},
		invertChild: function(a, b) {
			var c = b.style;
			css(a, {
				flip: "x",
				left: pInt(c.width) - 1,
				top: pInt(c.height) - 1,
				rotation: -90
			})
		},
		symbols: {
			arc: function(a, b, c, d, e) {
				var p, f = e.start,
				g = e.end,
				h = e.r || c || d,
				i = mathCos(f),
				j = mathSin(f),
				k = mathCos(g),
				l = mathSin(g),
				m = e.innerR,
				n = .08 / h,
				o = m && .1 / m || 0;
				return 0 === g - f ? ["x"] : (n > 2 * mathPI - g + f ? k = -n: o > g - f && (k = mathCos(f + o)), p = ["wa", a - h, b - h, a + h, b + h, a + h * i, b + h * j, a + h * k, b + h * l], e.open && !m && p.push("e", M, a, b), p.push("at", a - m, b - m, a + m, b + m, a + m * k, b + m * l, a + m * i, b + m * j, "x", "e"), p)
			},
			circle: function(a, b, c, d) {
				return ["wa", a, b, a + c, b + d, a + c, b + d / 2, a + c, b + d / 2, "e"]
			},
			rect: function(a, b, c, d, e) {
				var h, i, f = a + c,
				g = b + d;
				return defined(e) && e.r ? (i = mathMin(e.r, c, d), h = [M, a + i, b, L, f - i, b, "wa", f - 2 * i, b, f, b + 2 * i, f - i, b, f, b + i, L, f, g - i, "wa", f - 2 * i, g - 2 * i, f, g, f, g - i, f - i, g, L, a + i, g, "wa", a, g - 2 * i, a + 2 * i, g, a + i, g, a, g - i, L, a, b + i, "wa", a, b, a + 2 * i, b + 2 * i, a, b + i, a + i, b, "x", "e"]) : h = SVGRenderer.prototype.symbols.square.apply(0, arguments),
				h
			}
		}
	},
	VMLRenderer = function() {
		this.init.apply(this, arguments)
	},
	VMLRenderer.prototype = merge(SVGRenderer.prototype, VMLRendererExtension), Renderer = VMLRenderer),
	useCanVG && (CanVGRenderer = function() {
		SVG_NS = "http://www.w3.org/1999/xhtml"
	},
	CanVGRenderer.prototype.symbols = {},
	CanVGController = function() {
		function b() {
			var c, b = a.length;
			for (c = 0; b > c; c++) a[c]();
			a = []
		}
		var a = [];
		return {
			push: function(c, d) {
				0 === a.length && getScript(d, b),
				a.push(c)
			}
		}
	} ()),
	Renderer = VMLRenderer || CanVGRenderer || SVGRenderer,
	Tick.prototype = {
		addLabel: function() {
			var i, n, o, s, a = this,
			b = a.axis,
			c = b.options,
			d = b.chart,
			e = b.horiz,
			f = b.categories,
			g = a.pos,
			h = c.labels,
			j = b.tickPositions,
			k = f && e && f.length && !h.step && !h.staggerLines && !h.rotation && d.plotWidth / j.length || !e && d.plotWidth / 2,
			l = g === j[0],
			m = g === j[j.length - 1],
			p = f && defined(f[g]) ? f[g] : g,
			q = a.label,
			r = j.info;
			b.isDatetimeAxis && r && (s = c.dateTimeLabelFormats[r.higherRanks[g] || r.unitName]),
			a.isFirst = l,
			a.isLast = m,
			i = b.labelFormatter.call({
				axis: b,
				chart: d,
				isFirst: l,
				isLast: m,
				dateTimeLabelFormat: s,
				value: b.isLog ? correctFloat(lin2log(p)) : p
			}),
			n = k && {
				width: mathMax(1, mathRound(k - 2 * (h.padding || 10))) + PX
			},
			n = extend(n, h.style),
			defined(q) ? q && q.attr({
				text: i
			}).css(n) : (o = {
				align: h.align
			},
			isNumber(h.rotation) && (o.rotation = h.rotation), a.label = defined(i) && h.enabled ? d.renderer.text(i, 0, 0, h.useHTML).attr(o).css(n).add(b.labelGroup) : null)
		},
		getLabelSize: function() {
			var a = this.label,
			b = this.axis;
			return a ? (this.labelBBox = a.getBBox())[b.horiz ? "height": "width"] : 0
		},
		getLabelSides: function() {
			var a = this.labelBBox,
			b = this.axis,
			c = b.options,
			d = c.labels,
			e = a.width,
			f = e * {
				left: 0,
				center: .5,
				right: 1
			} [d.align] - d.x;
			return [ - f, e - f]
		},
		handleOverflow: function(a, b) {
			var k, l, m, n, o, p, q, c = !0,
			d = this.axis,
			e = d.chart,
			f = this.isFirst,
			g = this.isLast,
			h = b.x,
			i = d.reversed,
			j = d.tickPositions;
			return (f || g) && (k = this.getLabelSides(), l = k[0], m = k[1], n = e.plotLeft, o = n + d.len, p = d.ticks[j[a + (f ? 1 : -1)]], q = p && p.label.xy && p.label.xy.x + p.getLabelSides()[f ? 0 : 1], f && !i || g && i ? n > h + l && (h = n - l, p && h + m > q && (c = !1)) : h + m > o && (h = o - m, p && q > h + l && (c = !1)), b.x = h),
			c
		},
		getPosition: function(a, b, c, d) {
			var e = this.axis,
			f = e.chart,
			g = d && f.oldChartHeight || f.chartHeight;
			return {
				x: a ? e.translate(b + c, null, null, d) + e.transB: e.left + e.offset + (e.opposite ? (d && f.oldChartWidth || f.chartWidth) - e.right - e.left: 0),
				y: a ? g - e.bottom + e.offset - (e.opposite ? e.height: 0) : g - e.translate(b + c, null, null, d) - e.transB
			}
		},
		getLabelPosition: function(a, b, c, d, e, f, g, h) {
			var i = this.axis,
			j = i.transA,
			k = i.reversed,
			l = i.staggerLines;
			return a = a + e.x - (f && d ? f * j * (k ? -1 : 1) : 0),
			b = b + e.y - (f && !d ? f * j * (k ? 1 : -1) : 0),
			defined(e.y) || (b += .9 * pInt(c.styles.lineHeight) - c.getBBox().height / 2),
			l && (b += 16 * (g / (h || 1) % l)),
			{
				x: a,
				y: b
			}
		},
		getMarkPath: function(a, b, c, d, e, f) {
			return f.crispLine([M, a, b, L, a + (e ? 0 : -c), b + (e ? c: 0)], d)
		},
		render: function(a, b) {
			var w, y, A, c = this,
			d = c.axis,
			e = d.options,
			f = d.chart,
			g = f.renderer,
			h = d.horiz,
			i = c.type,
			j = c.label,
			k = c.pos,
			l = e.labels,
			m = c.gridLine,
			n = i ? i + "Grid": "grid",
			o = i ? i + "Tick": "tick",
			p = e[n + "LineWidth"],
			q = e[n + "LineColor"],
			r = e[n + "LineDashStyle"],
			s = e[o + "Length"],
			t = e[o + "Width"] || 0,
			u = e[o + "Color"],
			v = e[o + "Position"],
			x = c.mark,
			z = l.step,
			B = !0,
			C = d.tickmarkOffset,
			D = c.getPosition(h, k, C, b),
			E = D.x,
			F = D.y,
			G = d.staggerLines;
			p && (w = d.getPlotLinePath(k + C, p, b), m === UNDEFINED && (A = {
				stroke: q,
				"stroke-width": p
			},
			r && (A.dashstyle = r), i || (A.zIndex = 1), c.gridLine = m = p ? g.path(w).attr(A).add(d.gridGroup) : null), !b && m && w && m[c.isNew ? "attr": "animate"]({
				d: w
			})),
			t && s && ("inside" === v && (s = -s), d.opposite && (s = -s), y = c.getMarkPath(E, F, s, t, h, g), x ? x.animate({
				d: y
			}) : c.mark = g.path(y).attr({
				stroke: u,
				"stroke-width": t
			}).add(d.axisGroup)),
			j && !isNaN(E) && (j.xy = D = c.getLabelPosition(E, F, j, h, l, C, a, z), c.isFirst && !pick(e.showFirstLabel, 1) || c.isLast && !pick(e.showLastLabel, 1) ? B = !1 : G || !h || "justify" !== l.overflow || c.handleOverflow(a, D) || (B = !1), z && a % z && (B = !1), B ? (j[c.isNew ? "attr": "animate"](D), j.show(), c.isNew = !1) : j.hide())
		},
		destroy: function() {
			destroyObjectProperties(this, this.axis)
		}
	},
	PlotLineOrBand.prototype = {
		render: function() {
			var p, q, r, s, t, u, y, a = this,
			b = a.axis,
			c = b.horiz,
			d = (b.pointRange || 0) / 2,
			e = a.options,
			f = e.label,
			g = a.label,
			h = e.width,
			i = e.to,
			j = e.from,
			k = defined(j) && defined(i),
			l = e.value,
			m = e.dashStyle,
			n = a.svgElem,
			o = [],
			v = e.color,
			w = e.zIndex,
			x = e.events,
			z = b.chart.renderer;
			if (b.isLog && (j = log2lin(j), i = log2lin(i), l = log2lin(l)), h) o = b.getPlotLinePath(l, h),
			y = {
				stroke: v,
				"stroke-width": h
			},
			m && (y.dashstyle = m);
			else {
				if (!k) return;
				j = mathMax(j, b.min - d),
				i = mathMin(i, b.max + d),
				o = b.getPlotBandPath(j, i, e),
				y = {
					fill: v
				},
				e.borderWidth && (y.stroke = e.borderColor, y["stroke-width"] = e.borderWidth)
			}
			if (defined(w) && (y.zIndex = w), n) o ? n.animate({
				d: o
			},
			null, n.onGetPath) : (n.hide(), n.onGetPath = function() {
				n.show()
			});
			else if (o && o.length && (a.svgElem = n = z.path(o).attr(y).add(), x)) {
				p = function(b) {
					n.on(b,
					function(c) {
						x[b].apply(a, [c])
					})
				};
				for (q in x) p(q)
			}
			return f && defined(f.text) && o && o.length && b.width > 0 && b.height > 0 ? (f = merge({
				align: c && k && "center",
				x: c ? !k && 4 : 10,
				verticalAlign: !c && k && "middle",
				y: c ? k ? 16 : 10 : k ? 6 : -4,
				rotation: c && !k && 90
			},
			f), g || (a.label = g = z.text(f.text, 0, 0).attr({
				align: f.textAlign || f.align,
				rotation: f.rotation,
				zIndex: w
			}).css(f.style).add()), r = [o[1], o[4], pick(o[6], o[1])], s = [o[2], o[5], pick(o[7], o[2])], t = arrayMin(r), u = arrayMin(s), g.align(f, !1, {
				x: t,
				y: u,
				width: arrayMax(r) - t,
				height: arrayMax(s) - u
			}), g.show()) : g && g.hide(),
			a
		},
		destroy: function() {
			var a = this,
			b = a.axis;
			erase(b.plotLinesAndBands, a),
			destroyObjectProperties(a, this.axis)
		}
	},
	StackItem.prototype = {
		destroy: function() {
			destroyObjectProperties(this, this.axis)
		},
		setTotal: function(a) {
			this.total = a,
			this.cum = a
		},
		render: function(a) {
			var b = this.options.formatter.call(this);
			this.label ? this.label.attr({
				text: b,
				visibility: HIDDEN
			}) : this.label = this.axis.chart.renderer.text(b, 0, 0).css(this.options.style).attr({
				align: this.textAlign,
				rotation: this.options.rotation,
				visibility: HIDDEN
			}).add(a)
		},
		setOffset: function(a, b) {
			var o, c = this,
			d = c.axis,
			e = d.chart,
			f = e.inverted,
			g = this.isNegative,
			h = d.translate(this.percent ? 100 : this.total, 0, 0, 0, 1),
			i = d.translate(0),
			j = mathAbs(h - i),
			k = e.xAxis[0].translate(this.x) + a,
			l = e.plotHeight,
			m = {
				x: f ? g ? h: h - j: k,
				y: f ? l - k - b: g ? l - h - j: l - h,
				width: f ? j: b,
				height: f ? b: j
			},
			n = this.label;
			n && (n.align(this.alignOptions, null, m), o = n.alignAttr, n.attr({
				visibility: this.options.crop === !1 || e.isInsidePlot(o.x, o.y) ? hasSVG ? "inherit": VISIBLE: HIDDEN
			}))
		}
	},
	Axis.prototype = {
		defaultOptions: {
			dateTimeLabelFormats: {
				millisecond: "%H:%M:%S.%L",
				second: "%H:%M:%S",
				minute: "%H:%M",
				hour: "%H:%M",
				day: "%e. %b",
				week: "%e. %b",
				month: "%b '%y",
				year: "%Y"
			},
			endOnTick: !1,
			gridLineColor: "#C0C0C0",
			labels: defaultLabelOptions,
			lineColor: "#C0D0E0",
			lineWidth: 1,
			minPadding: .01,
			maxPadding: .01,
			minorGridLineColor: "#E0E0E0",
			minorGridLineWidth: 1,
			minorTickColor: "#A0A0A0",
			minorTickLength: 2,
			minorTickPosition: "outside",
			startOfWeek: 1,
			startOnTick: !1,
			tickColor: "#C0D0E0",
			tickLength: 5,
			tickmarkPlacement: "between",
			tickPixelInterval: 100,
			tickPosition: "outside",
			tickWidth: 1,
			title: {
				align: "middle",
				style: {
					color: "#6D869F",
					fontWeight: "bold"
				}
			},
			type: "linear"
		},
		defaultYAxisOptions: {
			endOnTick: !0,
			gridLineWidth: 1,
			tickPixelInterval: 72,
			showLastLabel: !0,
			labels: {
				align: "right",
				x: -8,
				y: 3
			},
			lineWidth: 0,
			maxPadding: .05,
			minPadding: .05,
			startOnTick: !0,
			tickWidth: 0,
			title: {
				rotation: 270,
				text: "Y-values"
			},
			stackLabels: {
				enabled: !1,
				formatter: function() {
					return this.total
				},
				style: defaultLabelOptions.style
			}
		},
		defaultLeftAxisOptions: {
			labels: {
				align: "right",
				x: -8,
				y: null
			},
			title: {
				rotation: 270
			}
		},

		defaultRightAxisOptions: {
			labels: {
				align: "left",
				x: 8,
				y: null
			},
			title: {
				rotation: 90
			}
		},
		defaultBottomAxisOptions: {
			labels: {
				align: "center",
				x: 0,
				y: 14
			},
			title: {
				rotation: 0
			}
		},
		defaultTopAxisOptions: {
			labels: {
				align: "center",
				x: 0,
				y: -5
			},
			title: {
				rotation: 0
			}
		},
		init: function(a, b) {
			var e, f, g, h, i, c = b.isX,
			d = this;
			d.horiz = a.inverted ? !c: c,
			d.isXAxis = c,
			d.xOrY = c ? "x": "y",
			d.opposite = b.opposite,
			d.side = d.horiz ? d.opposite ? 0 : 2 : d.opposite ? 1 : 3,
			d.setOptions(b),
			e = this.options,
			f = e.type,
			g = "datetime" === f,
			d.labelFormatter = e.labels.formatter || d.defaultLabelFormatter,
			d.staggerLines = d.horiz && e.labels.staggerLines,
			d.userOptions = b,
			d.minPixelPadding = 0,
			d.chart = a,
			d.reversed = e.reversed,
			d.categories = e.categories,
			d.isLog = "logarithmic" === f,
			d.isLinked = defined(e.linkedTo),
			d.isDatetimeAxis = g,
			d.tickmarkOffset = e.categories && "between" === e.tickmarkPlacement ? .5 : 0,
			d.ticks = {},
			d.minorTicks = {},
			d.plotLinesAndBands = [],
			d.alternateBands = {},
			d.len = 0,
			d.minRange = d.userMinRange = e.minRange || e.maxZoom,
			d.range = e.range,
			d.offset = e.offset || 0,
			d.stacks = {},
			d.max = null,
			d.min = null,
			i = d.options.events,
			a.axes.push(d),
			a[c ? "xAxis": "yAxis"].push(d),
			d.series = [],
			a.inverted && c && d.reversed === UNDEFINED && (d.reversed = !0),
			d.removePlotBand = d.removePlotBandOrLine,
			d.removePlotLine = d.removePlotBandOrLine,
			d.addPlotBand = d.addPlotBandOrLine,
			d.addPlotLine = d.addPlotBandOrLine;
			for (h in i) addEvent(d, h, i[h]);
			d.isLog && (d.val2lin = log2lin, d.lin2val = lin2log)
		},
		setOptions: function(a) {
			this.options = merge(this.defaultOptions, this.isXAxis ? {}: this.defaultYAxisOptions, [this.defaultTopAxisOptions, this.defaultRightAxisOptions, this.defaultBottomAxisOptions, this.defaultLeftAxisOptions][this.side], merge(defaultOptions[this.isXAxis ? "xAxis": "yAxis"], a))
		},
		defaultLabelFormatter: function() {
			var g, h, a = this.axis,
			b = this.value,
			c = a.categories,
			d = this.dateTimeLabelFormat,
			e = defaultOptions.lang.numericSymbols,
			f = e && e.length,
			i = a.isLog ? b: a.tickInterval;
			if (c) h = b;
			else if (d) h = dateFormat(d, b);
			else if (f && i >= 1e3) for (; f--&&h === UNDEFINED;) g = Math.pow(1e3, f + 1),
			i >= g && null !== e[f] && (h = numberFormat(b / g, -1) + e[f]);
			return h === UNDEFINED && (h = b >= 1e3 ? numberFormat(b, 0) : numberFormat(b, -1)),
			h
		},
		getSeriesExtremes: function() {
			var f, a = this,
			b = a.chart,
			c = a.stacks,
			d = [],
			e = [];
			a.hasVisibleSeries = !1,
			a.dataMin = a.dataMax = null,
			each(a.series,
			function(g) {
				var i, j, k, l, m, n, o, p, q, r, t, h, s, u, v, w, x, y, B, z, A, C;
				if (g.visible || !b.options.chart.ignoreHiddenSeries) if (h = g.options, s = h.threshold, u = [], v = 0, a.hasVisibleSeries = !0, a.isLog && 0 >= s && (s = h.threshold = null), a.isXAxis) o = g.xData,
				o.length && (a.dataMin = mathMin(pick(a.dataMin, o[0]), arrayMin(o)), a.dataMax = mathMax(pick(a.dataMax, o[0]), arrayMax(o)));
				else {
					for (z = g.cropped, A = g.xAxis.getExtremes(), C = !!g.modifyValue, i = h.stacking, a.usePercentage = "percent" === i, i && (m = h.stack, l = g.type + pick(m, ""), n = "-" + l, g.stackKey = l, j = d[l] || [], d[l] = j, k = e[n] || [], e[n] = k), a.usePercentage && (a.dataMin = 0, a.dataMax = 99), o = g.processedXData, p = g.processedYData, t = p.length, f = 0; t > f; f++) if (q = o[f], r = p[f], i && (w = s > r, x = w ? k: j, y = w ? n: l, r = x[q] = defined(x[q]) ? correctFloat(x[q] + r) : r, c[y] || (c[y] = {}), c[y][q] || (c[y][q] = new StackItem(a, a.options.stackLabels, w, q, m, i)), c[y][q].setTotal(r)), null !== r && r !== UNDEFINED && (C && (r = g.modifyValue(r)), z || (o[f + 1] || q) >= A.min && (o[f - 1] || q) <= A.max)) if (B = r.length) for (; B--;) null !== r[B] && (u[v++] = r[B]);
					else u[v++] = r; ! a.usePercentage && u.length && (a.dataMin = mathMin(pick(a.dataMin, u[0]), arrayMin(u)), a.dataMax = mathMax(pick(a.dataMax, u[0]), arrayMax(u))),
					defined(s) && (a.dataMin >= s ? (a.dataMin = s, a.ignoreMinPadding = !0) : a.dataMax < s && (a.dataMax = s, a.ignoreMaxPadding = !0))
				}
			})
		},
		translate: function(a, b, c, d, e, f) {
			var m, g = this,
			h = g.len,
			i = 1,
			j = 0,
			k = d ? g.oldTransA: g.transA,
			l = d ? g.oldMin: g.min,
			n = g.options.ordinal || g.isLog && e;
			return k || (k = g.transA),
			c && (i *= -1, j = h),
			g.reversed && (i *= -1, j -= i * h),
			b ? (g.reversed && (a = h - a), m = a / k + l, n && (m = g.lin2val(m))) : (n && (a = g.val2lin(a)), m = i * (a - l) * k + j + i * g.minPixelPadding + (f ? k * g.pointRange / 2 : 0)),
			m
		},
		getPlotLinePath: function(a, b, c) {
			var h, i, j, k, o, d = this,
			e = d.chart,
			f = d.left,
			g = d.top,
			l = d.translate(a, null, null, c),
			m = c && e.oldChartHeight || e.chartHeight,
			n = c && e.oldChartWidth || e.chartWidth,
			p = d.transB;
			return h = j = mathRound(l + p),
			i = k = mathRound(m - l - p),
			isNaN(l) ? o = !0 : d.horiz ? (i = g, k = m - d.bottom, (f > h || h > f + d.width) && (o = !0)) : (h = f, j = n - d.right, (g > i || i > g + d.height) && (o = !0)),
			o ? null: e.renderer.crispLine([M, h, i, L, j, k], b || 0)
		},
		getPlotBandPath: function(a, b) {
			var c = this.getPlotLinePath(b),
			d = this.getPlotLinePath(a);
			return d && c ? d.push(c[4], c[5], c[1], c[2]) : d = null,
			d
		},
		getLinearTickPositions: function(a, b, c) {
			var d, e, f = correctFloat(mathFloor(b / a) * a),
			g = correctFloat(mathCeil(c / a) * a),
			h = [];
			for (d = f; g >= d && (h.push(d), d = correctFloat(d + a), d !== e);) e = d;
			return h
		},
		getLogTickPositions: function(a, b, c, d) {
			var j, k, l, m, n, o, p, i, q, r, s, t, u, v, e = this,
			f = e.options,
			g = e.len,
			h = [];
			if (d || (e._minorAutoInterval = null), a >= .5) a = mathRound(a),
			h = e.getLinearTickPositions(a, b, c);
			else if (a >= .08) for (i = mathFloor(b), j = a > .3 ? [1, 2, 4] : a > .15 ? [1, 2, 4, 6, 8] : [1, 2, 3, 4, 5, 6, 7, 8, 9], k = i; c + 1 > k && !p; k++) for (m = j.length, l = 0; m > l && !p; l++) n = log2lin(lin2log(k) * j[l]),
			n > b && h.push(o),
			o > c && (p = !0),
			o = n;
			else q = lin2log(b),
			r = lin2log(c),
			s = f[d ? "minorTickInterval": "tickInterval"],
			t = "auto" === s ? null: s,
			u = f.tickPixelInterval / (d ? 5 : 1),
			v = d ? g / e.tickPositions.length: g,
			a = pick(t, e._minorAutoInterval, (r - q) * u / (v || 1)),
			a = normalizeTickInterval(a, null, math.pow(10, mathFloor(math.log(a) / math.LN10))),
			h = map(e.getLinearTickPositions(a, q, r), log2lin),
			d || (e._minorAutoInterval = a / 5);
			return d || (e.tickInterval = a),
			h
		},
		getMinorTickPositions: function() {
			var e, f, g, a = this,
			b = a.tickPositions,
			c = a.minorTickInterval,
			d = [];
			if (a.isLog) for (g = b.length, f = 1; g > f; f++) d = d.concat(a.getLogTickPositions(c, b[f - 1], b[f], !0));
			else for (e = a.min + (b[0] - a.min) % c; e <= a.max; e += c) d.push(e);
			return d
		},
		adjustForMinRange: function() {
			var e, g, h, i, j, k, l, m, n, a = this,
			b = a.options,
			c = a.min,
			d = a.max,
			f = a.dataMax - a.dataMin >= a.minRange;
			a.isXAxis && a.minRange === UNDEFINED && !a.isLog && (defined(b.min) || defined(b.max) ? a.minRange = null: (each(a.series,
			function(a) {
				for (j = a.xData, k = a.xIncrement ? 1 : j.length - 1, h = k; h > 0; h--) i = j[h] - j[h - 1],
				(g === UNDEFINED || g > i) && (g = i)
			}), a.minRange = mathMin(5 * g, a.dataMax - a.dataMin))),
			d - c < a.minRange && (n = a.minRange, e = (n - d + c) / 2, l = [c - e, pick(b.min, c - e)], f && (l[2] = a.dataMin), c = arrayMax(l), m = [c + n, pick(b.max, c + n)], f && (m[2] = a.dataMax), d = arrayMin(m), n > d - c && (l[0] = d - n, l[1] = pick(b.min, d - n), c = arrayMax(l))),
			a.min = c,
			a.max = d
		},
		setAxisTranslation: function() {
			var d, a = this,
			b = a.max - a.min,
			c = 0,
			e = 0,
			f = 0,
			g = a.linkedParent,
			h = a.transA;
			a.isXAxis && (g ? (e = g.minPointOffset, f = g.pointRangePadding) : each(a.series,
			function(a) {
				var b = a.pointRange,
				g = a.options.pointPlacement,
				h = a.closestPointRange;
				c = mathMax(c, b),
				e = mathMax(e, g ? 0 : b / 2),
				f = mathMax(f, "on" === g ? 0 : b),
				!a.noSharedTooltip && defined(h) && (d = defined(d) ? mathMin(d, h) : h)
			}), a.minPointOffset = e, a.pointRangePadding = f, a.pointRange = c, a.closestPointRange = d),
			a.oldTransA = h,
			a.translationSlope = a.transA = h = a.len / (b + f || 1),
			a.transB = a.horiz ? a.left: a.bottom,
			a.minPixelPadding = h * e
		},
		setTickPositions: function(a) {
			var j, m, n, r, t, u, v, b = this,
			c = b.chart,
			d = b.options,
			e = b.isLog,
			f = b.isDatetimeAxis,
			g = b.isXAxis,
			h = b.isLinked,
			i = b.options.tickPositioner,
			k = d.maxPadding,
			l = d.minPadding,
			o = d.tickInterval,
			p = d.minTickInterval,
			q = d.tickPixelInterval,
			s = b.categories;
			h ? (b.linkedParent = c[g ? "xAxis": "yAxis"][d.linkedTo], n = b.linkedParent.getExtremes(), b.min = pick(n.min, n.dataMin), b.max = pick(n.max, n.dataMax), d.type !== b.linkedParent.options.type && error(11, 1)) : (b.min = pick(b.userMin, d.min, b.dataMin), b.max = pick(b.userMax, d.max, b.dataMax)),
			e && (!a && mathMin(b.min, pick(b.dataMin, b.min)) <= 0 && error(10, 1), b.min = correctFloat(log2lin(b.min)), b.max = correctFloat(log2lin(b.max))),
			b.range && (b.userMin = b.min = mathMax(b.min, b.max - b.range), b.userMax = b.max, a && (b.range = null)),
			b.adjustForMinRange(),
			s || b.usePercentage || h || !defined(b.min) || !defined(b.max) || (m = b.max - b.min || 1, defined(d.min) || defined(b.userMin) || !l || !(b.dataMin < 0) && b.ignoreMinPadding || (b.min -= m * l), defined(d.max) || defined(b.userMax) || !k || !(b.dataMax > 0) && b.ignoreMaxPadding || (b.max += m * k)),
			b.tickInterval = b.min === b.max || void 0 === b.min || void 0 === b.max ? 1 : h && !o && q === b.linkedParent.options.tickPixelInterval ? b.linkedParent.tickInterval: pick(o, s ? 1 : (b.max - b.min) * q / (b.len || 1)),
			g && !a && each(b.series,
			function(a) {
				a.processData(b.min !== b.oldMin || b.max !== b.oldMax)
			}),
			b.setAxisTranslation(a),
			b.beforeSetTickPositions && b.beforeSetTickPositions(),
			b.postProcessTickInterval && (b.tickInterval = b.postProcessTickInterval(b.tickInterval)),
			!o && b.tickInterval < p && (b.tickInterval = p),
			f || e || (j = math.pow(10, mathFloor(math.log(b.tickInterval) / math.LN10)), o || (b.tickInterval = normalizeTickInterval(b.tickInterval, null, j, d))),
			b.minorTickInterval = "auto" === d.minorTickInterval && b.tickInterval ? b.tickInterval / 5 : d.minorTickInterval,
			b.tickPositions = r = d.tickPositions || i && i.apply(b, [b.min, b.max]),
			r || (r = f ? (b.getNonLinearTimeTicks || getTimeTicks)(normalizeTimeTickInterval(b.tickInterval, d.units), b.min, b.max, d.startOfWeek, b.ordinalPositions, b.closestPointRange, !0) : e ? b.getLogTickPositions(b.tickInterval, b.min, b.max) : b.getLinearTickPositions(b.tickInterval, b.min, b.max), b.tickPositions = r),
			h || (t = r[0], u = r[r.length - 1], v = b.minPointOffset || 0, d.startOnTick ? b.min = t: b.min - v > t && r.shift(), d.endOnTick ? b.max = u: b.max + v < u && r.pop())
		},
		setMaxTicks: function() {
			var a = this.chart,
			b = a.maxTicks,
			c = this.tickPositions,
			d = this.xOrY;
			b || (b = {
				x: 0,
				y: 0
			}),
			!this.isLinked && !this.isDatetimeAxis && c.length > b[d] && this.options.alignTicks !== !1 && (b[d] = c.length),
			a.maxTicks = b
		},
		adjustTickAmount: function() {
			var h, f, g, a = this,
			b = a.chart,
			c = a.xOrY,
			d = a.tickPositions,
			e = b.maxTicks;
			if (e && e[c] && !a.isDatetimeAxis && !a.categories && !a.isLinked && a.options.alignTicks !== !1) {
				if (f = a.tickAmount, g = d.length, a.tickAmount = h = e[c], h > g) {
					for (; d.length < h;) d.push(correctFloat(d[d.length - 1] + a.tickInterval));
					a.transA *= (g - 1) / (h - 1),
					a.max = d[d.length - 1]
				}
				defined(f) && h !== f && (a.isDirty = !0)
			}
		},
		setScale: function() {
			var c, d, e, f, a = this,
			b = a.stacks;
			if (a.oldMin = a.min, a.oldMax = a.max, a.oldAxisLength = a.len, a.setAxisSize(), f = a.len !== a.oldAxisLength, each(a.series,
			function(a) { (a.isDirtyData || a.isDirty || a.xAxis.isDirty) && (e = !0)
			}), (f || e || a.isLinked || a.userMin !== a.oldUserMin || a.userMax !== a.oldUserMax) && (a.getSeriesExtremes(), a.setTickPositions(), a.oldUserMin = a.userMin, a.oldUserMax = a.userMax, a.isDirty || (a.isDirty = f || a.min !== a.oldMin || a.max !== a.oldMax)), !a.isXAxis) for (c in b) for (d in b[c]) b[c][d].cum = b[c][d].total;
			a.setMaxTicks()
		},
		setExtremes: function(a, b, c, d, e) {
			var f = this,
			g = f.chart;
			c = pick(c, !0),
			e = extend(e, {
				min: a,
				max: b
			}),
			fireEvent(f, "setExtremes", e,
			function() {
				f.userMin = a,
				f.userMax = b,
				f.isDirtyExtremes = !0,
				c && g.redraw(d)
			})
		},
		zoom: function(a, b) {
			return this.setExtremes(a, b, !1, UNDEFINED, {
				trigger: "zoom"
			}),
			!0
		},
		setAxisSize: function() {
			var a = this,
			b = a.chart,
			c = a.options,
			d = c.offsetLeft || 0,
			e = c.offsetRight || 0;
			a.left = pick(c.left, b.plotLeft + d),
			a.top = pick(c.top, b.plotTop),
			a.width = pick(c.width, b.plotWidth - d + e),
			a.height = pick(c.height, b.plotHeight),
			a.bottom = b.chartHeight - a.height - a.top,
			a.right = b.chartWidth - a.width - a.left,
			a.len = mathMax(a.horiz ? a.width: a.height, 0)
		},
		getExtremes: function() {
			var a = this,
			b = a.isLog;
			return {
				min: b ? correctFloat(lin2log(a.min)) : a.min,
				max: b ? correctFloat(lin2log(a.max)) : a.max,
				dataMin: a.dataMin,
				dataMax: a.dataMax,
				userMin: a.userMin,
				userMax: a.userMax
			}
		},
		getThreshold: function(a) {
			var b = this,
			c = b.isLog,
			d = c ? lin2log(b.min) : b.min,
			e = c ? lin2log(b.max) : b.max;
			return d > a || null === a ? a = d: a > e && (a = e),
			b.translate(a, 0, 1, 0, 1)
		},
		addPlotBandOrLine: function(a) {
			var b = new PlotLineOrBand(this, a).render();
			return this.plotLinesAndBands.push(b),
			b
		},
		getOffset: function() {
			var i, j, l, s, a = this,
			b = a.chart,
			c = b.renderer,
			d = a.options,
			e = a.tickPositions,
			f = a.ticks,
			g = a.horiz,
			h = a.side,
			k = 0,
			m = 0,
			n = d.title,
			o = d.labels,
			p = 0,
			q = b.axisOffset,
			r = [ - 1, 1, 1, -1][h];
			if (a.hasData = i = a.hasVisibleSeries || defined(a.min) && defined(a.max) && !!e, a.showAxis = j = i || pick(d.showEmpty, !0), a.axisGroup || (a.gridGroup = c.g("grid").attr({
				zIndex: d.gridZIndex || 1
			}).add(), a.axisGroup = c.g("axis").attr({
				zIndex: d.zIndex || 2
			}).add(), a.labelGroup = c.g("axis-labels").attr({
				zIndex: o.zIndex || 7
			}).add()), i || a.isLinked) each(e,
			function(b) {
				f[b] ? f[b].addLabel() : f[b] = new Tick(a, b)
			}),
			each(e,
			function(a) { (0 === h || 2 === h || {
					1 : "left",
					3 : "right"
				} [h] === o.align) && (p = mathMax(f[a].getLabelSize(), p))
			}),
			a.staggerLines && (p += 16 * (a.staggerLines - 1));
			else for (s in f) f[s].destroy(),
			delete f[s];
			n && n.text && (a.axisTitle || (a.axisTitle = c.text(n.text, 0, 0, n.useHTML).attr({
				zIndex: 7,
				rotation: n.rotation || 0,
				align: n.textAlign || {
					low: "left",
					middle: "center",
					high: "right"
				} [n.align]
			}).css(n.style).add(a.axisGroup), a.axisTitle.isNew = !0), j && (k = a.axisTitle.getBBox()[g ? "height": "width"], m = pick(n.margin, g ? 5 : 10), l = n.offset), a.axisTitle[j ? "show": "hide"]()),
			a.offset = r * pick(d.offset, q[h]),
			a.axisTitleMargin = pick(l, p + m + (2 !== h && p && r * d.labels[g ? "y": "x"])),
			q[h] = mathMax(q[h], a.axisTitleMargin + k + r * a.offset)
		},
		getLinePath: function(a) {
			var b = this.chart,
			c = this.opposite,
			d = this.offset,
			e = this.horiz,
			f = this.left + (c ? this.width: 0) + d,
			g = b.chartHeight - this.bottom - (c ? this.height: 0) + d;
			return this.lineTop = g,
			b.renderer.crispLine([M, e ? this.left: f, e ? g: this.top, L, e ? b.chartWidth - this.right: f, e ? g: b.chartHeight - this.bottom], a)
		},
		getTitlePosition: function() {
			var a = this.horiz,
			b = this.left,
			c = this.top,
			d = this.len,
			e = this.options.title,
			f = a ? b: c,
			g = this.opposite,
			h = this.offset,
			i = pInt(e.style.fontSize || 12),
			j = {
				low: f + (a ? 0 : d),
				middle: f + d / 2,
				high: f + (a ? d: 0)
			} [e.align],
			k = (a ? c + this.height: b) + (a ? 1 : -1) * (g ? -1 : 1) * this.axisTitleMargin + (2 === this.side ? i: 0);
			return {
				x: a ? j: k + (g ? this.width: 0) + h + (e.x || 0),
				y: a ? k - (g ? this.height: 0) + h: j + (e.y || 0)
			}
		},
		render: function() {
			var q, v, w, x, y, z, A, a = this,
			b = a.chart,
			c = b.renderer,
			d = a.options,
			e = a.isLog,
			f = a.isLinked,
			g = a.tickPositions,
			h = a.axisTitle,
			i = a.stacks,
			j = a.ticks,
			k = a.minorTicks,
			l = a.alternateBands,
			m = d.stackLabels,
			n = d.alternateGridColor,
			o = a.tickmarkOffset,
			p = d.lineWidth,
			r = b.hasRendered,
			s = r && defined(a.oldMin) && !isNaN(a.oldMin),
			t = a.hasData,
			u = a.showAxis;
			if ((t || f) && (a.minorTickInterval && !a.categories && each(a.getMinorTickPositions(),
			function(b) {
				k[b] || (k[b] = new Tick(a, b, "minor")),
				s && k[b].isNew && k[b].render(null, !0),
				k[b].isActive = !0,
				k[b].render()
			}), each(g.slice(1).concat([g[0]]),
			function(b, c) {
				c = c === g.length - 1 ? 0 : c + 1,
				(!f || b >= a.min && b <= a.max) && (j[b] || (j[b] = new Tick(a, b)), s && j[b].isNew && j[b].render(c, !0), j[b].isActive = !0, j[b].render(c))
			}), n && each(g,
			function(b, c) {
				0 === c % 2 && b < a.max && (l[b] || (l[b] = new PlotLineOrBand(a)), v = b + o, w = g[c + 1] !== UNDEFINED ? g[c + 1] + o: a.max, l[b].options = {
					from: e ? lin2log(v) : v,
					to: e ? lin2log(w) : w,
					color: n
				},
				l[b].render(), l[b].isActive = !0)
			}), a._addedPlotLB || (each((d.plotLines || []).concat(d.plotBands || []),
			function(b) {
				a.addPlotBandOrLine(b)
			}), a._addedPlotLB = !0)), each([j, k, l],
			function(a) {
				var b;
				for (b in a) a[b].isActive ? a[b].isActive = !1 : (a[b].destroy(), delete a[b])
			}), p && (q = a.getLinePath(p), a.axisLine ? a.axisLine.animate({
				d: q
			}) : a.axisLine = c.path(q).attr({
				stroke: d.lineColor,
				"stroke-width": p,
				zIndex: 7
			}).add(a.axisGroup), a.axisLine[u ? "show": "hide"]()), h && u && (h[h.isNew ? "attr": "animate"](a.getTitlePosition()), h.isNew = !1), m && m.enabled) {
				A = a.stackTotalGroup,
				A || (a.stackTotalGroup = A = c.g("stack-labels").attr({
					visibility: VISIBLE,
					zIndex: 6
				}).add()),
				A.translate(b.plotLeft, b.plotTop);
				for (x in i) {
					y = i[x];
					for (z in y) y[z].render(A)
				}
			}
			a.isDirty = !1
		},
		removePlotBandOrLine: function(a) {
			for (var b = this.plotLinesAndBands,
			c = b.length; c--;) b[c].id === a && b[c].destroy()
		},
		setTitle: function(a, b) {
			var c = this.chart,
			d = this.options,
			e = this.axisTitle;
			d.title = merge(d.title, a),
			this.axisTitle = e && e.destroy(),
			this.isDirty = !0,
			pick(b, !0) && c.redraw()
		},
		redraw: function() {
			var a = this,
			b = a.chart;
			b.tracker.resetTracker && b.tracker.resetTracker(!0),
			a.render(),
			each(a.plotLinesAndBands,
			function(a) {
				a.render()
			}),
			each(a.series,
			function(a) {
				a.isDirty = !0
			})
		},
		setCategories: function(a, b) {
			var c = this,
			d = c.chart;
			c.categories = c.userOptions.categories = a,
			each(c.series,
			function(a) {
				a.translate(),
				a.setTooltipPoints(!0)
			}),
			c.isDirty = !0,
			pick(b, !0) && d.redraw()
		},
		destroy: function() {
			var c, a = this,
			b = a.stacks;
			removeEvent(a);
			for (c in b) destroyObjectProperties(b[c]),
			b[c] = null;
			each([a.ticks, a.minorTicks, a.alternateBands, a.plotLinesAndBands],
			function(a) {
				destroyObjectProperties(a)
			}),
			each(["stackTotalGroup", "axisLine", "axisGroup", "gridGroup", "labelGroup", "axisTitle"],
			function(b) {
				a[b] && (a[b] = a[b].destroy())
			})
		}
	},
	Tooltip.prototype = {
		destroy: function() {
			each(this.crosshairs,
			function(a) {
				a && a.destroy()
			}),
			this.label && (this.label = this.label.destroy())
		},
		move: function(a, b, c, d) {
			var e = this,
			f = e.now,
			g = e.options.animation !== !1 && !e.isHidden;
			extend(f, {
				x: g ? (2 * f.x + a) / 3 : a,
				y: g ? (f.y + b) / 2 : b,
				anchorX: g ? (2 * f.anchorX + c) / 3 : c,
				anchorY: g ? (f.anchorY + d) / 2 : d
			}),
			e.label.attr(f),
			g && (mathAbs(a - f.x) > 1 || mathAbs(b - f.y) > 1) && (clearTimeout(this.tooltipTimeout), this.tooltipTimeout = setTimeout(function() {
				e && e.move(a, b, c, d)
			},
			32))
		},
		hide: function() {
			if (!this.isHidden) {
				var a = this.chart.hoverPoints;
				this.label.hide(),
				a && each(a,
				function(a) {
					a.setState()
				}),
				this.chart.hoverPoints = null,
				this.isHidden = !0
			}
		},
		hideCrosshairs: function() {
			each(this.crosshairs,
			function(a) {
				a && a.hide()
			})
		},
		getAnchor: function(a, b) {
			var c, h, d = this.chart,
			e = d.inverted,
			f = 0,
			g = 0;
			return a = splat(a),
			c = a[0].tooltipPos,
			c || (each(a,
			function(a) {
				h = a.series.yAxis,
				f += a.plotX,
				g += (a.plotLow ? (a.plotLow + a.plotHigh) / 2 : a.plotY) + (!e && h ? h.top - d.plotTop: 0)
			}), f /= a.length, g /= a.length, c = [e ? d.plotWidth - g: f, this.shared && !e && a.length > 1 && b ? b.chartY - d.plotTop: e ? d.plotHeight - f: g]),
			map(c, mathRound)
		},
		getPosition: function(a, b, c) {
			var n, d = this.chart,
			e = d.plotLeft,
			f = d.plotTop,
			g = d.plotWidth,
			h = d.plotHeight,
			i = pick(this.options.distance, 12),
			j = c.plotX,
			k = c.plotY,
			l = j + e + (d.inverted ? i: -a - i),
			m = k - b + f + 15;
			return 7 > l && (l = e + mathMax(j, 0) + i),
			l + a > e + g && (l -= l + a - (e + g), m = k - b + f - i, n = !0),
			f + 5 > m && (m = f + 5, n && k >= m && m + b >= k && (m = k + f + i)),
			m + b > f + h && (m = mathMax(f, f + h - b - i)),
			{
				x: l,
				y: m
			}
		},
		refresh: function(a, b) {
			function g() {
				var d, a = this,
				b = a.points || splat(a),
				c = b[0].series;
				return d = [c.tooltipHeaderFormatter(b[0].key)],
				each(b,
				function(a) {
					c = a.series,
					d.push(c.tooltipFormatter && c.tooltipFormatter(a) || a.point.tooltipFormatter(c.tooltipOptions.pointFormat))
				}),
				d.push(f.footerFormat || ""),
				d.join("")
			}
			var j, m, q, r, u, v, x, y, w, c = this,
			d = c.chart,
			e = c.label,
			f = c.options,
			l = {},
			n = [],
			o = f.formatter || g,
			p = d.hoverPoints,
			s = f.crosshairs,
			t = c.shared,
			k = c.getAnchor(a, b),
			h = k[0],
			i = k[1];
			if (!t || a.series && a.series.noSharedTooltip ? l = a.getLabelConfig() : (d.hoverPoints = a, p && each(p,
			function(a) {
				a.setState()
			}), each(a,
			function(a) {
				a.setState(HOVER_STATE),
				n.push(a.getLabelConfig())
			}), l = {
				x: a[0].category,
				y: a[0].y
			},
			l.points = n, a = a[0]), m = o.call(l), u = a.series, j = t || !u.isCartesian || u.tooltipOutsidePlot || d.isInsidePlot(h, i), m !== !1 && j ? (c.isHidden && e.show(), e.attr({
				text: m
			}), r = f.borderColor || a.color || u.color || "#606060", e.attr({
				stroke: r
			}), q = (f.positioner || c.getPosition).call(c, e.width, e.height, {
				plotX: h,
				plotY: i
			}), c.move(mathRound(q.x), mathRound(q.y), h + d.plotLeft, i + d.plotTop), c.isHidden = !1) : this.hide(), s) for (s = splat(s), w = s.length; w--;) y = a.series[w ? "yAxis": "xAxis"],
			s[w] && y && (v = y.getPlotLinePath(w ? pick(a.stackY, a.y) : a.x, 1), c.crosshairs[w] ? c.crosshairs[w].attr({
				d: v,
				visibility: VISIBLE
			}) : (x = {
				"stroke-width": s[w].width || 1,
				stroke: s[w].color || "#C0C0C0",
				zIndex: s[w].zIndex || 2
			},
			s[w].dashStyle && (x.dashstyle = s[w].dashStyle), c.crosshairs[w] = d.renderer.path(v).attr(x).add()));
			fireEvent(d, "tooltipRefresh", {
				text: m,
				x: h + d.plotLeft,
				y: i + d.plotTop,
				borderColor: r
			})
		}
	},
	MouseTracker.prototype = {
		normalizeMouseEvent: function(a) {
			var b, c, d, e;
			return a = a || win.event,
			a.target || (a.target = a.srcElement),
			a = washMouseEvent(a),
			e = a.touches ? a.touches.item(0) : a,
			this.chartPosition = b = offset(this.chart.container),
			e.pageX === UNDEFINED ? (c = a.x, d = a.y) : (c = e.pageX - b.left, d = e.pageY - b.top),
			extend(a, {
				chartX: mathRound(c),
				chartY: mathRound(d)
			})
		},
		getMouseCoordinates: function(a) {
			var b = {
				xAxis: [],
				yAxis: []
			},
			c = this.chart;
			return each(c.axes,
			function(d) {
				var e = d.isXAxis,
				f = c.inverted ? !e: e;
				b[e ? "xAxis": "yAxis"].push({
					axis: d,
					value: d.translate((f ? a.chartX - c.plotLeft: d.top + d.len - a.chartY) - d.minPixelPadding, !0)
				})
			}),
			b
		},
		getIndex: function(a) {
			var b = this.chart;
			return b.inverted ? b.plotHeight + b.plotTop - a.chartY: a.chartX - b.plotLeft
		},
		onmousemove: function(a) {
			var f, g, j, k, b = this,
			c = b.chart,
			d = c.series,
			e = c.tooltip,
			h = c.hoverPoint,
			i = c.hoverSeries,
			l = c.chartWidth,
			m = b.getIndex(a);
			if (e && b.options.tooltip.shared && (!i || !i.noSharedTooltip)) {
				for (g = [], j = d.length, k = 0; j > k; k++) d[k].visible && d[k].options.enableMouseTracking !== !1 && !d[k].noSharedTooltip && d[k].tooltipPoints.length && (f = d[k].tooltipPoints[m], f._dist = mathAbs(m - f[d[k].xAxis.tooltipPosName || "plotX"]), l = mathMin(l, f._dist), g.push(f));
				for (j = g.length; j--;) g[j]._dist > l && g.splice(j, 1);
				g.length && g[0].plotX !== b.hoverX && (e.refresh(g, a), b.hoverX = g[0].plotX)
			}
			i && i.tracker && (f = i.tooltipPoints[m], f && f !== h && f.onMouseOver())
		},
		resetTracker: function(a) {
			var b = this,
			c = b.chart,
			d = c.hoverSeries,
			e = c.hoverPoint,
			f = c.tooltip,
			g = f && f.shared ? c.hoverPoints: e;
			a = a && f && g,
			a && splat(g)[0].plotX === UNDEFINED && (a = !1),
			a ? f.refresh(g) : (e && e.onMouseOut(), d && d.onMouseOut(), f && (f.hide(), f.hideCrosshairs()), b.hoverX = null)
		},
		setDOMEvents: function() {
			function h() {
				if (b.selectionMarker) {
					var j, a = {
						xAxis: [],
						yAxis: []
					},
					f = b.selectionMarker.getBBox(),
					g = f.x - c.plotLeft,
					i = f.y - c.plotTop;
					e && (each(c.axes,
					function(b) {
						if (b.options.zoomEnabled !== !1) {
							var d = b.isXAxis,
							e = c.inverted ? !d: d,
							h = b.translate(e ? g: c.plotHeight - i - f.height, !0, 0, 0, 1),
							k = b.translate((e ? g + f.width: c.plotHeight - i) - 2 * b.minPixelPadding, !0, 0, 0, 1);
							isNaN(h) || isNaN(k) || (a[d ? "xAxis": "yAxis"].push({
								axis: b,
								min: mathMin(h, k),
								max: mathMax(h, k)
							}), j = !0)
						}
					}), j && fireEvent(c, "selection", a,
					function(a) {
						c.zoom(a)
					})),
					b.selectionMarker = b.selectionMarker.destroy()
				}
				c && (css(d, {
					cursor: "auto"
				}), c.cancelClick = e, c.mouseIsDown = e = !1),
				removeEvent(doc, hasTouch ? "touchend": "mouseup", h)
			}
			var e, i, a = !0,
			b = this,
			c = b.chart,
			d = c.container,
			f = b.zoomX && !c.inverted || b.zoomY && c.inverted,
			g = b.zoomY && !c.inverted || b.zoomX && c.inverted;
			b.hideTooltipOnMouseMove = function(a) {
				a = washMouseEvent(a),
				b.chartPosition && c.hoverSeries && c.hoverSeries.isCartesian && !c.isInsidePlot(a.pageX - b.chartPosition.left - c.plotLeft, a.pageY - b.chartPosition.top - c.plotTop) && b.resetTracker()
			},
			b.hideTooltipOnMouseLeave = function() {
				b.resetTracker(),
				b.chartPosition = null
			},
			d.onmousedown = function(a) {
				a = b.normalizeMouseEvent(a),
				!hasTouch && a.preventDefault && a.preventDefault(),
				c.mouseIsDown = !0,
				c.cancelClick = !1,
				c.mouseDownX = b.mouseDownX = a.chartX,
				b.mouseDownY = a.chartY,
				addEvent(doc, hasTouch ? "touchend": "mouseup", h)
			},
			i = function(d) {
				var h, i, j, k, l, m;
				if (! (d && d.touches && d.touches.length > 1)) return d = b.normalizeMouseEvent(d),
				hasTouch || (d.returnValue = !1),
				h = d.chartX,
				i = d.chartY,
				j = !c.isInsidePlot(h - c.plotLeft, i - c.plotTop),
				hasTouch && "touchstart" === d.type && (attr(d.target, "isTracker") ? c.runTrackerClick || d.preventDefault() : c.runChartClick || j || d.preventDefault()),
				j && (h < c.plotLeft ? h = c.plotLeft: h > c.plotLeft + c.plotWidth && (h = c.plotLeft + c.plotWidth), i < c.plotTop ? i = c.plotTop: i > c.plotTop + c.plotHeight && (i = c.plotTop + c.plotHeight)),
				c.mouseIsDown && "touchstart" !== d.type && (e = Math.sqrt(Math.pow(b.mouseDownX - h, 2) + Math.pow(b.mouseDownY - i, 2)), e > 10 && (k = c.isInsidePlot(b.mouseDownX - c.plotLeft, b.mouseDownY - c.plotTop), c.hasCartesianSeries && (b.zoomX || b.zoomY) && k && (b.selectionMarker || (b.selectionMarker = c.renderer.rect(c.plotLeft, c.plotTop, f ? 1 : c.plotWidth, g ? 1 : c.plotHeight, 0).attr({
					fill: b.options.chart.selectionMarkerFill || "rgba(69,114,167,0.25)",
					zIndex: 7
				}).add())), b.selectionMarker && f && (l = h - b.mouseDownX, b.selectionMarker.attr({
					width: mathAbs(l),
					x: (l > 0 ? 0 : l) + b.mouseDownX
				})), b.selectionMarker && g && (m = i - b.mouseDownY, b.selectionMarker.attr({
					height: mathAbs(m),
					y: (m > 0 ? 0 : m) + b.mouseDownY
				})), k && !b.selectionMarker && b.options.chart.panning && c.pan(h))),
				j || b.onmousemove(d),
				a = j,
				j || !c.hasCartesianSeries
			},
			d.onmousemove = i,
			addEvent(d, "mouseleave", b.hideTooltipOnMouseLeave),
			addEvent(doc, "mousemove", b.hideTooltipOnMouseMove),
			d.ontouchstart = function(a) { (b.zoomX || b.zoomY) && d.onmousedown(a),
				i(a)
			},
			d.ontouchmove = i,
			d.ontouchend = function() {
				e && b.resetTracker()
			},
			d.onclick = function(a) {
				var e, f, d = c.hoverPoint;
				a = b.normalizeMouseEvent(a),
				a.cancelBubble = !0,
				c.cancelClick || (d && (attr(a.target, "isTracker") || attr(a.target.parentNode, "isTracker")) ? (e = d.plotX, f = d.plotY, extend(d, {
					pageX: b.chartPosition.left + c.plotLeft + (c.inverted ? c.plotWidth - f: e),
					pageY: b.chartPosition.top + c.plotTop + (c.inverted ? c.plotHeight - e: f)
				}), fireEvent(d.series, "click", extend(a, {
					point: d
				})), d.firePointEvent("click", a)) : (extend(a, b.getMouseCoordinates(a)), c.isInsidePlot(a.chartX - c.plotLeft, a.chartY - c.plotTop) && fireEvent(c, "click", a)))
			}
		},
		destroy: function() {
			var a = this,
			b = a.chart,
			c = b.container;
			b.trackerGroup && (b.trackerGroup = b.trackerGroup.destroy()),
			removeEvent(c, "mouseleave", a.hideTooltipOnMouseLeave),
			removeEvent(doc, "mousemove", a.hideTooltipOnMouseMove),
			c.onclick = c.onmousedown = c.onmousemove = c.ontouchstart = c.ontouchend = c.ontouchmove = null,
			clearInterval(this.tooltipTimeout)
		},
		init: function(a, b) {
			a.trackerGroup || (a.trackerGroup = a.renderer.g("tracker").attr({
				zIndex: 9
			}).add()),
			b.enabled && (a.tooltip = new Tooltip(a, b)),
			this.setDOMEvents()
		}
	},
	Legend.prototype = {
		init: function(a) {
			var d, e, f, b = this,
			c = b.options = a.options.legend;
			c.enabled && (d = c.itemStyle, e = pick(c.padding, 8), f = c.itemMarginTop || 0, b.baseline = pInt(d.fontSize) + 3 + f, b.itemStyle = d, b.itemHiddenStyle = merge(d, c.itemHiddenStyle), b.itemMarginTop = f, b.padding = e, b.initialItemX = e, b.initialItemY = e - 5, b.maxItemWidth = 0, b.chart = a, b.itemHeight = 0, b.lastLineHeight = 0, b.render(), addEvent(b.chart, "endResize",
			function() {
				b.positionCheckboxes()
			}))
		},
		colorizeItem: function(a, b) {
			var m, n, c = this,
			d = c.options,
			e = a.legendItem,
			f = a.legendLine,
			g = a.legendSymbol,
			h = c.itemHiddenStyle.color,
			i = b ? d.itemStyle.color: h,
			j = b ? a.color: h,
			k = a.options && a.options.marker,
			l = {
				stroke: j,
				fill: j
			};
			if (e && e.css({
				fill: i
			}), f && f.attr({
				stroke: j
			}), g) {
				if (k) {
					k = a.convertAttribs(k);
					for (m in k) n = k[m],
					n !== UNDEFINED && (l[m] = n)
				}
				g.attr(l)
			}
		},
		positionItem: function(a) {
			var b = this,
			c = b.options,
			d = c.symbolPadding,
			e = !c.rtl,
			f = a._legendItemPos,
			g = f[0],
			h = f[1],
			i = a.checkbox;
			a.legendGroup && a.legendGroup.translate(e ? g: b.legendWidth - g - 2 * d - 4, h),
			i && (i.x = g, i.y = h)
		},
		destroyItem: function(a) {
			var b = a.checkbox;
			each(["legendItem", "legendLine", "legendSymbol", "legendGroup"],
			function(b) {
				a[b] && a[b].destroy()
			}),
			b && discardElement(a.checkbox)
		},
		destroy: function() {
			var a = this,
			b = a.group,
			c = a.box;
			c && (a.box = c.destroy()),
			b && (a.group = b.destroy())
		},
		positionCheckboxes: function() {
			var a = this;
			each(a.allItems,
			function(b) {
				var c = b.checkbox,
				d = a.group.alignAttr;
				c && css(c, {
					left: d.translateX + b.legendItemWidth + c.x - 20 + PX,
					top: d.translateY + c.y + 3 + PX
				})
			})
		},
		renderItem: function(a) {
			var m, r, s, b = this,
			c = b.chart,
			d = c.renderer,
			e = b.options,
			f = "horizontal" === e.layout,
			g = e.symbolWidth,
			h = e.symbolPadding,
			i = b.itemStyle,
			j = b.itemHiddenStyle,
			k = b.padding,
			l = !e.rtl,
			n = e.width,
			o = e.itemMarginBottom || 0,
			p = b.itemMarginTop,
			q = b.initialItemX,
			t = a.legendItem,
			u = a.series || a,
			v = u.options,
			w = v.showCheckbox;
			t || (a.legendGroup = d.g("legend-item").attr({
				zIndex: 1
			}).add(b.scrollGroup), u.drawLegendSymbol(b, a), a.legendItem = t = d.text(e.labelFormatter.call(a), l ? g + h: -h, b.baseline, e.useHTML).css(merge(a.visible ? i: j)).attr({
				align: l ? "left": "right",
				zIndex: 2
			}).add(a.legendGroup), a.legendGroup.on("mouseover",
			function() {
				a.setState(HOVER_STATE),
				t.css(b.options.itemHoverStyle)
			}).on("mouseout",
			function() {
				t.css(a.visible ? i: j),
				a.setState()
			}).on("click",
			function(b) {
				var c = "legendItemClick",
				d = function() {
					a.setVisible()
				};
				b = {
					browserEvent: b
				},
				a.firePointEvent ? a.firePointEvent(c, b, d) : fireEvent(a, c, b, d)
			}), b.colorizeItem(a, a.visible), v && w && (a.checkbox = createElement("input", {
				type: "checkbox",
				checked: a.selected,
				defaultChecked: a.selected
			},
			e.itemCheckboxStyle, c.container), addEvent(a.checkbox, "click",
			function(b) {
				var c = b.target;
				fireEvent(a, "checkboxClick", {
					checked: c.checked
				},
				function() {
					a.select()
				})
			}))),
			r = t.getBBox(),
			s = a.legendItemWidth = e.itemWidth || g + h + r.width + k + (w ? 20 : 0),
			b.itemHeight = m = r.height,
			f && b.itemX - q + s > (n || c.chartWidth - 2 * k - q) && (b.itemX = q, b.itemY += p + b.lastLineHeight + o, b.lastLineHeight = 0),
			b.maxItemWidth = mathMax(b.maxItemWidth, s),
			b.lastItemY = p + b.itemY + o,
			b.lastLineHeight = mathMax(m, b.lastLineHeight),
			a._legendItemPos = [b.itemX, b.itemY],
			f ? b.itemX += s: (b.itemY += p + m + o, b.lastLineHeight = m),
			b.offsetWidth = n || mathMax(f ? b.itemX - q: s, b.offsetWidth)
		},
		render: function() {
			var e, f, g, h, a = this,
			b = a.chart,
			c = b.renderer,
			d = a.group,
			i = a.box,
			j = a.options,
			k = a.padding,
			l = j.borderWidth,
			m = j.backgroundColor;
			a.itemX = a.initialItemX,
			a.itemY = a.initialItemY,
			a.offsetWidth = 0,
			a.lastItemY = 0,
			d || (a.group = d = c.g("legend").attr({
				zIndex: 7
			}).add(), a.contentGroup = c.g().attr({
				zIndex: 1
			}).add(d), a.scrollGroup = c.g().add(a.contentGroup), a.clipRect = c.clipRect(0, 0, 9999, b.chartHeight), a.contentGroup.clip(a.clipRect)),
			e = [],
			each(b.series,
			function(a) {
				var b = a.options;
				b.showInLegend && (e = e.concat(a.legendItems || ("point" === b.legendType ? a.data: a)))
			}),
			stableSort(e,
			function(a, b) {
				return (a.options && a.options.legendIndex || 0) - (b.options && b.options.legendIndex || 0)
			}),
			j.reversed && e.reverse(),
			a.allItems = e,
			a.display = f = !!e.length,
			each(e,
			function(b) {
				a.renderItem(b)
			}),
			g = j.width || a.offsetWidth,
			h = a.lastItemY + a.lastLineHeight,
			h = a.handleOverflow(h),
			(l || m) && (g += k, h += k, i ? g > 0 && h > 0 && (i[i.isNew ? "attr": "animate"](i.crisp(null, null, null, g, h)), i.isNew = !1) : (a.box = i = c.rect(0, 0, g, h, j.borderRadius, l || 0).attr({
				stroke: j.borderColor,
				"stroke-width": l || 0,
				fill: m || NONE
			}).add(d).shadow(j.shadow), i.isNew = !0), i[f ? "show": "hide"]()),
			a.legendWidth = g,
			a.legendHeight = h,
			each(e,
			function(b) {
				a.positionItem(b)
			}),
			f && d.align(extend({
				width: g,
				height: h
			},
			j), !0, b.spacingBox),
			b.isResizing || this.positionCheckboxes()
		},
		handleOverflow: function(a) {
			var e, k, b = this,
			c = this.chart,
			d = c.renderer,
			f = this.options,
			g = f.y,
			h = "top" === f.verticalAlign,
			i = c.spacingBox.height + (h ? -g: g) - this.padding,
			j = f.maxHeight,
			l = this.clipRect,
			m = f.navigation,
			n = pick(m.animation, !0),
			o = m.arrowSize || 12,
			p = this.nav;
			return "horizontal" === f.layout && (i /= 2),
			j && (i = mathMin(i, j)),
			a > i ? (this.clipHeight = k = i - 20, this.pageCount = e = mathCeil(a / k), this.currentPage = pick(this.currentPage, 1), this.fullHeight = a, l.attr({
				height: k
			}), p || (this.nav = p = d.g().attr({
				zIndex: 1
			}).add(this.group), this.up = d.symbol("triangle", 0, 0, o, o).on("click",
			function() {
				b.scroll( - 1, n)
			}).add(p), this.pager = d.text("", 15, 10).css(m.style).add(p), this.down = d.symbol("triangle-down", 0, 0, o, o).on("click",
			function() {
				b.scroll(1, n)
			}).add(p)), b.scroll(0), a = i) : p && (l.attr({
				height: c.chartHeight
			}), p.hide(), this.scrollGroup.attr({
				translateY: 1
			})),
			a
		},
		scroll: function(a, b) {
			var c = this.pageCount,
			d = this.currentPage + a,
			e = this.clipHeight,
			f = this.options.navigation,
			g = f.activeColor,
			h = f.inactiveColor,
			i = this.pager,
			j = this.padding;
			d > c && (d = c),
			d > 0 && (b !== UNDEFINED && setAnimation(b, this.chart), this.nav.attr({
				translateX: j,
				translateY: e + 7,
				visibility: VISIBLE
			}), this.up.attr({
				fill: 1 === d ? h: g
			}).css({
				cursor: 1 === d ? "default": "pointer"
			}), i.attr({
				text: d + "/" + this.pageCount
			}), this.down.attr({
				x: 18 + this.pager.getBBox().width,
				fill: d === c ? h: g
			}).css({
				cursor: d === c ? "default": "pointer"
			}), this.scrollGroup.animate({
				translateY: -mathMin(e * (d - 1), this.fullHeight - e + j) + 1
			}), i.attr({
				text: d + "/" + c
			}), this.currentPage = d)
		}
	},
	Chart.prototype = {
		initSeries: function(a) {
			var b = this,
			c = b.options.chart,
			d = a.type || c.type || c.defaultSeriesType,
			e = new seriesTypes[d];
			return e.init(this, a),
			e
		},
		addSeries: function(a, b, c) {
			var d, e = this;
			return a && (setAnimation(c, e), b = pick(b, !0), fireEvent(e, "addSeries", {
				options: a
			},
			function() {
				d = e.initSeries(a),
				e.isDirtyLegend = !0,
				b && e.redraw()
			})),
			d
		},
		isInsidePlot: function(a, b, c) {
			var d = c ? b: a,
			e = c ? a: b;
			return d >= 0 && d <= this.plotWidth && e >= 0 && e <= this.plotHeight
		},
		adjustTickAmounts: function() {
			this.options.chart.alignTicks !== !1 && each(this.axes,
			function(a) {
				a.adjustTickAmount()
			}),
			this.maxTicks = null
		},
		redraw: function(a) {
			var h, l, b = this,
			c = b.axes,
			d = b.series,
			e = b.tracker,
			f = b.legend,
			g = b.isDirtyLegend,
			i = b.isDirtyBox,
			j = d.length,
			k = j,
			m = b.renderer,
			n = m.isHidden(),
			o = [];
			for (setAnimation(a, b), n && b.cloneRenderTo(); k--;) if (l = d[k], l.isDirty && l.options.stacking) {
				h = !0;
				break
			}
			if (h) for (k = j; k--;) l = d[k],
			l.options.stacking && (l.isDirty = !0);
			each(d,
			function(a) {
				a.isDirty && "point" === a.options.legendType && (g = !0)
			}),
			g && f.options.enabled && (f.render(), b.isDirtyLegend = !1),
			b.hasCartesianSeries && (b.isResizing || (b.maxTicks = null, each(c,
			function(a) {
				a.setScale()
			})), b.adjustTickAmounts(), b.getMargins(), each(c,
			function(a) {
				a.isDirtyExtremes && (a.isDirtyExtremes = !1, o.push(function() {
					fireEvent(a, "afterSetExtremes", a.getExtremes())
				})),
				(a.isDirty || i || h) && (a.redraw(), i = !0)
			})),
			i && b.drawChartBox(),
			each(d,
			function(a) {
				a.isDirty && a.visible && (!a.isCartesian || a.xAxis) && a.redraw()
			}),
			e && e.resetTracker && e.resetTracker(!0),
			m.draw(),
			fireEvent(b, "redraw"),
			n && b.cloneRenderTo(!0),
			each(o,
			function(a) {
				a.call()
			})
		},
		showLoading: function(a) {
			var b = this,
			c = b.options,
			d = b.loadingDiv,
			e = c.loading;
			d || (b.loadingDiv = d = createElement(DIV, {
				className: PREFIX + "loading"
			},
			extend(e.style, {
				left: b.plotLeft + PX,
				top: b.plotTop + PX,
				width: b.plotWidth + PX,
				height: b.plotHeight + PX,
				zIndex: 10,
				display: NONE
			}), b.container), b.loadingSpan = createElement("span", null, e.labelStyle, d)),
			b.loadingSpan.innerHTML = a || c.lang.loading,
			b.loadingShown || (css(d, {
				opacity: 0,
				display: ""
			}), animate(d, {
				opacity: e.style.opacity
			},
			{
				duration: e.showDuration || 0
			}), b.loadingShown = !0)
		},
		hideLoading: function() {
			var a = this.options,
			b = this.loadingDiv;
			b && animate(b, {
				opacity: 0
			},
			{
				duration: a.loading.hideDuration || 100,
				complete: function() {
					css(b, {
						display: NONE
					})
				}
			}),
			this.loadingShown = !1
		},
		get: function(a) {
			var e, f, g, b = this,
			c = b.axes,
			d = b.series;
			for (e = 0; e < c.length; e++) if (c[e].options.id === a) return c[e];
			for (e = 0; e < d.length; e++) if (d[e].options.id === a) return d[e];
			for (e = 0; e < d.length; e++) for (g = d[e].points || [], f = 0; f < g.length; f++) if (g[f].id === a) return g[f];
			return null
		},
		getAxes: function() {
			var e, f, a = this,
			b = this.options,
			c = b.xAxis || {},
			d = b.yAxis || {};
			c = splat(c),
			each(c,
			function(a, b) {
				a.index = b,
				a.isX = !0
			}),
			d = splat(d),
			each(d,
			function(a, b) {
				a.index = b
			}),
			e = c.concat(d),
			each(e,
			function(b) {
				f = new Axis(a, b)
			}),
			a.adjustTickAmounts()
		},
		getSelectedPoints: function() {
			var a = [];
			return each(this.series,
			function(b) {
				a = a.concat(grep(b.points,
				function(a) {
					return a.selected
				}))
			}),
			a
		},
		getSelectedSeries: function() {
			return grep(this.series,
			function(a) {
				return a.selected
			})
		},
		showResetZoom: function() {
			return
		},
		zoomOut: function() {
			var a = this,
			b = a.resetZoomButton;
			fireEvent(a, "selection", {
				resetSelection: !0
			},
			function() {
				a.zoom()
			}),
			b && (a.resetZoomButton = b.destroy())
		},
		zoom: function(a) {
			var c, b = this; ! a || a.resetSelection ? each(b.axes,
			function(a) {
				c = a.zoom()
			}) : each(a.xAxis.concat(a.yAxis),
			function(a) {
				var d = a.axis;
				b.tracker[d.isXAxis ? "zoomX": "zoomY"] && (c = d.zoom(a.min, a.max))
			}),
			b.resetZoomButton || b.showResetZoom(),
			c && b.redraw(pick(b.options.chart.animation, b.pointCount < 100))
		},
		reload: function(a) {
			var b = this; (!a || a.resetSelection) && each(b.axes,
			function(a) {
				var b = a.getExtremes();
				b && b.userMin && b.userMax ? a.setExtremes(b.userMin, b.userMax, !1, UNDEFINED, {
					trigger: "zoom"
				}) : a.setExtremes(null, null, !1, UNDEFINED, {
					trigger: "zoom"
				})
			}),
			b.redraw(pick(b.options.chart.animation, b.pointCount < 100))
		},
		reloadex: function(a) {
			var b = this; (!a || a.resetSelection) && each(b.axes,
			function(a) {
				a.getExtremes(),
				a.setExtremes(null, null, !1, UNDEFINED, {
					trigger: "zoom"
				})
			}),
			b.redraw(pick(b.options.chart.animation, b.pointCount < 100))
		},
		pan: function(a) {
			var b = this,
			c = b.xAxis[0],
			d = b.mouseDownX,
			e = c.pointRange / 2,
			f = c.getExtremes(),
			g = c.translate(d - a, !0) + e,
			h = c.translate(d + b.plotWidth - a, !0) - e,
			i = b.hoverPoints;
			i && each(i,
			function(a) {
				a.setState()
			}),
			c.series.length && g > mathMin(f.dataMin, f.min) && h < mathMax(f.dataMax, f.max) && c.setExtremes(g, h, !0, !1, {
				trigger: "pan"
			}),
			b.mouseDownX = a,
			css(b.container, {
				cursor: "move"
			})
		},
		setTitle: function(a, b) {
			var e, f, c = this,
			d = c.options;
			c.chartTitleOptions = e = merge(d.title, a),
			c.chartSubtitleOptions = f = merge(d.subtitle, b),
			each([["title", a, e], ["subtitle", b, f]],
			function(a) {
				var b = a[0],
				d = c[b],
				e = a[1],
				f = a[2];
				d && e && (c[b] = d = d.destroy()),
				f && f.text && !d && (c[b] = c.renderer.text(f.text, 0, 0, f.useHTML).attr({
					align: f.align,
					"class": PREFIX + b,
					zIndex: f.zIndex || 4
				}).css(f.style).add().align(f, !1, c.spacingBox))
			})
		},
		getChartSize: function() {
			var a = this,
			b = a.options.chart,
			c = a.renderToClone || a.renderTo;
			a.containerWidth = adapterRun(c, "width"),
			a.containerHeight = adapterRun(c, "height"),
			a.chartWidth = b.width || a.containerWidth || 600,
			a.chartHeight = b.height || (a.containerHeight > 19 ? a.containerHeight: 400)
		},
		cloneRenderTo: function(a) {
			var b = this.renderToClone,
			c = this.container;
			a ? b && (this.renderTo.appendChild(c), discardElement(b), delete this.renderToClone) : (c && this.renderTo.removeChild(c), this.renderToClone = b = this.renderTo.cloneNode(0), css(b, {
				position: ABSOLUTE,
				top: "-9999px",
				display: "block"
			}), doc.body.appendChild(b), c && b.appendChild(c))
		},
		getContainer: function() {
			var b, d, e, f, g, a = this,
			c = a.options.chart;
			a.renderTo = f = c.renderTo,
			g = PREFIX + idCounter++,
			isString(f) && (a.renderTo = f = doc.getElementById(f)),
			f || error(13, !0),
			f.innerHTML = "",
			f.offsetWidth || a.cloneRenderTo(),
			a.getChartSize(),
			d = a.chartWidth,
			e = a.chartHeight,
			a.container = b = createElement(DIV, {
				className: PREFIX + "container" + (c.className ? " " + c.className: ""),
				id: g
			},
			extend({
				position: RELATIVE,
				overflow: HIDDEN,
				width: d + PX,
				height: e + PX,
				textAlign: "left",
				lineHeight: "normal",
				zIndex: 0
			},
			c.style), a.renderToClone || f),
			a.renderer = c.forExport ? new SVGRenderer(b, d, e, !0) : new Renderer(b, d, e),
			useCanVG && a.renderer.create(a, b, d, e)
		},
		getMargins: function() {
			var g, u, a = this,
			b = a.options.chart,
			c = b.spacingTop,
			d = b.spacingRight,
			e = b.spacingBottom,
			f = b.spacingLeft,
			h = a.legend,
			i = a.optionsMarginTop,
			j = a.optionsMarginLeft,
			k = a.optionsMarginRight,
			l = a.optionsMarginBottom,
			m = a.chartTitleOptions,
			n = a.chartSubtitleOptions,
			o = a.options.legend,
			p = pick(o.margin, 10),
			q = o.x,
			r = o.y,
			s = o.align,
			t = o.verticalAlign;
			a.resetMargins(),
			g = a.axisOffset,
			!a.title && !a.subtitle || defined(a.optionsMarginTop) || (u = mathMax(a.title && !m.floating && !m.verticalAlign && m.y || 0, a.subtitle && !n.floating && !n.verticalAlign && n.y || 0), u && (a.plotTop = mathMax(a.plotTop, u + pick(m.margin, 15) + c))),
			h.display && !o.floating && ("right" === s ? defined(k) || (a.marginRight = mathMax(a.marginRight, h.legendWidth - q + p + d)) : "left" === s ? defined(j) || (a.plotLeft = mathMax(a.plotLeft, h.legendWidth + q + p + f)) : "top" === t ? defined(i) || (a.plotTop = mathMax(a.plotTop, h.legendHeight + r + p + c)) : "bottom" === t && (defined(l) || (a.marginBottom = mathMax(a.marginBottom, h.legendHeight - r + p + e)))),
			a.extraBottomMargin && (a.marginBottom += a.extraBottomMargin),
			a.extraTopMargin && (a.plotTop += a.extraTopMargin),
			a.hasCartesianSeries && each(a.axes,
			function(a) {
				a.getOffset()
			}),
			defined(j) || (a.plotLeft += g[3]),
			defined(i) || (a.plotTop += g[0]),
			defined(l) || (a.marginBottom += g[2]),
			defined(k) || (a.marginRight += g[1]),
			a.setChartSize()
		},
		initReflow: function() {
			function e(e) {
				var f = b.width || adapterRun(c, "width"),
				g = b.height || adapterRun(c, "height"),
				h = e ? e.target: win;
				f && g && (h === win || h === doc) && ((f !== a.containerWidth || g !== a.containerHeight) && (clearTimeout(d), a.reflowTimeout = d = setTimeout(function() {
					a.container && a.resize(f, g, !1)
				},
				100)), a.containerWidth = f, a.containerHeight = g)
			}
			var d, a = this,
			b = a.options.chart,
			c = a.renderTo;
			addEvent(win, "resize", e),
			addEvent(a, "destroy",
			function() {
				removeEvent(win, "resize", e)
			})
		},
		resize: function(a, b, c) {
			var e, f, g, k, d = this,
			h = d.resetZoomButton,
			i = d.title,
			j = d.subtitle;
			d.isResizing += 1,
			k = function() {
				d && fireEvent(d, "endResize", null,
				function() {
					d.isResizing -= 1
				})
			},
			setAnimation(c, d),
			d.oldChartHeight = d.chartHeight,
			d.oldChartWidth = d.chartWidth,
			defined(a) && (d.chartWidth = e = mathRound(a)),
			defined(b) && (d.chartHeight = f = mathRound(b)),
			css(d.container, {
				width: e + PX,
				height: f + PX
			}),
			d.renderer.setSize(e, f, c),
			d.plotWidth = e - d.plotLeft - d.marginRight,
			d.plotHeight = f - d.plotTop - d.marginBottom,
			d.maxTicks = null,
			each(d.axes,
			function(a) {
				a.isDirty = !0,
				a.setScale()
			}),
			each(d.series,
			function(a) {
				a.isDirty = !0
			}),
			d.isDirtyLegend = !0,
			d.isDirtyBox = !0,
			d.getMargins(),
			g = d.spacingBox,
			i && i.align(null, null, g),
			j && j.align(null, null, g),
			h && h.align && h.align(null, null, d[h.alignTo]),
			d.redraw(c),
			d.oldChartHeight = null,
			fireEvent(d, "resize"),
			globalAnimation === !1 ? k() : setTimeout(k, globalAnimation && globalAnimation.duration || 500)
		},
		setChartSize: function() {
			var j, k, l, m, n, a = this,
			b = a.inverted,
			c = a.chartWidth,
			d = a.chartHeight,
			e = a.options.chart,
			f = e.spacingTop,
			g = e.spacingRight,
			h = e.spacingBottom,
			i = e.spacingLeft;
			a.plotLeft = j = mathRound(a.plotLeft),
			a.plotTop = k = mathRound(a.plotTop),
			a.plotWidth = l = mathRound(c - j - a.marginRight),
			a.plotHeight = m = mathRound(d - k - a.marginBottom),
			a.plotSizeX = b ? m: l,
			a.plotSizeY = b ? l: m,
			a.plotBorderWidth = n = e.plotBorderWidth || 0,
			a.spacingBox = {
				x: i,
				y: f,
				width: c - i - g,
				height: d - f - h
			},
			a.plotBox = {
				x: j,
				y: k,
				width: l,
				height: m
			},
			a.clipBox = {
				x: n / 2,
				y: n / 2,
				width: a.plotSizeX - n,
				height: a.plotSizeY - n
			},
			each(a.axes,
			function(a) {
				a.setAxisSize(),
				a.setAxisTranslation()
			})
		},
		resetMargins: function() {
			var a = this,
			b = a.options.chart,
			c = b.spacingTop,
			d = b.spacingRight,
			e = b.spacingBottom,
			f = b.spacingLeft;
			a.plotTop = pick(a.optionsMarginTop, c),
			a.marginRight = pick(a.optionsMarginRight, d),
			a.marginBottom = pick(a.optionsMarginBottom, e),
			a.plotLeft = pick(a.optionsMarginLeft, f),
			a.axisOffset = [0, 0, 0, 0]
		},
		drawChartBox: function() {
			var o, p, a = this,
			b = a.options.chart,
			c = a.renderer,
			d = a.chartWidth,
			e = a.chartHeight,
			f = a.chartBackground,
			g = a.plotBackground,
			h = a.plotBorder,
			i = a.plotBGImage,
			j = b.borderWidth || 0,
			k = b.backgroundColor,
			l = b.plotBackgroundColor,
			m = b.plotBackgroundImage,
			n = b.plotBorderWidth || 0,
			q = a.plotLeft,
			r = a.plotTop,
			s = a.plotWidth,
			t = a.plotHeight,
			u = a.plotBox,
			v = a.clipRect,
			w = a.clipBox;
			o = j + (b.shadow ? 8 : 0),
			(j || k) && (f ? f.animate(f.crisp(null, null, null, d - o, e - o)) : (p = {
				fill: k || NONE
			},
			j && (p.stroke = b.borderColor, p["stroke-width"] = j), a.chartBackground = c.rect(o / 2, o / 2, d - o, e - o, b.borderRadius, j).attr(p).add().shadow(b.shadow))),
			l && (g ? g.animate(u) : a.plotBackground = c.rect(q, r, s, t, 0).attr({
				fill: l
			}).add().shadow(b.plotShadow)),
			m && (i ? i.animate(u) : a.plotBGImage = c.image(m, q, r, s, t).add()),
			v ? v.animate({
				width: w.width,
				height: w.height
			}) : a.clipRect = c.clipRect(w),
			n && (h ? h.animate(h.crisp(null, q, r, s, t)) : a.plotBorder = c.rect(q, r, s, t, 0, n).attr({
				stroke: b.plotBorderColor,
				"stroke-width": n,
				zIndex: 1
			}).add()),
			a.isDirtyBox = !1
		},
		propFromSeries: function() {
			var c, e, f, a = this,
			b = a.options.chart,
			d = a.options.series;
			each(["inverted", "angular", "polar"],
			function(g) {
				for (c = seriesTypes[b.type || b.defaultSeriesType], f = a[g] || b[g] || c && c.prototype[g], e = d && d.length; ! f && e--;) c = seriesTypes[d[e].type],
				c && c.prototype[g] && (f = !0);
				a[g] = f
			})
		},
		render: function() {
			var g, a = this,
			b = a.axes,
			c = a.renderer,
			d = a.options,
			e = d.labels,
			f = d.credits;
			a.setTitle(),
			a.legend = new Legend(a),
			each(b,
			function(a) {
				a.setScale()
			}),
			a.getMargins(),
			a.maxTicks = null,
			each(b,
			function(a) {
				a.setTickPositions(!0),
				a.setMaxTicks()
			}),
			a.adjustTickAmounts(),
			a.getMargins(),
			a.drawChartBox(),
			a.hasCartesianSeries && each(b,
			function(a) {
				a.render()
			}),
			a.seriesGroup || (a.seriesGroup = c.g("series-group").attr({
				zIndex: 3
			}).add()),
			each(a.series,
			function(a) {
				a.translate(),
				a.setTooltipPoints(),
				a.render()
			}),
			e.items && each(e.items,
			function(b) {
				var d = extend(e.style, b.style),
				f = pInt(d.left) + a.plotLeft,
				g = pInt(d.top) + a.plotTop + 12;
				delete d.left,
				delete d.top,
				c.text(b.html, f, g).attr({
					zIndex: 2
				}).css(d).add()
			}),
			f.enabled && !a.credits && (g = f.href, a.credits = c.text(f.text, 0, 0).on("click",
			function() {
				g && (location.href = g)
			}).attr({
				align: f.position.align,
				zIndex: 8
			}).css(f.style).add().align(f.position)),
			a.hasRendered = !0
		},
		destroy: function() {
			var e, a = this,
			b = a.axes,
			c = a.series,
			d = a.container,
			f = d && d.parentNode;
			for (fireEvent(a, "destroy"), removeEvent(a), e = b.length; e--;) b[e] = b[e].destroy();
			for (e = c.length; e--;) c[e] = c[e].destroy();
			each(["title", "subtitle", "chartBackground", "plotBackground", "plotBGImage", "plotBorder", "seriesGroup", "clipRect", "credits", "tracker", "scroller", "rangeSelector", "legend", "resetZoomButton", "tooltip", "renderer"],
			function(b) {
				var c = a[b];
				c && c.destroy && (a[b] = c.destroy())
			}),
			d && (d.innerHTML = "", removeEvent(d), f && discardElement(d));
			for (e in a) delete a[e]
		},
		firstRender: function() {
			var a = this,
			b = a.options,
			c = a.callback,
			d = "onreadystatechange",
			e = "complete";
			return ! hasSVG && win == win.top && doc.readyState !== e || useCanVG && !win.canvg ? (useCanVG ? CanVGController.push(function() {
				a.firstRender()
			},
			b.global.canvasToolsURL) : doc.attachEvent(d,
			function() {
				doc.detachEvent(d, a.firstRender),
				doc.readyState === e && a.firstRender()
			}), void 0) : (a.getContainer(), fireEvent(a, "init"), Highcharts.RangeSelector && b.rangeSelector.enabled && (a.rangeSelector = new Highcharts.RangeSelector(a)), a.resetMargins(), a.setChartSize(), a.propFromSeries(), a.getAxes(), each(b.series || [],
			function(b) {
				a.initSeries(b)
			}), Highcharts.Scroller && (b.navigator.enabled || b.scrollbar.enabled) && (a.scroller = new Highcharts.Scroller(a)), a.tracker = new MouseTracker(a, b), a.render(), a.renderer.draw(), c && c.apply(a, [a]), each(a.callbacks,
			function(b) {
				b.apply(a, [a])
			}), a.cloneRenderTo(!0), fireEvent(a, "load"), void 0)
		},
		init: function(a) {
			var d, b = this,
			c = b.options.chart;
			if (c.reflow !== !1 && addEvent(b, "load", b.initReflow), a) for (d in a) addEvent(b, d, a[d]);
			b.xAxis = [],
			b.yAxis = [],
			b.animation = useCanVG ? !1 : pick(c.animation, !0),
			b.setSize = b.resize,
			b.pointCount = 0,
			b.counters = new ChartCounters,
			b.firstRender()
		}
	},
	Chart.prototype.callbacks = [],
	Point = function() {},
	Point.prototype = {
		init: function(a, b, c) {
			var f, d = this,
			e = a.chart.counters;
			return d.series = a,
			d.applyOptions(b, c),
			d.pointAttr = {},
			a.options.colorByPoint && (f = a.chart.options.colors, d.color = d.color || f[e.color++], e.wrapColor(f.length)),
			a.chart.pointCount++,
			d
		},
		applyOptions: function(a, b) {
			var c = this,
			d = c.series,
			e = typeof a;
			c.config = a,
			"number" === e || null === a ? c.y = a: "number" == typeof a[0] ? (c.x = a[0], c.y = a[1]) : "object" === e && "number" != typeof a.length ? (extend(c, a), c.options = a, a.dataLabels && (d._hasPointLabels = !0), a.marker && (d._hasPointMarkers = !0)) : "string" == typeof a[0] && (c.name = a[0], c.y = a[1]),
			c.x === UNDEFINED && (c.x = b === UNDEFINED ? d.autoIncrement() : b)
		},
		destroy: function() {
			var e, a = this,
			b = a.series,
			c = b.chart,
			d = c.hoverPoints;
			c.pointCount--,
			d && (a.setState(), erase(d, a), d.length || (c.hoverPoints = null)),
			a === c.hoverPoint && a.onMouseOut(),
			(a.graphic || a.dataLabel) && (removeEvent(a), a.destroyElements()),
			a.legendItem && c.legend.destroyItem(a);
			for (e in a) a[e] = null
		},
		destroyElements: function() {
			for (var c, a = this,
			b = ["graphic", "tracker", "dataLabel", "dataLabelUpper", "group", "connector", "shadowGroup"], d = 6; d--;) c = b[d],
			a[c] && (a[c] = a[c].destroy())
		},
		getLabelConfig: function() {
			var a = this;
			return {
				x: a.category,
				y: a.y,
				key: a.name || a.category,
				series: a.series,
				point: a,
				percentage: a.percentage,
				total: a.total || a.stackTotal
			}
		},
		select: function(a, b) {
			var c = this,
			d = c.series,
			e = d.chart;
			a = pick(a, !c.selected),
			c.firePointEvent(a ? "select": "unselect", {
				accumulate: b
			},
			function() {
				c.selected = a,
				c.setState(a && SELECT_STATE),
				b || each(e.getSelectedPoints(),
				function(a) {
					a.selected && a !== c && (a.selected = !1, a.setState(NORMAL_STATE), a.firePointEvent("unselect"))
				})
			})
		},
		onMouseOver: function() {
			var a = this,
			b = a.series,
			c = b.chart,
			d = c.tooltip,
			e = c.hoverPoint;
			e && e !== a && e.onMouseOut(),
			a.firePointEvent("mouseOver"),
			!d || d.shared && !b.noSharedTooltip || d.refresh(a),
			a.setState(HOVER_STATE),
			c.hoverPoint = a
		},
		onMouseOut: function() {
			var a = this.series.chart,
			b = a.hoverPoints;
			b && -1 !== inArray(this, b) || (this.firePointEvent("mouseOut"), this.setState(), a.hoverPoint = null)
		},
		tooltipFormatter: function(a) {
			var g, h, i, j, k, l, m, b = this,
			c = b.series,
			d = c.tooltipOptions,
			e = a.match(/\{(series|point)\.[a-zA-Z]+\}/g),
			f = /[{\.}]/,
			n = {
				y: 0,
				open: 0,
				high: 0,
				low: 0,
				close: 0,
				percentage: 1,
				total: 1
			};
			d.valuePrefix = d.valuePrefix || d.yPrefix,
			d.valueDecimals = d.valueDecimals || d.yDecimals,
			d.valueSuffix = d.valueSuffix || d.ySuffix;
			for (m in e) h = e[m],
			isString(h) && h !== a && (k = (" " + h).split(f), g = {
				point: b,
				series: c
			} [k[1]], l = k[2], g === b && n.hasOwnProperty(l) ? (j = n[l] ? l: "value", i = (d[j + "Prefix"] || "") + numberFormat(b[l], pick(d[j + "Decimals"], -1)) + (d[j + "Suffix"] || "")) : i = g[l], a = a.replace(h, i));
			return a
		},
		update: function(a, b, c) {
			var g, d = this,
			e = d.series,
			f = d.graphic,
			h = e.data,
			i = h.length,
			j = e.chart;
			b = pick(b, !0),
			d.firePointEvent("update", {
				options: a
			},
			function() {
				for (d.applyOptions(a), isObject(a) && (e.getAttribs(), f && f.attr(d.pointAttr[e.state])), g = 0; i > g; g++) if (h[g] === d) {
					e.xData[g] = d.x,
					e.yData[g] = d.y,
					e.options.data[g] = a;
					break
				}
				e.isDirty = !0,
				e.isDirtyData = !0,
				b && j.redraw(c)
			})
		},
		remove: function(a, b) {
			var f, c = this,
			d = c.series,
			e = d.chart,
			g = d.data,
			h = g.length;
			setAnimation(b, e),
			a = pick(a, !0),
			c.firePointEvent("remove", null,
			function() {
				for (f = 0; h > f; f++) if (g[f] === c) {
					g.splice(f, 1),
					d.options.data.splice(f, 1),
					d.xData.splice(f, 1),
					d.yData.splice(f, 1);
					break
				}
				c.destroy(),
				d.isDirty = !0,
				d.isDirtyData = !0,
				a && e.redraw()
			})
		},
		firePointEvent: function(a, b, c) {
			var d = this,
			e = this.series,
			f = e.options; (f.point.events[a] || d.options && d.options.events && d.options.events[a]) && this.importEvents(),
			"click" === a && f.allowPointSelect && (c = function(a) {
				d.select(null, a.ctrlKey || a.metaKey || a.shiftKey)
			}),
			fireEvent(this, a, b, c)
		},
		importEvents: function() {
			if (!this.hasImportedEvents) {
				var d, a = this,
				b = merge(a.series.options.point, a.options),
				c = b.events;
				a.events = c;
				for (d in c) addEvent(a, d, c[d]);
				this.hasImportedEvents = !0
			}
		},
		setState: function(a) {
			var m, b = this,
			c = b.plotX,
			d = b.plotY,
			e = b.series,
			f = e.options.states,
			g = defaultPlotOptions[e.type].marker && e.options.marker,
			h = g && !g.enabled,
			i = g && g.states[a],
			j = i && i.enabled === !1,
			k = e.stateMarkerGraphic,
			l = e.chart,
			n = b.pointAttr;
			a = a || NORMAL_STATE,
			a === b.state || b.selected && a !== SELECT_STATE || f[a] && f[a].enabled === !1 || a && (j || h && !i.enabled) || (b.graphic ? (m = g && b.graphic.symbolName && n[a].r, b.graphic.attr(merge(n[a], m ? {
				x: c - m,
				y: d - m,
				width: 2 * m,
				height: 2 * m
			}: {}))) : (a && i && (m = i.radius, k ? k.attr({
				x: c - m,
				y: d - m
			}) : e.stateMarkerGraphic = k = l.renderer.symbol(e.symbol, c - m, d - m, 2 * m, 2 * m).attr(n[a]).add(e.markerGroup)), k && k[a && l.isInsidePlot(c, d) ? "show": "hide"]()), b.state = a)
		}
	},
	Series = function() {},
	Series.prototype = {
		isCartesian: !0,
		type: "line",
		pointClass: Point,
		sorted: !0,
		pointAttrToOptions: {
			stroke: "lineColor",
			"stroke-width": "lineWidth",
			fill: "fillColor",
			r: "radius"
		},
		init: function(a, b) {
			var d, e, c = this;
			c.chart = a,
			c.options = b = c.setOptions(b),
			c.bindAxes(),
			extend(c, {
				name: b.name,
				state: NORMAL_STATE,
				pointAttr: {},
				visible: b.visible !== !1,
				selected: b.selected === !0
			}),
			useCanVG && (b.animation = !1),
			e = b.events;
			for (d in e) addEvent(c, d, e[d]); (e && e.click || b.point && b.point.events && b.point.events.click || b.allowPointSelect) && (a.runTrackerClick = !0),
			c.getColor(),
			c.getSymbol(),
			c.setData(b.data, !1),
			c.isCartesian && (a.hasCartesianSeries = !0),
			a.series.push(c),
			stableSort(a.series,
			function(a, b) {
				return (a.options.index || 0) - (b.options.index || 0)
			}),
			each(a.series,
			function(a, b) {
				a.index = b,
				a.name = a.name || "Series " + (b + 1)
			})
		},
		bindAxes: function() {
			var d, a = this,
			b = a.options,
			c = a.chart;
			a.isCartesian && each(["xAxis", "yAxis"],
			function(e) {
				each(c[e],
				function(c) {
					d = c.options,
					(b[e] === d.index || b[e] === UNDEFINED && 0 === d.index) && (c.series.push(a), a[e] = c, c.isDirty = !0)
				})
			})
		},
		autoIncrement: function() {
			var a = this,
			b = a.options,
			c = a.xIncrement;
			return c = pick(c, b.pointStart, 0),
			a.pointInterval = pick(a.pointInterval, b.pointInterval, 1),
			a.xIncrement = c + a.pointInterval,
			c
		},
		getSegments: function() {
			var d, a = this,
			b = -1,
			c = [],
			e = a.points,
			f = e.length;
			if (f) if (a.options.connectNulls) {
				for (d = f; d--;) null === e[d].y && e.splice(d, 1);
				e.length && (c = [e])
			} else each(e,
			function(a, d) {
				null === a.y ? (d > b + 1 && c.push(e.slice(b + 1, d)), b = d) : d === f - 1 && c.push(e.slice(b + 1, d + 1))
			});
			a.segments = c
		},
		setOptions: function(a) {
			var g, b = this.chart,
			c = b.options,
			d = c.plotOptions,
			e = d[this.type],
			f = a.data;
			return a.data = null,
			g = merge(e, d.series, a),
			g.data = a.data = f,
			this.tooltipOptions = merge(c.tooltip, g.tooltip),
			null === e.marker && delete g.marker,
			g
		},
		getColor: function() {
			var a = this.options,
			b = this.chart.options.colors,
			c = this.chart.counters;
			this.color = a.color || !a.colorByPoint && b[c.color++] || "gray",
			c.wrapColor(b.length)
		},
		getSymbol: function() {
			var a = this,
			b = a.options.marker,
			c = a.chart,
			d = c.options.symbols,
			e = c.counters;
			a.symbol = b.symbol || d[e.symbol++],
			/^url/.test(a.symbol) && (b.radius = 0),
			e.wrapSymbol(d.length)
		},
		drawLegendSymbol: function(a) {
			var d, f, k, b = this.options,
			c = b.marker,
			e = a.options,
			g = e.symbolWidth,
			h = this.chart.renderer,
			i = this.legendGroup,
			j = a.baseline;
			b.lineWidth && (k = {
				"stroke-width": b.lineWidth
			},
			b.dashStyle && (k.dashstyle = b.dashStyle), this.legendLine = h.path([M, 0, j - 4, L, g, j - 4]).attr(k).add(i)),
			c && c.enabled && (d = c.radius, this.legendSymbol = f = h.symbol(this.symbol, g / 2 - d, j - 4 - d, 2 * d, 2 * d).add(i))
		},
		addPoint: function(a, b, c, d) {
			var n, e = this,
			f = e.data,
			g = e.graph,
			h = e.area,
			i = e.chart,
			j = e.xData,
			k = e.yData,
			l = g && g.shift || 0,
			m = e.options.data,
			o = e.pointClass.prototype;
			setAnimation(d, i),
			g && c && (g.shift = l + 1),
			h && (c && (h.shift = l + 1), h.isArea = !0),
			b = pick(b, !0),
			n = {
				series: e
			},
			o.applyOptions.apply(n, [a]),
			j.push(n.x),
			k.push(o.toYData ? o.toYData.call(n) : n.y),
			m.push(a),
			c && (f[0] && f[0].remove ? f[0].remove(!1) : (f.shift(), j.shift(), k.shift(), m.shift())),
			e.getAttribs(),
			e.isDirty = !0,
			e.isDirtyData = !0,
			b && i.redraw()
		},
		setData: function(a, b) {
			var j, p, l, m, n, o, q, r, s, t, c = this,
			d = c.points,
			e = c.options,
			f = c.initialColor,
			g = c.chart,
			h = null,
			i = c.xAxis,
			k = c.pointClass.prototype;
			if (c.xIncrement = null, c.pointRange = i && i.categories ? 1 : e.pointRange, defined(f) && (g.counters.color = f), l = [], m = [], n = a ? a.length: [], o = e.turboThreshold || 1e3, q = c.pointArrayMap, r = q && q.length, n > o) {
				for (j = 0; null === h && n > j;) h = a[j],
				j++;
				if (isNumber(h)) {
					for (s = pick(e.pointStart, 0), t = pick(e.pointInterval, 1), j = 0; n > j; j++) l[j] = s,
					m[j] = a[j],
					s += t;
					c.xIncrement = s
				} else if (isArray(h)) if (r) for (j = 0; n > j; j++) p = a[j],
				l[j] = p[0],
				m[j] = p.slice(1, r + 1);
				else for (j = 0; n > j; j++) p = a[j],
				l[j] = p[0],
				m[j] = p[1]
			} else for (j = 0; n > j; j++) p = {
				series: c
			},
			k.applyOptions.apply(p, [a[j]]),
			l[j] = p.x,
			m[j] = k.toYData ? k.toYData.call(p) : p.y;
			for (isString(m[0]) && error(14, !0), c.data = [], c.options.data = a, c.xData = l, c.yData = m, j = d && d.length || 0; j--;) d[j] && d[j].destroy && d[j].destroy();
			i && (i.minRange = i.userMinRange),
			c.isDirty = c.isDirtyData = g.isDirtyBox = !0,
			pick(b, !0) && g.redraw(!1)
		},
		remove: function(a, b) {
			var c = this,
			d = c.chart;
			a = pick(a, !0),
			c.isRemoving || (c.isRemoving = !0, fireEvent(c, "remove", null,
			function() {
				c.destroy(),
				d.isDirtyLegend = d.isDirtyBox = !0,
				a && d.redraw(b)
			})),
			c.isRemoving = !1
		},
		processData: function(a) {
			var h, i, j, l, p, q, r, b = this,
			c = b.xData,
			d = b.yData,
			e = c.length,
			f = 0,
			g = e,
			k = b.xAxis,
			m = b.options,
			n = m.cropThreshold,
			o = b.isCartesian;
			if (o && !b.isDirty && !k.isDirty && !b.yAxis.isDirty && !a) return ! 1;
			if (o && b.sorted && (!n || e > n || b.forceCrop)) if (p = k.getExtremes(), q = p.min, r = p.max, c[e - 1] < q || c[0] > r) c = [],
			d = [];
			else if (c[0] < q || c[e - 1] > r) {
				for (l = 0; e > l; l++) if (c[l] >= q) {
					f = mathMax(0, l - 1);
					break
				}
				for (; e > l; l++) if (c[l] > r) {
					g = l + 1;
					break
				}
				c = c.slice(f, g),
				d = d.slice(f, g),
				h = !0
			}
			for (l = c.length - 1; l > 0; l--) i = c[l] - c[l - 1],
			i > 0 && (j === UNDEFINED || j > i) && (j = i);
			b.cropped = h,
			b.cropStart = f,
			b.processedXData = c,
			b.processedYData = d,
			null === m.pointRange && (b.pointRange = j || 1),
			b.closestPointRange = j
		},
		generatePoints: function() {
			var e, k, m, o, p, a = this,
			b = a.options,
			c = b.data,
			d = a.data,
			f = a.processedXData,
			g = a.processedYData,
			h = a.pointClass,
			i = f.length,
			j = a.cropStart || 0,
			l = a.hasGroupedData,
			n = [];
			for (d || l || (p = [], p.length = c.length, d = a.data = p), o = 0; i > o; o++) k = j + o,
			l ? n[o] = (new h).init(a, [f[o]].concat(splat(g[o]))) : (d[k] ? m = d[k] : c[k] !== UNDEFINED && (d[k] = m = (new h).init(a, c[k], f[o])), n[o] = m);
			if (d && (i !== (e = d.length) || l)) for (o = 0; e > o; o++) o !== j || l || (o += i),
			d[o] && (d[o].destroyElements(), d[o].plotX = UNDEFINED);
			a.data = d,
			a.points = n
		},
		translate: function() {
			var k, a, b, c, d, e, f, g, h, i, j, l, m, n, t, u, o, p, q, r, s;
			for (this.processedXData || this.processData(), this.generatePoints(), a = this, b = a.chart, c = a.options, d = c.stacking, e = a.xAxis, f = e.categories, g = a.yAxis, h = a.points, i = h.length, j = !!a.modifyValue, l = g.series, m = l.length, n = "between" === c.pointPlacement; m--;) if (l[m].visible) {
				l[m] === a && (k = !0);
				break
			}
			for (m = 0; i > m; m++) o = h[m],
			p = o.x,
			q = o.y,
			r = o.low,
			s = g.stacks[(q < c.threshold ? "-": "") + a.stackKey],
			o.plotX = e.translate(p, 0, 0, 0, 1, n),
			d && a.visible && s && s[p] && (t = s[p], u = t.total, t.cum = r = t.cum - q, q = r + q, k && (r = pick(c.threshold, g.min)), g.isLog && 0 >= r && (r = null), "percent" === d && (r = u ? 100 * r / u: 0, q = u ? 100 * q / u: 0), o.percentage = u ? 100 * o.y / u: 0, o.total = o.stackTotal = u, o.stackY = q),
			o.yBottom = defined(r) ? g.translate(r, 0, 1, 0, 1) : null,
			j && (q = a.modifyValue(q, o)),
			o.plotY = "number" == typeof q ? mathRound(10 * g.translate(q, 0, 1, 0, 1)) / 10 : UNDEFINED,
			o.clientX = b.inverted ? b.plotHeight - o.plotX: o.plotX,
			o.category = f && f[o.x] !== UNDEFINED ? f[o.x] : o.x;
			a.getSegments()
		},
		setTooltipPoints: function(a) {
			var d, e, f, j, k, b = this,
			c = [],
			g = b.xAxis,
			h = g ? g.tooltipLen || g.len: b.chart.plotSizeX,
			i = g && g.tooltipPosName || "plotX",
			l = [];
			if (b.options.enableMouseTracking !== !1) {
				for (a && (b.tooltipPoints = null), each(b.segments || b.points,
				function(a) {
					c = c.concat(a)
				}), g && g.reversed && (c = c.reverse()), d = c.length, k = 0; d > k; k++) for (j = c[k], e = c[k - 1] ? f + 1 : 0, f = c[k + 1] ? mathMax(0, mathFloor((j[i] + (c[k + 1] ? c[k + 1][i] : h)) / 2)) : h; e >= 0 && f >= e;) l[e++] = j;
				b.tooltipPoints = l
			}
		},
		tooltipHeaderFormatter: function(a) {
			var g, b = this,
			c = b.tooltipOptions,
			d = c.xDateFormat,
			e = b.xAxis,
			f = e && "datetime" === e.options.type;
			if (f && !d) for (g in timeUnits) if (timeUnits[g] >= e.closestPointRange) {
				d = c.dateTimeLabelFormats[g];
				break
			}
			return c.headerFormat.replace("{point.key}", f && isNumber(a) ? dateFormat(d, a) : a).replace("{series.name}", b.name).replace("{series.color}", b.color)
		},
		onMouseOver: function() {
			var a = this,
			b = a.chart,
			c = b.hoverSeries;
			c && c !== a && c.onMouseOut(),
			a.options.events.mouseOver && fireEvent(a, "mouseOver"),
			a.setState(HOVER_STATE),
			b.hoverSeries = a
		},
		onMouseOut: function() {
			var a = this,
			b = a.options,
			c = a.chart,
			d = c.tooltip,
			e = c.hoverPoint;
			e && e.onMouseOut(),
			a && b.events.mouseOut && fireEvent(a, "mouseOut"),
			!d || b.stickyTracking || d.shared || d.hide(),
			a.setState(),
			c.hoverSeries = null
		},
		animate: function(a) {
			var e, f, j, b = this,
			c = b.chart,
			d = c.renderer,
			g = b.options.animation,
			h = c.clipBox,
			i = c.inverted;
			g && !isObject(g) && (g = defaultPlotOptions[b.type].animation),
			j = "_sharedClip" + g.duration + g.easing,
			a ? (e = c[j], f = c[j + "m"], e || (c[j] = e = d.clipRect(extend(h, {
				width: 0
			})), c[j + "m"] = f = d.clipRect( - 99, i ? -c.plotLeft: -c.plotTop, 99, i ? c.chartWidth: c.chartHeight)), b.group.clip(e), b.markerGroup.clip(f), b.sharedClipKey = j) : (e = c[j], e && (e.animate({
				width: c.plotSizeX
			},
			g), c[j + "m"].animate({
				width: c.plotSizeX + 99
			},
			g)), b.animate = null, b.animationTimeout = setTimeout(function() {
				b.afterAnimate()
			},
			g.duration))
		},
		afterAnimate: function() {
			var a = this.chart,
			b = this.sharedClipKey,
			c = this.group;
			c && this.options.clip !== !1 && (c.clip(a.clipRect), this.markerGroup.clip()),
			setTimeout(function() {
				b && a[b] && (a[b] = a[b].destroy(), a[b + "m"] = a[b + "m"].destroy())
			},
			100)
		},
		drawPoints: function() {
			var b, e, f, g, h, i, j, k, l, o, p, q, a = this,
			c = a.points,
			d = a.chart,
			m = a.options,
			n = m.marker,
			r = a.markerGroup;
			if (n.enabled || a._hasPointMarkers) for (g = c.length; g--;) h = c[g],
			e = h.plotX,
			f = h.plotY,
			l = h.graphic,
			o = h.marker || {},
			p = n.enabled && o.enabled === UNDEFINED || o.enabled,
			q = d.isInsidePlot(e, f, d.inverted),
			p && f !== UNDEFINED && !isNaN(f) ? (b = h.pointAttr[h.selected ? SELECT_STATE: NORMAL_STATE], i = b.r, j = pick(o.symbol, a.symbol), k = 0 === j.indexOf("url"), l ? l.attr({
				visibility: q ? hasSVG ? "inherit": VISIBLE: HIDDEN
			}).animate(extend({
				x: e - i,
				y: f - i
			},
			l.symbolName ? {
				width: 2 * i,
				height: 2 * i
			}: {})) : q && (i > 0 || k) && (h.graphic = l = d.renderer.symbol(j, e - i, f - i, 2 * i, 2 * i).attr(b).add(r))) : l && (h.graphic = l.destroy())
		},
		convertAttribs: function(a, b, c, d) {
			var f, g, e = this.pointAttrToOptions,
			h = {};
			a = a || {},
			b = b || {},
			c = c || {},
			d = d || {};
			for (f in e) g = e[f],
			h[f] = pick(a[g], b[f], c[f], d[f]);
			return h
		},
		getAttribs: function() {
			var e, i, j, l, n, o, a = this,
			b = defaultPlotOptions[a.type].marker ? a.options.marker: a.options,
			c = b.states,
			d = c[HOVER_STATE],
			f = a.color,
			g = {
				stroke: f,
				fill: f
			},
			h = a.points || [],
			k = [],
			m = a.pointAttrToOptions;
			for (a.options.marker ? (d.radius = d.radius || b.radius + 2, d.lineWidth = d.lineWidth || b.lineWidth + 1) : d.color = d.color || Color(d.color || f).brighten(d.brightness).get(), k[NORMAL_STATE] = a.convertAttribs(b, g), each([HOVER_STATE, SELECT_STATE],
			function(b) {
				k[b] = a.convertAttribs(c[b], k[NORMAL_STATE])
			}), a.pointAttr = k, i = h.length; i--;) {
				if (j = h[i], b = j.options && j.options.marker || j.options, b && b.enabled === !1 && (b.radius = 0), n = a.options.colorByPoint, j.options) for (o in m) defined(b[m[o]]) && (n = !0);
				n ? (b = b || {},
				l = [], c = b.states || {},
				e = c[HOVER_STATE] = c[HOVER_STATE] || {},
				a.options.marker || (e.color = Color(e.color || j.color).brighten(e.brightness || d.brightness).get()), l[NORMAL_STATE] = a.convertAttribs(extend({
					color: j.color
				},
				b), k[NORMAL_STATE]), l[HOVER_STATE] = a.convertAttribs(c[HOVER_STATE], k[HOVER_STATE], l[NORMAL_STATE]), l[SELECT_STATE] = a.convertAttribs(c[SELECT_STATE], k[SELECT_STATE], l[NORMAL_STATE])) : l = k,
				j.pointAttr = l
			}
		},
		destroy: function() {
			var d, e, g, h, i, a = this,
			b = a.chart,
			c = /AppleWebKit\/533/.test(userAgent),
			f = a.data || [];
			for (fireEvent(a, "destroy"), removeEvent(a), each(["xAxis", "yAxis"],
			function(b) {
				i = a[b],
				i && (erase(i.series, a), i.isDirty = !0)
			}), a.legendItem && a.chart.legend.destroyItem(a), e = f.length; e--;) g = f[e],
			g && g.destroy && g.destroy();
			a.points = null,
			clearTimeout(a.animationTimeout),
			each(["area", "graph", "dataLabelsGroup", "group", "markerGroup", "tracker", "trackerGroup"],
			function(b) {
				a[b] && (d = c && "group" === b ? "hide": "destroy", a[b][d]())
			}),
			b.hoverSeries === a && (b.hoverSeries = null),
			erase(b.series, a);
			for (h in a) delete a[h]
		},
		drawDataLabels: function() {
			var e, f, g, h, a = this,
			b = a.options,
			c = b.dataLabels,
			d = a.points; (c.enabled || a._hasPointLabels) && (a.dlProcessOptions && a.dlProcessOptions(c), h = a.plotGroup("dataLabelsGroup", "data-labels", a.visible ? VISIBLE: HIDDEN, 6), f = c, each(d,
			function(b) {
				var d, j, k, l, i = b.dataLabel,
				m = !0;
				if (e = b.options && b.options.dataLabels, d = f.enabled || e && e.enabled, i && !d) b.dataLabel = i.destroy();
				else if (d) {
					if (l = c.rotation, c = merge(f, e), g = c.formatter.call(b.getLabelConfig(), c), c.style.color = pick(c.color, c.style.color, a.color, "black"), i) i.attr({
						text: g
					}),
					m = !1;
					else if (defined(g)) {
						j = {
							fill: c.backgroundColor,
							stroke: c.borderColor,
							"stroke-width": c.borderWidth,
							r: c.borderRadius || 0,
							rotation: l,
							padding: c.padding,
							zIndex: 1
						};
						for (k in j) j[k] === UNDEFINED && delete j[k];
						i = b.dataLabel = a.chart.renderer[l ? "text": "label"](g, 0, -999, null, null, null, c.useHTML).attr(j).css(c.style).add(h).shadow(c.shadow)
					}
					i && a.alignDataLabel(b, i, c, null, m)
				}
			}))
		},
		alignDataLabel: function(a, b, c, d, e) {
			var k, f = this.chart,
			g = f.inverted,
			h = pick(a.plotX, -999),
			i = pick(a.plotY, -999),
			j = b.getBBox();
			d = extend({
				x: g ? f.plotWidth - i: h,
				y: mathRound(g ? f.plotHeight - h: i),
				width: 0,
				height: 0
			},
			d),
			extend(c, {
				width: j.width,
				height: j.height
			}),
			c.rotation ? (k = {
				align: c.align,
				x: d.x + c.x + d.width / 2,
				y: d.y + c.y + d.height / 2
			},

			b[e ? "attr": "animate"](k)) : (b.align(c, null, d), k = b.alignAttr),
			b.attr({
				visibility: c.crop === !1 || f.isInsidePlot(k.x, k.y) || f.isInsidePlot(h, i, g) ? hasSVG ? "inherit": VISIBLE: HIDDEN
			})
		},
		getSegmentPath: function(a) {
			var b = this,
			c = [];
			return each(a,
			function(d, e) {
				if (b.getPointSpline) c.push.apply(c, b.getPointSpline(a, d, e));
				else {
					if (c.push(e ? L: M), e && b.options.step) {
						var f = a[e - 1];
						c.push(d.plotX, f.plotY)
					}
					c.push(d.plotX, d.plotY)
				}
			}),
			c
		},
		getGraphPath: function() {
			var c, a = this,
			b = [],
			d = [];
			return each(a.segments,
			function(e) {
				c = a.getSegmentPath(e),
				e.length > 1 ? b = b.concat(c) : d.push(e[0])
			}),
			a.singlePoints = d,
			a.graphPath = b,
			b
		},
		drawGraph: function() {
			var g, a = this.options,
			b = this.graph,
			c = this.group,
			d = a.lineColor || this.color,
			e = a.lineWidth,
			f = a.dashStyle,
			h = this.getGraphPath();
			b ? (stop(b), b.animate({
				d: h
			})) : e && (g = {
				stroke: d,
				"stroke-width": e,
				zIndex: 1
			},
			f && (g.dashstyle = f), this.graph = this.chart.renderer.path(h).attr(g).add(c).shadow(a.shadow))
		},
		invertGroups: function() {
			function c() {
				var b = {
					width: a.yAxis.len,
					height: a.xAxis.len
				};
				each(["group", "trackerGroup", "markerGroup"],
				function(c) {
					a[c] && a[c].attr(b).invert()
				})
			}
			var a = this,
			b = a.chart;
			addEvent(b, "resize", c),
			addEvent(a, "destroy",
			function() {
				removeEvent(b, "resize", c)
			}),
			c(),
			a.invertGroups = c
		},
		plotGroup: function(a, b, c, d, e) {
			var f = this[a],
			g = this.chart,
			h = this.xAxis,
			i = this.yAxis;
			return f || (this[a] = f = g.renderer.g(b).attr({
				visibility: c,
				zIndex: d || .1
			}).add(e)),
			f.translate(h ? h.left: g.plotLeft, i ? i.top: g.plotTop),
			f
		},
		render: function() {
			var c, a = this,
			b = a.chart,
			d = a.options,
			e = d.animation,
			f = e && !!a.animate,
			g = a.visible ? VISIBLE: HIDDEN,
			h = d.zIndex,
			i = a.hasRendered,
			j = b.seriesGroup;
			c = a.plotGroup("group", "series", g, h, j),
			a.markerGroup = a.plotGroup("markerGroup", "markers", g, h, j),
			f && a.animate(!0),
			a.getAttribs(),
			c.inverted = b.inverted,
			a.drawGraph && a.drawGraph(),
			a.drawPoints(),
			a.drawDataLabels(),
			a.options.enableMouseTracking !== !1 && a.drawTracker(),
			b.inverted && a.invertGroups(),
			d.clip === !1 || a.sharedClipKey || i || (c.clip(b.clipRect), this.trackerGroup && this.trackerGroup.clip(b.clipRect)),
			f ? a.animate() : i || a.afterAnimate(),
			a.isDirty = a.isDirtyData = !1,
			a.hasRendered = !0
		},
		redraw: function() {
			var a = this,
			b = a.chart,
			c = a.isDirtyData,
			d = a.group;
			d && (b.inverted && d.attr({
				width: b.plotWidth,
				height: b.plotHeight
			}), d.animate({
				translateX: a.xAxis.left,
				translateY: a.yAxis.top
			})),
			a.translate(),
			a.setTooltipPoints(!0),
			a.render(),
			c && fireEvent(a, "updatedData")
		},
		setState: function(a) {
			var b = this,
			c = b.options,
			d = b.graph,
			e = c.states,
			f = c.lineWidth;
			if (a = a || NORMAL_STATE, b.state !== a) {
				if (b.state = a, e[a] && e[a].enabled === !1) return;
				a && (f = e[a].lineWidth || f + 1),
				d && !d.dashstyle && d.attr({
					"stroke-width": f
				},
				a ? 0 : 500)
			}
		},
		setVisible: function(a, b) {
			var j, k, m, c = this,
			d = c.chart,
			e = c.legendItem,
			f = c.group,
			g = c.tracker,
			h = c.dataLabelsGroup,
			i = c.markerGroup,
			l = c.points,
			n = d.options.chart.ignoreHiddenSeries,
			o = c.visible;
			if (c.visible = a = a === UNDEFINED ? !o: a, j = a ? "show": "hide", f && f[j](), i && i[j](), g) g[j]();
			else if (l) for (k = l.length; k--;) m = l[k],
			m.tracker && m.tracker[j]();
			h && h[j](),
			e && d.legend.colorizeItem(c, a),
			c.isDirty = !0,
			c.options.stacking && each(d.series,
			function(a) {
				a.options.stacking && a.visible && (a.isDirty = !0)
			}),
			n && (d.isDirtyBox = !0),
			b !== !1 && d.redraw(),
			fireEvent(c, j)
		},
		show: function() {
			this.setVisible(!0)
		},
		hide: function() {
			this.setVisible(!1)
		},
		select: function(a) {
			var b = this;
			b.selected = a = a === UNDEFINED ? !b.selected: a,
			b.checkbox && (b.checkbox.checked = a),
			fireEvent(b, a ? "select": "unselect")
		},
		drawTracker: function() {
			var n, o, a = this,
			b = a.options,
			c = b.trackByArea,
			d = [].concat(c ? a.areaPath: a.graphPath),
			e = d.length,
			f = a.chart,
			g = f.renderer,
			h = f.options.tooltip.snap,
			i = a.tracker,
			j = b.cursor,
			k = j && {
				cursor: j
			},
			l = a.singlePoints,
			m = this.isCartesian && this.plotGroup("trackerGroup", null, VISIBLE, b.zIndex || 1, f.trackerGroup);
			if (e && !c) for (o = e + 1; o--;) d[o] === M && d.splice(o + 1, 0, d[o + 1] - h, d[o + 2], L),
			(o && d[o] === M || o === e) && d.splice(o, 0, L, d[o - 2] + h, d[o - 1]);
			for (o = 0; o < l.length; o++) n = l[o],
			d.push(M, n.plotX - h, n.plotY, L, n.plotX + h, n.plotY);
			i ? i.attr({
				d: d
			}) : a.tracker = g.path(d).attr({
				isTracker: !0,
				"stroke-linejoin": "bevel",
				visibility: a.visible ? VISIBLE: HIDDEN,
				stroke: TRACKER_FILL,
				fill: c ? TRACKER_FILL: NONE,
				"stroke-width": b.lineWidth + (c ? 0 : 2 * h)
			}).on(hasTouch ? "touchstart": "mouseover",
			function() {
				f.hoverSeries !== a && a.onMouseOver()
			}).on("mouseout",
			function() {
				b.stickyTracking || a.onMouseOut()
			}).css(k).add(m)
		}
	},
	LineSeries = extendClass(Series),
	seriesTypes.line = LineSeries,
	defaultPlotOptions.area = merge(defaultSeriesOptions, {
		threshold: 0
	}),
	AreaSeries = extendClass(Series, {
		type: "area",
		getSegmentPath: function(a) {
			var d, b = Series.prototype.getSegmentPath.call(this, a),
			c = [].concat(b),
			e = this.options,
			f = b.length;
			if (3 === f && c.push(L, b[1], b[2]), e.stacking && !this.closedStacks) for (d = a.length - 1; d >= 0; d--) d < a.length - 1 && e.step && c.push(a[d + 1].plotX, a[d].yBottom),
			c.push(a[d].plotX, a[d].yBottom);
			else this.closeSegment(c, a);
			return this.areaPath = this.areaPath.concat(c),
			b
		},
		closeSegment: function(a, b) {
			var c = this.yAxis.getThreshold(this.options.threshold);
			a.push(L, b[b.length - 1].plotX, c, L, b[0].plotX, c)
		},
		drawGraph: function() {
			this.areaPath = [],
			Series.prototype.drawGraph.apply(this);
			var a = this.areaPath,
			b = this.options,
			c = this.area;
			c ? c.animate({
				d: a
			}) : this.area = this.chart.renderer.path(a).attr({
				fill: pick(b.fillColor, Color(this.color).setOpacity(b.fillOpacity || .75).get()),
				zIndex: 0
			}).add(this.group)
		},
		drawLegendSymbol: function(a, b) {
			b.legendSymbol = this.chart.renderer.rect(0, a.baseline - 11, a.options.symbolWidth, 12, 2).attr({
				zIndex: 3
			}).add(b.legendGroup)
		}
	}),
	seriesTypes.area = AreaSeries,
	defaultPlotOptions.spline = merge(defaultSeriesOptions),
	SplineSeries = extendClass(Series, {
		type: "spline",
		getPointSpline: function(a, b, c) {
			var j, k, l, m, n, s, o, p, q, r, d = 1.5,
			e = d + 1,
			f = b.plotX,
			g = b.plotY,
			h = a[c - 1],
			i = a[c + 1];
			return h && i && (o = h.plotX, p = h.plotY, q = i.plotX, r = i.plotY, j = (d * f + o) / e, k = (d * g + p) / e, l = (d * f + q) / e, m = (d * g + r) / e, s = (m - k) * (l - f) / (l - j) + g - m, k += s, m += s, k > p && k > g ? (k = mathMax(p, g), m = 2 * g - k) : p > k && g > k && (k = mathMin(p, g), m = 2 * g - k), m > r && m > g ? (m = mathMax(r, g), k = 2 * g - m) : r > m && g > m && (m = mathMin(r, g), k = 2 * g - m), b.rightContX = l, b.rightContY = m),
			c ? (n = ["C", h.rightContX || h.plotX, h.rightContY || h.plotY, j || f, k || g, f, g], h.rightContX = h.rightContY = null) : n = [M, f, g],
			n
		}
	}),
	seriesTypes.spline = SplineSeries,
	defaultPlotOptions.areaspline = merge(defaultPlotOptions.area),
	areaProto = AreaSeries.prototype,
	AreaSplineSeries = extendClass(SplineSeries, {
		type: "areaspline",
		closedStacks: !0,
		getSegmentPath: areaProto.getSegmentPath,
		closeSegment: areaProto.closeSegment,
		drawGraph: areaProto.drawGraph
	}),
	seriesTypes.areaspline = AreaSplineSeries,
	defaultPlotOptions.column = merge(defaultSeriesOptions, {
		borderColor: "#FFFFFF",
		borderWidth: 1,
		borderRadius: 0,
		groupPadding: .2,
		marker: null,
		pointPadding: .1,
		minPointLength: 0,
		cropThreshold: 50,
		pointRange: null,
		states: {
			hover: {
				brightness: .1,
				shadow: !1
			},
			select: {
				color: "#C0C0C0",
				borderColor: "#000000",
				shadow: !1
			}
		},
		dataLabels: {
			align: null,
			verticalAlign: null,
			y: null
		},
		threshold: 0
	}),
	ColumnSeries = extendClass(Series, {
		type: "column",
		tooltipOutsidePlot: !0,
		pointAttrToOptions: {
			stroke: "borderColor",
			"stroke-width": "borderWidth",
			fill: "color",
			r: "borderRadius"
		},
		init: function() {
			Series.prototype.init.apply(this, arguments);
			var a = this,
			b = a.chart;
			b.hasRendered && each(b.series,
			function(b) {
				b.type === a.type && (b.isDirty = !0)
			})
		},
		translate: function() {
			var j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, a = this,
			b = a.chart,
			c = a.options,
			d = c.stacking,
			e = c.borderWidth,
			f = 0,
			g = a.xAxis,
			h = g.reversed,
			i = {};
			Series.prototype.translate.apply(a),
			c.grouping === !1 ? f = 1 : each(b.series,
			function(b) {
				var c = b.options;
				b.type === a.type && b.visible && a.options.group === c.group && (c.stacking ? (j = b.stackKey, i[j] === UNDEFINED && (i[j] = f++), k = i[j]) : c.grouping !== !1 && (k = f++), b.columnIndex = k)
			}),
			l = a.points,
			m = mathAbs(g.transA) * (g.ordinalSlope || c.pointRange || g.closestPointRange || 1),
			n = m * c.groupPadding,
			o = m - 2 * n,
			p = o / f,
			q = c.pointWidth,
			r = defined(q) ? (p - q) / 2 : p * c.pointPadding,
			s = pick(q, p - 2 * r),
			t = mathCeil(mathMax(s, 1 + 2 * e)),
			u = (h ? f - a.columnIndex: a.columnIndex) || 0,
			v = r + (n + u * p - m / 2) * (h ? -1 : 1),
			w = c.threshold,
			x = a.translatedThreshold = a.yAxis.getThreshold(w),
			y = pick(c.minPointLength, 5),
			each(l,
			function(c) {
				var l, f = c.plotY,
				g = pick(c.yBottom, x),
				h = c.plotX + v,
				i = mathCeil(mathMin(f, g)),
				j = mathCeil(mathMax(f, g) - i),
				k = a.yAxis.stacks[(c.y < 0 ? "-": "") + a.stackKey];
				d && a.visible && k && k[c.x] && k[c.x].setOffset(v, t),
				mathAbs(j) < y && y && (j = y, i = mathAbs(i - x) > y ? g - y: x - (x >= f ? y: 0)),
				c.barX = h,
				c.pointWidth = s,
				c.shapeType = "rect",
				c.shapeArgs = l = b.renderer.Element.prototype.crisp.call(0, e, h, i, t, j),
				e % 2 && (l.y -= 1, l.height += 1),
				c.trackerArgs = mathAbs(j) < 3 && merge(c.shapeArgs, {
					height: 6,
					y: i - 3
				})
			})
		},
		getSymbol: noop,
		drawLegendSymbol: AreaSeries.prototype.drawLegendSymbol,
		drawGraph: noop,
		drawPoints: function() {
			var d, a = this,
			b = a.options,
			c = a.chart.renderer;
			each(a.points,
			function(e) {
				var f = e.plotY,
				g = e.graphic;
				f === UNDEFINED || isNaN(f) || null === e.y ? g && (e.graphic = g.destroy()) : (d = e.shapeArgs, g ? (stop(g), g.animate(merge(d))) : e.graphic = g = c[e.shapeType](d).attr(e.pointAttr[e.selected ? SELECT_STATE: NORMAL_STATE]).add(a.group).shadow(b.shadow, null, b.stacking && !b.borderRadius))
			})
		},
		drawTracker: function() {
			var d, e, k, l, m, a = this,
			b = a.chart,
			c = b.renderer,
			f = +new Date,
			g = a.options,
			h = g.cursor,
			i = h && {
				cursor: h
			},
			j = a.isCartesian && a.plotGroup("trackerGroup", null, VISIBLE, g.zIndex || 1, b.trackerGroup);
			each(a.points,
			function(h) {
				e = h.tracker,
				d = h.trackerArgs || h.shapeArgs,
				l = h.plotY,
				m = !a.isCartesian || l !== UNDEFINED && !isNaN(l),
				delete d.strokeWidth,
				null !== h.y && m && (e ? e.attr(d) : h.tracker = c[h.shapeType](d).attr({
					isTracker: f,
					fill: TRACKER_FILL,
					visibility: a.visible ? VISIBLE: HIDDEN
				}).on(hasTouch ? "touchstart": "mouseover",
				function(c) {
					k = c.relatedTarget || c.fromElement,
					b.hoverSeries !== a && attr(k, "isTracker") !== f && a.onMouseOver(),
					h.onMouseOver()
				}).on("mouseout",
				function(b) {
					g.stickyTracking || (k = b.relatedTarget || b.toElement, attr(k, "isTracker") !== f && a.onMouseOut())
				}).css(i).add(h.group || j))
			})
		},
		alignDataLabel: function(a, b, c, d, e) {
			var f = this.chart,
			g = f.inverted,
			h = a.below || a.plotY > (this.translatedThreshold || f.plotSizeY),
			i = this.options.stacking || c.inside;
			a.shapeArgs && (d = merge(a.shapeArgs), g && (d = {
				x: f.plotWidth - d.y - d.height,
				y: f.plotHeight - d.x - d.width,
				width: d.height,
				height: d.width
			}), i || (g ? (d.x += h ? 0 : d.width, d.width = 0) : (d.y += h ? d.height: 0, d.height = 0))),
			c.align = pick(c.align, !g || i ? "center": h ? "right": "left"),
			c.verticalAlign = pick(c.verticalAlign, g || i ? "middle": h ? "top": "bottom"),
			Series.prototype.alignDataLabel.call(this, a, b, c, d, e)
		},
		animate: function(a) {
			var b = this,
			c = b.points,
			d = b.options;
			a || (each(c,
			function(a) {
				var c = a.graphic,
				e = a.shapeArgs,
				f = b.yAxis,
				g = d.threshold;
				c && (c.attr({
					height: 0,
					y: defined(g) ? f.getThreshold(g) : f.translate(f.getExtremes().min, 0, 1, 0, 1)
				}), c.animate({
					height: e.height,
					y: e.y
				},
				d.animation))
			}), b.animate = null)
		},
		remove: function() {
			var a = this,
			b = a.chart;
			b.hasRendered && each(b.series,
			function(b) {
				b.type === a.type && (b.isDirty = !0)
			}),
			Series.prototype.remove.apply(a, arguments)
		}
	}),
	seriesTypes.column = ColumnSeries,
	defaultPlotOptions.bar = merge(defaultPlotOptions.column),
	BarSeries = extendClass(ColumnSeries, {
		type: "bar",
		inverted: !0
	}),
	seriesTypes.bar = BarSeries,
	defaultPlotOptions.scatter = merge(defaultSeriesOptions, {
		lineWidth: 0,
		states: {
			hover: {
				lineWidth: 0
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size: 10px; color:{series.color}">{series.name}</span><br/>',
			pointFormat: "x: <b>{point.x}</b><br/>y: <b>{point.y}</b><br/>"
		}
	}),
	ScatterSeries = extendClass(Series, {
		type: "scatter",
		sorted: !1,
		translate: function() {
			var a = this;
			Series.prototype.translate.apply(a),
			each(a.points,
			function(b) {
				b.shapeType = "circle",
				b.shapeArgs = {
					x: b.plotX,
					y: b.plotY,
					r: a.chart.options.tooltip.snap
				}
			})
		},
		drawTracker: function() {
			for (var f, a = this,
			b = a.options.cursor,
			c = b && {
				cursor: b
			},
			d = a.points, e = d.length; e--;) f = d[e].graphic,
			f && (f.element._i = e);
			a._hasTracking ? a._hasTracking = !0 : a.markerGroup.attr({
				isTracker: !0
			}).on(hasTouch ? "touchstart": "mouseover",
			function(b) {
				a.onMouseOver(),
				b.target._i !== UNDEFINED && d[b.target._i].onMouseOver()
			}).on("mouseout",
			function() {
				a.options.stickyTracking || a.onMouseOut()
			}).css(c)
		}
	}),
	seriesTypes.scatter = ScatterSeries,
	defaultPlotOptions.pie = merge(defaultSeriesOptions, {
		borderColor: "#FFFFFF",
		borderWidth: 1,
		center: ["50%", "50%"],
		colorByPoint: !0,
		dataLabels: {
			distance: 30,
			enabled: !0,
			formatter: function() {
				return this.point.name
			}
		},
		legendType: "point",
		marker: null,
		size: "75%",
		showInLegend: !1,
		slicedOffset: 10,
		states: {
			hover: {
				brightness: .1,
				shadow: !1
			}
		}
	}),
	PiePoint = extendClass(Point, {
		init: function() {
			Point.prototype.init.apply(this, arguments);
			var b, a = this;
			return extend(a, {
				visible: a.visible !== !1,
				name: pick(a.name, "Slice")
			}),
			b = function() {
				a.slice()
			},
			addEvent(a, "select", b),
			addEvent(a, "unselect", b),
			a
		},
		setVisible: function(a) {
			var i, b = this,
			c = b.series,
			d = c.chart,
			e = b.tracker,
			f = b.dataLabel,
			g = b.connector,
			h = b.shadowGroup;
			b.visible = a = a === UNDEFINED ? !b.visible: a,
			i = a ? "show": "hide",
			b.group[i](),
			e && e[i](),
			f && f[i](),
			g && g[i](),
			h && h[i](),
			b.legendItem && d.legend.colorizeItem(b, a),
			!c.isDirty && c.options.ignoreHiddenPoint && (c.isDirty = !0, d.redraw())
		},
		slice: function(a, b, c) {
			var h, d = this,
			e = d.series,
			f = e.chart,
			g = d.slicedTranslation;
			setAnimation(c, f),
			b = pick(b, !0),
			a = d.sliced = defined(a) ? a: !d.sliced,
			h = {
				translateX: a ? g[0] : f.plotLeft,
				translateY: a ? g[1] : f.plotTop
			},
			d.group.animate(h),
			d.shadowGroup && d.shadowGroup.animate(h)
		}
	}),
	PieSeries = {
		type: "pie",
		isCartesian: !1,
		pointClass: PiePoint,
		pointAttrToOptions: {
			stroke: "borderColor",
			"stroke-width": "borderWidth",
			fill: "color"
		},
		getColor: function() {
			this.initialColor = this.chart.counters.color
		},
		animate: function() {
			var a = this,
			b = a.points;
			each(b,
			function(b) {
				var c = b.graphic,
				d = b.shapeArgs,
				e = -mathPI / 2;
				c && (c.attr({
					r: 0,
					start: e,
					end: e
				}), c.animate({
					r: d.r,
					start: d.start,
					end: d.end
				},
				a.options.animation))
			}),
			a.animate = null
		},
		setData: function(a, b) {
			Series.prototype.setData.call(this, a, !1),
			this.processData(),
			this.generatePoints(),
			pick(b, !0) && this.chart.redraw()
		},
		getCenter: function() {
			var g, a = this.options,
			b = this.chart,
			c = b.plotWidth,
			d = b.plotHeight,
			e = a.center.concat([a.size, a.innerSize || 0]),
			f = mathMin(c, d);
			return map(e,
			function(a, b) {
				return g = /%$/.test(a),
				g ? [c, d, f, f][b] * pInt(a) / 100 : a
			})
		},
		translate: function() {
			this.generatePoints();
			var h, j, k, l, o, p, q, t, v, a = 0,
			b = this,
			c = -.25,
			d = 1e3,
			e = b.options,
			f = e.slicedOffset,
			g = f + e.borderWidth,
			i = b.chart,
			m = b.points,
			n = 2 * mathPI,
			r = e.dataLabels.distance,
			s = e.ignoreHiddenPoint,
			u = m.length;
			for (b.center = h = b.getCenter(), b.getX = function(a, b) {
				return l = math.asin((a - h[1]) / (h[2] / 2 + r)),
				h[0] + (b ? -1 : 1) * mathCos(l) * (h[2] / 2 + r)
			},
			t = 0; u > t; t++) v = m[t],
			a += s && !v.visible ? 0 : v.y;
			for (t = 0; u > t; t++) v = m[t],
			o = a ? v.y / a: 0,
			j = mathRound(c * n * d) / d,
			(!s || v.visible) && (c += o),
			k = mathRound(c * n * d) / d,
			v.shapeType = "arc",
			v.shapeArgs = {
				x: h[0],
				y: h[1],
				r: h[2] / 2,
				innerR: h[3] / 2,
				start: j,
				end: k
			},
			l = (k + j) / 2,
			v.slicedTranslation = map([mathCos(l) * f + i.plotLeft, mathSin(l) * f + i.plotTop], mathRound),
			p = mathCos(l) * h[2] / 2,
			q = mathSin(l) * h[2] / 2,
			v.tooltipPos = [h[0] + .7 * p, h[1] + .7 * q],
			v.labelPos = [h[0] + p + mathCos(l) * r, h[1] + q + mathSin(l) * r, h[0] + p + mathCos(l) * g, h[1] + q + mathSin(l) * g, h[0] + p, h[1] + q, 0 > r ? "center": n / 4 > l ? "left": "right", l],
			v.percentage = 100 * o,
			v.total = a;
			this.setTooltipPoints()
		},
		render: function() {
			var a = this;
			a.getAttribs(),
			this.drawPoints(),
			a.options.enableMouseTracking !== !1 && a.drawTracker(),
			this.drawDataLabels(),
			a.options.animation && a.animate && a.animate(),
			a.isDirty = !1
		},
		drawPoints: function() {
			var d, e, f, h, i, a = this,
			b = a.chart,
			c = b.renderer,
			g = a.options.shadow;
			each(a.points,
			function(j) {
				e = j.graphic,
				i = j.shapeArgs,
				f = j.group,
				h = j.shadowGroup,
				g && !h && (h = j.shadowGroup = c.g("shadow").attr({
					zIndex: 4
				}).add()),
				f || (f = j.group = c.g("point").attr({
					zIndex: 5
				}).add()),
				d = j.sliced ? j.slicedTranslation: [b.plotLeft, b.plotTop],
				f.translate(d[0], d[1]),
				h && h.translate(d[0], d[1]),
				e ? e.animate(i) : j.graphic = e = c.arc(i).setRadialReference(a.center).attr(extend(j.pointAttr[NORMAL_STATE], {
					"stroke-linejoin": "round"
				})).add(j.group).shadow(g, h),
				j.visible === !1 && j.setVisible(!1)
			})
		},
		drawDataLabels: function() {
			var c, h, i, p, q, r, t, u, v, w, x, z, B, E, G, A, C, D, F, I, J, H, K, N, a = this,
			b = a.data,
			d = a.chart,
			e = a.options.dataLabels,
			f = pick(e.connectorPadding, 10),
			g = pick(e.connectorWidth, 1),
			j = pick(e.softConnector, !0),
			k = e.distance,
			l = a.center,
			m = l[2] / 2,
			n = l[1],
			o = k > 0,
			s = [[], []],
			y = 2;
			if (e.enabled || a._hasPointLabels) for (Series.prototype.drawDataLabels.apply(a), each(b,
			function(a) {
				a.dataLabel && s[a.labelPos[7] < mathPI / 2 ? 0 : 1].push(a)
			}), s[1].reverse(), x = function(a, b) {
				return b.y - a.y
			},
			r = s[0][0] && s[0][0].dataLabel && (s[0][0].dataLabel.getBBox().height || 21); y--;) {
				if (A = [], C = [], D = s[y], F = D.length, k > 0) {
					for (E = n - m - k; n + m + k >= E; E += r) A.push(E);
					if (B = A.length, F > B) {
						for (w = [].concat(D), w.sort(x), z = F; z--;) w[z].rank = z;
						for (z = F; z--;) D[z].rank >= B && D.splice(z, 1);
						F = D.length
					}
					for (z = 0; F > z; z++) {
						for (c = D[z], q = c.labelPos, H = 9999, J = 0; B > J; J++) I = mathAbs(A[J] - q[1]),
						H > I && (H = I, G = J);
						if (z > G && null !== A[z]) G = z;
						else if (F - z + G > B && null !== A[z]) for (G = B - F + z; null === A[G];) G++;
						else for (; null === A[G];) G++;
						C.push({
							i: G,
							y: A[G]
						}),
						A[G] = null
					}
					C.sort(x)
				}
				for (z = 0; F > z; z++) c = D[z],
				q = c.labelPos,
				p = c.dataLabel,
				v = c.visible === !1 ? HIDDEN: VISIBLE,
				N = q[1],
				k > 0 ? (K = C.pop(), G = K.i, u = K.y, (N > u && null !== A[G + 1] || u > N && null !== A[G - 1]) && (u = N)) : u = N,
				t = e.justify ? l[0] + (y ? -1 : 1) * (m + k) : a.getX(0 === G || G === A.length - 1 ? N: u, y),
				p.attr({
					visibility: v,
					align: q[6]
				})[p.moved ? "animate": "attr"]({
					x: t + e.x + ({
						left: f,
						right: -f
					} [q[6]] || 0),
					y: u + e.y - 10
				}),
				p.moved = !0,
				o && g && (h = c.connector, i = j ? [M, t + ("left" === q[6] ? 5 : -5), u, "C", t, u, 2 * q[2] - q[4], 2 * q[3] - q[5], q[2], q[3], L, q[4], q[5]] : [M, t + ("left" === q[6] ? 5 : -5), u, L, q[2], q[3], L, q[4], q[5]], h ? (h.animate({
					d: i
				}), h.attr("visibility", v)) : c.connector = h = a.chart.renderer.path(i).attr({
					"stroke-width": g,
					stroke: e.connectorColor || c.color || "#606060",
					visibility: v,
					zIndex: 3
				}).translate(d.plotLeft, d.plotTop).add())
			}
		},
		alignDataLabel: noop,
		drawTracker: ColumnSeries.prototype.drawTracker,
		drawLegendSymbol: AreaSeries.prototype.drawLegendSymbol,
		getSymbol: function() {}
	},
	PieSeries = extendClass(Series, PieSeries),
	seriesTypes.pie = PieSeries,
	DATA_GROUPING = "dataGrouping",
	seriesProto = Series.prototype,
	baseProcessData = seriesProto.processData,
	baseGeneratePoints = seriesProto.generatePoints,
	baseDestroy = seriesProto.destroy,
	baseTooltipHeaderFormatter = seriesProto.tooltipHeaderFormatter,
	NUMBER = "number",
	commonOptions = {
		approximation: "average",
		groupPixelWidth: 2,
		dateTimeLabelFormats: hash(MILLISECOND, ["%A, %b %e, %H:%M:%S.%L", "%A, %b %e, %H:%M:%S.%L", "-%H:%M:%S.%L"], SECOND, ["%A, %b %e, %H:%M:%S", "%A, %b %e, %H:%M:%S", "-%H:%M:%S"], MINUTE, ["%A, %b %e, %H:%M", "%A, %b %e, %H:%M", "-%H:%M"], HOUR, ["%A, %b %e, %H:%M", "%A, %b %e, %H:%M", "-%H:%M"], DAY, ["%A, %b %e, %Y", "%A, %b %e", "-%A, %b %e, %Y"], WEEK, ["Week from %A, %b %e, %Y", "%A, %b %e", "-%A, %b %e, %Y"], MONTH, ["%B %Y", "%B", "-%B %Y"], YEAR, ["%Y", "%Y", "-%Y"])
	},
	specificOptions = {
		line: {},
		spline: {},
		area: {},
		areaspline: {},
		column: {
			approximation: "sum",
			groupPixelWidth: 10
		},
		arearange: {
			approximation: "range"
		},
		areasplinerange: {
			approximation: "range"
		},
		columnrange: {
			approximation: "range",
			groupPixelWidth: 10
		},
		candlestick: {
			approximation: "ohlc",
			groupPixelWidth: 10
		},
		ohlc: {
			approximation: "ohlc",
			groupPixelWidth: 5
		}
	},
	defaultDataGroupingUnits = [[MILLISECOND, [1, 2, 5, 10, 20, 25, 50, 100, 200, 500]], [SECOND, [1, 2, 5, 10, 15, 30]], [MINUTE, [1, 2, 5, 10, 15, 30]], [HOUR, [1, 2, 3, 4, 6, 8, 12]], [DAY, [1]], [WEEK, [1]], [MONTH, [1, 3, 6]], [YEAR, null]],
	approximations = {
		sum: function(a) {
			var c, b = a.length;
			if (!b && a.hasNulls) c = null;
			else if (b) for (c = 0; b--;) c += a[b];
			return c
		},
		average: function(a) {
			var b = a.length,
			c = approximations.sum(a);
			return typeof c === NUMBER && b && (c /= b),
			c
		},
		open: function(a) {
			return a.length ? a[0] : a.hasNulls ? null: UNDEFINED
		},
		high: function(a) {
			return a.length ? arrayMax(a) : a.hasNulls ? null: UNDEFINED
		},
		low: function(a) {
			return a.length ? arrayMin(a) : a.hasNulls ? null: UNDEFINED
		},
		close: function(a) {
			return a.length ? a[a.length - 1] : a.hasNulls ? null: UNDEFINED
		},
		ohlc: function(a, b, c, d) {
			return a = approximations.open(a),
			b = approximations.high(b),
			c = approximations.low(c),
			d = approximations.close(d),
			typeof a === NUMBER || typeof b === NUMBER || typeof c === NUMBER || typeof d === NUMBER ? [a, b, c, d] : void 0
		},
		range: function(a, b) {
			return a = approximations.low(a),
			b = approximations.high(b),
			typeof a === NUMBER || typeof b === NUMBER ? [a, b] : void 0
		}
	},
	seriesProto.groupData = function(a, b, c, d) {
		var k, l, m, s, v, w, t, u, e = this,
		f = e.data,
		g = e.options.data,
		h = [],
		i = [],
		j = a.length,
		n = !!b,
		o = [[], [], [], []],
		p = "function" == typeof d ? d: approximations[d],
		q = e.pointArrayMap,
		r = q && q.length;
		for (s = 0; j >= s; s++) {
			for (; (c[1] !== UNDEFINED && a[s] >= c[1] || s === j) && (k = c.shift(), m = p.apply(0, o), m !== UNDEFINED && (h.push(k), i.push(m)), o[0] = [], o[1] = [], o[2] = [], o[3] = [], s !== j););
			if (s === j) break;
			if (q) for (t = e.cropStart + s, u = f && f[t] || e.pointClass.prototype.applyOptions.apply({
				series: e
			},
			[g[t]]), v = 0; r > v; v++) w = u[q[v]],
			typeof w === NUMBER ? o[v].push(w) : null === w && (o[v].hasNulls = !0);
			else l = n ? b[s] : null,
			typeof l === NUMBER ? o[0].push(l) : null === l && (o[0].hasNulls = !0)
		}
		return [h, i]
	},
	seriesProto.processData = function() {
		var f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, a = this,
		b = a.chart,
		c = a.options,
		d = c[DATA_GROUPING],
		e = d && pick(d.enabled, b.options._stock);
		if (a.forceCrop = e, baseProcessData.apply(a, arguments) !== !1 && e) {
			if (a.destroyGroupedData(), h = a.processedXData, i = a.processedYData, j = b.plotSizeX, k = a.xAxis, l = pick(k.groupPixelWidth, d.groupPixelWidth), m = h.length, n = b.series, o = a.pointRange, !k.groupPixelWidth) {
				for (g = n.length; g--;) n[g].xAxis === k && n[g].options[DATA_GROUPING] && (l = mathMax(l, n[g].options[DATA_GROUPING].groupPixelWidth));
				k.groupPixelWidth = l
			}
			if (m > j / l || m && d.forced) {
				if (f = !0, a.points = null, p = k.getExtremes(), q = p.min, r = p.max, s = k.getGroupIntervalFactor && k.getGroupIntervalFactor(q, r, h) || 1, t = l * (r - q) / j * s, u = (k.getNonLinearTimeTicks || getTimeTicks)(normalizeTimeTickInterval(t, d.units || defaultDataGroupingUnits), q, r, null, h, a.closestPointRange), v = seriesProto.groupData.apply(a, [h, i, u, d.approximation]), w = v[0], x = v[1], d.smoothed) {
					for (g = w.length - 1, w[g] = r; g--&&g > 0;) w[g] += t / 2;
					w[0] = q
				}
				a.currentDataGrouping = u.info,
				null === c.pointRange && (a.pointRange = u.info.totalRange),
				a.closestPointRange = u.info.totalRange,
				a.processedXData = w,
				a.processedYData = x
			} else a.currentDataGrouping = null,
			a.pointRange = o;
			a.hasGroupedData = f
		}
	},
	seriesProto.destroyGroupedData = function() {
		var a = this.groupedData;
		each(a || [],
		function(b, c) {
			b && (a[c] = b.destroy ? b.destroy() : null)
		}),
		this.groupedData = null
	},
	seriesProto.generatePoints = function() {
		baseGeneratePoints.apply(this),
		this.destroyGroupedData(),
		this.groupedData = this.hasGroupedData ? this.points: null
	},
	seriesProto.tooltipHeaderFormatter = function(a) {
		var g, i, j, k, l, m, n, b = this,
		c = b.options,
		d = b.tooltipOptions,
		e = c.dataGrouping,
		f = d.xDateFormat,
		h = b.xAxis;
		if (h && "datetime" === h.options.type && e && isNumber(a)) {
			if (i = b.currentDataGrouping, j = e.dateTimeLabelFormats, i) k = j[i.unitName],
			1 === i.count ? f = k[0] : (f = k[1], g = k[2]);
			else if (!f) for (m in timeUnits) if (timeUnits[m] >= h.closestPointRange) {
				f = j[m][0];
				break
			}
			l = dateFormat(f, a),
			g && (l += dateFormat(g, a + i.totalRange - 1)),
			n = d.headerFormat.replace("{point.key}", l)
		} else n = baseTooltipHeaderFormatter.apply(b, [a]);
		return n
	},
	seriesProto.destroy = function() {
		for (var a = this,
		b = a.groupedData || [], c = b.length; c--;) b[c] && b[c].destroy();
		baseDestroy.apply(a)
	},
	wrap(seriesProto, "setOptions",
	function(a, b) {
		var c = a.call(this, b),
		d = this.type,
		e = this.chart.options.plotOptions;
		return specificOptions[d] && (defaultPlotOptions[d].dataGrouping || (defaultPlotOptions[d].dataGrouping = merge(commonOptions, specificOptions[d])), c.dataGrouping = merge(defaultPlotOptions[d].dataGrouping, e.series && e.series.dataGrouping, e[d].dataGrouping, b.dataGrouping)),
		c
	}),
	defaultPlotOptions.ohlc = merge(defaultPlotOptions.column, {
		lineWidth: 1,
		tooltip: {
			pointFormat: '<span style="color:{series.color};font-weight:bold">{series.name}</span><br/>Open: {point.open}<br/>High: {point.high}<br/>Low: {point.low}<br/>Close: {point.close}<br/>'
		},
		states: {
			hover: {
				lineWidth: 3
			}
		},
		threshold: null
	}),
	OHLCPoint = extendClass(Point, {
		applyOptions: function(a) {
			var b = this,
			c = b.series,
			d = c.pointArrayMap,
			e = 0,
			f = 0,
			g = d.length;
			if ("object" == typeof a && "number" != typeof a.length) extend(b, a),
			b.options = a;
			else if (a.length) for (a.length > g && ("string" == typeof a[0] ? b.name = a[0] : "number" == typeof a[0] && (b.x = a[0]), e++); g > f;) b[d[f++]] = a[e++];
			return b.y = b[c.pointValKey],
			b.x === UNDEFINED && c && (b.x = c.autoIncrement()),
			b
		},
		tooltipFormatter: function() {
			var a = this,
			b = a.series;
			return Highcharts.myOpt && Highcharts.myOpt.symbolColor ? ['<span style="color:' + Highcharts.myOpt.symbolColor + ';font-weight:bold">', a.name || b.name, "</span><br/>", "Open: ", a.open, "<br/>", "High: ", a.high, "<br/>", "Low: ", a.low, "<br/>", "Close: ", a.close, "<br/>"].join("") : ['<span style="color:' + b.color + ';font-weight:bold">', a.name || b.name, "</span><br/>", "Open: ", a.open, "<br/>", "High: ", a.high, "<br/>", "Low: ", a.low, "<br/>", "Close: ", a.close, "<br/>"].join("")
		},
		toYData: function() {
			return [this.open, this.high, this.low, this.close]
		}
	}),
	OHLCSeries = extendClass(seriesTypes.column, {
		type: "ohlc",
		pointArrayMap: ["open", "high", "low", "close"],
		pointValKey: "high",
		pointClass: OHLCPoint,
		pointAttrToOptions: {
			stroke: "color",
			"stroke-width": "lineWidth"
		},
		upColorProp: "stroke",
		getAttribs: function() {
			seriesTypes.column.prototype.getAttribs.apply(this, arguments);
			var a = this,
			b = a.options,
			c = b.states,
			d = b.upColor || a.color,
			e = merge(a.pointAttr),
			f = a.upColorProp;
			e[""][f] = d,
			e.hover[f] = c.hover.upColor || d,
			e.select[f] = c.select.upColor || d,
			each(a.points,
			function(a) {
				a.open < a.close && (a.pointAttr = e)
			})
		},
		translate: function() {
			var a = this,
			b = a.yAxis;
			seriesTypes.column.prototype.translate.apply(a),
			each(a.points,
			function(a) {
				null !== a.open && (a.plotOpen = b.translate(a.open, 0, 1, 0, 1)),
				null !== a.close && (a.plotClose = b.translate(a.close, 0, 1, 0, 1))
			})
		},
		drawPoints: function() {
			var d, e, f, g, h, i, j, k, a = this,
			b = a.points,
			c = a.chart;
			each(b,
			function(b) {
				b.plotY !== UNDEFINED && (j = b.graphic, d = b.pointAttr[b.selected ? "selected": ""], g = d["stroke-width"] % 2 / 2, k = mathRound(b.plotX) + g, h = mathRound(b.shapeArgs.width / 2), i = ["M", k, mathRound(b.yBottom), "L", k, mathRound(b.plotY)], null !== b.open && (e = mathRound(b.plotOpen) + g, i.push("M", k, e, "L", k - h, e)), null !== b.close && (f = mathRound(b.plotClose) + g, i.push("M", k, f, "L", k + h, f)), j ? j.animate({
					d: i
				}) : b.graphic = c.renderer.path(i).attr(d).add(a.group))
			})
		},
		animate: null
	}),
	seriesTypes.ohlc = OHLCSeries,
	defaultPlotOptions.candlestick = merge(defaultPlotOptions.column, {
		lineColor: "black",
		lineWidth: 1,
		states: {
			hover: {
				lineWidth: 2
			}
		},
		tooltip: defaultPlotOptions.ohlc.tooltip,
		threshold: null,
		upColor: "white"
	}),
	CandlestickSeries = extendClass(OHLCSeries, {
		type: "candlestick",
		pointAttrToOptions: {
			fill: "color",
			stroke: "lineColor",
			"stroke-width": "lineWidth"
		},
		upColorProp: "fill",
		drawPoints: function() {
			var d, e, f, g, h, i, j, k, l, m, a = this,
			b = a.points,
			c = a.chart;
			each(b,
			function(b) {
				k = b.graphic,
				b.plotY !== UNDEFINED && (d = b.pointAttr[b.selected ? "selected": ""], i = d["stroke-width"] % 2 / 2, j = mathRound(b.plotX) + i, e = mathRound(b.plotOpen) + i, f = mathRound(b.plotClose) + i, g = math.min(e, f), h = math.max(e, f), m = mathRound(b.shapeArgs.width / 2), l = ["M", j - m, h, "L", j - m, g, "L", j + m, g, "L", j + m, h, "L", j - m, h, "M", j, h, "L", j, mathRound(b.yBottom), "M", j, g, "L", j, mathRound(b.plotY), "Z"], k ? k.animate({
					d: l
				}) : b.graphic = c.renderer.path(l).attr(d).add(a.group))
			})
		}
	}),
	seriesTypes.candlestick = CandlestickSeries,
	symbols = SVGRenderer.prototype.symbols,
	defaultPlotOptions.flags = merge(defaultPlotOptions.column, {
		dataGrouping: null,
		fillColor: "white",
		lineWidth: 1,
		pointRange: 0,
		shape: "flag",
		stackDistance: 7,
		states: {
			hover: {
				lineColor: "black",
				fillColor: "#FCFFC5"
			}
		},
		style: {
			fontSize: "11px",
			fontWeight: "bold",
			textAlign: "center"
		},
		threshold: null,
		y: -30
	}),
	seriesTypes.flags = extendClass(seriesTypes.column, {
		type: "flags",
		sorted: !1,
		noSharedTooltip: !0,
		takeOrdinalPosition: !1,
		forceCrop: !0,
		init: Series.prototype.init,
		pointAttrToOptions: {
			fill: "fillColor",
			stroke: "color",
			"stroke-width": "lineWidth",
			r: "radius"
		},
		translate: function() {
			seriesTypes.column.prototype.translate.apply(this);
			var f, g, o, p, q, a = this,
			b = a.options,
			c = a.chart,
			d = a.points,
			e = d.length - 1,
			h = b.onSeries,
			i = h && c.get(h),
			j = i && i.options.step,
			k = i && i.points,
			l = k && k.length,
			m = a.xAxis,
			n = m.getExtremes();
			if (i && i.visible && l) for (p = k[l - 1].x, d.sort(function(a, b) {
				return a.x - b.x
			}); l--&&d[e] && (f = d[e], o = k[l], !(o.x <= f.x && o.plotY !== UNDEFINED && (f.x <= p && (f.plotY = o.plotY, o.x < f.x && !j && (q = k[l + 1], q && q.plotY !== UNDEFINED && (f.plotY += (f.x - o.x) / (q.x - o.x) * (q.plotY - o.plotY)))), e--, l++, 0 > e))););
			each(d,
			function(a, b) {
				a.plotY === UNDEFINED && (a.x >= n.min && a.x <= n.max ? a.plotY = m.lineTop - c.plotTop: a.shapeArgs = {}),
				g = d[b - 1],
				g && g.plotX === a.plotX && (g.stackIndex === UNDEFINED && (g.stackIndex = 0), a.stackIndex = g.stackIndex + 1)
			})
		},
		drawPoints: function() {
			var b, f, g, k, l, m, n, o, p, q, s, t, a = this,
			c = a.points,
			d = a.chart,
			e = d.renderer,
			h = a.options,
			i = h.y,
			j = h.shape,
			r = h.lineWidth % 2 / 2;
			for (m = c.length; m--;) n = c[m],
			f = n.plotX + r,
			q = n.stackIndex,
			g = n.plotY,
			g !== UNDEFINED && (g = n.plotY + i + r - (q !== UNDEFINED && q * h.stackDistance)),
			s = q ? UNDEFINED: n.plotX + r,
			t = q ? UNDEFINED: n.plotY,
			o = n.graphic,
			p = n.tracker,
			g !== UNDEFINED ? (b = n.pointAttr[n.selected ? "select": ""], o ? o.attr({
				x: f,
				y: g,
				r: b.r,
				anchorX: s,
				anchorY: t
			}) : o = n.graphic = e.label(n.options.title || h.title || "A", f, g, j, s, t).css(merge(h.style, n.style)).attr(b).attr({
				align: "flag" === j ? "left": "center",
				width: h.width,
				height: h.height
			}).add(a.group).shadow(h.shadow), k = o.box, l = k.getBBox(), n.shapeArgs = extend(l, {
				x: f - ("flag" === j ? 0 : k.attr("width") / 2),
				y: g
			})) : o && (n.graphic = o.destroy(), p && p.attr("y", -9999))
		},
		drawTracker: function() {
			seriesTypes.column.prototype.drawTracker.apply(this),
			each(this.points,
			function(a) {
				a.tracker && addEvent(a.tracker.element, "mouseover",
				function() {
					a.graphic.toFront()
				})
			})
		},
		tooltipFormatter: function(a) {
			return a.point.text
		},
		animate: function() {}
	}),
	symbols.flag = function(a, b, c, d, e) {
		var f = e && e.anchorX || a,
		g = e && e.anchorY || b;
		return ["M", f, g, "L", a, b + d, a, b, a + c, b, a + c, b + d, a, b + d, "M", f, g, "Z"]
	},
	each(["circle", "square"],
	function(a) {
		symbols[a + "pin"] = function(b, c, d, e, f) {
			var g = f && f.anchorX,
			h = f && f.anchorY,
			i = symbols[a](b, c, d, e);
			return g && h && i.push("M", g, c + e, "L", g, h),
			i
		}
	}),
	Renderer === VMLRenderer && each(["flag", "circlepin", "squarepin"],
	function(a) {
		VMLRenderer.prototype.symbols[a] = symbols[a]
	}),
	MOUSEDOWN = hasTouch ? "touchstart": "mousedown",
	MOUSEMOVE = hasTouch ? "touchmove": "mousemove",
	MOUSEUP = hasTouch ? "touchend": "mouseup",
	buttonGradient = hash(LINEAR_GRADIENT, {
		x1: 0,
		y1: 0,
		x2: 0,
		y2: 1
	},
	STOPS, [[0, "#FFF"], [1, "#CCC"]]),
	units = [].concat(defaultDataGroupingUnits),
	units[4] = [DAY, [1, 2, 3, 4]],
	units[5] = [WEEK, [1, 2, 3]],
	extend(defaultOptions, {
		navigator: {
			handles: {
				backgroundColor: "#FFF",
				borderColor: "#666"
			},
			height: 40,
			margin: 10,
			maskFill: "rgba(255, 255, 255, 0.75)",
			outlineColor: "#444",
			outlineWidth: 1,
			series: {
				type: "areaspline",
				color: "#4572A7",
				compare: null,
				fillOpacity: .4,
				dataGrouping: {
					approximation: "average",
					groupPixelWidth: 2,
					smoothed: !0,
					units: units
				},
				dataLabels: {
					enabled: !1
				},
				id: PREFIX + "navigator-series",
				lineColor: "#4572A7",
				lineWidth: 1,
				marker: {
					enabled: !1
				},
				pointRange: 0,
				shadow: !1
			},
			xAxis: {
				tickWidth: 0,
				lineWidth: 0,
				gridLineWidth: 1,
				tickPixelInterval: 200,
				labels: {
					align: "left",
					x: 3,
					y: -4
				}
			},
			yAxis: {
				gridLineWidth: 0,
				startOnTick: !1,
				endOnTick: !1,
				minPadding: .1,
				maxPadding: .1,
				labels: {
					enabled: !1
				},
				title: {
					text: null
				},
				tickWidth: 0
			}
		},
		scrollbar: {
			height: hasTouch ? 20 : 14,
			barBackgroundColor: buttonGradient,
			barBorderRadius: 2,
			barBorderWidth: 1,
			barBorderColor: "#666",
			buttonArrowColor: "#666",
			buttonBackgroundColor: buttonGradient,
			buttonBorderColor: "#666",
			buttonBorderRadius: 2,
			buttonBorderWidth: 1,
			rifleColor: "#666",
			trackBackgroundColor: hash(LINEAR_GRADIENT, {
				x1: 0,
				y1: 0,
				x2: 0,
				y2: 1
			},
			STOPS, [[0, "#EEE"], [1, "#FFF"]]),
			trackBorderColor: "#CCC",
			trackBorderWidth: 1
		}
	}),
	Scroller.prototype = {
		getAxisTop: function(a) {
			return this.navigatorOptions.top || a - this.height - this.scrollbarHeight - this.chart.options.chart.spacingBottom
		},
		drawHandle: function(a, b) {
			var j, c = this,
			d = c.chart,
			e = d.renderer,
			f = c.elementsToDestroy,
			g = c.handles,
			h = c.navigatorOptions.handles,
			i = {
				fill: h.backgroundColor,
				stroke: h.borderColor,
				"stroke-width": 1
			};
			c.rendered || (g[b] = e.g().css({
				cursor: "e-resize"
			}).attr({
				zIndex: 4 - b
			}).add(), j = e.rect( - 4.5, 0, 9, 16, 3, 1).attr(i).add(g[b]), f.push(j), j = e.path(["M", -1.5, 4, "L", -1.5, 12, "M", .5, 4, "L", .5, 12]).attr(i).add(g[b]), f.push(j)),
			g[b].translate(c.scrollerLeft + c.scrollbarHeight + parseInt(a, 10), c.top + c.height / 2 - 8)
		},
		drawScrollbarButton: function(a) {
			var i, b = this,
			c = b.chart,
			d = c.renderer,
			e = b.elementsToDestroy,
			f = b.scrollbarButtons,
			g = b.scrollbarHeight,
			h = b.scrollbarOptions;
			b.rendered || (f[a] = d.g().add(b.scrollbarGroup), i = d.rect( - .5, -.5, g + 1, g + 1, h.buttonBorderRadius, h.buttonBorderWidth).attr({
				stroke: h.buttonBorderColor,
				"stroke-width": h.buttonBorderWidth,
				fill: h.buttonBackgroundColor
			}).add(f[a]), e.push(i), i = d.path(["M", g / 2 + (a ? -1 : 1), g / 2 - 3, "L", g / 2 + (a ? -1 : 1), g / 2 + 3, g / 2 + (a ? 2 : -2), g / 2]).attr({
				fill: h.buttonArrowColor
			}).add(f[a]), e.push(i)),
			a && f[a].attr({
				translateX: b.scrollerWidth - g
			})
		},
		render: function(a, b, c, d) {
			var h, i, j, k, y, z, A, D, F, H, I, J, K, N, e = this,
			f = e.chart,
			g = f.renderer,
			l = e.scrollbarGroup,
			m = e.scrollbar,
			n = e.xAxis,
			o = e.scrollbarTrack,
			p = e.scrollbarHeight,
			q = e.scrollbarEnabled,
			r = e.navigatorOptions,
			s = e.scrollbarOptions,
			t = e.height,
			u = e.top,
			v = e.navigatorEnabled,
			w = r.outlineWidth,
			x = w / 2,
			B = e.outlineHeight,
			C = s.barBorderRadius,
			E = s.barBorderWidth,
			G = u + x;
			isNaN(a) || (e.navigatorLeft = h = pick(n.left, f.plotLeft + p), e.navigatorWidth = i = pick(n.len, f.plotWidth - 2 * p), e.scrollerLeft = j = h - p, e.scrollerWidth = k = k = i + 2 * p, n.getExtremes && (H = f.xAxis[0].getExtremes(), I = null === H.dataMin, J = n.getExtremes(), K = mathMin(H.dataMin, J.dataMin), N = mathMax(H.dataMax, J.dataMax), I || K === J.min && N === J.max || n.setExtremes(K, N, !0, !1)), c = pick(c, n.translate(a)), d = pick(d, n.translate(b)), e.zoomedMin = y = mathMax(pInt(mathMin(c, d)), 0), e.zoomedMax = z = mathMin(pInt(mathMax(c, d)), i), e.range = A = z - y, e.rendered || (v && (e.leftShade = g.rect().attr({
				fill: r.maskFill,
				zIndex: 3
			}).add(), e.rightShade = g.rect().attr({
				fill: r.maskFill,
				zIndex: 3
			}).add(), e.outline = g.path().attr({
				"stroke-width": w,
				stroke: r.outlineColor,
				zIndex: 3
			}).add()), q && (e.scrollbarGroup = l = g.g().add(), D = s.trackBorderWidth, e.scrollbarTrack = o = g.rect().attr({
				y: -D % 2 / 2,
				fill: s.trackBackgroundColor,
				stroke: s.trackBorderColor,
				"stroke-width": D,
				r: s.trackBorderRadius || 0,
				height: p
			}).add(l), e.scrollbar = m = g.rect().attr({
				y: -E % 2 / 2,
				height: p,
				fill: s.barBackgroundColor,
				stroke: s.barBorderColor,
				"stroke-width": E,
				r: C
			}).add(l), e.scrollbarRifles = g.path().attr({
				stroke: s.rifleColor,
				"stroke-width": 1
			}).add(l))), v && (e.leftShade.attr({
				x: h,
				y: u,
				width: y,
				height: t
			}), e.rightShade.attr({
				x: h + z,
				y: u,
				width: i - z,
				height: t
			}), e.outline.attr({
				d: [M, j, G, L, h + y + x, G, h + y + x, G + B - p, M, h + z - x, G + B - p, L, h + z - x, G, j + k, G]
			}), e.drawHandle(y + x, 0), e.drawHandle(z + x, 1)), q && (e.drawScrollbarButton(0), e.drawScrollbarButton(1), l.translate(j, mathRound(G + t)), o.attr({
				width: k
			}), m.attr({
				x: mathRound(p + y) + E % 2 / 2,
				width: A - E
			}), F = p + y + A / 2 - .5, e.scrollbarRifles.attr({
				d: [M, F - 3, p / 4, L, F - 3, 2 * p / 3, M, F, p / 4, L, F, 2 * p / 3, M, F + 3, p / 4, L, F + 3, 2 * p / 3],
				visibility: A > 12 ? VISIBLE: HIDDEN
			})), e.rendered = !0)
		},
		addEvents: function() {
			var a = this,
			b = a.chart;
			addEvent(b.container, MOUSEDOWN, a.mouseDownHandler),
			addEvent(b.container, MOUSEMOVE, a.mouseMoveHandler),
			addEvent(document, MOUSEUP, a.mouseUpHandler)
		},
		removeEvents: function() {
			var a = this,
			b = a.chart;
			removeEvent(b.container, MOUSEDOWN, a.mouseDownHandler),
			removeEvent(b.container, MOUSEMOVE, a.mouseMoveHandler),
			removeEvent(document, MOUSEUP, a.mouseUpHandler),
			a.navigatorEnabled && removeEvent(a.baseSeries, "updatedData", a.updatedDataHandler)
		},
		init: function() {
			var c, d, i, j, k, m, o, p, q, r, t, s, u, v, a = this,
			b = a.chart,
			e = a.scrollbarHeight,
			f = a.navigatorOptions,
			g = a.height,
			h = a.top,
			l = document.body.style,
			n = a.baseSeries;
			a.mouseDownHandler = function(d) {
				d = b.tracker.normalizeMouseEvent(d);
				var u, v, e = a.zoomedMin,
				f = a.zoomedMax,
				h = a.top,
				i = a.scrollbarHeight,
				k = a.scrollerLeft,
				n = a.scrollerWidth,
				o = a.navigatorLeft,
				p = a.navigatorWidth,
				q = a.range,
				r = d.chartX,
				s = d.chartY,
				t = hasTouch ? 10 : 7;
				s > h && h + g + i > s && (v = !a.scrollbarEnabled || h + g > s, v && math.abs(r - e - o) < t ? (a.grabbedLeft = !0, a.otherHandlePos = f) : v && math.abs(r - f - o) < t ? (a.grabbedRight = !0, a.otherHandlePos = e) : r > o + e && o + f > r ? (a.grabbedCenter = r, b.renderer.isSVG && (m = l.cursor, l.cursor = "ew-resize"), j = r - e) : r > k && k + n > r && (u = v ? r - o - q / 2 : o > r ? e - mathMin(10, q) : r > k + n - i ? e + mathMin(10, q) : o + e > r ? e - q: f, 0 > u ? u = 0 : u + q > p && (u = p - q), u !== e && b.xAxis[0].setExtremes(c.translate(u, !0), c.translate(u + q, !0), !0, !1, {
					trigger: "navigator"
				})))
			},
			a.mouseMoveHandler = function(c) {
				var l, d = a.scrollbarHeight,
				e = a.navigatorLeft,
				f = a.navigatorWidth,
				g = a.scrollerLeft,
				h = a.scrollerWidth,
				i = a.range;
				c = b.tracker.normalizeMouseEvent(c),
				l = c.chartX,
				e > l ? l = e: l > g + h - d && (l = g + h - d),
				a.grabbedLeft ? (k = !0, a.render(0, 0, l - e, a.otherHandlePos)) : a.grabbedRight ? (k = !0, a.render(0, 0, a.otherHandlePos, l - e)) : a.grabbedCenter && (k = !0, j > l ? l = j: l > f + j - i && (l = f + j - i), a.render(0, 0, l - j, l - j + i))
			},
			a.mouseUpHandler = function() {
				var d = a.zoomedMin,
				e = a.zoomedMax;
				k && b.xAxis[0].setExtremes(c.translate(d, !0), c.translate(e, !0), !0, !1, {
					trigger: "navigator"
				}),
				a.grabbedLeft = a.grabbedRight = a.grabbedCenter = k = j = null,
				l.cursor = m || ""
			},
			a.updatedDataHandler = function() {
				var k, l, m, p, q, c = n.xAxis,
				d = c.getExtremes(),
				e = d.min,
				f = d.max,
				g = d.dataMin,
				h = d.dataMax,
				j = f - e,
				r = i.xData,
				s = !!c.setExtremes;
				l = f >= r[r.length - 1],
				k = g >= e,
				o || (i.options.pointStart = n.xData[0], i.setData(n.options.data, !1), q = !0),
				k && (p = g, m = p + j),
				l && (m = h, k || (p = mathMax(m - j, i.xData[0]))),
				s && (k || l) ? c.setExtremes(p, m, !0, !1, {
					trigger: "updatedData"
				}) : (q && b.redraw(!1), a.render(mathMax(e, g), mathMin(f, h)))
			},
			p = b.xAxis.length,
			q = b.yAxis.length,
			r = b.setSize,
			b.extraBottomMargin = a.outlineHeight + f.margin,
			a.top = h = a.getAxisTop(b.chartHeight),
			a.navigatorEnabled ? (s = n ? n.options: {},
			u = s.data, v = f.series, o = v.data, s.data = v.data = null, a.xAxis = c = new Axis(b, merge({
				ordinal: n && n.xAxis.options.ordinal
			},
			f.xAxis, {
				isX: !0,
				type: "datetime",
				index: p,
				height: g,
				top: h,
				offset: 0,
				offsetLeft: e,
				offsetRight: -e,
				startOnTick: !1,
				endOnTick: !1,
				minPadding: 0,
				maxPadding: 0,
				zoomEnabled: !1
			})), a.yAxis = d = new Axis(b, merge(f.yAxis, {
				alignTicks: !1,
				height: g,
				top: h,
				offset: 0,
				index: q,
				zoomEnabled: !1
			})), t = merge(s, v, {
				threshold: null,
				clip: !1,
				enableMouseTracking: !1,
				group: "nav",
				padXAxis: !1,
				xAxis: p,
				yAxis: q,
				name: "Navigator",
				showInLegend: !1,
				isInternal: !0,
				visible: !0
			}), s.data = u, v.data = o, t.data = o || u, i = b.initSeries(t), f.adaptToUpdatedData !== !1 && addEvent(n, "updatedData", a.updatedDataHandler)) : a.xAxis = c = {
				translate: function(a, c) {
					var d = b.xAxis[0].getExtremes(),
					f = b.plotWidth - 2 * e,
					g = d.dataMin,
					h = d.dataMax - g;
					return c ? a * h / f + g: f * (a - g) / h
				}
			},
			a.series = i,
			b.setSize = function(e, f, g) {
				a.top = h = a.getAxisTop(f),
				c && d && (c.options.top = d.options.top = h),
				r.call(b, e, f, g)
			},
			a.addEvents()
		},
		destroy: function() {
			var a = this;
			a.removeEvents(),
			each([a.xAxis, a.yAxis, a.leftShade, a.rightShade, a.outline, a.scrollbarTrack, a.scrollbarRifles, a.scrollbarGroup, a.scrollbar],
			function(a) {
				a && a.destroy && a.destroy()
			}),
			a.xAxis = a.yAxis = a.leftShade = a.rightShade = a.outline = a.scrollbarTrack = a.scrollbarRifles = a.scrollbarGroup = a.scrollbar = null,
			each([a.scrollbarButtons, a.handles, a.elementsToDestroy],
			function(a) {
				destroyObjectProperties(a)
			})
		}
	},
	Highcharts.Scroller = Scroller,
	wrap(Axis.prototype, "zoom",
	function(a, b, c) {
		var g, j, d = this.chart,
		e = d.options,
		f = e.chart.zoomType,
		h = e.navigator,
		i = e.rangeSelector;
		return this.isXAxis && (h && h.enabled || i && i.enabled) && ("x" === f ? d.resetZoomButton = "blocked": "y" === f ? j = !1 : "xy" === f && (g = this.previousZoom, defined(b) ? this.previousZoom = [this.min, this.max] : g && (b = g[0], c = g[1], delete this.previousZoom))),
		j !== UNDEFINED ? j: a.call(this, b, c)
	}),
	extend(defaultOptions, {
		rangeSelector: {
			buttonTheme: {
				width: 28,
				height: 16,
				padding: 1,
				r: 0,
				zIndex: 7
			}
		}
	}),
	defaultOptions.lang = merge(defaultOptions.lang, {
		rangeSelectorZoom: "Zoom",
		rangeSelectorFrom: "From:",
		rangeSelectorTo: "To:"
	}),
	RangeSelector.prototype = {
		clickButton: function(a, b, c) {
			var q, s, w, x, y, z, d = this,
			e = d.chart,
			f = d.buttons,
			g = e.xAxis[0],
			h = g && g.getExtremes(),
			i = e.scroller && e.scroller.xAxis,
			j = i && i.getExtremes && i.getExtremes(),
			k = j && j.dataMin,
			l = j && j.dataMax,
			m = h && h.dataMin,
			n = h && h.dataMax,
			o = (defined(m) && defined(k) ? mathMin: pick)(m, k),
			p = (defined(n) && defined(l) ? mathMax: pick)(n, l),
			r = g && mathMin(h.max, p),
			t = new Date(r),
			u = b.type,
			v = b.count,
			A = {
				millisecond: 1,
				second: 1e3,
				minute: 6e4,
				hour: 36e5,
				day: 864e5,
				week: 6048e5
			};
			null !== o && null !== p && a !== d.selected && (A[u] ? (x = A[u] * v, q = mathMax(r - x, o)) : "month" === u ? (t.setMonth(t.getMonth() - v), q = mathMax(t.getTime(), o), x = 2592e6 * v) : "ytd" === u ? (t = new Date(0), s = new Date(p), z = s.getFullYear(), t.setFullYear(z), String(z) !== dateFormat("%Y", t) && t.setFullYear(z - 1), q = y = mathMax(o || 0, t.getTime()), s = s.getTime(), r = mathMin(p || s, s)) : "year" === u ? (t.setFullYear(t.getFullYear() - v), q = mathMax(o, t.getTime()), x = 31536e6 * v) : "all" === u && g && (q = o, r = p), f[a] && f[a].setState(2), g ? setTimeout(function() {
				g.setExtremes(q, r, pick(c, 1), 0, {
					trigger: "rangeSelectorButton",
					rangeSelectorButton: b
				}),
				d.selected = a
			},
			1) : (w = e.options.xAxis, w[0] = merge(w[0], {
				range: x,
				min: y
			}), d.selected = a))
		},
		init: function(a) {
			var b = this,
			c = b.chart,
			d = c.options.rangeSelector,
			e = d.buttons || a,
			f = b.buttons,
			g = b.leftBox,
			h = b.rightBox,
			i = d.selected;
			c.extraTopMargin = 25,
			b.buttonOptions = e,
			b.mouseDownHandler = function() {
				g && g.blur(),
				h && h.blur()
			},
			addEvent(c.container, MOUSEDOWN, b.mouseDownHandler),
			i !== UNDEFINED && e[i] && this.clickButton(i, e[i], !1),
			addEvent(c, "load",
			function() {
				addEvent(c.xAxis[0], "afterSetExtremes",
				function() {
					f[b.selected] && !c.renderer.forExport && f[b.selected].setState(0),
					b.selected = null
				})
			})
		},
		setInputValue: function(a, b) {
			var c = this,
			d = c.chart,
			e = d.options.rangeSelector,
			f = a.hasFocus ? e.inputEditDateFormat || "%Y-%m-%d": e.inputDateFormat || "%b %e, %Y";
			b && (a.HCTime = b),
			a.value = dateFormat(f, a.HCTime)
		},
		drawInput: function(a) {
			var i, b = this,
			c = b.chart,
			d = c.options.rangeSelector,
			e = b.boxSpanElements,
			f = defaultOptions.lang,
			g = b.div,
			h = "min" === a;
			return e[a] = createElement("span", {
				innerHTML: f[h ? "rangeSelectorFrom": "rangeSelectorTo"]
			},
			d.labelStyle, g),
			i = createElement("input", {
				name: a,
				className: PREFIX + "range-selector",
				type: "text"
			},
			extend({
				width: "80px",
				height: "16px",
				border: "1px solid silver",
				marginLeft: "5px",
				marginRight: h ? "5px": "0",
				textAlign: "center"
			},
			d.inputStyle), g),
			i.onfocus = i.onblur = function(a) {
				a = a || window.event || {},
				i.hasFocus = "focus" === a.type,
				b.setInputValue(i)
			},
			i.onchange = function() {
				var a = i.value,
				d = Date.parse(a),
				e = c.xAxis[0].getExtremes();
				isNaN(d) && (d = a.split("-"), d = Date.UTC(pInt(d[0]), pInt(d[1]) - 1, pInt(d[2]))),
				!isNaN(d) && (h && d >= e.dataMin && d <= b.rightBox.HCTime || !h && d <= e.dataMax && d >= b.leftBox.HCTime) && c.xAxis[0].setExtremes(h ? d: e.min, h ? e.max: d, UNDEFINED, UNDEFINED, {
					trigger: "rangeSelectorInput"
				})
			},
			i
		},
		render: function(a, b) {
			var p, c = this,
			d = c.chart,
			e = d.renderer,
			f = d.container,
			g = d.options.rangeSelector,
			h = c.buttons,
			i = defaultOptions.lang,
			j = c.div,
			k = d.options.chart.style,
			l = g.buttonTheme,
			m = g.inputEnabled !== !1,
			n = l && l.states,
			o = d.plotLeft;
			c.rendered || (c.zoomText = e.text(i.rangeSelectorZoom, o, d.plotTop - 10).css(g.labelStyle).add(), p = o + c.zoomText.getBBox().width + 5, each(c.buttonOptions,
			function(a, b) {
				h[b] = e.button(a.text, p, d.plotTop - 25,
				function() {
					c.clickButton(b, a),
					c.isActive = !0
				},
				l, n && n.hover, n && n.select).css({
					textAlign: "center"
				}).add(),
				p += h[b].width + (g.buttonSpacing || 0),
				c.selected === b && h[b].setState(2)
			}), m && (c.divRelative = j = createElement("div", null, {
				position: "relative",
				height: 0,
				fontFamily: k.fontFamily,
				fontSize: k.fontSize,
				zIndex: 1
			}), f.parentNode.insertBefore(j, f), c.divAbsolute = c.div = j = createElement("div", null, extend({
				position: "absolute",
				top: d.plotTop - 25 + "px",
				right: d.chartWidth - d.plotLeft - d.plotWidth + "px"
			},
			g.inputBoxStyle), j), c.leftBox = c.drawInput("min"), c.rightBox = c.drawInput("max"))),
			m && (c.setInputValue(c.leftBox, a), c.setInputValue(c.rightBox, b)),
			c.rendered = !0
		},
		destroy: function() {
			var a = this,
			b = a.leftBox,
			c = a.rightBox,
			d = a.boxSpanElements,
			e = a.divRelative,
			f = a.divAbsolute,
			g = a.zoomText;
			removeEvent(a.chart.container, MOUSEDOWN, a.mouseDownHandler),
			each([a.buttons],
			function(a) {
				destroyObjectProperties(a)
			}),
			g && (a.zoomText = g.destroy()),
			b && (b.onfocus = b.onblur = b.onchange = null),
			c && (c.onfocus = c.onblur = c.onchange = null),
			each([b, c, d.min, d.max, f, e],
			function(a) {
				discardElement(a)
			}),
			a.leftBox = a.rightBox = a.boxSpanElements = a.div = a.divAbsolute = a.divRelative = null
		}
	},
	Highcharts.RangeSelector = RangeSelector,
	Chart.prototype.callbacks.push(function(a) {
		function e() {
			b = a.xAxis[0].getExtremes(),
			c.render(mathMax(b.min, b.dataMin), mathMin(b.max, b.dataMax))
		}
		function f() {
			b = a.xAxis[0].getExtremes(),
			d.render(b.min, b.max)
		}
		function g(a) {
			c.render(a.min, a.max)
		}
		function h(a) {
			d.render(a.min, a.max)
		}
		function i() {
			c && (removeEvent(a, "resize", e), removeEvent(a.xAxis[0], "afterSetExtremes", g)),
			d && (removeEvent(a, "resize", f), removeEvent(a.xAxis[0], "afterSetExtremes", h))
		}
		var b, c = a.scroller,
		d = a.rangeSelector;
		c && (addEvent(a.xAxis[0], "afterSetExtremes", g), addEvent(a, "resize", e), e()),
		d && (addEvent(a.xAxis[0], "afterSetExtremes", h), addEvent(a, "resize", f), f()),
		addEvent(a, "destroy", i)
	}),
	Highcharts.StockChart = function(a, b) {
		var d, c = a.series,
		e = {
			marker: {
				enabled: !1,
				states: {
					hover: {
						radius: 5
					}
				}
			},
			shadow: !1,
			states: {
				hover: {
					lineWidth: 2
				}
			}
		},
		f = {
			shadow: !1,
			borderWidth: 0
		};
		return a.xAxis = map(splat(a.xAxis || {}),
		function(a) {
			return merge({
				minPadding: 0,
				maxPadding: 0,
				ordinal: !0,
				title: {
					text: null
				},
				labels: {
					overflow: "justify"
				},
				showLastLabel: !0
			},
			a, {
				type: "datetime",
				categories: null
			})
		}),
		a.yAxis = map(splat(a.yAxis || {}),
		function(a) {
			return d = a.opposite,
			merge({
				labels: {
					align: d ? "right": "left",
					x: d ? -2 : 2,
					y: -2
				},
				showLastLabel: !0,
				title: {
					text: null
				}
			},
			a)
		}),
		a.series = null,
		a = merge({
			chart: {
				panning: !0
			},
			navigator: {
				enabled: !0
			},
			scrollbar: {
				enabled: !0
			},
			rangeSelector: {
				enabled: !0
			},
			title: {
				text: null
			},
			tooltip: {
				shared: !0,
				crosshairs: !0
			},
			legend: {
				enabled: !1
			},
			plotOptions: {
				line: e,
				spline: e,
				area: e,
				areaspline: e,
				arearange: e,
				areasplinerange: e,
				column: f,
				columnrange: f,
				candlestick: f,
				ohlc: f
			}
		},
		a, {
			_stock: !0,
			chart: {
				inverted: !1
			}
		}),
		a.series = c,
		new Chart(a, b)
	},
	seriesInit = seriesProto.init,
	seriesProcessData = seriesProto.processData,
	pointTooltipFormatter = Point.prototype.tooltipFormatter,
	seriesProto.init = function() {
		seriesInit.apply(this, arguments);
		var a = this,
		b = a.options.compare;
		b && (a.modifyValue = function(a, c) {
			var d = this.compareValue;
			return a = "value" === b ? a - d: a = 100 * (a / d) - 100,
			c && (c.change = a),
			a
		})
	},
	seriesProto.processData = function() {
		var b, c, d, e, f, a = this;
		if (seriesProcessData.apply(this, arguments), a.options.compare) for (b = 0, c = a.processedXData, d = a.processedYData, e = d.length, f = a.xAxis.getExtremes().min; e > b; b++) if (typeof d[b] === NUMBER && c[b] >= f) {
			a.compareValue = d[b];
			break
		}
	},
	Point.prototype.tooltipFormatter = function(a) {
		var b = this;
		return a = a.replace("{point.change}", (b.change > 0 ? "+": "") + numberFormat(b.change, b.series.tooltipOptions.changeDecimals || 2)),
		pointTooltipFormatter.apply(this, [a])
	},
	function() {
		var a = seriesProto.init,
		b = seriesProto.getSegments;
		seriesProto.init = function() {
			var c, d, e, b = this;
			a.apply(b, arguments),
			c = b.chart,
			d = b.xAxis,
			d && d.options.ordinal && addEvent(b, "updatedData",
			function() {
				delete d.ordinalIndex
			}),
			d && d.options.ordinal && !d.hasOrdinalExtension && (d.hasOrdinalExtension = !0, d.beforeSetTickPositions = function() {
				var b, f, j, k, l, m, a = this,
				c = [],
				e = !1,
				g = a.getExtremes(),
				h = g.min,
				i = g.max;
				if (a.options.ordinal) {
					if (each(a.series,
					function(a, d) {
						if (a.visible !== !1 && a.takeOrdinalPosition !== !1 && (c = c.concat(a.processedXData), b = c.length, d && b)) for (c.sort(function(a, b) {
							return a - b
						}), d = b - 1; d--;) c[d] === c[d + 1] && c.splice(d, 1)
					}), b = c.length, b > 2) for (f = c[1] - c[0], m = b - 1; m--&&!e;) c[m + 1] - c[m] !== f && (e = !0);
					e ? (a.ordinalPositions = c, j = d.val2lin(h, !0), k = d.val2lin(i, !0), a.ordinalSlope = l = (i - h) / (k - j), a.ordinalOffset = h - j * l) : a.ordinalPositions = a.ordinalSlope = a.ordinalOffset = UNDEFINED
				}
			},
			d.val2lin = function(a, b) {
				var f, g, h, e, c = this,
				d = c.ordinalPositions;
				if (d) {
					for (e = d.length, f = e; f--;) if (d[f] === a) {
						h = f;
						break
					}
					for (f = e - 1; f--;) if (a > d[f] || 0 === f) {
						g = (a - d[f]) / (d[f + 1] - d[f]),
						h = f + g;
						break
					}
					return b ? h: c.ordinalSlope * (h || 0) + c.ordinalOffset
				}
				return a
			},
			d.lin2val = function(a, b) {
				var h, i, j, e, f, g, c = this,
				d = c.ordinalPositions;
				if (d) {
					if (e = c.ordinalSlope, f = c.ordinalOffset, g = d.length - 1, b) 0 > a ? a = d[0] : a > g ? a = d[g] : (g = mathFloor(a), j = a - g);
					else for (; g--;) if (h = e * g + f, a >= h) {
						i = e * (g + 1) + f,
						j = (a - h) / (i - h);
						break
					}
					return j !== UNDEFINED && d[g] !== UNDEFINED ? d[g] + (j ? j * (d[g + 1] - d[g]) : 0) : a
				}
				return a
			},
			d.getExtendedPositions = function() {
				var g, h, a = d.series[0].currentDataGrouping,
				b = d.ordinalIndex,
				e = a ? a.count + a.unitName: "raw",
				f = d.getExtremes();
				return b || (b = d.ordinalIndex = {}),
				b[e] || (g = {
					series: [],
					getExtremes: function() {
						return {
							min: f.dataMin,
							max: f.dataMax
						}
					},
					options: {
						ordinal: !0
					}
				},
				each(d.series,
				function(b) {
					h = {
						xAxis: g,
						xData: b.xData,
						chart: c,
						destroyGroupedData: noop
					},
					h.options = {
						dataGrouping: a ? {
							enabled: !0,
							forced: !0,
							approximation: "open",
							units: [[a.unitName, [a.count]]]
						}: {
							enabled: !1
						}
					},
					b.processData.apply(h),
					g.series.push(h)
				}), d.beforeSetTickPositions.apply(g), b[e] = g.ordinalPositions),
				b[e]
			},
			d.getGroupIntervalFactor = function(a, b, c) {
				for (var g, d = 0,
				e = c.length,
				f = []; e - 1 > d; d++) f[d] = c[d + 1] - c[d];
				return f.sort(function(a, b) {
					return a - b
				}),
				g = f[mathFloor(e / 2)],
				e * g / (b - a)
			},
			d.postProcessTickInterval = function(a) {
				var b = this.ordinalSlope;
				return b ? a / (b / d.closestPointRange) : a
			},
			d.getNonLinearTimeTicks = function(a, b, c, e, f, g, h) {
				var k, m, n, o, p, u, v, x, y, z, s, t, w, A, i = 0,
				j = 0,
				l = {},
				q = [],
				r = d.options.tickPixelInterval;
				if (!f || b === UNDEFINED) return getTimeTicks(a, b, c, e);
				for (o = f.length; o > j && (p = j && f[j - 1] > c, f[j] < b && (i = j), (j === o - 1 || f[j + 1] - f[j] > 5 * g || p) && (k = getTimeTicks(a, f[i], f[j], e), q = q.concat(k), i = j + 1), !p); j++);
				if (n = k.info, h && n.unitRange <= timeUnits[HOUR]) {
					for (j = q.length - 1, i = 1; j > i; i++) new Date(q[i])[getDate]() !== new Date(q[i - 1])[getDate]() && (l[q[i]] = DAY, m = !0);
					m && (l[q[0]] = DAY),
					n.higherRanks = l
				}
				if (q.info = n, h && defined(r)) {
					for (s = q.length, t = s, w = [], A = []; t--;) v = d.translate(q[t]),
					x && (A[t] = x - v),
					w[t] = x = v;
					for (A.sort(), y = A[mathFloor(A.length / 2)], .6 * r > y && (y = null), t = q[s - 1] > c ? s - 1 : s, x = void 0; t--;) v = w[t],
					z = x - v,
					x && .8 * r > z && (null === y || .8 * y > z) ? (l[q[t]] && !l[q[t + 1]] ? (u = t + 1, x = v) : u = t, q.splice(u, 1)) : x = v
				}
				return q
			},
			e = c.pan, c.pan = function(a) {
				var k, l, r, s, v, f, g, h, i, j, m, n, o, p, q, t, u, b = c.xAxis[0],
				d = !1;
				b.options.ordinal && b.series.length ? (f = c.mouseDownX, g = b.getExtremes(), h = g.dataMax, i = g.min, j = g.max, m = c.hoverPoints, n = b.closestPointRange, o = b.translationSlope * (b.ordinalSlope || n), p = (f - a) / o, q = {
					ordinalPositions: b.getExtendedPositions()
				},
				t = b.lin2val, u = b.val2lin, q.ordinalPositions ? mathAbs(p) > 1 && (m && each(m,
				function(a) {
					a.setState()
				}), 0 > p ? (s = q, v = b.ordinalPositions ? b: q) : (s = b.ordinalPositions ? b: q, v = q), r = v.ordinalPositions, h > r[r.length - 1] && r.push(h), k = t.apply(s, [u.apply(s, [i, !0]) + p, !0]), l = t.apply(v, [u.apply(v, [j, !0]) + p, !0]), k > mathMin(g.dataMin, i) && l < mathMax(h, j) && b.setExtremes(k, l, !0, !1, {
					trigger: "pan"
				}), c.mouseDownX = a, css(c.container, {
					cursor: "move"
				})) : d = !0) : d = !0,
				d && e.apply(c, arguments)
			})
		},
		seriesProto.getSegments = function() {
			var c, a = this,
			d = a.options.gapSize;
			b.apply(a),
			d && (c = a.segments, each(c,
			function(b, e) {
				for (var f = b.length - 1; f--;) b[f + 1].x - b[f].x > a.xAxis.closestPointRange * d && c.splice(e + 1, 0, b.splice(f + 1, b.length - f))
			}))
		}
	} (),
	eval(function(a, b, c, d, e, f) {
		if (e = function(a) {
			return (b > a ? "": e(parseInt(a / b))) + ((a %= b) > 35 ? String.fromCharCode(a + 29) : a.toString(36))
		},
		!"".replace(/^/, String)) {
			for (; c--;) f[e(c)] = d[c] || e(c);
			d = [function(a) {
				return f[a]
			}],
			e = function() {
				return "\\w+"
			},
			c = 1
		}
		for (; c--;) d[c] && (a = a.replace(new RegExp("\\b" + e(c) + "\\b", "g"), d[c]));
		return a
	}),
	extend(Highcharts, {
		Axis: Axis,
		CanVGRenderer: CanVGRenderer,
		Chart: Chart,
		Color: Color,
		Legend: Legend,
		MouseTracker: MouseTracker,
		Point: Point,
		Tick: Tick,
		Tooltip: Tooltip,
		Renderer: Renderer,
		Series: Series,
		SVGRenderer: SVGRenderer,
		VMLRenderer: VMLRenderer,
		dateFormat: dateFormat,
		pathAnim: pathAnim,
		getOptions: getOptions,
		hasBidiBug: hasBidiBug,
		numberFormat: numberFormat,
		seriesTypes: seriesTypes,
		setOptions: setOptions,
		addEvent: addEvent,
		removeEvent: removeEvent,
		createElement: createElement,
		discardElement: discardElement,
		css: css,
		each: each,
		extend: extend,
		map: map,
		merge: merge,
		pick: pick,
		splat: splat,
		extendClass: extendClass,
		pInt: pInt,
		wrap: wrap,
		svg: hasSVG,
		canvas: useCanVG,
		vml: !hasSVG && !useCanVG,
		product: "Highstock",
		version: "1.2.4"
	})
} ();