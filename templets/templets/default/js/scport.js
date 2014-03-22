!function($){$.timer=function(func,time,autostart){return this.set=function(func,time,autostart){var paramList,arg;if(this.init=!0,"object"==typeof func){paramList=["autostart","time"];for(arg in paramList)void 0!=func[paramList[arg]]&&eval(paramList[arg]+" = func[paramList[arg]]");func=func.action}return"function"==typeof func&&(this.action=func),isNaN(time)||(this.intervalTime=time),autostart&&!this.isActive&&(this.isActive=!0,this.setTimer()),this},this.once=function(a){var b=this;return isNaN(a)&&(a=0),window.setTimeout(function(){b.action()},a),this},this.play=function(a){return this.isActive||(a?this.setTimer():this.setTimer(this.remaining),this.isActive=!0),this},this.pause=function(){return this.isActive&&(this.isActive=!1,this.remaining-=new Date-this.last,this.clearTimer()),this},this.stop=function(){return this.isActive=!1,this.remaining=this.intervalTime,this.clearTimer(),this},this.toggle=function(a){return this.isActive?this.pause():a?this.play(!0):this.play(),this},this.reset=function(){return this.isActive=!1,this.play(!0),this},this.clearTimer=function(){window.clearTimeout(this.timeoutObject)},this.setTimer=function(a){var b=this;"function"==typeof this.action&&(isNaN(a)&&(a=this.intervalTime),this.remaining=a,this.last=new Date,this.clearTimer(),this.timeoutObject=window.setTimeout(function(){b.go()},a))},this.go=function(){this.isActive&&(this.action(),this.setTimer())},this.init?new $.timer(func,time,autostart):(this.set(func,time,autostart),this)}}(jQuery);
/*
 Highstock JS v1.2.4 (2012-10-08)
 Exporting module

 (c) 2010-2011 Torstein Hønsi

 License: www.highcharts.com/license
*/
(function(){function x(a){for(var b=a.length;b--;)typeof a[b]==="number"&&(a[b]=Math.round(a[b])-0.5);return a}var g=Highcharts,y=g.Chart,z=g.addEvent,B=g.removeEvent,r=g.createElement,u=g.discardElement,t=g.css,s=g.merge,k=g.each,n=g.extend,C=Math.max,h=document,D=window,A=h.documentElement.ontouchstart!==void 0,v=g.getOptions();n(v.lang,{downloadPNG:"Download PNG image",downloadJPEG:"Download JPEG image",downloadPDF:"Download PDF document",downloadSVG:"Download SVG vector image",exportButtonTitle:"Export to raster or vector image",
printButtonTitle:"Print the chart"});v.navigation={menuStyle:{border:"1px solid #A0A0A0",background:"#FFFFFF"},menuItemStyle:{padding:"0 5px",background:"none",color:"#303030",fontSize:A?"14px":"11px"},menuItemHoverStyle:{background:"#4572A5",color:"#FFFFFF"},buttonOptions:{align:"right",backgroundColor:{linearGradient:[0,0,0,20],stops:[[0.4,"#F7F7F7"],[0.6,"#E3E3E3"]]},borderColor:"#B0B0B0",borderRadius:3,borderWidth:1,height:20,hoverBorderColor:"#909090",hoverSymbolFill:"#81A7CF",hoverSymbolStroke:"#4572A5",
symbolFill:"#E0E0E0",symbolStroke:"#A0A0A0",symbolX:11.5,symbolY:10.5,verticalAlign:"top",width:24,y:10}};v.exporting={type:"image/png",url:"http://export.highcharts.com/",width:800,buttons:{exportButton:{symbol:"exportIcon",x:-10,symbolFill:"#A8BF77",hoverSymbolFill:"#768F3E",_id:"exportButton",_titleKey:"exportButtonTitle",menuItems:[{textKey:"downloadPNG",onclick:function(){this.exportChart()}},{textKey:"downloadJPEG",onclick:function(){this.exportChart({type:"image/jpeg"})}},{textKey:"downloadPDF",
onclick:function(){this.exportChart({type:"application/pdf"})}},{textKey:"downloadSVG",onclick:function(){this.exportChart({type:"image/svg+xml"})}}]},printButton:{symbol:"printIcon",x:-36,symbolFill:"#B5C9DF",hoverSymbolFill:"#779ABF",_id:"printButton",_titleKey:"printButtonTitle",onclick:function(){this.print()}}}};n(y.prototype,{getSVG:function(a){var b=this,c,d,e,f=s(b.options,a);if(!h.createElementNS)h.createElementNS=function(a,b){return h.createElement(b)};a=r("div",null,{position:"absolute",
top:"-9999em",width:b.chartWidth+"px",height:b.chartHeight+"px"},h.body);n(f.chart,{renderTo:a,forExport:!0});f.exporting.enabled=!1;f.chart.plotBackgroundImage=null;f.series=[];k(b.series,function(a){e=s(a.options,{animation:!1,showCheckbox:!1,visible:a.visible});if(!e.isInternal){if(e&&e.marker&&/^url\(/.test(e.marker.symbol))e.marker.symbol="circle";f.series.push(e)}});c=new Highcharts.Chart(f);k(["xAxis","yAxis"],function(a){k(b[a],function(b,d){var e=c[a][d],f=b.getExtremes(),g=f.userMin,f=f.userMax;
(g!==void 0||f!==void 0)&&e.setExtremes(g,f,!0,!1)})});d=c.container.innerHTML;f=null;c.destroy();u(a);d=d.replace(/zIndex="[^"]+"/g,"").replace(/isShadow="[^"]+"/g,"").replace(/symbolName="[^"]+"/g,"").replace(/jQuery[0-9]+="[^"]+"/g,"").replace(/isTracker="[^"]+"/g,"").replace(/url\([^#]+#/g,"url(#").replace(/<svg /,'<svg xmlns:xlink="http://www.w3.org/1999/xlink" ').replace(/ href=/g," xlink:href=").replace(/\n/," ").replace(/<\/svg>.*?$/,"</svg>").replace(/&nbsp;/g," ").replace(/&shy;/g,"­").replace(/<IMG /g,
"<image ").replace(/height=([^" ]+)/g,'height="$1"').replace(/width=([^" ]+)/g,'width="$1"').replace(/hc-svg-href="([^"]+)">/g,'xlink:href="$1"/>').replace(/id=([^" >]+)/g,'id="$1"').replace(/class=([^" ]+)/g,'class="$1"').replace(/ transform /g," ").replace(/:(path|rect)/g,"$1").replace(/style="([^"]+)"/g,function(a){return a.toLowerCase()});d=d.replace(/(url\(#highcharts-[0-9]+)&quot;/g,"$1").replace(/&quot;/g,"'");d.match(/ xmlns="/g).length===2&&(d=d.replace(/xmlns="[^"]+"/,""));return d},exportChart:function(a,
b){var c,d=this.getSVG(s(this.options.exporting.chartOptions,b)),a=s(this.options.exporting,a);c=r("form",{method:"post",action:a.url,enctype:"multipart/form-data"},{display:"none"},h.body);k(["filename","type","width","svg"],function(b){r("input",{type:"hidden",name:b,value:{filename:a.filename||"chart",type:a.type,width:a.width,svg:d}[b]},null,c)});c.submit();u(c)},print:function(){var a=this,b=a.container,c=[],d=b.parentNode,e=h.body,f=e.childNodes;if(!a.isPrinting)a.isPrinting=!0,k(f,function(a,
b){if(a.nodeType===1)c[b]=a.style.display,a.style.display="none"}),e.appendChild(b),D.print(),setTimeout(function(){d.appendChild(b);k(f,function(a,b){if(a.nodeType===1)a.style.display=c[b]});a.isPrinting=!1},1E3)},contextMenu:function(a,b,c,d,e,f){var i=this,g=i.options.navigation,h=g.menuItemStyle,o=i.chartWidth,p=i.chartHeight,q="cache-"+a,j=i[q],l=C(e,f),m,w;if(!j)i[q]=j=r("div",{className:"highcharts-"+a},{position:"absolute",zIndex:1E3,padding:l+"px"},i.container),m=r("div",null,n({MozBoxShadow:"3px 3px 10px #888",
WebkitBoxShadow:"3px 3px 10px #888",boxShadow:"3px 3px 10px #888"},g.menuStyle),j),w=function(){t(j,{display:"none"})},z(j,"mouseleave",w),k(b,function(a){if(a){var b=r("div",{onmouseover:function(){t(this,g.menuItemHoverStyle)},onmouseout:function(){t(this,h)},innerHTML:a.text||i.options.lang[a.textKey]},n({cursor:"pointer"},h),m);b[A?"ontouchstart":"onclick"]=function(){w();a.onclick.apply(i,arguments)};i.exportDivElements.push(b)}}),i.exportDivElements.push(m,j),i.exportMenuWidth=j.offsetWidth,
i.exportMenuHeight=j.offsetHeight;a={display:"block"};c+i.exportMenuWidth>o?a.right=o-c-e-l+"px":a.left=c-l+"px";d+f+i.exportMenuHeight>p?a.bottom=p-d-l+"px":a.top=d+f-l+"px";t(j,a)},addButton:function(a){function b(){p.attr(l);o.attr(j)}var c=this,d=c.renderer,e=s(c.options.navigation.buttonOptions,a),f=e.onclick,g=e.menuItems,h=e.width,k=e.height,o,p,q,a=e.borderWidth,j={stroke:e.borderColor},l={stroke:e.symbolStroke,fill:e.symbolFill},m=e.symbolSize||12;if(!c.exportDivElements)c.exportDivElements=
[],c.exportSVGElements=[];e.enabled!==!1&&(o=d.rect(0,0,h,k,e.borderRadius,a).align(e,!0).attr(n({fill:e.backgroundColor,"stroke-width":a,zIndex:19},j)).add(),q=d.rect(0,0,h,k,0).align(e).attr({id:e._id,fill:"rgba(255, 255, 255, 0.001)",title:c.options.lang[e._titleKey],zIndex:21}).css({cursor:"pointer"}).on("mouseover",function(){p.attr({stroke:e.hoverSymbolStroke,fill:e.hoverSymbolFill});o.attr({stroke:e.hoverBorderColor})}).on("mouseout",b).on("click",b).add(),g&&(f=function(){b();var a=q.getBBox();
c.contextMenu("export-menu",g,a.x,a.y,h,k)}),q.on("click",function(){f.apply(c,arguments)}),p=d.symbol(e.symbol,e.symbolX-m/2,e.symbolY-m/2,m,m).align(e,!0).attr(n(l,{"stroke-width":e.symbolStrokeWidth||1,zIndex:20})).add(),c.exportSVGElements.push(o,q,p))},destroyExport:function(){var a,b;for(a=0;a<this.exportSVGElements.length;a++)b=this.exportSVGElements[a],b.onclick=b.ontouchstart=null,this.exportSVGElements[a]=b.destroy();for(a=0;a<this.exportDivElements.length;a++)b=this.exportDivElements[a],
B(b,"mouseleave"),this.exportDivElements[a]=b.onmouseout=b.onmouseover=b.ontouchstart=b.onclick=null,u(b)}});g.Renderer.prototype.symbols.exportIcon=function(a,b,c,d){return x(["M",a,b+c,"L",a+c,b+d,a+c,b+d*0.8,a,b+d*0.8,"Z","M",a+c*0.5,b+d*0.8,"L",a+c*0.8,b+d*0.4,a+c*0.4,b+d*0.4,a+c*0.4,b,a+c*0.6,b,a+c*0.6,b+d*0.4,a+c*0.2,b+d*0.4,"Z"])};g.Renderer.prototype.symbols.printIcon=function(a,b,c,d){return x(["M",a,b+d*0.7,"L",a+c,b+d*0.7,a+c,b+d*0.4,a,b+d*0.4,"Z","M",a+c*0.2,b+d*0.4,"L",a+c*0.2,b,a+c*
0.8,b,a+c*0.8,b+d*0.4,"Z","M",a+c*0.2,b+d*0.7,"L",a,b+d,a+c,b+d,a+c*0.8,b+d*0.7,"Z"])};y.prototype.callbacks.push(function(a){var b,c=a.options.exporting,d=c.buttons;if(c.enabled!==!1){for(b in d)a.addButton(d[b]);z(a,"destroy",a.destroyExport)}})})();

//This file should be used if you want to debug and develop
function jqGridInclude()
{
    var script_path = '/scport?p=WyJqcy9qcUdyaWQvanMvaTE4bi9ncmlkLmxvY2FsZS1lbi5qcyIsImpzL2pxR3JpZC9qcy9ncmlkLmJhc2UuanMiLCJqcy9qcUdyaWQvanMvZ3JpZC5jb21tb24uanMiLCJqcy9qcUdyaWQvanMvZ3JpZC5mb3JtZWRpdC5qcyIsImpzL2pxR3JpZC9qcy9ncmlkLmlubGluZWRpdC5qcyIsImpzL2pxR3JpZC9qcy9ncmlkLmNlbGxlZGl0LmpzIiwianMvanFHcmlkL2pzL2dyaWQuc3ViZ3JpZC5qcyIsImpzL2pxR3JpZC9qcy9ncmlkLnRyZWVncmlkLmpzIiwianMvanFHcmlkL2pzL2dyaWQuZ3JvdXBpbmcuanMiLCJqcy9qcUdyaWQvanMvZ3JpZC5jdXN0b20uanMiLCJqcy9qcUdyaWQvanMvZ3JpZC50Ymx0b2dyaWQuanMiLCJqcy9qcUdyaWQvanMvZ3JpZC5pbXBvcnQuanMiLCJqcy9qcUdyaWQvanMvanF1ZXJ5LmZtYXR0ZXIuanMiLCJqcy9qcUdyaWQvanMvSnNvblhtbC5qcyIsImpzL2pxR3JpZC9qcy9ncmlkLmpxdWVyeXVpLmpzIiwianMvanFHcmlkL2pzL2dyaWQuZmlsdGVyLmpzIl0=.js';
    var btype = GetBrowserType();
    if( btype == "Firefox" ){
        jQuery.ajax({url:script_path,dataType:'script', async:false, cache: true});
    }
    else if(jQuery.browser.safari) {
        jQuery.ajax({url:script_path,dataType:'script', async:false, cache: true});
    } else {
        if (jQuery.browser.msie) {
            document.write('<script charset="utf-8" type="text/javascript" src="'+script_path+'"></script>');
        } else {
            IncludeJavaScript(script_path);
        }
    }
    /*
    var pathtojsfiles = "js/jqGrid/js/"; // need to be ajusted
    // set include to false if you do not want some modules to be included
    var modules = [
        { include: true, incfile:'i18n/grid.locale-en.js'}, // jqGrid translation
        { include: true, incfile:'grid.base.js'}, // jqGrid base
        { include: true, incfile:'grid.common.js'}, // jqGrid common for editing
        { include: true, incfile:'grid.formedit.js'}, // jqGrid Form editing
        { include: true, incfile:'grid.inlinedit.js'}, // jqGrid inline editing
        { include: true, incfile:'grid.celledit.js'}, // jqGrid cell editing
        { include: true, incfile:'grid.subgrid.js'}, //jqGrid subgrid
        { include: true, incfile:'grid.treegrid.js'}, //jqGrid treegrid
	{ include: true, incfile:'grid.grouping.js'}, //jqGrid grouping
        { include: true, incfile:'grid.custom.js'}, //jqGrid custom
        { include: true, incfile:'grid.tbltogrid.js'}, //jqGrid table to grid
        { include: true, incfile:'grid.import.js'}, //jqGrid import
        { include: true, incfile:'jquery.fmatter.js'}, //jqGrid formater
        { include: true, incfile:'JsonXml.js'}, //xmljson utils
        { include: true, incfile:'grid.jqueryui.js'}, //jQuery UI utils
        { include: true, incfile:'grid.filter.js'} // filter Plugin
    ];
    var filename;
    for(var i=0;i<modules.length; i++)
    {
        if(modules[i].include === true) {
        	filename = pathtojsfiles+modules[i].incfile;
			if(jQuery.browser.safari) {
				jQuery.ajax({url:filename,dataType:'script', async:false, cache: true});
			} else {
				if (jQuery.browser.msie) {
					document.write('<script charset="utf-8" type="text/javascript" src="'+filename+'"></script>');
				} else {
					IncludeJavaScript(filename);
				}
			}
		}
    }
    */

    function IncludeJavaScript(jsFile)
    {
        var oHead = document.getElementsByTagName('head')[0];
        var oScript = document.createElement('script');
        oScript.setAttribute('type', 'text/javascript');
        oScript.setAttribute('language', 'javascript');
        oScript.setAttribute('src', jsFile);
        oHead.appendChild(oScript);
    }
}
jqGridInclude();

(function (jQuery) {

		var daysInWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
		var shortMonthsInYear = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var longMonthsInYear = ["January", "February", "March", "April", "May", "June",
														"July", "August", "September", "October", "November", "December"];
		var shortMonthsToNumber = [];
		shortMonthsToNumber["Jan"] = "01";
		shortMonthsToNumber["Feb"] = "02";
		shortMonthsToNumber["Mar"] = "03";
		shortMonthsToNumber["Apr"] = "04";
		shortMonthsToNumber["May"] = "05";
		shortMonthsToNumber["Jun"] = "06";
		shortMonthsToNumber["Jul"] = "07";
		shortMonthsToNumber["Aug"] = "08";
		shortMonthsToNumber["Sep"] = "09";
		shortMonthsToNumber["Oct"] = "10";
		shortMonthsToNumber["Nov"] = "11";
		shortMonthsToNumber["Dec"] = "12";

    jQuery.format = (function () {
        function strDay(value) {
            return daysInWeek[parseInt(value, 10)] || value;
        }

        function strMonth(value) {
            var monthArrayIndex = parseInt(value, 10) - 1;
            return shortMonthsInYear[monthArrayIndex] || value;
        }

        function strLongMonth(value) {
            var monthArrayIndex = parseInt(value, 10) - 1;
            return longMonthsInYear[monthArrayIndex] || value;
        }

        var parseMonth = function (value) {
            return shortMonthsToNumber[value] || value;
        };

        var parseTime = function (value) {
                var retValue = value;
                var millis = "";
                if (retValue.indexOf(".") !== -1) {
                    var delimited = retValue.split('.');
                    retValue = delimited[0];
                    millis = delimited[1];
                }

                var values3 = retValue.split(":");

                if (values3.length === 3) {
                    hour = values3[0];
                    minute = values3[1];
                    second = values3[2];

                    return {
                        time: retValue,
                        hour: hour,
                        minute: minute,
                        second: second,
                        millis: millis
                    };
                } else {
                    return {
                        time: "",
                        hour: "",
                        minute: "",
                        second: "",
                        millis: ""
                    };
                }
            };

        return {
            date: function (value, format) {
                /*
					value = new java.util.Date()
                 	2009-12-18 10:54:50.546
				*/
                try {
                    var date = null;
                    var year = null;
                    var month = null;
                    var dayOfMonth = null;
                    var dayOfWeek = null;
                    var time = null;
                    if (typeof value == "number"){
                            return this.date(new Date(value), format);
                    } else if (typeof value.getFullYear == "function") {
                        year = value.getFullYear();
                        month = value.getMonth() + 1;
                        dayOfMonth = value.getDate();
                        dayOfWeek = value.getDay();
                        time = parseTime(value.toTimeString());
                    } else if (value.search(/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.?\d{0,3}[Z\-+]?(\d{2}:?\d{2})?/) != -1) {
                        /* 2009-04-19T16:11:05+02:00 || 2009-04-19T16:11:05Z */
                        var values = value.split(/[T\+-]/);
                        year = values[0];
                        month = values[1];
                        dayOfMonth = values[2];
                        time = parseTime(values[3].split(".")[0]);
                        date = new Date(year, month - 1, dayOfMonth);
                        dayOfWeek = date.getDay();
                    } else {
                        var values = value.split(" ");
                        switch (values.length) {
                        case 6:
                            /* Wed Jan 13 10:43:41 CET 2010 */
                            year = values[5];
                            month = parseMonth(values[1]);
                            dayOfMonth = values[2];
                            time = parseTime(values[3]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        case 2:
                            /* 2009-12-18 10:54:50.546 */
                            var values2 = values[0].split("-");
                            year = values2[0];
                            month = values2[1];
                            dayOfMonth = values2[2];
                            time = parseTime(values[1]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        case 7:
                            /* Tue Mar 01 2011 12:01:42 GMT-0800 (PST) */
                        case 9:
                            /*added by Larry, for Fri Apr 08 2011 00:00:00 GMT+0800 (China Standard Time) */
                        case 10:
                            /* added by Larry, for Fri Apr 08 2011 00:00:00 GMT+0200 (W. Europe Daylight Time) */
                            year = values[3];
                            month = parseMonth(values[1]);
                            dayOfMonth = values[2];
                            time = parseTime(values[4]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        case 1:
                            /* added by Jonny, for 2012-02-07CET00:00:00 (Doctrine Entity -> Json Serializer) */
                            var values2 = values[0].split("");
                            year=values2[0]+values2[1]+values2[2]+values2[3];
                            month= values2[5]+values2[6];
                            dayOfMonth = values2[8]+values2[9];
                            time = parseTime(values2[13]+values2[14]+values2[15]+values2[16]+values2[17]+values2[18]+values2[19]+values2[20])
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        default:
                            return value;
                        }
                    }

                    var pattern = "";
                    var retValue = "";
                    var unparsedRest = "";
                    /*
						Issue 1 - variable scope issue in format.date
                    	Thanks jakemonO
					*/
                    for (var i = 0; i < format.length; i++) {
                        var currentPattern = format.charAt(i);
                        pattern += currentPattern;
                        unparsedRest = "";
                        switch (pattern) {
                        case "ddd":
                            retValue += strDay(dayOfWeek);
                            pattern = "";
                            break;
                        case "dd":
                            if (format.charAt(i + 1) == "d") {
                                break;
                            }
                            if (String(dayOfMonth).length === 1) {
                                dayOfMonth = '0' + dayOfMonth;
                            }
                            retValue += dayOfMonth;
                            pattern = "";
                            break;
                        case "d":
                            if (format.charAt(i + 1) == "d") {
                                break;
                            }
                            retValue += parseInt(dayOfMonth, 10);
                            pattern = "";
                            break;
                        case "MMMM":
                            retValue += strLongMonth(month);
                            pattern = "";
                            break;
                        case "MMM":
                            if (format.charAt(i + 1) === "M") {
                                break;
                            }
                            retValue += strMonth(month);
                            pattern = "";
                            break;
                        case "MM":
                            if (format.charAt(i + 1) == "M") {
                                break;
                            }
                            if (String(month).length === 1) {
                                month = '0' + month;
                            }
                            retValue += month;
                            pattern = "";
                            break;
                        case "M":
                            if (format.charAt(i + 1) == "M") {
                                break;
                            }
                            retValue += parseInt(month, 10);
                            pattern = "";
                            break;
                        case "yyyy":
                            retValue += year;
                            pattern = "";
                            break;
                        case "yy":
                            if (format.charAt(i + 1) == "y" &&
                           	format.charAt(i + 2) == "y") {
                            	break;
                      	    }
                            retValue += String(year).slice(-2);
                            pattern = "";
                            break;
                        case "HH":
                            retValue += time.hour;
                            pattern = "";
                            break;
                        case "hh":
                            /* time.hour is "00" as string == is used instead of === */
                            var hour = (time.hour == 0 ? 12 : time.hour < 13 ? time.hour : time.hour - 12);
                            hour = String(hour).length == 1 ? '0' + hour : hour;
                            retValue += hour;
                            pattern = "";
                            break;
                        case "h":
                            if (format.charAt(i + 1) == "h") {
                                break;
                            }
                            var hour = (time.hour == 0 ? 12 : time.hour < 13 ? time.hour : time.hour - 12);
                            retValue += parseInt(hour, 10);
                                        // Fixing issue https://github.com/phstc/jquery-dateFormat/issues/21
                                        // retValue = parseInt(retValue, 10);
                            pattern = "";
                            break;
                        case "mm":
                            retValue += time.minute;
                            pattern = "";
                            break;
                        case "ss":
                            /* ensure only seconds are added to the return string */
                            retValue += time.second.substring(0, 2);
                            pattern = "";
                            break;
                        case "SSS":
                            retValue += time.millis.substring(0, 3);
                            pattern = "";
                            break;
                        case "a":
                            retValue += time.hour >= 12 ? "PM" : "AM";
                            pattern = "";
                            break;
                        case " ":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        case "/":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        case ":":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        default:
                            if (pattern.length === 2 && pattern.indexOf("y") !== 0 && pattern != "SS") {
                                retValue += pattern.substring(0, 1);
                                pattern = pattern.substring(1, 2);
                            } else if ((pattern.length === 3 && pattern.indexOf("yyy") === -1)) {
                                pattern = "";
                            } else {
                            	unparsedRest = pattern;
                            }
                        }
                    }
                    retValue += unparsedRest;
                    return retValue;
                } catch (e) {
                    console.log(e);
                    return value;
                }
            }
        };
    }());
}(jQuery));

jQuery.format.date.defaultShortDateFormat = "dd/MM/yyyy";
jQuery.format.date.defaultLongDateFormat = "dd/MM/yyyy hh:mm:ss";

jQuery(document).ready(function () {
    jQuery(".shortDateFormat").each(function (idx, elem) {
        if (jQuery(elem).is(":input")) {
            jQuery(elem).val(jQuery.format.date(jQuery(elem).val(), jQuery.format.date.defaultShortDateFormat));
        } else {
            jQuery(elem).text(jQuery.format.date(jQuery(elem).text(), jQuery.format.date.defaultShortDateFormat));
        }
    });
    jQuery(".longDateFormat").each(function (idx, elem) {
        if (jQuery(elem).is(":input")) {
            jQuery(elem).val(jQuery.format.date(jQuery(elem).val(), jQuery.format.date.defaultLongDateFormat));
        } else {
            jQuery(elem).text(jQuery.format.date(jQuery(elem).text(), jQuery.format.date.defaultLongDateFormat));
        }
    });
});

