DROP TABLE IF EXISTS `vsftpd_user`;

CREATE TABLE `vsftpd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `setting` text NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `vsftpd_user` (`username`, `password`, `name`, `setting`) values ('choateyao', '123456', '', '{"download_enable":0,"write_enable":1,"local_root":"/data/data1/video","cmds_denied":""}');