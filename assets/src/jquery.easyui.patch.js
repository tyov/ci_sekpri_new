/**
 * The Patch for jQuery EasyUI 1.4.2
 */

(function($){
	$.extend($.fn.textbox.methods, {
	        setText: function(jq, value){
	                return jq.each(function(){
	                        var opts = $(this).textbox('options');
	                        var input = $(this).textbox('textbox');
	                        value = value == undefined ? '' : String(value);
	                        if ($(this).textbox('getText') != value){
	                                input.val(value);
	                        }
	                        opts.value = value;
	                        if (!input.is(':focus')){
	                                if (value){
	                                        input.removeClass('textbox-prompt');
	                                } else {
	                                        input.val(opts.prompt).addClass('textbox-prompt');
	                                }
	                        }
	                        $(this).textbox('validate');
	                });
	        }                
	})
})(jQuery);

(function($){
        function setValues(target, values, remainText){
                var state = $.data(target, 'combogrid');
                var opts = state.options;
                var grid = state.grid;
                
                var oldValues = $(target).combo('getValues');
                var cOpts = $(target).combo('options');
                var onChange = cOpts.onChange;
                cOpts.onChange = function(){};  // prevent from triggering onChange event
                var gOpts = grid.datagrid('options');
                var onSelect = gOpts.onSelect;
                var onUnselectAll = gOpts.onUnselectAll;
                gOpts.onSelect = gOpts.onUnselectAll = function(){};
                
                if (!$.isArray(values)){values = values.split(opts.separator)}
                var selectedRows = [];
                $.map(grid.datagrid('getSelections'), function(row){
                        if ($.inArray(row[opts.idField], values) >= 0){
                                selectedRows.push(row);
                        }
                });
                grid.datagrid('clearSelections');
                grid.data('datagrid').selectedRows = selectedRows;

                var ss = [];
                for(var i=0; i<values.length; i++){
                        var value = values[i];
                        var index = grid.datagrid('getRowIndex', value);
                        if (index >= 0){
                                grid.datagrid('selectRow', index);
                        }
                        ss.push(findText(value, grid.datagrid('getRows')) ||
                                        findText(value, grid.datagrid('getSelections')) ||
                                        findText(value, opts.mappingRows) ||
                                        value
                        );
                }

                opts.unselectedValues = [];
                var selectedValues = $.map(selectedRows, function(row){
                        return row[opts.idField];
                });
                $.map(values, function(value){
                        if ($.inArray(value, selectedValues) == -1){
                                opts.unselectedValues.push(value);
                        }
                });

                $(target).combo('setValues', oldValues);
                cOpts.onChange = onChange;      // restore to trigger onChange event
                gOpts.onSelect = onSelect;
                gOpts.onUnselectAll = onUnselectAll;
                
                if (!remainText){
                        var s = ss.join(opts.separator);
                        if ($(target).combo('getText') != s){
                                $(target).combo('setText', s);
                        }
                }
                $(target).combo('setValues', values);
                
                function findText(value, a){
                        for(var i=0; i<a.length; i++){
                                if (value == a[i][opts.idField]){
                                        return a[i][opts.textField];
                                }
                        }
                        return undefined;
                }
        }
        function setMe(target){
                var state = $.data(target, 'combogrid');
                var opts = state.options;
                var grid = state.grid;
                $.extend($(target).combogrid('grid').datagrid('options'), {
                        onLoadSuccess: function(data){
                                var values = $(target).combo('getValues');
                                // prevent from firing onSelect event.
                                var oldOnSelect = opts.onSelect;
                                opts.onSelect = function(){};
                                setValues(target, values, state.remainText);
                                opts.onSelect = oldOnSelect;
                                
                                opts.onLoadSuccess.apply(target, arguments);
                        },
                        onClickRow: onClickRow,
                        onSelect: function(index, row){retrieveValues(); opts.onSelect.call(this, index, row);},
                        onUnselect: function(index, row){retrieveValues(); opts.onUnselect.call(this, index, row);},
                        onSelectAll: function(rows){retrieveValues(); opts.onSelectAll.call(this, rows);},
                        onUnselectAll: function(rows){
                                if (opts.multiple) retrieveValues(); 
                                opts.onUnselectAll.call(this, rows);
                        }
                });
                function onClickRow(index, row){
                        state.remainText = false;
                        retrieveValues();
                        if (!opts.multiple){
                                $(target).combo('hidePanel');
                        }
                        opts.onClickRow.call(this, index, row);
                }
                function retrieveValues(){
                        var vv = $.map(grid.datagrid('getSelections'), function(row){
                                return row[opts.idField];
                        });
                        vv = vv.concat(opts.unselectedValues);
                        if (!opts.multiple){
                                vv = vv.length ? [vv[0]] : [''];
                        }
                        setValues(target, vv, state.remainText);
                }
        }
        var plugin = $.fn.combogrid;
        $.fn.combogrid = function(options, param){
                if (typeof options == 'string'){
                        return plugin.call(this, options, param);
                } else {
                        return this.each(function(){
                                plugin.call($(this), options, param);
                                setMe(this);
                        });
                }
        }
        $.fn.combogrid.defaults = plugin.defaults;
        $.fn.combogrid.methods = plugin.methods;
        $.fn.combogrid.parseOptions = plugin.parseOptions;

        $.extend($.fn.combogrid.defaults, {
                unselectedValues: [],
                mappingRows: [],
                filter: function(q, row){
                        var opts = $(this).combogrid('options');
                        return (row[opts.textField]||'').toLowerCase().indexOf(q.toLowerCase()) == 0;
                }
        });
        $.extend($.fn.combogrid.methods, {
                setValues: function(jq, values){
                        return jq.each(function(){
                                var opts = $(this).combogrid('options');
                                if ($.isArray(values)){
                                        values = $.map(values, function(value){
                                                if (typeof value == 'object'){
                                                        var v = value[opts.idField];
                                                        (function(){
                                                                for(var i=0; i<opts.mappingRows.length; i++){
                                                                        if (v == opts.mappingRows[i][opts.idField]){
                                                                                return;
                                                                        }
                                                                }
                                                                opts.mappingRows.push(value);
                                                        })();
                                                        return v;
                                                } else {
                                                        return value;
                                                }
                                        });
                                }
                                setValues(this, values);
                        });
                },
                setValue: function(jq, value){
                        return jq.each(function(){
                                $(this).combogrid('setValues', [value]);
                        });
                }
        });
})(jQuery);

(function($){
        function getTableTarget(t){
            return $(t).closest('div.datagrid-view').children('.datagrid-f')[0];
        }
        function getClosestTr(t){
            var tr = $(t).closest('tr.datagrid-row');
            if (tr.length && tr.parent().length){
                return tr;
            } else {
                return undefined;
            }
        }
        function getTrIndex(tr){
            if (tr.attr('datagrid-row-index')){
                return parseInt(tr.attr('datagrid-row-index'));
            } else {
                return tr.attr('node-id');
            }
        }
        function contextmenuEventHandler(e){
            var tr = getClosestTr(e.target);
            if (tr){
                var target = getTableTarget(tr);
                var opts = $.data(target, 'datagrid').options;
                var index = getTrIndex(tr);
                var row = opts.finder.getRow(target, index);
                opts.onRowContextMenu.call(target, e, index, row);
            } else {
                var body = $(e.target).closest('.datagrid-body');
                if (body.length && body.parent().length){
                    var target = getTableTarget(body);
                    var opts = $.data(target, 'datagrid').options;
                    opts.onRowContextMenu.call(target, e, -1, null);
                }
            }
        }
        function setBodySize(target){
                var state = $.data(target, 'datagrid');
                var opts = state.options;
                var dc = state.dc;
                var wrap = state.panel;
                var innerWidth = wrap.width();
                var innerHeight = wrap.height();
                
                var view = dc.view;
                var view1 = dc.view1;
                var view2 = dc.view2;
                var header1 = view1.children('div.datagrid-header');
                var header2 = view2.children('div.datagrid-header');
                var table1 = header1.find('table');
                var table2 = header2.find('table');
                
                // set view width
                view.width(innerWidth);
                var headerInner = header1.children('div.datagrid-header-inner').show();
                view1.width(headerInner.find('table').width());
                if (!opts.showHeader) headerInner.hide();
                view2.width(innerWidth - view1._outerWidth());
                view1.children()._outerWidth(view1.width());
                view2.children()._outerWidth(view2.width());
                
                // set header height
                var all = header1.add(header2).add(table1).add(table2);
                all.css('height', '');
                var hh = Math.max(table1.height(), table2.height());
                all._outerHeight(hh);
                
                // set body height
                dc.body1.add(dc.body2).children('table.datagrid-btable-frozen').css({
                        position: 'absolute',
                        top: dc.header2._outerHeight()
                });
                var frozenHeight = dc.body2.children('table.datagrid-btable-frozen')._outerHeight();
                var fixedHeight = frozenHeight + header2._outerHeight() + view2.children('.datagrid-footer')._outerHeight();
                wrap.children(':not(.datagrid-view,.datagrid-mask,.datagrid-mask-msg)').each(function(){
                        fixedHeight += $(this)._outerHeight();
                });
                
                var distance = wrap.outerHeight() - wrap.height();
                var minHeight = wrap._size('minHeight') || '';
                var maxHeight = wrap._size('maxHeight') || '';
                view1.add(view2).children('div.datagrid-body').css({
                        marginTop: frozenHeight,
                        height: (isNaN(parseInt(opts.height)) ? '' : (innerHeight-fixedHeight)),
                        minHeight: (minHeight ? minHeight-distance-fixedHeight : ''),
                        maxHeight: (maxHeight ? maxHeight-distance-fixedHeight : '')
                });
                
                view.height(view2.height());
        }

        var plugin = $.fn.datagrid;
        $.fn.datagrid = function(options, param){
                if (typeof options == 'string'){
                        return plugin.call(this, options, param);
                } else {
                        return this.each(function(){
                                var dg = $(this);
                                plugin.call(dg, options, param);
                                var opts = $.data(this, 'datagrid').options;
                                var panel = $(this).datagrid('getPanel');
                                panel.panel('options').onResize = function(width, height){
                                        setBodySize(dg[0]);
                                        dg.datagrid('fitColumns');
                                        opts.onResize.call(panel[0], width, height);
                                };
                                panel.panel('options').onExpand = function(){
                                        dg.datagrid('fixRowHeight').datagrid('fitColumns');
                                        opts.onExpand.call(panel[0]);
                                }
                        });
                }
        };
        $.fn.datagrid.defaults = plugin.defaults;
        $.fn.datagrid.methods = plugin.methods;
        $.fn.datagrid.parseOptions = plugin.parseOptions;
        $.fn.datagrid.parseData = plugin.parseData;

        $.extend($.fn.datagrid.defaults.rowEvents, {
            contextmenu: contextmenuEventHandler
        });
        $.extend($.fn.datagrid.defaults.view, {
                renderEmptyRow: function(target){
                        var cols = $.map($(target).datagrid('getColumnFields'), function(field){
                                return $(target).datagrid('getColumnOption', field);
                        });
                        $.map(cols, function(col){
                                col.formatter1 = col.formatter;
                                col.styler1 = col.styler;
                                col.formatter = col.styler = undefined;
                        });
                        var body2 = $.data(target, 'datagrid').dc.body2;
                        body2.html(this.renderTable(target, 0, [{}], false));
                        body2.find('tbody *').css({
                                height: 1,
                                borderColor: 'transparent',
                                background: 'transparent'
                        });
                        var tr = body2.find('.datagrid-row');
                        tr.removeClass('datagrid-row').removeAttr('datagrid-row-index');
                        tr.find('.datagrid-cell,.datagrid-cell-check').empty();
                        $.map(cols, function(col){
                                col.formatter = col.formatter1;
                                col.styler = col.styler1;
                                col.formatter1 = col.styler1 = undefined;
                        });
                        $(target).datagrid('autoSizeColumn');
                }
        });
        $.fn.treegrid.defaults.view.renderEmptyRow = $.fn.datagrid.defaults.view.renderEmptyRow;

        var setSelectionState = $.fn.datagrid.methods.setSelectionState;
        $.fn.datagrid.methods.setSelectionState = function(jq){
                return jq.each(function(){
                        setSelectionState($(this));
                        setBodySize(this);
                });
        }        
})(jQuery);

(function($){
	var plugin = $.fn.window;
	$.fn.window = function(options, param){
		if (typeof options == 'string'){
			return plugin.call(this, options, param);
		} else {
			return this.each(function(){
				plugin.call($(this), options, param);
				var state = $.data(this, 'window');
				if (state.mask && state.options.inline){
					state.mask.css({
						width: '100%',
						height: '100%'
					});
				}
			});
		}
	}
	$.fn.window.defaults = plugin.defaults;
	$.fn.window.methods = plugin.methods;
	$.fn.window.parseOptions = plugin.parseOptions;
})(jQuery);

(function($){
        function updateTab(container, param){
                param.type = param.type || 'all';
                var selectHis = $.data(container, 'tabs').selectHis;
                var pp = param.tab;     // the tab panel
                var opts = pp.panel('options'); // get the tab panel options
                var oldTitle = opts.title;
                $.extend(opts, param.options, {
                        iconCls: (param.options.icon ? param.options.icon : undefined)
                });

                if (param.type == 'all' || param.type == 'body'){
                        pp.panel();
                }
                if (param.type == 'all' || param.type == 'header'){
                        var tab = opts.tab;
                        
                        if (opts.header){
                                tab.find('.tabs-inner').html($(opts.header));
                        } else {
                                var s_title = tab.find('span.tabs-title');
                                var s_icon = tab.find('span.tabs-icon');
                                s_title.html(opts.title);
                                s_icon.attr('class', 'tabs-icon');
                                
                                tab.find('a.tabs-close').remove();
                                if (opts.closable){
                                        s_title.addClass('tabs-closable');
                                        $('<a href="javascript:void(0)" class="tabs-close"></a>').appendTo(tab);
                                } else{
                                        s_title.removeClass('tabs-closable');
                                }
                                if (opts.iconCls){
                                        s_title.addClass('tabs-with-icon');
                                        s_icon.addClass(opts.iconCls);
                                } else {
                                        s_title.removeClass('tabs-with-icon');
                                }
                                if (opts.tools){
                                        var p_tool = tab.find('span.tabs-p-tool');
                                        if (!p_tool.length){
                                                var p_tool = $('<span class="tabs-p-tool"></span>').insertAfter(tab.find('a.tabs-inner'));
                                        }
                                        if ($.isArray(opts.tools)){
                                                p_tool.empty();
                                                for(var i=0; i<opts.tools.length; i++){
                                                        var t = $('<a href="javascript:void(0)"></a>').appendTo(p_tool);
                                                        t.addClass(opts.tools[i].iconCls);
                                                        if (opts.tools[i].handler){
                                                                t.bind('click', {handler:opts.tools[i].handler}, function(e){
                                                                        if ($(this).parents('li').hasClass('tabs-disabled')){return;}
                                                                        e.data.handler.call(this);
                                                                });
                                                        }
                                                }
                                        } else {
                                                $(opts.tools).children().appendTo(p_tool);
                                        }
                                        var pr = p_tool.children().length * 12;
                                        if (opts.closable) {
                                                pr += 8;
                                        } else {
                                                pr -= 3;
                                                p_tool.css('right','5px');
                                        }
                                        s_title.css('padding-right', pr+'px');
                                } else {
                                        tab.find('span.tabs-p-tool').remove();
                                        s_title.css('padding-right', '');
                                }
                        }
                        if (oldTitle != opts.title){
                                for(var i=0; i<selectHis.length; i++){
                                        if (selectHis[i] == oldTitle){
                                                selectHis[i] = opts.title;
                                        }
                                }
                        }
                }
                
                $(container).tabs('resize');
                
                $.data(container, 'tabs').options.onUpdate.call(container, opts.title, $(container).tabs('getTabIndex',pp));
        }

        $.extend($.fn.tabs.methods, {
                update: function(jq, param){
                        return jq.each(function(){
                                updateTab(this, param);
                        })
                }
        })
})(jQuery);

(function($){
	var render = $.fn.tree.defaults.view.render;
	$.fn.tree.defaults.view.render = function(target, ul, data){
		render(target, ul, data);
		var opts = $(target).tree('options');
		if (opts.dnd){
			$(target).find('.tree-node').draggable({
				delay:0
			});
		}
	}
})(jQuery);

(function($){
        function setValues(target, values, remainText){
                var opts = $.data(target, 'combobox').options;
                var panel = $(target).combo('panel');
                
                if (!$.isArray(values)){values = values.split(opts.separator)}
                panel.find('div.combobox-item-selected').removeClass('combobox-item-selected');
                var vv = [], ss = [];
                for(var i=0; i<values.length; i++){
                        var v = values[i];
                        var s = v;
                        opts.finder.getEl(target, v).addClass('combobox-item-selected');
                        var row = opts.finder.getRow(target, v);
                        if (row){
                                s = row[opts.textField];
                        }
                        vv.push(v);
                        ss.push(s);
                }
                
                if (!remainText){
                        $(target).combo('setText', ss.join(opts.separator));
                }
                $(target).combo('setValues', vv);
        }

        function doQuery(target, q){
                var state = $.data(target, 'combobox');
                var opts = state.options;
                
                var qq = opts.multiple ? q.split(opts.separator) : [q];
                var panel = $(target).combo('panel');
                panel.find('div.combobox-item-selected,div.combobox-item-hover').removeClass('combobox-item-selected combobox-item-hover');
                panel.find('div.combobox-item,div.combobox-group').hide();
                var data = state.data;
                var vv = [];
                $.map(qq, function(q){
                        q = $.trim(q);
                        var value = q;
                        var group = undefined;
                        for(var i=0; i<data.length; i++){
                                var row = data[i];
                                if (opts.filter.call(target, q, row)){
                                        var v = row[opts.valueField];
                                        var s = row[opts.textField];
                                        var g = row[opts.groupField];
                                        var item = opts.finder.getEl(target, v).show();
                                        if (s.toLowerCase() == q.toLowerCase()){
                                                value = v;
                                                item.addClass('combobox-item-selected');
                                                opts.onSelect.call(target, row);
                                        }
                                        if (opts.groupField && group != g){
                                                $('#'+state.groupIdPrefix+'_'+$.inArray(g, state.groups)).show();
                                                group = g;
                                        }
                                }
                        }
                        vv.push(value);
                });
                _setValues(vv);

                function _setValues(vv){
                        setValues(target, opts.multiple ? (q?vv:[]) : vv, true);
                }
        }

        var query = $.fn.combobox.defaults.keyHandler.query;
        $.fn.combobox.defaults.keyHandler.query = function(q,e){
                var opts = $(this).combobox('options');
                if (opts.mode == 'remote'){
                        query.call(this, q, e);
                } else {
                        doQuery(this, q);
                }
        }
})(jQuery);
