/**
 * 密码修改页面的js
 */
$(function(){
	$('#modifyPwdForm').submit(function(){
		$.post({
			"url":"/admin/admin/modifypasswd",
			"data":$(this).serialize(),
			"success": function(json) {
				if(json.code === 0) {
					$.alert('操作成功 !');
					return false;
				}
				$.alert({'msg': json.msg, 'style': 'danger'});
			}
		});	
		return false;
	});
});