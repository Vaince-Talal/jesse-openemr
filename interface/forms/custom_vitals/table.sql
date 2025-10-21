CREATE TABLE IF NOT EXISTS `form_custom_vitals` (
`id`                bigint(20)      NOT NULL auto_increment,
`uuid`              binary(16)      DEFAULT NULL,
`date`              datetime        default NULL,
`pid`               bigint(20)      default 0,
`user`              varchar(255)    default NULL,
`groupname`         varchar(255)    default NULL,
`authorized`        tinyint(4)      default 0,
`activity`          tinyint(4)      default 0,
`bps`               varchar(40)     default 0,
`bpd`               varchar(40)     default 0,
`pulse`             FLOAT(5,2)      default 0,
`respiration`       FLOAT(5,2)      default 0,
`oxygen_saturation` FLOAT(5,2)      default 0,
`mean_arterial_pressure` FLOAT(5,2) default 0,
`note`              VARCHAR(255)   default NULL,
PRIMARY KEY (id),
UNIQUE KEY `uuid` (uuid)
) ENGINE=InnoDB;