CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '角色名',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`, `status`, `create_time`, `update_time`) VALUES
(1, '超管', 1, '2019-10-31 17:31:01', '2019-10-31 17:31:01');