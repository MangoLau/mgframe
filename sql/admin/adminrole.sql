CREATE TABLE `adminrole` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '管理员账号ID',
  `roleid` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色表';

--
-- 转存表中的数据 `adminrole`
--

INSERT INTO `adminrole` (`id`, `uid`, `roleid`) VALUES
(1, 1, 1);