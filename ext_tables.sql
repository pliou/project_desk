CREATE TABLE tx_project_desk_team (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  pid INT DEFAULT 0 NOT NULL,
  tstamp INT DEFAULT 0 NOT NULL,
  crdate INT DEFAULT 0 NOT NULL,
  deleted TINYINT(1) DEFAULT 0 NOT NULL,
  hidden TINYINT(1) DEFAULT 0 NOT NULL,

  name VARCHAR(255) NOT NULL
);

CREATE TABLE tx_project_desk_license (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  deleted tinyint(4) DEFAULT '0' NOT NULL,

  identifier varchar(255) NOT NULL,
  license_key varchar(255) DEFAULT '' NOT NULL,
  license_type varchar(50) DEFAULT '' NOT NULL,

  PRIMARY KEY (uid),
  UNIQUE KEY identifier (identifier)
);

CREATE TABLE tx_project_desk_team_be_users_mm (
  uid_local INT NOT NULL,
  uid_foreign INT NOT NULL,
  PRIMARY KEY (uid_local, uid_foreign)
);
