/**
 * 管理员角色相关的功能
 */
$(function(){
	//角色列表的表格
	var RolesTable = new (function(id){
		//jquery的datatable对象
		var _datatable;
		//jquery的table对象
		var _element = $(id);
		//添加记录
		function _add(name){
			$.post('/admin/system_role/add', {'name': name}, function(resp){
				if (!resp) {
					$.alert({'msg': '操作失败，请重试！', 'style': 'danger'});
				} else if (resp.code == 0) {
					_datatable.ajax.reload();
					$('#roleform').get(0).reset();
				} else {
					$.alert({'msg': resp.msg, 'style': 'danger'});
				}
			}, 'json');
		}
		//修改名称
		function _editName() {
			var tr = _datatable.row($(this).parents('tr'));
			var td = _datatable.cell($(this).parents('td'));
			var rowData = tr.data();
			$.confirm({
				'title': '修改角色名称',
				'html': `<form><div class="form-group"><input type="text" class="form-control" name="" value="${rowData.name}" maxlength="10" /></div></form>`,
				'callback': function(dlg){
					var name = $('input', dlg).val();
					if(name == '') {
						$.alert('名称不能为空！');
						return false; //阻塞弹出框关闭
					} else if(name == rowData.name) {
						$.alert('没有变化！');
						return false;
					} else {
						$.post('/admin/system_role/updateName', {'id': rowData.id, 'name': name}, function(resp){
							if(!resp) {
								console.debug(resp);
								$.alert('请求服务器失败，请查看控制台信息！');
							} else if(resp.code == 0) {
								td.data(name).draw();
							} else {
								$.alert({'title': '出错了', 'msg': resp.msg, 'style': 'danger'});
							}
						}, 'json');
					}
				}
			});
		}
		//删除事件函数
		function _delete() {
			var tr = _datatable.row($(this).parents('tr'));
			var rowData = tr.data();
			$.confirm({
				'msg': '确定要删除该角色吗？',
				'style': 'danger',
				'callback': function(){
					$.post('/admin/system_role/del', {'id': rowData.id}, function(resp){
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
					$.post('/admin/system_role/disable', {'id': rowData.id}, function(resp){
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
			$.post('/admin/system_role/enable', {'id': rowData.id}, function(resp){
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
				'url': '/admin/system_role/list',
				'type': 'GET'
			},
			'language': {
				'url': $RS_URI + '/adminlte/bower_components/datatables.net-bs/lang/Chinese.json'
			},
			'columns': [
				{'data': 'id'},
				{
					'data': 'name',
					'render': function(data, type, row, meta) {
						if(type == 'display') {
							var str = data;
							str += ' <a href="#" class="fa fa-edit text-blue edit_name"></a>';
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
						if(type == 'display') {
							var str;
							var status = parseInt(data);
							if (status) {
								str = '<a href="#" class="btn btn-xs btn-success">已启用</a>';
							} else {
								str = '<a href="#" class="btn btn-xs btn-warning">已禁用</a>';
							}
							str += ' <a href="#" class="btn btn-xs btn-danger">删除</a>';
							return str;
						}
						return data;
					}
				}
			],
			'drawCallback': function(){
				$('a.btn', _element).off('click');
				$('a.edit_name', _element).on('click', _editName);
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
	$('#roleform').submit(function(){
		if (this.rolename.value == '') {
			$.alert({'msg': '角色名不能为空!', 'style': 'danger'});
		} else {
			RolesTable.add(this.rolename.value);
		}
		return false;
	});
});