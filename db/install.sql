CREATE TABLE IF NOT EXISTS `export_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `template` varchar(128) COLLATE latin1_german1_ci NOT NULL,
  `edits` text COLLATE latin1_german1_ci NOT NULL,
  `name` varchar(256) COLLATE latin1_german1_ci NOT NULL,
  `format` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `params` text COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
);
