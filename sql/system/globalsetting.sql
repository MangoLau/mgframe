CREATE TABLE `globalsetting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `k` varchar(32) COLLATE latin1_general_ci NOT NULL COMMENT '配置的KEY',
  `kk` varchar(128) COLLATE latin1_general_ci NOT NULL DEFAULT '' COMMENT '配置的二级KEY',
  `v` varchar(10240) CHARACTER SET utf8mb4 NOT NULL COMMENT '配置的值',
  `vformat` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '值的格式(1:原始 2:JSON)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `k_kk` (`k`,`kk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='全局配置表';