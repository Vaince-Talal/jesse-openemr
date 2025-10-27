CREATE TABLE IF NOT EXISTS `form_narrative_notes` (
`id`                bigint(20)      NOT NULL auto_increment,
`uuid`              binary(16)      DEFAULT NULL,
`date`              datetime        default NULL,
`pid`               bigint(20)      default 0,
`user`              varchar(255)    default NULL,
`groupname`         varchar(255)    default NULL,
`authorized`        tinyint(4)      default 0,
`activity`          tinyint(4)      default 0,
`note_content`       TEXT            default NULL,
PRIMARY KEY (id),
UNIQUE KEY `uuid` (uuid)
) ENGINE=InnoDB;
