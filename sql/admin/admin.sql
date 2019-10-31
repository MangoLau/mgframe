CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '',
  `passwd` varchar(64) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `last_ip` varchar(15) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`);
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `account`, `passwd`, `role_id`, `name`, `avatar`, `status`, `last_ip`, `last_login_time`, `create_time`, `update_time`) VALUES
(1, 'MangoLau', '$2y$10$UkSWr4.Rb3FaVE.J/mZyLORB6SC7EhWxF0P3/8sEmPL0ImoI8yWhS', NULL, '刘宏威', NULL, 1, '', '2019-10-31 16:18:45', '2019-07-02 14:15:18', '2019-10-31 16:18:25');
