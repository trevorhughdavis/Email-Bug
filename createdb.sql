delimiter $$

CREATE TABLE `web_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `HTTP_USER_AGENT` varchar(145) DEFAULT NULL,
  `REMOTE_ADDR` varchar(145) DEFAULT NULL,
  `REQUEST_URI` varchar(245) DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

