CREATE TABLE `crontab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `task` varchar(100) NOT NULL COMMENT '任务',
  `args` varchar(2048) NOT NULL DEFAULT '' COMMENT '参数',
  `start_minute` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '每天开始时间(从0点开始的分钟数)',
  `interval_minutes` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '执行的间隔(分钟)',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否被禁用',
  `note` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `idx_start_minute` (`start_minute`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='计划任务表';