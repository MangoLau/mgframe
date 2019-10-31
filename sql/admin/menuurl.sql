CREATE TABLE `menuurl` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `menuid` int(10) UNSIGNED NOT NULL COMMENT '菜单模块ID',
  `url` varchar(50) NOT NULL DEFAULT '' COMMENT 'URL地址',
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menuid` (`menuid`,`url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='后台菜单模块URL列表';

--
-- 转存表中的数据 `menuurl`
--

INSERT INTO `menuurl` (`id`, `menuid`, `url`) VALUES
(1, 1, '/admin/index/index');