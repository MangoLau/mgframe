CREATE TABLE `menus` (
  `menuid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `menu` varchar(20) NOT NULL COMMENT '模块名',
  `index_url` varchar(50) NOT NULL DEFAULT '' COMMENT '首页地址',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级模块ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态 1:正常 0:禁用',
  `sort_no` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序值(越大越靠前)',
  `icon` varchar(40) NOT NULL DEFAULT '' COMMENT '图标',
  PRIMARY KEY (`menuid`),
  KEY `idx_status` (`status`),
  KEY `orderno` (`sort_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单模块列表';

--
-- 转存表中的数据 `menus`
--

INSERT INTO `menus` (`menuid`, `menu`, `index_url`, `parent_id`, `status`, `sort_no`, `icon`) VALUES
(1, '首页', '/admin/index/index', 0, 1, 100, 'fa fa-home');