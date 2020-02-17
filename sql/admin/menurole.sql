CREATE TABLE `menurole` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `menuid` int(10) UNSIGNED NOT NULL COMMENT '后台菜单模块ID',
  `roleid` int(10) UNSIGNED NOT NULL COMMENT '可访问角色ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `menuid` (`menuid`,`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='后台菜单模块访问角色配置表';

--
-- 转存表中的数据 `menurole`
--

INSERT INTO `menurole` (`id`, `menuid`, `roleid`) VALUES
(1, 1, 1);