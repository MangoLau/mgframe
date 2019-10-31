(function($){
	function __dialog(opt){
		if (Object.prototype.toString.call(opt) == '[object String]') {
			opt = {'msg': opt};
		} else if (Object.prototype.toString.call(opt) != '[object Object]') {
			console.debug("[opt] must be a object!");
			return false;
		}

		var jqe;
		var defaults = {
			'title': '提示',
			'msg': '',
			'html': '',
			'style': 'default',
			'size': 2,
			'withCancel': false,
			'init': null,
			'callback': function(dlg){ jqe.modal('hide'); }
		};
		opt = $.extend(defaults, opt);

		var sizeClass = opt.size == 1 ? 'modal-sm' : (opt.size == 3 ? 'modal-lg' : '');
		var sizeStyle = opt.size == 4 ? 'width:1200px;' : (opt.size == 5 ? 'width:1400px;' : '');
		var btnStyle = opt.style == 'default' ? '' : 'btn-outline';
		var ID = 'dialog_' + parseInt(Math.random() * 100000000);
		if (opt.html == '') {
			opt.html = '<p>' + opt.msg + '</p>';
		}
		var cancelBtn = '';
		if (opt.withCancel) {
			cancelBtn = `<button type="button" class="btn ${btnStyle} btn-default pull-left" data-dismiss="modal" aria-label="Close">取消</button>`;
		}
		var html = `<div class="modal modal-${opt.style} fade" id="${ID}">
	          <div class="modal-dialog ${sizeClass}" style="${sizeStyle}">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">${opt.title}</h4>
              </div>
              <div class="modal-body">${opt.html}</div>
              <div class="modal-footer">
                ${cancelBtn}
                <button type="button" class="btn ${btnStyle} btn-primary">确定</button>
              </div>
            </div>
          </div>
        </div>`;
        jqe = $(html);
        $('body').append(jqe);
        function __on_show(){
        	if (Object.prototype.toString.call(opt.init) == '[object Function]') {
        		opt.init(jqe);
        	}
        }
        function __on_hide(){
        	jqe.remove();
	    }
        jqe.on('show.bs.modal', __on_show);
        jqe.on('hide.bs.modal', __on_hide)
        jqe.modal('show');
        if (Object.prototype.toString.call(opt.callback) == '[object Function]') {
	        $('.modal-footer button.btn-primary', jqe).click(function(){
	        	var flag = opt.callback.call(this, jqe);
	        	if(flag !== false) {
	        		jqe.modal('hide');
	        	}
	        });
        }
        return jqe; //可以将返回值赋值给一个变量后通过 .modal('hide') 方法进行关闭弹框
	}

	$.extend($, {
		/**
		 * 提示框
		 * @param object opt 配置选项，字段列表:
		 * 	string msg - 提示消息
		 *  string html - HTML格式的内容，与msg二选一即可
		 * 	string title - 标题
		 * 	string style - 提示框样式，可选值"default"(默认),"success","danger","info","primary"
		 * 	int size - 提示框大小，可选值1,2(默认),3
		 *  function init - 弹出框展示之后的回调函数
		 * 	function callback - 点击确定后的回调函数
		 */
		'alert': __dialog,

		/**
		 * 确认框
		 * @param object opt 配置选项，字段列表:
		 * 	string msg - 提示消息
		 *  string html - HTML格式的内容，与msg二选一即可
		 * 	string title - 标题
		 * 	string style - 提示框样式，可选值"default"(默认),"success","danger","info","primary"
		 * 	int size - 提示框大小，可选值1,2(默认),3
		 *  function init - 弹出框展示之后的回调函数
		 * 	function callback - 点击确定后的回调函数
		 */
		'confirm': function(opt) {
			if (Object.prototype.toString.call(opt) != '[object Object]') {
				console.debug("[opt] must be a object!");
				return;
			}
			opt.withCancel = true;
			return __dialog(opt);
		}
	});

})($);

function formatfloat(number, fix) {
	fix = fix || 2;
	return parseFloat(number).toFixed(fix);
}

function time2str(timestamp, cnFormat) {
	var obj = new Date(timestamp * 1000);
	if(cnFormat) {
		var Y = obj.getFullYear(),
			m = obj.getMonth() + 1,
			d = obj.getDate(),
			H = obj.getHours(),
			i = obj.getMinutes(),
			s = obj.getSeconds();
		return Y
			+ '-' + (m < 10 ? '0'+m : m)
			+ '-' + (d < 10 ? '0'+d : d)
			+ ' ' + (H < 10 ? '0'+H : H)
			+ ':' + (i < 10 ? '0'+i : i)
			+ ':' + (s < 10 ? '0'+s : s);
	} else {
		return obj.toLocaleString();
	}
}

function size2str(size) {
	if(size < 1024) {
		return size + '字节';
	} else if(size < 1024*1024) {
		return formatfloat(size / 1024, 2) + 'K';
	} else {
		return formatfloat(size / 1024 / 1024, 2) + 'M';
	}
}

function numberFormat(n, len) {
	n = parseInt(n);
	len = len == 4 ? 4 : 3;
	if(n == NaN) {
		return 0;
	}
	var sign = n < 0 ? '-' : '';
	var str = '' + Math.abs(n), ret = '';
	var mod = str.length % len;
	if(mod > 0) {
		ret += str.substr(0, mod) + ',';
	}
	for(var i=mod; i<str.length; i+=len) {
		ret += str.substr(i, len) + ',';
	}
	return sign + ret.substr(0, ret.length - 1);
}

function numberRound(n, len) {
	n = parseFloat(n);
	len = len || 2;
	if(n == NaN || len < 1) {
		return 0;
	}
	var x = Math.pow(10, len);
	var str = '' + Math.round(n * x) / x;
	var pos = str.indexOf('.');
	var declen = pos == -1 ? 0 : str.length - pos - 1;
	if (declen == len) {
		return str;
	}
	if (pos == -1) {
		str += '.';
	}
	for (var i=declen; i<len; i++) {
		str += '0';
	}
	return str;
}