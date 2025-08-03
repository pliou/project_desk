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

CREATE TABLE tx_project_desk_access_config_by_team (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  pid INT DEFAULT 0 NOT NULL,
  tstamp INT DEFAULT 0 NOT NULL,
  crdate INT DEFAULT 0 NOT NULL,
  deleted TINYINT(1) DEFAULT 0 NOT NULL,
  hidden TINYINT(1) DEFAULT 0 NOT NULL,

  team VARCHAR(255) NOT NULL,
  task_permissions TEXT NOT NULL,
  phase_permissions TEXT NOT NULL,
);

CREATE TABLE tx_project_desk_general_config (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  pid INT NOT NULL DEFAULT 0,
  tstamp INT NOT NULL DEFAULT 0,
  crdate INT NOT NULL DEFAULT 0,
  deleted TINYINT(1) NOT NULL DEFAULT 0,
  hidden  TINYINT(1) NOT NULL DEFAULT 0,
  show_board_in_frontend  TEXT NOT NULL,
  allow_archiving  TEXT NOT NULL
);
