function mpage_operator(server_time, cur_symbol, uid) {
	var page_vars = {
		server_mark_ex: server_time,
		server_mark_cc: server_time
	};
	var main_rate_obj = {
		rates_mark_ex: server_time,
		line_type: 5,
		cur_time_line: trade_global.time_line[5],
		need_zoom: false,
		recheck_main_rates: function() {
			if (page_vars.server_mark_ex > main_rate_obj.rates_mark_ex) {
				main_rate_obj.reload_main_rates(10);
				main_rate_obj.rates_mark_ex = page_vars.server_mark_ex
			}
		},
		switch_line_type: function(ltype) {
			main_rate_obj.line_type = ltype;
			main_rate_obj.cur_time_line = trade_global.time_line[ltype];
			main_rate_obj.need_zoom = true;
			if (main_rate_obj.rates_mark_ex - main_rate_obj.cur_time_line.last_update > ltype * 60 * 10) {
				main_rate_obj.reload_main_rates(100)
			} else {
				main_rate_obj.reload_main_rates(10)
			};
			trade_global.main_chart.zoomOut()
		},
		reload_main_rates: function(reload_count) {
			var json_req = {
				symbol: cur_symbol,
				count: reload_count,
				type: 'tline',
				tspan: main_rate_obj.line_type * 60
			};
			if (reload_count > 10) {
				trade_global.main_chart.showLoading()
			};
			$.ajax({
				type: "post",
				url: "/data.php?op=query" + page_rand(),
				data: json_req,
				dataType: "json",//返回json格式的数据
				success: function(data, state) {
					var json_res = data;
					if (json_res && json_res.result && json_res.tline && json_res.tspan == main_rate_obj.line_type * 60) {
						//alert(json_res.tline.length);
						for (var i = 0; i < json_res.tline.length; i++) {
							var tnode = json_res.tline[i];
							var new_tline_node = [(Number(tnode.time) + 8 * 3600) * 1000, Number(tnode.open), Number(tnode.high), Number(tnode.low), Number(tnode.close), Number(tnode.vol)];
							
							if (0 == main_rate_obj.cur_time_line.datas.length) {
								main_rate_obj.cur_time_line.datas.push(new_tline_node)
							} else if (new_tline_node[0] == main_rate_obj.cur_time_line.datas[main_rate_obj.cur_time_line.datas.length - 1][0]) {
								main_rate_obj.cur_time_line.datas[main_rate_obj.cur_time_line.datas.length - 1] = new_tline_node
							} else if (new_tline_node[0] > main_rate_obj.cur_time_line.datas[main_rate_obj.cur_time_line.datas.length - 1][0]) {
								main_rate_obj.cur_time_line.datas.push(new_tline_node);
							}
						};
						var datas = main_rate_obj.cur_time_line.datas;
						var rates = [];
						var vols = [];
						var start_pos = datas.length > 100 ? datas.length - 100 : 0;
						for (i = start_pos; i < datas.length; i++) {
							rates.push([datas[i][0], datas[i][1], datas[i][2], datas[i][3], datas[i][4]]);
							vols.push([datas[i][0], datas[i][5]])
						};
						trade_global.main_chart.series[0].setData(rates, true);
						trade_global.main_chart.series[1].setData(vols, true);
						trade_global.main_chart.reload();
						trade_global.main_chart.redraw();
						if (main_rate_obj.need_zoom) {
							main_rate_obj.need_zoom = false;
							trade_global.main_chart.reloadex()
						}
					}
				},
				complete: function() {
					if (reload_count > 10) {
						trade_global.main_chart.hideLoading()
					}
				}
			})
		},
		recheck: function() {
			main_rate_obj.recheck_main_rates(cur_symbol)
		}
	};
	var main_ask_bid_list_obj = {
		ask_list: [],
		bid_list: [],
		best_ask_rate: 0,
		best_bid_rate: 0,
		ab_mark_cc: server_time,
		ab_mark_ex: server_time,
		init: function(ask_list_data, bid_list_data) {
			this.ask_list = ask_list_data;
			this.bid_list = bid_list_data;
			this.recalc_best_rate()
		},
		recalc_best_rate: function() {
			best_ask_rate: 0;
			best_bid_rate: 0;
			if(this.bid_list) var bid_length = this.bid_list.length;
			else  var bid_length = 0;
			if(this.ask_list) var ask_length = this.ask_list.length;
			else  var ask_length = 0;
			if (bid_length > 0) {
				this.best_ask_rate = this.bid_list[0].rate
				
			};
			
			if (ask_length> 0) {
				this.best_bid_rate = this.ask_list[0].rate
			};
			page_obj.update_best_rate()
		},
		reload_ask_bid_grid: function() {
			/*jQuery("#tableAskList").clearGridData();
			jQuery("#tableBidList").clearGridData();*/
			var mastcount=0;
			$("#l_divAskList").html("");
			$("#l_divBidList").html("");
			$("#l_divList").html("");
			if(this.bid_list) var bid_length = this.bid_list.length;
			else  var bid_length = 0;
			if(this.ask_list) var ask_length = this.ask_list.length;
			else  var ask_length = 0;
			for (var i = 0; i < ask_length; i++){
				if(this.ask_list[i].symbol_r>mastcount) mastcount=this.ask_list[i].symbol_r;
			}
			for (var i = 0; i < bid_length; i++){
				if(this.bid_list[i].symbol_r>mastcount) mastcount=this.bid_list[i].symbol_r;
			}
			for (var i = 0; i < ask_length; i++){
				/*jQuery("#tableAskList").jqGrid('addRowData', i + 1, this.ask_list[i]);*/
				$("#l_divAskList").append("<li style='width:15%;color:#990000'>卖("+(i+1)+")</li><li style=\"width:25%;\">"+ this.ask_list[i].rate.toFixed(4) +"</li><li style=\"width:25%;\">"+this.ask_list[i].symbol_l.toFixed(4)+"</li><li style=\"width: 30%;\" title='"+ this.ask_list[i].count + "单'><div style='background-color:#990000;height:20px;width:"+(this.ask_list[i].symbol_r/mastcount*100)+"%'></div></li>");
				if(i<5) $("#l_divList").prepend("<li style='width:15%;color:#990000'>卖("+(i+1)+")</li><li style=\"width:25%;\">"+ this.ask_list[i].rate.toFixed(4) +"</li><li style=\"width:25%;\">"+this.ask_list[i].symbol_l.toFixed(4)+"</li><li style=\"width: 30%;text-align:left\" title='"+ this.ask_list[i].count + "单'><div style='background-color:#990000;height:20px;width:"+(this.ask_list[i].symbol_r/mastcount*100)+"%'></div></li>");
			}
			/*jQuery("#tableAskList").setGridParam({
				sortname: 'rate',
				sortorder: 'asc'
			}).trigger('reloadGrid');*/
			for (var i = 0; i < bid_length; i++){
				/*jQuery("#tableBidList").jqGrid('addRowData', i + 1, this.bid_list[i]);*/
				$("#l_divBidList").append("<li style='width:15%;color:#009900'>买("+(i+1)+")</li><li style=\"width:25%;\">"+ this.bid_list[i].rate.toFixed(4) +"</li><li style=\"width:25%;\">"+this.bid_list[i].symbol_l.toFixed(4)+"</li><li style=\"width: 30%;\" title='"+ this.bid_list[i].count + "单'><div style='background-color:#009900;height:20px;width:"+(this.bid_list[i].symbol_r/mastcount*100)+"%'></div></li>");
				if(i<5) $("#l_divList").append("<li style='width:15%;color:#009900'>买("+(i+1)+")</li><li style=\"width:25%;\">"+ this.bid_list[i].rate.toFixed(4) +"</li><li style=\"width:25%;\">"+this.bid_list[i].symbol_l.toFixed(4)+"</li><li style=\"width: 30%;text-align:left\" title='"+ this.bid_list[i].count + "单'><div style='background-color:#009900;height:20px;width:"+(this.bid_list[i].symbol_r/mastcount*100)+"%'></div></li>");
			}
			/*jQuery("#tableBidList").setGridParam({
				sortname: 'rate',
				sortorder: 'desc'
			}).trigger('reloadGrid')*/
		},
		reload_ask_bid_list: function() {
			var json_req = {
				type: 'rate_list',
				symbol: cur_symbol
			};
			//alert(server_time);
			$.ajax({//读取列表
				type: "post",
				url: "/data.php?op=query" + page_rand(),
				data: json_req,
				dataType: "json",//返回json格式的数据
				success: function(data, state) {
					var json_res = data;
					if (json_res && json_res.result) {
						
						main_ask_bid_list_obj.ask_list = [];
						main_ask_bid_list_obj.bid_list = [];
						if (json_res.rate_list.asks) {
							for (var i = 0; i < json_res.rate_list.asks.length; i++) {
								var node = {};
								node.rate = json_res.rate_list.asks[i].rate;
								node.symbol_l = json_res.rate_list.asks[i].vol;
								node.symbol_r = (node.symbol_l*10) * (node.rate*10)/100;
								node.count = json_res.rate_list.asks[i].count;
								main_ask_bid_list_obj.ask_list.push(node)
							}
						};
						if (json_res.rate_list.bids) {
							for (var i = 0; i < json_res.rate_list.bids.length; i++) {
								var node = {};
								node.rate = json_res.rate_list.bids[i].rate;
								node.symbol_l = json_res.rate_list.bids[i].vol;
								node.symbol_r = (node.symbol_l*10) * (node.rate*10)/100;
								node.count = json_res.rate_list.bids[i].count;
								main_ask_bid_list_obj.bid_list.push(node)
							}
							//alert(main_ask_bid_list_obj.bid_list[0].rate);
						};
						main_ask_bid_list_obj.recalc_best_rate();//我加入的
						main_ask_bid_list_obj.reload_ask_bid_grid()
					} else {
						main_ask_bid_list_obj.ab_mark_ex = 0;
						main_ask_bid_list_obj.ab_mark_cc = 0;
					}
				}
			});
			return true
		},
		recheck_ask_bid_list: function() {
			
			if (page_vars.server_mark_cc > main_ask_bid_list_obj.ab_mark_cc || page_vars.server_mark_ex > main_ask_bid_list_obj.ab_mark_ex) {
				//$("#showdivmsg").html(page_vars.server_mark_cc +"-"+ main_ask_bid_list_obj.ab_mark_cc);
				main_ask_bid_list_obj.reload_ask_bid_list();
				main_ask_bid_list_obj.ab_mark_ex = page_vars.server_mark_ex;
				//main_ask_bid_list_obj.ab_mark_cc = page_vars.server_mark_ex
				main_ask_bid_list_obj.ab_mark_cc = page_vars.server_mark_cc
			}
		},
		recheck: function() {
			main_ask_bid_list_obj.recheck_ask_bid_list()
		}
	};
	var main_history_grid_obj = {
		history_data: [],
		th_mark_ex: server_time,
		init: function(history_data) {
			this.history_data = history_data;
			if(this.history_data) var history_length = this.history_data.length;
			else  var history_length = 0;
			for (var i = 0; i < this.history_data.length; i++){
				/*jQuery("#tableHistoryList").jqGrid('addRowData', null, this.history_data[i]);*/
			
				//var stime=$.format.date(this.history_data[i].date * 1000, 'yyyy/MM/dd HH:mm:ss');
				var stime=$.format.date(this.history_data[i].date * 1000, 'HH:mm:ss');
				/*$("#divHistoryList").append("<li style=\"width: 180px;\">"+ stime +"</li><li style=\"width: 80px;\">"+this.history_data[i].order+"</li><li style=\"width: 120px;\">"+this.history_data[i].rate+"</li><li style=\"width: 140px;\">"+ this.history_data[i].amount_l +"</li><li style=\"width: 140px;\">"+ this.history_data[i].amount_r + "</li>");
				$("#hqHistoryList").append("<ul><span style=\"width:25%;\">"+ stime +"</span><span style=\"width: 10%;\">"+this.history_data[i].order+"</span><span style=\"width:20%;\">"+(this.history_data[i].rate.toFixed(4))+"</span><span style=\"width:20%;\">"+(this.history_data[i].amount_l.toFixed(4))+"</span><span style=\"width: 20%;\">"+ this.history_data[i].amount_r + "</span></ul>");*/
				$("#hqHistoryList").append("<ul><span style=\"width: 15%;\">"+this.history_data[i].order+"</span><span style=\"width:25%;\">"+ stime +"</span><span style=\"width:30%;\">"+(this.history_data[i].rate.toFixed(4))+"</span><span style=\"width:20%;\">"+(this.history_data[i].amount_l.toFixed(4))+"</span></ul>");
			}
			/*jQuery("#tableHistoryList").setGridParam({
				sortname: 'date',
				sortorder: 'desc'
			}).trigger('reloadGrid')*/
		},
		reload_thistory_data: function() {
			var json_req = {
				type: "ex_rec",
				symbol: cur_symbol,
				count: 5
			};
			$.ajax({
				type: "post",
				url: "/data.php?op=query" + page_rand(),
				data: json_req,
				dataType: "json",//返回json格式的数据
				success: function(data, state) {
					var json_res = data;
					if (json_res && json_res.result && json_res.history) {
						for (var i = 0; i < json_res.history.length; i++) {
							var node_his = {};
							node_his.ticket = json_res.history[i].ticket;
							node_his.order = json_res.history[i].order == 1 ? "<font color='#009900'>卖</font>": "<font color='#FF0000'>买</font>";
							node_his.rate = json_res.history[i].rate;
							node_his.amount_l = json_res.history[i].vol;
							node_his.amount_r = node_his.amount_l * node_his.rate;
							node_his.date = $.format.date(json_res.history[i].date * 1000, 'yyyy/MM/dd HH:mm:ss');
							for (var j = 0; j < main_history_grid_obj.history_data.length; j++) {
								if (main_history_grid_obj.history_data[j].ticket == node_his.ticket) {
									node_his = null;
									break
								}
							};
							if (node_his) {
								main_history_grid_obj.history_data.push(node_his);
								$("#divHistoryList").append("<li style=\"width: 180px;\">"+ node_his.date +"</li><li style=\"width: 80px;\">"+node_his.order+"</li><li style=\"width: 120px;\">"+node_his.rate+"</li><li style=\"width: 140px;\">"+ node_his.amount_l +"</li><li style=\"width: 140px;\">"+ node_his.amount_r.toFixed(4)+ "</li>");
								/*jQuery("#tableHistoryList").jqGrid('addRowData', null, node_his)*/
							}
						};
						/*jQuery("#tableHistoryList").setGridParam({
							sortname: 'date',
							sortorder: 'desc'
						}).trigger('reloadGrid');*/
						page_obj.update_new_rate()
					} else {
						main_history_grid_obj.th_mark_ex = 0
					}
				}
			})
		},
		recheck_thistory_data: function() {
			//alert(NoOne);
			if (page_vars.server_mark_ex > main_history_grid_obj.th_mark_ex) {
				main_history_grid_obj.reload_thistory_data();
				main_history_grid_obj.th_mark_ex = page_vars.server_mark_ex
			}
		},
		recheck: function() {
			main_history_grid_obj.recheck_thistory_data()
		}
	};
	var page_obj = {
		fee: trade_global.fee,
		on_request_ask_bid: function(type, rate, vol) {
			if (!uid) {
				apprise('请先登录', {
					animate: false,
					textOk: "确定"
				});
				return
			};
			if (vol <= 0) {
				apprise('请输入交易量', {
					animate: false,
					textOk: "确定"
				});
				return
			};
			if (rate >= 10000000 || rate == 0) {
				apprise('交易价必须大于0且小于10000000', {
					animate: false,
					textOk: '确定'
				});
				return
			};
			if (vol >= 1000000) {
				apprise('单笔交易量必须小于1000000', {
					animate: false,
					textOk: '确定'
				});
				return
			};
			if (type == 'ask') {
				var amount_ask_able = get_element('amount_ask_able').innerHTML;
				if (vol > Number(amount_ask_able)) {
					apprise('超出可买入额，请检查后重新输入', {
						animate: false,
						textOk: '确定'
					});
					return
				}
			} else if (type == 'bid') {
				var balance_bid_able = get_element('balance_bid_able').innerHTML;
				if (vol > Number(balance_bid_able)) {
					apprise('超出可卖出额，请检查后重新输入', {
						animate: false,
						textOk: '确定'
					});
					return
				}
			} else {
				apprise('未知错误', {
					animate: false,
					textOk: '确定'
				});
				return
			};
			var dlg_processing = apprise("<img style='margin: 65px;' src='img/loading.gif'/>", {
	animate: false,
	buttonShow: false,
	textOk: "确定"
});
			$("#bun"+type).hide;
			$("#bun"+type+"No").show;
var json_req = {
	kpsign:0,
	mdggper:
	mkggper:
	type: type,
	symbol: cur_symbol,
	rate: rate,
	vol: vol
};
$.ajax({
	type: "post",
	url: "/member/qh_deal.php?op=exchange" + page_rand(),
	data: json_req,
	dataType: "json",//返回json格式的数据
	success: function(data, textStatus) {
		var json_res = data;
		var view_code = "";
		//alert(data);
		if (json_res.records) {
			view_code += "已成交:";
			view_code += "<br/>";
			view_code += "---------------------------------------------------";
			/*view_code += "<hr/>";*/
			view_code += "<table id='tableRecords'>";
			for (var i = 0; i < json_res.records.length; i++) {
				view_code += "<tr><td>";
				view_code += (i + 1) + ".</td><td>";
				view_code += "成交价: " + num_fix(json_res.records[i].rate, 4) + "</td>";
				if (type == 'ask') {
					//view_code += "<td>成交量: </td><td>" + num_fix(json_res.records[i].vol + 0.000000004, 4) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></td>";
					view_code += "<td>成交量: </td><td>" + json_res.records[i].vol + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></td>";
					view_code += "<td>手续费: </td><td>" + num_fix(json_res.records[i].vol * json_res.records[i].rate * Number(page_obj.fee), 8) + '&nbsp;' + get_view_symbol(cur_symbol, 'r') + "</td></tr>"
				} else if (type == 'bid') {
					//view_code += "<td>成交量: </td><td>" + num_fix(json_res.records[i].vol + 0.000000004, 4) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></td>";
					view_code += "<td>成交量: </td><td>" + json_res.records[i].vol + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></td>";
					//view_code += "<td>手续费: </td><td>" + num_fix(json_res.records[i].vol * Number(page_obj.fee), 8) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>"
					view_code += "<td>手续费: </td><td>" + json_res.records[i].fee + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>"
				}
			};
			view_code += "</table>";
			view_code += "<br/>"
		};
		if (json_res.pending) {
			view_code += "已挂单:";
			view_code += "<br/>"
			view_code += "---------------------------------------------------"
			/*view_code += "<hr/>";*/
			view_code += "<table id='tablePending'>";
			for (var i = 0; i < json_res.pending.length; i++) {
				view_code += "<tr><td>单号: </td><td>" + json_res.pending[i].id + "</td></tr>";
				view_code += "<tr><td>价格: </td><td>" + num_fix(json_res.pending[i].rate, 4) + "</td></tr>";
				if (type == 'ask') {
					//view_code += "<tr><td>挂单量: </td><td>" + num_fix(json_res.pending[i].vol + 0.000000004, 4) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>";
					view_code += "<tr><td>挂单量: </td><td>" + json_res.pending[i].vol + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>";
					view_code += "<tr><td>手续费: </td><td>" + num_fix(json_res.pending[i].vol * json_res.pending[i].rate * Number(page_obj.fee), 8) + '&nbsp;' + get_view_symbol(cur_symbol, 'r') + "</td></tr>"
				} else if (type == 'bid') {
					//view_code += "<tr><td>挂单量: </td><td>" + num_fix(json_res.pending[i].vol + 0.000000004, 4) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>";
					view_code += "<tr><td>挂单量: </td><td>" + json_res.pending[i].vol + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>";
					//view_code += "<tr><td>手续费: </td><td>" + num_fix(json_res.pending[i].vol * Number(page_obj.fee), 8) + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>"
					view_code += "<tr><td>手续费: </td><td>" + json_res.pending[i].fee + '&nbsp;' + get_view_symbol(cur_symbol, 'l') + "</td></tr>"
				}
			};
			view_code += "</table>";
			view_code += "<br/>"
		};
		if (!json_res.result) {
			view_code = json_res.showMsg+"处理失败！"
		};
		apprise(view_code, {
			//animate: false,
			animate: true,
			textOk: "确定"
		},
		function(r) {
			if (r) reload_page()
		})
	},
	error: function() {
		apprise("网络错误！", {
			animate: true,
			textOk: "确定"
		},
		function(r) {
			if (r) reload_page()
		})
	},
	complete: function() {
		dlg_processing.close()
	}
})
},
recalc_fee: function(type) {
	if (type == "ask") {
		var ask_vol = get_element("ask_vol").value;
		var ask_rate = get_element("ask_rate").value;
		var ask_amount = get_element("ask_amount").value;
		get_element("ask_fee").innerHTML = dformat(Number(ask_vol) * Number(ask_rate) * Number(this.fee), 8, 11, true)
	} else {
		var bid_vol = get_element("bid_vol").value;
		var bid_rate = get_element("bid_rate").value;
		var bid_amount = get_element("bid_amount").value;
		get_element("bid_fee").innerHTML = dformat(Number(bid_vol) / (1 + Number(this.fee)) * Number(this.fee), 8, 11, true)
	};
	return true
},
on_input_ask_vol: function() {
	var element_amount = get_element('ask_amount');
	var element_rate = get_element('ask_rate');
	var element_vol = get_element('ask_vol');
	var fixed_num = num_need_fix(element_vol.value, 4);
	if (fixed_num) {
		element_vol.value = fixed_num
	};
	var amount = element_rate.value * element_vol.value * (1 + Number(page_obj.fee));
	element_amount.value = amount;
	if (element_amount.value < 0.0001) {
		element_amount.value = 0.0000
	};
	var fixed_num = num_need_fix(element_amount.value, 4);
	if (fixed_num) {
		element_amount.value = fixed_num
	};
	return true
},
on_input_bid_vol: function() {
	var element_amount = get_element('bid_amount');
	var element_rate = get_element('bid_rate');
	var element_vol = get_element('bid_vol');
	var fixed_num = num_need_fix(element_vol.value, 4);
	if (fixed_num) {
		element_vol.value = fixed_num
	};
	var amount = element_rate.value * element_vol.value / (1 + Number(page_obj.fee));
	element_amount.value = amount;
	var fixed_num = num_need_fix(amount, 4);
	if (fixed_num) {
		element_amount.value = fixed_num
	};
	return true
},
on_input_ask_rate: function() {
	var element_rate = get_element('ask_rate');
	var fixed_num = num_need_fix(element_rate.value, 4);
	if (fixed_num) {
		element_rate.value = fixed_num
	};
	var balance_ask_able = get_element('balance_ask_able');
	var amount_ask_able = get_element('amount_ask_able');
	//if(element_rate.value==0) element_rate.value=1;
	//alert(num_fix(balance_ask_able.innerHTML / element_rate.value / (1 + Number(page_obj.fee)), 4));
	amount_ask_able.innerHTML = num_fix(balance_ask_able.innerHTML / element_rate.value / (1 + Number(page_obj.fee)), 4);
	page_obj.on_input_ask_vol()
},
on_input_bid_rate: function() {
	var element_rate = get_element('bid_rate');
	var fixed_num = num_need_fix(element_rate.value, 4);
	if (fixed_num) {
		element_rate.value = fixed_num
	};
	page_obj.on_input_bid_vol()
},
on_input_ask_amount: function() {
	var element_amount = get_element('ask_amount');
	var element_rate = get_element('ask_rate');
	var element_vol = get_element('ask_vol');
	var fixed_num = num_need_fix(element_amount.value, 4);

	if (fixed_num) {
		element_amount.value = fixed_num
	};
	var vol = element_amount.value * 10000 / (1 + Number(page_obj.fee)) / element_rate.value;
	if (vol < 0.01) {
		vol = 0
	};
	vol /= 10000;
	element_vol.value = vol;
	var fixed_num = num_need_fix(vol, 4);
	if (fixed_num) {
		element_vol.value = fixed_num
	}
},
on_input_bid_amount: function() {
	var element_amount = get_element('bid_amount');
	var element_rate = get_element('bid_rate');
	var element_vol = get_element('bid_vol');
	var fixed_num = num_need_fix(element_amount.value, 4);
	if (fixed_num) {
		element_amount.value = fixed_num
	};
	var vol = element_amount.value * 10000 * (1 + Number(page_obj.fee)) / element_rate.value;
	if (vol < 0.01) {
		vol = 0
	};
	vol /= 10000;
	element_vol.value = vol;
	var fixed_num = num_need_fix(vol, 4);
	if (fixed_num) {
		element_vol.value = fixed_num
	};
	return true
},
cancel_order: function(symbol, tid) {
	var dlg_processing = apprise("<img style='margin: 65px;'src='img/loading.gif'/>", {
		animate: false,
		buttonShow: false,
		textOk: "确定"
	});
	var json_req = {
		type: "cancel",
		symbol: symbol,
		tid: tid
	};
	$.ajax({
		type: "post",
		url: "data.php?op=exchange" + page_rand(),
		data: json_req,
		dataType: "json",//返回json格式的数据
		success: function(data, textStatus) {
			var json_res = data;
			if (json_res && json_res.result=="true") {
				view_code = "<div class='cancel_content'>撤单成功!</div>"
			} else {
				view_code = "<div class='cancel_content'>撤单失败!</div>"
			};
			apprise(view_code, {
				animate: true,
				textOk: "确定"
			},
			function(r) {
				if (r) reload_page()
			})
		},
		error: function() {
			apprise("网络错误！", {
				animate: true,
				textOk: "确定"
			},
			function(r) {
				if (r) reload_page()
			})
		},
		complete: function() {
			dlg_processing.close()
		}
	})
},
update_new_rate: function() {
	var json_req = {
		type: "ticker",
		symbol: cur_symbol
	};
	//
	$.ajax({
		type: "post",
		url: "/data.php?op=query" + page_rand(),
		data: json_req,
		dataType: "json",//返回json格式的数据
		success: function(data, state) {
			var json_res = data;
			if (json_res && json_res.result) {
				var pb_close = get_element('pb_close');
				var pb_high = get_element('pb_high');
				var pb_low = get_element('pb_low');
				var pb_vol = get_element('pb_vol');
				pb_close.innerHTML = json_res.ticker.last_rate;
				pb_high.innerHTML = json_res.ticker.high;
				pb_low.innerHTML = json_res.ticker.low;
				pb_vol.innerHTML = json_res.ticker.vol.toFixed(2)
				/*pb_close.innerHTML = json_res.ticker.last_rate.toFixed(4);
				pb_high.innerHTML = json_res.ticker.high.toFixed(4);
				pb_low.innerHTML = json_res.ticker.low.toFixed(4);
				pb_vol.innerHTML = json_res.ticker.vol.toFixed(4)*/
			}
		}
	})
},
update_best_rate: function() {
	if(get_element('rate_best_ask').innerHTML=="0") get_element('ask_rate').value = num_fix(Number(Number(main_ask_bid_list_obj.best_bid_rate) + 0.00000004).toFixed(10), 4);
	if(get_element('rate_best_bid').innerHTML=="0") get_element('bid_rate').value = num_fix(Number(Number(main_ask_bid_list_obj.best_ask_rate) + 0.00000004).toFixed(10), 4);
	get_element('rate_best_ask').innerHTML = num_fix(Number(Number(main_ask_bid_list_obj.best_bid_rate) + 0.00000004).toFixed(10), 4);
	get_element('rate_best_bid').innerHTML = num_fix(Number(Number(main_ask_bid_list_obj.best_ask_rate) + 0.00000004).toFixed(10), 4);
	
	this.update_able_amount()
},
update_able_amount: function() {
	var rate_best_ask = main_ask_bid_list_obj.best_ask_rate;
	var rate_best_bid = main_ask_bid_list_obj.best_bid_rate;
	var balance_ask_able = get_element('balance_ask_able').innerHTML;
	var balance_bid_able = get_element('balance_bid_able').innerHTML;
	//if(rate_best_bid==0) amount_ask_able=1;
	//alert();
	var amount_ask_able = balance_ask_able / rate_best_bid / (1 + Number(page_obj.fee));
	var amount_bid_able = balance_bid_able * rate_best_ask / (1 + Number(page_obj.fee));
	get_element('amount_ask_able').innerHTML = num_fix(Number(amount_ask_able).toFixed(10), 4);
	get_element('amount_bid_able').innerHTML = num_fix(Number(amount_bid_able).toFixed(10), 4)
}
};
return {
	main_rate: main_rate_obj,
	main_ask_bid_list: main_ask_bid_list_obj,
	main_history_grid: main_history_grid_obj,
	obj: page_obj,
	run: function() {
		$.timer(function() {
			var json_req = {
				type: "time_mark",
				symbol: cur_symbol,
				mtype: "ex_cc"
			};
			$.ajax({
				type: "post",
				url: "/data.php?op=query" + page_rand(),
				data: json_req,
				dataType: "json",//返回json格式的数据
				success: function(data, state) {
					var json_res = data;
					
					if (json_res && json_res.result) {
						page_vars.server_mark_ex = json_res.mark_ex;
						page_vars.server_mark_cc = json_res.mark_cc;
						main_rate_obj.recheck();
						main_history_grid_obj.recheck();
						main_ask_bid_list_obj.recheck()
					}
				}
			})
		},
		5000, true)
	}
}
};