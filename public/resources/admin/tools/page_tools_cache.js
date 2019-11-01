$(function(){
	
	var Cache = {};
	
	function show_dbs() {
		var k = $(this).attr('data-value');
		if (!k) {
			return false;
		}
		if (Cache[k]) {
			var html = '<table class="table table-bordered" style=""><thead><tr><th>DB序号</th><th>使用情况</th></tr></thead><tbody>';
			var dbs = parseInt(Cache[k].databases);
			if (dbs > 0) {
				for (var i=0; i<dbs; i++) {
					var kk = 'db' + i;
					var desc = Cache[k][kk] ? Cache[k][kk] : '未使用';
					html += `<tr><td>${i}</td><td>${desc}</td></tr>`;
				}
			}
			html += '</tbody></table>';
			$.alert({
				'title': '[' + k + '] ' + Cache[k].server,
				'html': html,
				'size': 2
			});
		}
		return false;
	}

	function load() {
		$('#loading').show();
		$.post('/admin/tools/cache', {'load': 1}, function(resp){
			$('#loading').hide();
			if (resp.data) {
				var html = '';
				Cache = resp.data;
				for(var k in resp.data) {
					var item = resp.data[k];
					item.process_id = parseInt(item.process_id);
					if (item.process_id) {
						item.redis_version = item.redis_version ? item.redis_version : '未知';
						item.uptime_in_days = parseInt(item.uptime_in_days);
						item.used_cpu_user = parseInt(item.used_cpu_user);
						item.instantaneous_ops_per_sec = parseInt(item.instantaneous_ops_per_sec);
						item.maxmemory = parseInt(item.maxmemory);
						item.used_memory = parseInt(item.used_memory);
						item.expired_keys = item.expired_keys ? parseInt(item.expired_keys) : 0;
						item.evicted_keys = item.evicted_keys ? parseInt(item.evicted_keys) : 0;
						item.connected_clients = parseInt(item.connected_clients);
						item.blocked_clients = item.blocked_clients ? parseInt(item.blocked_clients) : 0;
						item.total_connections_received = parseInt(item.total_connections_received);
						item.total_commands_processed = parseInt(item.total_commands_processed);
						item.rejected_connections = item.rejected_connections ? parseInt(item.rejected_connections) : 0;
						item.keyspace_hits = item.keyspace_hits ? parseInt(item.keyspace_hits) : 0;
						item.keyspace_misses = item.keyspace_misses ? parseInt(item.keyspace_misses) : 0;
						item.databases = item.databases ? parseInt(item.databases) : 0;
						item.db0 = item.db0 && item.db0 != '' ? item.db0 : '未使用';
					} else {
						item.redis_version = '';
						item.uptime_in_days = 0;
						item.used_cpu_user = 0;
						item.instantaneous_ops_per_sec = 0;
						item.maxmemory = 0;
						item.used_memory = 0;
						item.expired_keys = 0;
						item.evicted_keys = 0;
						item.connected_clients = 0;
						item.blocked_clients = 0;
						item.total_connections_received = 0;
						item.total_commands_processed = 0;
						item.rejected_connections = 0;
						item.keyspace_hits = 0;
						item.keyspace_misses = 0;
						item.databases = 0;
						item.db0 = '未使用';
					}
					var process_id_style = item.process_id == 0 ? 'text-red' : '';
					var process_id_desc = item.process_id == 0 ? '未启动' : item.process_id;
					var maxmemory_style = item.maxmemory == 0 || isNaN(item.maxmemory) ? 'text-red' : '';
					var maxmemory_m = item.maxmemory ? formatfloat(item.maxmemory / 1048576, 2) + 'M' : '无限';
					var used_memory_style = item.maxmemory && item.used_memory && item.used_memory / item.maxmemory > 0.8 ? 'text-red' : '';
					var used_memory_m = formatfloat(item.used_memory / 1048576, 2) + 'M';
					var evicted_keys_style = item.evicted_keys > 0 ? 'text-red': '';
					var blocked_clients_style = item.blocked_clients > 0 ? 'text-red': '';
					var rejected_connections_style = item.rejected_connections > 0 ? 'text-red': '';
					html += `
                		<tr>
                			<td class="text-blue">${k}</td>
                			<td class="">${item.server}</td>
                			<td class="${process_id_style} text-right">${process_id_desc}</td>
                			<td class="">${item.redis_version}</td>
                			<td class="text-right">${item.uptime_in_days}天</td>
                			<td class="text-right">${item.used_cpu_user}秒</td>
                			<td class="text-right">${item.instantaneous_ops_per_sec}</td>
                			<td class="text-right ${maxmemory_style}">${maxmemory_m}</td>
                			<td class="text-right ${used_memory_style}">${used_memory_m}</td>
                			<td class="text-right">${item.expired_keys}</td>
                			<td class="text-right ${evicted_keys_style}">${item.evicted_keys}</td>
                			<td class="text-right">${item.connected_clients}</td>
                			<td class="text-right ${blocked_clients_style}">${item.blocked_clients}</td>
                			<td class="text-right">${item.total_connections_received}</td>
                			<td class="text-right">${item.total_commands_processed}</td>
                			<td class="text-right ${rejected_connections_style}">${item.rejected_connections}</td>
                			<td class="text-right">${item.keyspace_hits}</td>
                			<td class="text-right">${item.keyspace_misses}</td>
                			<td class="">${item.db0}</td>
                			<td><a href="#" class="link_dbs" data-value="${k}">查看</a></td>
                		</tr>`;
				}
				$('#list').html(html);
				$('#list a.link_dbs').click(show_dbs);
			}
		}, 'json');
	}
	
	load();
});