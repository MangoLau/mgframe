$(function(){
	var mainform = $('#mainform');
	var mainformDom = mainform.get(0);

	mainform.submit(function(){
		var form = this;
		form.output.value = '';
		if (form.code.value == '') {
			$.alert('请输入要执行的代码！');
			return false;
		}
		$('#loading').show();
		$.post('/admin/tools/exec', {
			'code': this.code.value
		}, function(resp){
			$('#loading').hide();
			if (!resp) {
				$.alert({'msg': '操作失败！', 'style': 'danger'});
			} else if (resp.errno) {
				$.alert({'msg': resp.error, 'style': 'danger'});
			} else {
				form.output.value = resp.data.output;
			}
		});
		return false;
	});

	//重启/crontab/daemon进程
	$('#restart_crontab_daemon').click(function(){
		mainformDom.code.value = 'var_dump( ( new XQueueModel() )->notifyDaemonRestart() );';
		return false;
	});

	//重启/async/daemon进程
	$('#restart_async_daemon').click(function(){
		mainformDom.code.value = 'var_dump( touch( ROOT_PATH . "/logs/async.daemon.1.die" ) );';
		return false;
	});
});