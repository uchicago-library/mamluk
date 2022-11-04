CREATE TABLE member (
id int NOT NULL auto_increment,
username varchar(20) NOT NULL default 'admin',
password char(32) binary NOT NULL default '95c334bb38de86730797817c8a59c4a8',
cookie char(32) binary NOT NULL default '',
session char(32) binary NOT NULL default '',
ip varchar(15) binary NOT NULL default '',
PRIMARY KEY (id),
UNIQUE KEY username (username)
);

