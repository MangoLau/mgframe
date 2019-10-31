/**
 * 使用方法
 * var cfg = {
 * 	'keys': [
 * 		'web_application', //网页程序
 * 		'hand', //手势
 * 		'transportation', //交通工具
 * 		'gender', //性别
 * 		'file_type', //文件类型
 * 		'spinner', //旋转
 * 		'form_control', //表单控件
 * 		'payment', //支付方式
 * 		'chart', //图表
 * 		'currency', //货币单位
 * 		'text_editor', //文本编辑器
 * 		'directional', //方向
 * 		'video_player', //视频播放
 * 		'brand', //品牌
 * 		'medical', //医疗
 * 		'glyphicon' //象形文字
 * 	],
 * 
 * 	//参数 el 是输入框的dom对象
 * 	//参数 icon 是点击的图标 class 值，如: "fa fa-gear"
 * 	'callback': function(el, icon){
 * 	}
 * };
 * $('#selector').iconSelector(cfg);
 */
(function($){
	var Icons = {
	    'web_application': {
	    	'name': '网页程序',
	    	'prefix': 'fa',
	    	'icons': ['fa-adjust','fa-anchor','fa-archive','fa-area-chart','fa-arrows','fa-arrows-h','fa-arrows-v','fa-asterisk','fa-at','fa-automobile','fa-balance-scale','fa-ban','fa-bank','fa-bar-chart','fa-bar-chart-o','fa-barcode','fa-bars','fa-battery-0','fa-battery-1','fa-battery-2','fa-battery-3','fa-battery-4','fa-battery-empty','fa-battery-full','fa-battery-half','fa-battery-quarter','fa-battery-three-quarters','fa-bed','fa-beer','fa-bell','fa-bell-o','fa-bell-slash','fa-bell-slash-o','fa-bicycle','fa-binoculars','fa-birthday-cake','fa-bolt','fa-bomb','fa-book','fa-bookmark','fa-bookmark-o','fa-briefcase','fa-bug','fa-building','fa-building-o','fa-bullhorn','fa-bullseye','fa-bus','fa-cab','fa-calculator','fa-calendar','fa-calendar-check-o','fa-calendar-minus-o','fa-calendar-o','fa-calendar-plus-o','fa-calendar-times-o','fa-camera','fa-camera-retro','fa-car','fa-caret-square-o-down','fa-caret-square-o-left','fa-caret-square-o-right','fa-caret-square-o-up','fa-cart-arrow-down','fa-cart-plus','fa-cc','fa-certificate','fa-check','fa-check-circle','fa-check-circle-o','fa-check-square','fa-check-square-o','fa-child','fa-circle','fa-circle-o','fa-circle-o-notch','fa-circle-thin','fa-clock-o','fa-clone','fa-close','fa-cloud','fa-cloud-download','fa-cloud-upload','fa-code','fa-code-fork','fa-coffee','fa-cog','fa-cogs','fa-comment','fa-comment-o','fa-commenting','fa-commenting-o','fa-comments','fa-comments-o','fa-compass','fa-copyright','fa-creative-commons','fa-credit-card','fa-crop','fa-crosshairs','fa-cube','fa-cubes','fa-cutlery','fa-dashboard','fa-database','fa-desktop','fa-diamond','fa-dot-circle-o','fa-download','fa-edit','fa-ellipsis-h','fa-ellipsis-v','fa-envelope','fa-envelope-o','fa-envelope-square','fa-eraser','fa-exchange','fa-exclamation','fa-exclamation-circle','fa-exclamation-triangle','fa-external-link','fa-external-link-square','fa-eye','fa-eye-slash','fa-eyedropper','fa-fax','fa-feed','fa-female','fa-fighter-jet','fa-file-archive-o','fa-file-audio-o','fa-file-code-o','fa-file-excel-o','fa-file-image-o','fa-file-movie-o','fa-file-pdf-o','fa-file-photo-o','fa-file-picture-o','fa-file-powerpoint-o','fa-file-sound-o','fa-file-video-o','fa-file-word-o','fa-file-zip-o','fa-film','fa-filter','fa-fire','fa-fire-extinguisher','fa-flag','fa-flag-checkered','fa-flag-o','fa-flash','fa-flask','fa-folder','fa-folder-o','fa-folder-open','fa-folder-open-o','fa-frown-o','fa-futbol-o','fa-gamepad','fa-gavel','fa-gear','fa-gears','fa-gift','fa-glass','fa-globe','fa-graduation-cap','fa-group','fa-hand-grab-o','fa-hand-lizard-o','fa-hand-paper-o','fa-hand-peace-o','fa-hand-pointer-o','fa-hand-rock-o','fa-hand-scissors-o','fa-hand-spock-o','fa-hand-stop-o','fa-hdd-o','fa-headphones','fa-heart','fa-heart-o','fa-heartbeat','fa-history','fa-home','fa-hotel','fa-hourglass','fa-hourglass-1','fa-hourglass-2','fa-hourglass-3','fa-hourglass-end','fa-hourglass-half','fa-hourglass-o','fa-hourglass-start','fa-i-cursor','fa-image','fa-inbox','fa-industry','fa-info','fa-info-circle','fa-institution','fa-key','fa-keyboard-o','fa-language','fa-laptop','fa-leaf','fa-legal','fa-lemon-o','fa-level-down','fa-level-up','fa-life-bouy','fa-life-buoy','fa-life-ring','fa-life-saver','fa-lightbulb-o','fa-line-chart','fa-location-arrow','fa-lock','fa-magic','fa-magnet','fa-mail-forward','fa-mail-reply','fa-mail-reply-all','fa-male','fa-map','fa-map-marker','fa-map-o','fa-map-pin','fa-map-signs','fa-meh-o','fa-microphone','fa-microphone-slash','fa-minus','fa-minus-circle','fa-minus-square','fa-minus-square-o','fa-mobile','fa-mobile-phone','fa-money','fa-moon-o','fa-mortar-board','fa-motorcycle','fa-mouse-pointer','fa-music','fa-navicon','fa-newspaper-o','fa-object-group','fa-object-ungroup','fa-paint-brush','fa-paper-plane','fa-paper-plane-o','fa-paw','fa-pencil','fa-pencil-square','fa-pencil-square-o','fa-phone','fa-phone-square','fa-photo','fa-picture-o','fa-pie-chart','fa-plane','fa-plug','fa-plus','fa-plus-circle','fa-plus-square','fa-plus-square-o','fa-power-off','fa-print','fa-puzzle-piece','fa-qrcode','fa-question','fa-question-circle','fa-quote-left','fa-quote-right','fa-random','fa-recycle','fa-refresh','fa-registered','fa-remove','fa-reorder','fa-reply','fa-reply-all','fa-retweet','fa-road','fa-rocket','fa-rss','fa-rss-square','fa-search','fa-search-minus','fa-search-plus','fa-send','fa-send-o','fa-server','fa-share','fa-share-alt','fa-share-alt-square','fa-share-square','fa-share-square-o','fa-shield','fa-ship','fa-shopping-cart','fa-sign-in','fa-sign-out','fa-signal','fa-sitemap','fa-sliders','fa-smile-o','fa-soccer-ball-o','fa-sort','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-asc','fa-sort-desc','fa-sort-down','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-sort-up','fa-space-shuttle','fa-spinner','fa-spoon','fa-square','fa-square-o','fa-star','fa-star-half','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-star-o','fa-sticky-note','fa-sticky-note-o','fa-street-view','fa-suitcase','fa-sun-o','fa-support','fa-tablet','fa-tachometer','fa-tag','fa-tags','fa-tasks','fa-taxi','fa-television','fa-terminal','fa-thumb-tack','fa-thumbs-down','fa-thumbs-o-down','fa-thumbs-o-up','fa-thumbs-up','fa-ticket','fa-times','fa-times-circle','fa-times-circle-o','fa-tint','fa-toggle-down','fa-toggle-left','fa-toggle-off','fa-toggle-on','fa-toggle-right','fa-toggle-up','fa-trademark','fa-trash','fa-trash-o','fa-tree','fa-trophy','fa-truck','fa-tty','fa-tv','fa-umbrella','fa-university','fa-unlock','fa-unlock-alt','fa-unsorted','fa-upload','fa-user','fa-user-plus','fa-user-secret','fa-user-times','fa-users','fa-video-camera','fa-volume-down','fa-volume-off','fa-volume-up','fa-warning','fa-wheelchair','fa-wifi','fa-wrench']
	    },
	    'hand': {
	    	'name': '手势',
	    	'prefix': 'fa',
	    	'icons': ['fa-hand-grab-o','fa-hand-lizard-o','fa-hand-o-down','fa-hand-o-left','fa-hand-o-right','fa-hand-o-up','fa-hand-paper-o','fa-hand-peace-o','fa-hand-pointer-o','fa-hand-rock-o','fa-hand-scissors-o','fa-hand-spock-o','fa-hand-stop-o','fa-thumbs-down','fa-thumbs-o-down','fa-thumbs-o-up','fa-thumbs-up']
	    },
	    'transportation': {
	    	'name': '交通工具',
	    	'prefix': 'fa',
	    	'icons': ['fa-ambulance','fa-automobile','fa-bicycle','fa-bus','fa-cab','fa-car','fa-fighter-jet','fa-motorcycle','fa-plane','fa-rocket','fa-ship','fa-space-shuttle','fa-subway','fa-taxi','fa-train','fa-truck','fa-wheelchair']
	    },
	    'gender': {
	    	'name': '性别',
	    	'prefix': 'fa',
	    	'icons': ['fa-genderless','fa-intersex','fa-mars','fa-mars-double','fa-mars-stroke','fa-mars-stroke-h','fa-mars-stroke-v','fa-mercury','fa-neuter','fa-transgender','fa-transgender-alt','fa-venus','fa-venus-double','fa-venus-mars']
	    },
	    'file_type': {
	    	'name': '文件类型',
	    	'prefix': 'fa',
	    	'icons': ['fa-file','fa-file-archive-o','fa-file-audio-o','fa-file-code-o','fa-file-excel-o','fa-file-image-o','fa-file-movie-o','fa-file-o','fa-file-pdf-o','fa-file-photo-o','fa-file-picture-o','fa-file-powerpoint-o','fa-file-sound-o','fa-file-text','fa-file-text-o','fa-file-video-o','fa-file-word-o','fa-file-zip-o']
	    },
	    'spinner': {
	    	'name': '旋转',
	    	'prefix': 'fa',
	    	'icons': ['fa-circle-o-notch','fa-cog','fa-gear','fa-refresh','fa-spinner']
	    },
	    'form_control': {
	    	'name': '表单控件',
	    	'prefix': 'fa',
	    	'icons': ['fa-check-square','fa-check-square-o','fa-circle','fa-circle-o','fa-dot-circle-o','fa-minus-square','fa-minus-square-o','fa-plus-square','fa-plus-square-o','fa-square','fa-square-o']
	    },
	    'payment': {
	    	'name': '支付方式',
	    	'prefix': 'fa',
	    	'icons': ['fa-cc-amex','fa-cc-diners-club','fa-cc-discover','fa-cc-jcb','fa-cc-mastercard','fa-cc-paypal','fa-cc-stripe','fa-cc-visa','fa-credit-card','fa-google-wallet','fa-paypal']
	    },
	    'chart': {
	    	'name': '图表',
	    	'prefix': 'fa',
	    	'icons': ['fa-area-chart','fa-bar-chart','fa-bar-chart-o','fa-line-chart','fa-pie-chart']
	    },
	    'currency': {
	    	'name': '货币单位',
	    	'prefix': 'fa',
	    	'icons': ['fa-bitcoin','fa-btc','fa-cny','fa-dollar','fa-eur','fa-euro','fa-gbp','fa-gg','fa-gg-circle','fa-ils','fa-inr','fa-jpy','fa-krw','fa-money','fa-rmb','fa-rouble','fa-rub','fa-ruble','fa-rupee','fa-shekel','fa-sheqel','fa-try','fa-turkish-lira','fa-usd','fa-won','fa-yen ']
	    },
	    'text_editor': {
	    	'name': '文本编辑器',
	    	'prefix': 'fa',
	    	'icons': ['fa-align-center','fa-align-justify','fa-align-left','fa-align-right','fa-bold','fa-chain','fa-chain-broken','fa-clipboard','fa-columns','fa-copy','fa-cut','fa-dedent','fa-eraser','fa-file','fa-file-o','fa-file-text','fa-file-text-o','fa-files-o','fa-floppy-o','fa-font','fa-header','fa-indent','fa-italic','fa-link','fa-list','fa-list-alt','fa-list-ol','fa-list-ul','fa-outdent','fa-paperclip','fa-paragraph','fa-paste','fa-repeat','fa-rotate-left','fa-rotate-right','fa-save','fa-scissors','fa-strikethrough','fa-subscript','fa-superscript','fa-table','fa-text-height','fa-text-width','fa-th','fa-th-large','fa-th-list','fa-underline','fa-undo','fa-unlink']
	    },
	    'directional': {
	    	'name': '方向',
	    	'prefix': 'fa',
	    	'icons': ['fa-angle-double-down','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-arrow-circle-down','fa-arrow-circle-left','fa-arrow-circle-o-down','fa-arrow-circle-o-left','fa-arrow-circle-o-right','fa-arrow-circle-o-up','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-down','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrows','fa-arrows-alt','fa-arrows-h','fa-arrows-v','fa-caret-down','fa-caret-left','fa-caret-right','fa-caret-square-o-down','fa-caret-square-o-left','fa-caret-square-o-right','fa-caret-square-o-up','fa-caret-up','fa-chevron-circle-down','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-down','fa-chevron-left','fa-chevron-right','fa-chevron-up','fa-exchange','fa-hand-o-down','fa-hand-o-left','fa-hand-o-right','fa-hand-o-up','fa-long-arrow-down','fa-long-arrow-left','fa-long-arrow-right','fa-long-arrow-up','fa-toggle-down','fa-toggle-left','fa-toggle-right','fa-toggle-up']
	    },
	    'video_player': {
	    	'name': '视频播放',
	    	'prefix': 'fa',
	    	'icons': ['fa-arrows-alt','fa-backward','fa-compress','fa-eject','fa-expand','fa-fast-backward','fa-fast-forward','fa-forward','fa-pause','fa-play','fa-play-circle','fa-play-circle-o','fa-random','fa-step-backward','fa-step-forward','fa-stop','fa-youtube-play']
	    },
	    'brand': {
	    	'name': '品牌',
	    	'prefix': 'fa',
	    	'icons': ['fa-500px','fa-adn','fa-amazon','fa-android','fa-angellist','fa-apple','fa-behance','fa-behance-square','fa-bitbucket','fa-bitbucket-square','fa-bitcoin','fa-black-tie','fa-btc','fa-buysellads','fa-cc-amex','fa-cc-diners-club','fa-cc-discover','fa-cc-jcb','fa-cc-mastercard','fa-cc-paypal','fa-cc-stripe','fa-cc-visa','fa-chrome','fa-codepen','fa-connectdevelop','fa-contao','fa-css3','fa-dashcube','fa-delicious','fa-deviantart','fa-digg','fa-dribbble','fa-dropbox','fa-drupal','fa-empire','fa-expeditedssl','fa-facebook','fa-facebook-f','fa-facebook-official','fa-facebook-square','fa-firefox','fa-flickr','fa-fonticons','fa-forumbee','fa-foursquare','fa-ge','fa-get-pocket','fa-gg','fa-gg-circle','fa-git','fa-git-square','fa-github','fa-github-alt','fa-github-square','fa-gittip','fa-google','fa-google-plus','fa-google-plus-square','fa-google-wallet','fa-gratipay','fa-hacker-news','fa-houzz','fa-html5','fa-instagram','fa-internet-explorer','fa-ioxhost','fa-joomla','fa-jsfiddle','fa-lastfm','fa-lastfm-square','fa-leanpub','fa-linkedin','fa-linkedin-square','fa-linux','fa-maxcdn','fa-meanpath','fa-medium','fa-odnoklassniki','fa-odnoklassniki-square','fa-opencart','fa-openid','fa-opera','fa-optin-monster','fa-pagelines','fa-paypal','fa-pied-piper','fa-pied-piper-alt','fa-pinterest','fa-pinterest-p','fa-pinterest-square','fa-qq','fa-ra','fa-rebel','fa-reddit','fa-reddit-square','fa-renren','fa-safari','fa-sellsy','fa-share-alt','fa-share-alt-square','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-skype','fa-slack','fa-slideshare','fa-soundcloud','fa-spotify','fa-stack-exchange','fa-stack-overflow','fa-steam','fa-steam-square','fa-stumbleupon','fa-stumbleupon-circle','fa-tencent-weibo','fa-trello','fa-tripadvisor','fa-tumblr','fa-tumblr-square','fa-twitch','fa-twitter','fa-twitter-square','fa-viacoin','fa-vimeo','fa-vimeo-square','fa-vine','fa-vk','fa-wechat','fa-weibo','fa-weixin','fa-whatsapp','fa-wikipedia-w','fa-windows','fa-wordpress','fa-xing','fa-xing-square','fa-y-combinator','fa-y-combinator-square','fa-yahoo','fa-yc','fa-yc-square','fa-yelp','fa-youtube','fa-youtube-play','fa-youtube-square']
	    },
	    'medical': {
	    	'name': '医疗',
	    	'prefix': 'fa',
	    	'icons': ['fa-ambulance','fa-h-square','fa-heart','fa-heart-o','fa-heartbeat','fa-hospital-o','fa-medkit','fa-plus-square','fa-stethoscope','fa-user-md','fa-wheelchair']
	    },
	    'glyphicon': {
	    	'name': '象形文字',
	    	'prefix': 'glyphicon',
	    	'icons': ['glyphicon-asterisk','glyphicon-plus','glyphicon-euro','glyphicon-eur','glyphicon-minus','glyphicon-cloud','glyphicon-envelope','glyphicon-pencil','glyphicon-glass','glyphicon-music','glyphicon-search','glyphicon-heart','glyphicon-star','glyphicon-star-empty','glyphicon-user','glyphicon-film','glyphicon-th-large','glyphicon-th','glyphicon-th-list','glyphicon-ok','glyphicon-remove','glyphicon-zoom-in','glyphicon-zoom-out','glyphicon-off','glyphicon-signal','glyphicon-cog','glyphicon-trash','glyphicon-home','glyphicon-file','glyphicon-time','glyphicon-road','glyphicon-download-alt','glyphicon-download','glyphicon-upload','glyphicon-inbox','glyphicon-play-circle','glyphicon-repeat','glyphicon-refresh','glyphicon-list-alt','glyphicon-lock','glyphicon-flag','glyphicon-headphones','glyphicon-volume-off','glyphicon-volume-down','glyphicon-volume-up','glyphicon-qrcode','glyphicon-barcode','glyphicon-tag','glyphicon-tags','glyphicon-book','glyphicon-bookmark','glyphicon-print','glyphicon-camera','glyphicon-font','glyphicon-bold','glyphicon-italic','glyphicon-text-height','glyphicon-text-width','glyphicon-align-left','glyphicon-align-center','glyphicon-align-right','glyphicon-align-justify','glyphicon-list','glyphicon-indent-left','glyphicon-indent-right','glyphicon-facetime-video','glyphicon-picture','glyphicon-map-marker','glyphicon-adjust','glyphicon-tint','glyphicon-edit','glyphicon-share','glyphicon-check','glyphicon-move','glyphicon-step-backward','glyphicon-fast-backward','glyphicon-backward','glyphicon-play','glyphicon-pause','glyphicon-stop','glyphicon-forward','glyphicon-fast-forward','glyphicon-step-forward','glyphicon-eject','glyphicon-chevron-left','glyphicon-chevron-right','glyphicon-plus-sign','glyphicon-minus-sign','glyphicon-remove-sign','glyphicon-ok-sign','glyphicon-question-sign','glyphicon-info-sign','glyphicon-screenshot','glyphicon-remove-circle','glyphicon-ok-circle','glyphicon-ban-circle','glyphicon-arrow-left','glyphicon-arrow-right','glyphicon-arrow-up','glyphicon-arrow-down','glyphicon-share-alt','glyphicon-resize-full','glyphicon-resize-small','glyphicon-exclamation-sign','glyphicon-gift','glyphicon-leaf','glyphicon-fire','glyphicon-eye-open','glyphicon-eye-close','glyphicon-warning-sign','glyphicon-plane','glyphicon-calendar','glyphicon-random','glyphicon-comment','glyphicon-magnet','glyphicon-chevron-up','glyphicon-chevron-down','glyphicon-retweet','glyphicon-shopping-cart','glyphicon-folder-close','glyphicon-folder-open','glyphicon-resize-vertical','glyphicon-resize-horizontal','glyphicon-hdd','glyphicon-bullhorn','glyphicon-bell','glyphicon-certificate','glyphicon-thumbs-up','glyphicon-thumbs-down','glyphicon-hand-right','glyphicon-hand-left','glyphicon-hand-up','glyphicon-hand-down','glyphicon-circle-arrow-right','glyphicon-circle-arrow-left','glyphicon-circle-arrow-up','glyphicon-circle-arrow-down','glyphicon-globe','glyphicon-wrench','glyphicon-tasks','glyphicon-filter','glyphicon-briefcase','glyphicon-fullscreen','glyphicon-dashboard','glyphicon-paperclip','glyphicon-heart-empty','glyphicon-link','glyphicon-phone','glyphicon-pushpin','glyphicon-usd','glyphicon-gbp','glyphicon-sort','glyphicon-sort-by-alphabet','glyphicon-sort-by-alphabet-alt','glyphicon-sort-by-order','glyphicon-sort-by-order-alt','glyphicon-sort-by-attributes','glyphicon-sort-by-attributes-alt','glyphicon-unchecked','glyphicon-expand','glyphicon-collapse-down','glyphicon-collapse-up','glyphicon-log-in','glyphicon-flash','glyphicon-log-out','glyphicon-new-window','glyphicon-record','glyphicon-save','glyphicon-open','glyphicon-saved','glyphicon-import','glyphicon-export','glyphicon-send','glyphicon-floppy-disk','glyphicon-floppy-saved','glyphicon-floppy-remove','glyphicon-floppy-save','glyphicon-floppy-open','glyphicon-credit-card','glyphicon-transfer','glyphicon-cutlery','glyphicon-header','glyphicon-compressed','glyphicon-earphone','glyphicon-phone-alt','glyphicon-tower','glyphicon-stats','glyphicon-sd-video','glyphicon-hd-video','glyphicon-subtitles','glyphicon-sound-stereo','glyphicon-sound-dolby','glyphicon-sound-5-1','glyphicon-sound-6-1','glyphicon-sound-7-1','glyphicon-copyright-mark','glyphicon-registration-mark','glyphicon-cloud-download','glyphicon-cloud-upload','glyphicon-tree-conifer','glyphicon-tree-deciduous','glyphicon-cd','glyphicon-save-file','glyphicon-open-file','glyphicon-level-up','glyphicon-copy','glyphicon-paste','glyphicon-alert','glyphicon-equalizer','glyphicon-king','glyphicon-queen','glyphicon-pawn','glyphicon-bishop','glyphicon-knight','glyphicon-baby-formula','glyphicon-tent','glyphicon-blackboard','glyphicon-bed','glyphicon-apple','glyphicon-erase','glyphicon-hourglass','glyphicon-lamp','glyphicon-duplicate','glyphicon-piggy-bank','glyphicon-scissors','glyphicon-bitcoin','glyphicon-btc','glyphicon-xbt','glyphicon-yen','glyphicon-jpy','glyphicon-ruble','glyphicon-rub','glyphicon-scale','glyphicon-ice-lolly','glyphicon-ice-lolly-tasted','glyphicon-education','glyphicon-option-horizontal','glyphicon-option-vertical','glyphicon-menu-hamburger','glyphicon-modal-window','glyphicon-oil','glyphicon-grain','glyphicon-sunglasses','glyphicon-text-size','glyphicon-text-color','glyphicon-text-background','glyphicon-object-align-top','glyphicon-object-align-bottom','glyphicon-object-align-horizontal','glyphicon-object-align-left','glyphicon-object-align-vertical','glyphicon-object-align-right','glyphicon-triangle-right','glyphicon-triangle-left','glyphicon-triangle-bottom','glyphicon-triangle-top','glyphicon-console','glyphicon-superscript','glyphicon-subscript','glyphicon-menu-left','glyphicon-menu-right','glyphicon-menu-down','glyphicon-menu-up']
	    }
	};

	//打开弹出窗
	function _open_dialog(el, cfg) {
		var defaults = {
			'keys': [],
			'callback': function(ipt, val){
				if (ipt && typeof ipt.value != 'undefined') {
					ipt.value = val;
				}
			}
		};
		for (var k in Icons) {
			defaults.keys.push(k);
		}
		if (cfg) {
			cfg = $.extend(defaults, cfg);
		} else {
			cfg = defaults;
		}
		console.debug(cfg);
		var tabs = '';
		var contents = '';
		var actived = false;
		for (var n=0; n<cfg.keys.length; n++) {
			var k = cfg.keys[n];
			if (!Icons[k]) {
				continue;
			}
			var group = Icons[k];
			tabs += '<li class="' + (actived ? '' : 'active') + '"><a href="#icons_' + k + '" data-toggle="tab" aria-expanded="' + (actived ? 'false' : 'true') + '">' + group.name + '</a></li>';
			contents += '<div class="tab-pane ' + (actived ? '' : 'active') + '" id="icons_' + k + '"><div class="row">';
			for (var i=0; i<group.icons.length; i++) {
				contents += '<div class="col-md-2"><a href="#" class="btn btn-app" title="' + group.icons[i] + '"><i class="' + group.prefix + ' ' + group.icons[i] + '"></i>' + (group.icons[i].length <= 10 ? group.icons[i] : group.icons[i].substr(0, 7) + '...') + '</a></div>';
			}
			contents += '</div></div>';
			actived = true;
		}
		$.confirm({
			'title': '图标列表',
			'html': '<div class="nav-tabs-custom"><ul class="nav nav-tabs">' + tabs + '</ul><div class="tab-content">' + contents + '</div></div>',
			'size': 3,
			'init': function(dlg){
				$('a.btn-app', dlg).click(function(){
					var icon = $('i', this).attr('class');
					if (icon != '') {
						cfg.callback(el, icon);
						dlg.modal('hide');
					}
					return false;
				});
			}
		});
	}

	//扩展方法
	$.fn.iconSelector = function(cfg){
		this.each(function(){
			$(this).click(function(){
				_open_dialog(this, cfg);
			});
		});
	};
})(window.jQuery);