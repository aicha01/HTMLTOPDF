CREATE TABLE IF NOT EXISTS `historique` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`lien` varchar(255) NOT NULL,
`created` datetime,
`modified` timestamp DEFAULT CURRENT_TIMESTAMP,
primary key (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


INSERT INTO historique VALUES (1, 'https://fr-fr.facebook.com/', '2020-7-04', '2020-9-03');

INSERT INTO historique VALUES (2,'https://www.seloger.com/annonces/achat/appartement/paris-19eme-75/danube-porte-des-lilas/191958405.htm?projects=2,5&types=2,1&natures=1,2,4&places=[%7B%22subDivisions%22:[%2275%22]%7D]&enterprise=0&qsVersion=1.0&m=search_to_detail', '2019-1-01', '2019-5-14');

INSERT INTO historique VALUES (3,'https://www.seloger.com/annonces/achat/appartement/paris-19eme-75/danube-porte-des-lilas/191958405.htm', '2019-2-06', '2019-6-23');

INSERT INTO historique VALUES (4,'https://www.google.com/', '2019-2-06', '2019-6-23'); -->



 CREATE TABLE IF NOT EXISTS `parametre` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`page_size` varchar(255) NULL,
	`orientation` varchar(255) NULL,
	`margin_top` varchar(255)  NULL,
	`margin_bottom` varchar(255)  NULL,
	`margin_left` varchar(255)  NULL,
	`margin_right` varchar(255)  NULL,
	`zoom` varchar(255) NULL,
	`headerHtml` text  NULL,
	`footerHtml` text(255)  NULL,
	`footer_num_page` varchar(255)  NULL,
	`created` datetime,
	`modified` timestamp DEFAULT CURRENT_TIMESTAMP,
	primary key (id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8; 


CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    api_key VARCHAR(255) NULL UNIQUE,
	is_admin VARCHAR(10) NULL DEFAULT '0',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);