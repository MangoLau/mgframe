/**
 * 管理后台菜单模块相关的功能
 */
$(function(){
	//后台菜单表格
	var MenuTable = new (function(id){
		//jquery的datatable对象
		var _datatable;
		//jquery的table对象
		var _element = $(id);
		//最后修改的记录
		var _lastEdit = null;
		//添加记录
		function _add(record){
			$.post('/admin/system_menu/add', record, function(resp){
				if (!resp) {
					$.alert({'msg': '操作失败，请重试！', 'style': 'danger'});
				} else if (resp.code == 0) {
					_datatable.ajax.reload();
					$('#addform').get(0).reset();
				} else {
					$.alert({'msg': resp.msg, 'style': 'danger'});
				}
			}, 'json');
		}
		//开始编辑记录
		function _edit() {
			var tr = _datatable.row($(this).parents('tr'));
			var rowData = tr.data();
			var form = $('#editform');
			var formDom = form[0];
			_lastEdit = tr;
			formDom.menuid.value = rowData.menuid;
			formDom.menu.value = rowData.menu;
			formDom.index_url.value = rowData.index_url;
			formDom.urls.value = rowData.urls.join("\n");
			formDom.parent_id.value = rowData.parent_id;
			formDom.status.value = rowData.status;
			formDom.sort_no.value = rowData.sort_no;
			formDom.icon.value = rowData.icon;
			if(rowData.roles.length) {
				for(var i=0; i<rowData.roles.length; i++) {
					$('input[name=roles][value=' + rowData.roles[i] + ']', formDom).attr('checked', true);
				}
			}
			$('#add_box').hide();
			$('#edit_box').show();
		}
		//修改记录
		function _update(record) {
			$.post('/admin/system_menu/update', record, function(resp){
				if (!resp) {
					$.alert({'msg': '操作失败，请重试！', 'style': 'danger'});
				} else if (resp.code == 0) {
					var form = $('#editform');
					_lastEdit.data(record).draw();
					$('#edit_box').hide();
					$('#add_box').show();
					form[0].reset();
				} else {
					$.alert({'msg': resp.msg, 'style': 'danger'});
				}
			}, 'json');
		}
		//删除事件函数
		function _delete() {
			var tr = _datatable.row($(this).parents('tr'));
			var rowData = tr.data();
			$.confirm({
				'msg': '确定要删除该菜单吗？',
				'style': 'danger',
				'callback': function(){
					$.post('/admin/system_menu/del', {'menuid': rowData.menuid}, function(resp){
						if (resp.code == 0) {
							tr.remove().draw();
						} else {
							$.alert({'msg': resp.msg, 'style': 'danger'});
						}
					}, 'json');
				}
			});
			return false;
		}
		//禁用事件函数
		function _disable() {
			var td = _datatable.cell($(this).parents('td'));
			var tr = _datatable.row($(this).parents('tr'));
			var rowData = tr.data();
			$.confirm({
				'msg': '确定要禁用该角色吗？',
				'style': 'warning',
				'callback': function(){
					$.post('/admin/system_menu/disable', {'menuid': rowData.menuid}, function(resp){
						if (resp.code == 0) {
							td.data('0').draw();
						} else {
							$.alert({'msg': resp.msg, 'style': 'danger'});
						}
					}, 'json');
				}
			});
			return false;
		}
		//启用事件函数
		function _enable() {
			var td = _datatable.cell($(this).parents('td'));
			var tr = _datatable.row($(this).parents('tr'));
			var rowData = tr.data();
			$.post('/admin/system_menu/enable', {'menuid': rowData.menuid}, function(resp){
				if (resp.code == 0) {
					td.data('1').draw();
				} else {
					$.alert({'msg': resp.msg, 'style': 'danger'});
				}
			}, 'json');
			return false;
		}
		//初始化
		_datatable = _element.DataTable({
			'serverSide': true,
			'pageLength': 25,
			'ajax': {
				'url': '/admin/system_menu/list',
				'type': 'POST'
			},
			'language': {
				'url': $RS_URI + '/adminlte/bower_components/datatables.net-bs/lang/Chinese.json'
			},
			'columns': [
				{'data': 'menuid'},
				{
					'data': 'menu',
					'render': function(data, type, row, meta){
						if(type == 'display') {
							var icon = row.index_url == '' ? 'fa fa-folder-o' : (row.icon == '' ? 'fa fa-gear' : row.icon);
							return '<i class="' + icon + '"></i>' + data;
						}
						return data;
					}
				},
				{'data': 'index_url'},
				{
					'data': 'urls',
					'orderable': false,
					'searchable': false,
					'render': function(data, type, row, meta){
						if(type == 'display') {
							var str = '';
							if(data.length) {
								for(var i=0; i<data.length; i++) {
									str += data[i] + '<br />';
								}
							}
							return str;
						}
						return data;
					}
				},
				{
					'data': 'parent_id',
					'searchable': false,
					'render': function(data, type, row, meta){
						if(type == 'display') {
							data = parseInt(data);
							if(data) {
								return $VARS.topMenus[data] ? '#' + $VARS.topMenus[data].menuid + ' ' + $VARS.topMenus[data].menu : '<del>无效值</del>';
							} else {
								return '无';
							}
						}
						return data;
					}
				},
				{
					'data': 'roles',
					'orderable': false,
					'searchable': false,
					'render': function(data, type, row, meta){
						if(type == 'display') {
							var str = '';
							if (data.length) {
								for(var i=0; i<data.length; i++) {
									var id = parseInt(data[i]);
									str += '<span class="label label-' + (id == 1 ? 'warning' : 'default') + '">' + $VARS['roles'][id]['name'] + '</span><br />';
								}
							}
							return str;
						}
						return data;
					}
				},
				{
					'data': 'sort_no',
					'searchable': false
				},
				{
					'data': 'status',
					'searchable': false,
					'render': function(data, type, row, meta) {
						var str = '';
						var status = parseInt(data);
						if(type == 'display') {
							str += ' <a href="#" class="btn btn-xs btn-default">修改</a>';
							if (status) {
								str += ' <a href="#" class="btn btn-xs btn-success">已启用</a>';
							} else {
								str += ' <a href="#" class="btn btn-xs btn-warning">已禁用</a>';
							}
							str += ' <a href="#" class="btn btn-xs btn-danger">删除</a>';
							return str;
						}
						return data;
					}
				}
			],
			'drawCallback': function(){
				$('td a', _element).off('click');
				$('a.btn-default', _element).on('click', _edit);
				$('a.btn-success', _element).on('click', _disable);
				$('a.btn-warning', _element).on('click', _enable);
				$('a.btn-danger', _element).on('click', _delete);
			},
			'paging': true,
			'searching': true,
			'ordering': true,
			'info': true
		});
		//对外接口
		$.extend(this, {
			'reload': function(){ _datatable.ajax.reload(); }, //刷新数据
			'add': _add, //添加记录
			'update': _update //修改记录
		});
	})('#DT');

	//添加角色的表单
	$('#addform').submit(function(){
		if (this.menu.value == '') {
			$.alert({'msg': '名称不能为空!', 'style': 'danger'});
		} else if(this.index_url.value != '' && /[^\w*\/]/.test(this.index_url.value)) {
			$.alert({'msg': '主URL格式不正确，只能使用字母、数字、下划线、斜线和星号!', 'style': 'danger'});
		} else if(this.sort_no.value != '' && /\D/.test(this.sort_no.value)) {
			$.alert({'msg': '排序值只能是数字!', 'style': 'danger'});
		} else {
			var urls = this.urls.value.replace("\r", "");
			urlsArray = urls.split("\n");
			for(var i=0; i<urlsArray.length; i++) {
				urlsArray[i] = $.trim(urlsArray[i]);
				if(urlsArray[i] != '' && /[^\w*\/]/.test(urlsArray[i])) {
					$.alert({'msg': '第' + (i+1) + '个URL格式不正确！', 'style': 'danger'});
					return false;
				}
			}
			var checkboxes = $('input[name=roles]:checked', this), roles = [];
			if(checkboxes.length > 0) {
				for(var i=0; i<checkboxes.length; i++) {
					roles[i] = checkboxes[i].value;
				}
				var record = {
					'menu': this.menu.value,
					'index_url': this.index_url.value,
					'urls': urlsArray,
					'parent_id': this.parent_id.value,
					'sort_no': this.sort_no.value,
					'roles': roles,
					'icon': this.icon.value
				};
				MenuTable.add(record);
			} else {
				$.alert({'msg': '请至少选择一个角色!', 'style': 'danger'});
			}
		}
		return false;
	});

	//修改记录的表单
	$('#editform').submit(function(){
		if (this.menu.value == '') {
			$.alert({'msg': '名称不能为空!', 'style': 'danger'});
		} else if(this.index_url.value != '' && /[^\w*\/]/.test(this.index_url.value)) {
			$.alert({'msg': '主URL格式不正确，只能使用字母、数字、下划线、斜线和星号!', 'style': 'danger'});
		} else if(this.sort_no.value != '' && /\D/.test(this.sort_no.value)) {
			$.alert({'msg': '排序值只能是数字!', 'style': 'danger'});
		} else {
			var urls = this.urls.value.replace("\r", "");
			urlsArray = urls.split("\n");
			for(var i=0; i<urlsArray.length; i++) {
				urlsArray[i] = $.trim(urlsArray[i]);
				if(urlsArray[i] != '' && /[^\w*\/]/.test(urlsArray[i])) {
					$.alert({'msg': '第' + (i+1) + '个URL格式不正确！', 'style': 'danger'});
					return false;
				}
			}
			var checkboxes = $('input[name=roles]:checked', this), roles = [];
			if(checkboxes.length > 0) {
				for(var i=0; i<checkboxes.length; i++) {
					roles[i] = checkboxes[i].value;
				}
				var record = {
					'menuid': this.menuid.value,
					'menu': this.menu.value,
					'index_url': this.index_url.value,
					'urls': urlsArray,
					'parent_id': this.parent_id.value,
					'roles': roles,
					'sort_no': this.sort_no.value,
					'status': this.status.value,
					'icon': this.icon.value
				};
				MenuTable.update(record);
			} else {
				$.alert({'msg': '请至少选择一个角色!', 'style': 'danger'});
			}
		}
		return false;
	});

	//取消编辑
	$('#editform_cancel').click(function(){
		$('#edit_box').hide();
		$('#add_box').show();
		this.form.reset();
	});

	//图标选择
	$('.icon-selector').iconSelector({
		'keys': ['web_application','spinner','payment','chart','currency','brand','medical','glyphicon']
	});
});