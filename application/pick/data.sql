
CREATE TABLE IF NOT EXISTS `user`(
    `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `pwd` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
	UNIQUE (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `user_batch`(
    `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(8) unsigned NOT NULL,
    `batch_id` int(8) unsigned NOT NULL,
    `is_preference` int(8) unsigned NOT NULL,
    PRIMARY KEY (`id`),
	UNIQUE (`user_id`, `batch_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_token`(
    `user_id` int(8) unsigned NOT NULL,
    `token` varchar(255) NOT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `batch`(
    `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `des` varchar(255) NOT NULL,
    `preference_time` datetime NOT NULL,
    `begin_time` datetime NOT NULL,
    `end_time` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `building`(
    `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
    `batch_id` int(8) unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `des` varchar(255) NOT NULL,
    `is_parking` int(8) unsigned NOT NULL,
    `area` varchar(255) NOT NULL,
    `unit_price` int(8) unsigned default 0,
    `total_price` int(8) unsigned NOT NULL,
    `user_id` int(8) unsigned default 0,
    `favorite` int(8) unsigned default 0,
    PRIMARY KEY (`id`),
	UNIQUE (`batch_id`, `name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `favorite`(
    `user_id` int(8) unsigned NOT NULL,
    `building_id` int(8) unsigned NOT NULL,
    PRIMARY KEY (`user_id`, `building_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `order`(
    `user_id` int(8) unsigned NOT NULL,
    `building_id` int(8) unsigned NOT NULL,
    `batch_id` int(8) unsigned NOT NULL,
    PRIMARY KEY (`user_id`, `building_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

INSERT INTO `user`(`name`, `pwd`) VALUES
    ("13968021231", md5('123456')),
    ("13968021232", md5('123456')),
    ("13968021233", md5('123456')),
    ("13968021234", md5('123456')),
    ("13968021235", md5('123456')),
    ("13968021236", md5('123456'));

INSERT INTO `batch`(`name`, `des`, `preference_time`,`begin_time`, `end_time`) VALUES
    ("一期", "一期", "2018-06-06 12:00:00", "2018-06-06 12:05:00", "2018-06-06 12:30:00"),
    ("二期", "二期", "2018-06-06 12:00:00", "2018-06-06 12:05:00", "2018-06-06 12:30:00"),
    ("三期", "三期", "2018-06-06 12:00:00", "2018-06-06 12:05:00", "2018-06-06 12:30:00"),
    ("车位", "车位", "2018-06-06 12:00:00", "2018-06-06 12:05:00", "2018-06-06 12:30:00");

INSERT INTO `building`(`batch_id`, `name`, `des`, `is_parking`, `area`, `unit_price`, `total_price`) VALUES
    (1, "101", "一室一厅", 0, "100", "10000", "1000000"),
    (1, "102", "一室一厅", 0, "100", "10000", "1000000"),
    (1, "103", "一室一厅", 0, "100", "10000", "1000000"),
    (1, "104", "一室一厅", 0, "100", "10000", "1000000");

INSERT INTO `building`(`batch_id`, `name`, `des`, `is_parking`,`area`, `unit_price`, `total_price`) VALUES
    (2, "101", "二室一厅", 0, "100", "10000", "1000000"),
    (2, "102", "二室一厅", 0, "100", "10000", "1000000"),
    (2, "103", "二室一厅", 0, "100", "10000", "1000000"),
    (2, "104", "二室一厅", 0, "100", "10000", "1000000");
    
INSERT INTO `building`(`batch_id`, `name`, `des`, `is_parking`,`area`, `unit_price`, `total_price`) VALUES
    (3, "101", "三室一厅", 0, "100", "10000", "1000000"),
    (3, "102", "三室一厅", 0, "100", "10000", "1000000"),
    (3, "103", "三室一厅", 0, "100", "10000", "1000000"),
    (3, "104", "三室一厅", 0, "100", "10000", "1000000");
    
INSERT INTO `building`(`batch_id`, `name`, `des`, `is_parking`,`area`, `unit_price`, `total_price`) VALUES
    (4, "101", "", 1, "", "", "1000000"),
    (4, "102", "", 1, "", "", "1000000"),
    (4, "103", "", 1, "", "", "1000000"),
    (4, "104", "", 1, "", "", "1000000");

INSERT INTO `user_batch`(`user_id`, `batch_id`, `is_preference`) VALUES
    (1, 1, 0),
    (1, 4, 0),
    (2, 2, 0),
    (2, 4, 0),
    (3, 3, 0),
    (3, 4, 0),
    (4, 1, 1),
    (4, 4, 1),
    (5, 2, 1),
    (5, 4, 1),
    (6, 3, 1),
    (6, 4, 1);