/**
 * 管理员账号相关的功能
 */
$(function(){
	//管理列表的表格
	var AdminTable = new (function(id){
		//jquery的datatable对象
		var _datatable;
		//jquery的table对象
		var _element = $(id);
		//修改名称
		function _updateName() {
			var _td = _datatable.cell($(this).parents('td'));
			var _tr = _datatable.row($(this).parents('tr'));
			var oldname = _td.data();
			var rowData = _tr.data();
			var uid = rowData.id;
			$.confirm({
				'title': '请输入新名称',
				'msg': '<input type="text" class="form-control" name="nick" value="' + oldname + '" maxlength="10" />',
				'callback': function(dlg) {
					var name = $('input[name=nick]', dlg).val();
					if(name == oldname) {
						$.alert({'msg': '无变化！', 'style': 'danger'});
						return false;
					} else if(name.length == 0) {
						$.alert({'msg': '名称不能为空！', 'style': 'danger'});
						return false;
					} else {
						$.post('/admin/system_admin/updateName', {'uid': uid, 'name': name}, function(resp){
							if (resp.code == 0) {
								_td.data(name).draw();
							} else {
								$.alert({'msg': resp.msg, 'style': 'danger'});
							}
						}, 'json');
					}
				}
			});
		}
		//修改角色
		function _updateRoles() {
			var _td = _datatable.cell($(this).parents('td'));
			var _tr = _datatable.row($(this).parents('tr'));
			var oldRoles = _td.data();
			var rowData = _tr.data();
			var formHtml = '<form><div class="form-group">';
			for(var k in $VARS.roles) {
				var it = $VARS['roles'][k];
				var flag = $.inArray(parseInt(k), oldRoles);
				formHtml += '<div class="checkbox"><label><input type="checkbox" name="roles" value="' + k + '" ' + (flag > -1 ? 'checked="checked"' : '') + ' />&nbsp;' + it.name + '</label></div>';
			}
			formHtml += '</div></form>';
			$.confirm({
				'title': '请选择要设置的角色',
				'html': formHtml,
				'callback': function(dlg) {
					var checkboxes = $('input[name=roles]:checked', dlg), roles = [];
					if(checkboxes.length > 0) {
						for(var i=0; i<checkboxes.length; i++) {
							roles[i] = parseInt(checkboxes[i].value);
						}
						$.post('/admin/system_admin/updateRoles', {'uid': rowData.id, 'roles': roles}, function(resp){
							if (resp.code == 0) {
								_td.data(roles).draw();
							} else {
								$.alert({'msg': resp.msg, 'style': 'danger'});
							}
						}, 'json');
					} else {
						$.alert({'msg': '请至少选择一个角色！', 'style': 'danger'});
						return false;
					}
				}
			});
		}
		//修改密码
		function _updatePasswd() {
			var _tr = _datatable.row($(this).parents('tr'));
			var rowData = _tr.data();
			$.confirm({
				'title': '请输入新密码',
				'msg': '<input type="text" class="form-control" name="passwd" value="" maxlength="20" />',
				'callback': function(dlg) {
					var pwd = $('input[name=passwd]', dlg).val();
					if(pwd == '') {
						$.alert({'msg': '密码不能为空！', 'style': 'danger'});
						return false;
					} else if (pwd.length < 6) {
						$.alert({'msg': '密码长度不能小于6！', 'style': 'danger'});
						return false;
					} else {
						$.post('/admin/system_admin/updatePasswd', {'uid': rowData.id, 'passwd': pwd}, function(resp){
							if (resp.code == 0) {
								$.alert({'msg': '已修改!', 'style': 'success'});
							} else {
								$.alert({'msg': resp.msg, 'style': 'danger'});
							}
						}, 'json');
					}
				}
			});
		}
		//添加记录
		function _add(record){
			$.post('/admin/system_admin/add', record, function(resp){
				if (!resp) {
					$.alert({'msg': '操作失败，请重试！', 'style': 'danger'});
				} else if (resp.code == 0) {
					_datatable.ajax.reload();
					$('#adminform').get(0).reset();
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
				'msg': '确定要删除该角色吗？',
				'style': 'danger',
				'callback': function(){
					$.post('/admin/system_admin/del', {'uid': rowData.id}, function(resp){
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
					$.post('/admin/system_admin/disable', {'uid': rowData.id}, function(resp){
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
			$.post('/admin/system_admin/enable', {'uid': rowData.id}, function(resp){
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
			'ajax': {
				'url': '/admin/system_admin/list',
				'type': 'GET'
			},
			'language': {
				'url': $RS_URI + '/adminlte/bower_components/datatables.net-bs/lang/Chinese.json'
			},
			'columns': [
				{'data': 'id'},
				{'data': 'account'},
				{
					'data': 'name',
					'render': function(data, type, row, meta){
						if(type == 'display') {
							return data + '&nbsp;<a href="#" title="修改名称" class="fa fa-edit text-blue name_edit"></a>';
						}
						return data;
					}
				},
				{
					'data': 'last_login_time',
					'searchable': false
				},
				{
					'data': 'create_time',
					'searchable': false
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
									str += '<span class="label label-' + (id == 1 ? 'warning' : 'default') + '">' + $VARS['roles'][id]['name'] + '</span>&nbsp;';
								}
							}
							str += '<a href="#" class="fa fa-edit text-green roles_edit" title="设置角色"></a>';
							return str;
						}
						return data;
					}
				},
				{
					'data': 'status',
					'orderable': false,
					'searchable': false,
					'render': function(data, type, row, meta) {
						var str;
						var status = parseInt(data);
						if(type == 'display') {
							str = '<a href="#" class="btn btn-xs btn-default">修改密码</a>'
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
				$('a.name_edit', _element).on('click', _updateName);
				$('a.roles_edit', _element).on('click', _updateRoles);
				$('a.btn-default', _element).on('click', _updatePasswd);
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
			'add': _add //添加记录
		});
	})('#DT');

	//添加角色的表单
	$('#adminform').submit(function(){
		if (this.account.value == '') {
			$.alert({'msg': '账号不能为空!', 'style': 'danger'});
		} else if(/\W/.test(this.account.value) || !/^[A-Za-z]/.test(this.account.value)) {
			$.alert({'msg': '账号只能使用字母、数字和下划线，并且只能使用字母开头!', 'style': 'danger'});
		} else if (this.nickname.value == '') {
			$.alert({'msg': '名称不能为空!', 'style': 'danger'});
		} else if (this.passwd.value == '') {
			$.alert({'msg': '密码不能为空!', 'style': 'danger'});
		} else if(this.passwd.value.length < 6) {
			$.alert({'msg': '密码长度不能小于6!', 'style': 'danger'});
		} else {
			var checkboxes = $('input[name=roles]:checked', this), roles = [];
			if(checkboxes.length > 0) {
				for(var i=0; i<checkboxes.length; i++) {
					roles[i] = checkboxes[i].value;
				}
				var record = {
					'account': this.account.value,
					'name': this.nickname.value,
					'passwd': this.passwd.value,
					'roles': roles
				};
				AdminTable.add(record);
			} else {
				$.alert({'msg': '请至少选择一个角色!', 'style': 'danger'});
			}
		}
		return false;
	});

});